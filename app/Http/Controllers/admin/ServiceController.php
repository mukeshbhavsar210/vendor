<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\DiscountPercentage;
use App\Models\Service;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ServiceImage;
use App\Models\StockNotification;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\Vendor;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;

class ServiceController extends Controller {
    public function index(Request $request){
        $categories = Category::withCount('subCategories')->orderBy('menu_order', 'ASC')->where('status', 1)->paginate(20);
        $discounts = DiscountPercentage::get();
        $subCategories = SubCategory::where('status', 1)->get();        

        $user = auth('admin')->user();
        $vendorId = null;

        if ($user?->role === 'vendor') {
            $vendorId = $user->vendor?->id;
        }

        $services = Service::with(['serviceImages'])
            ->when($vendorId, function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })->latest('id')->get();

        $vendorServices = Service::with(['serviceImages'])
            ->when($vendorId, function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })->latest('id')->get();

        if ($request->get('keyword') != ""){
            $services = $services->where('name', 'like', '%'.$request->keyword.'%');
        }
        
        $vendor = Vendor::where('user_id', auth()->id())->firstOrFail();
        $serviceTotal = Service::when($vendorId, function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })->count();
                          
            $data = [                                 
                'refresh'       => route('categories.index'),
                'total'         => $serviceTotal,            
                'modals' => [
                    'category' => [
                        'title'      => 'Create Service',
                        'modal_id'   => 'serviceModal',
                        'form_id'    => 'serviceForm',
                        'method_id'  => 'service_method',                    
                        'formConfig' => [
                            'action' => '',
                            'method' => 'POST',
                            'button' => 'Add',
                            'fields' => [
                                [
                                    'type' => 'accordion',
                                    'name' => '',
                                    'label' => '',
                                    'class' => '',
                                    'items' => [
                                        [
                                            'title' => 'Service Details',
                                            'fields' => [   
                                                [
                                                    'type' => 'dropzone',
                                                    'name' => 'images',
                                                    'label' => 'Service Images',
                                                    'col' => 'col-12',
                                                    'maxFiles' => 5,
                                                    'acceptedFiles' => 'image/*',
                                                    'multiple' => true,
                                                ],                                                
                                                [
                                                    'type' => 'select',
                                                    'name' => 'vendor_id',
                                                    'label' => 'Vendor',
                                                    'options' => [
                                                        $vendor->id => $vendor->business_name
                                                    ],
                                                    'value' => $vendor->id,
                                                    'col' => 'col-12 d-none',
                                                ],
                                                [
                                                    'type' => 'select',
                                                    'name' => 'category_id',
                                                    'label' => 'Select Category',
                                                    'options' => $categories->pluck('category_name','id')->toArray(),
                                                    'col' => 'col-12',
                                                    'id' => 'category_id',
                                                ],
                                                [
                                                    'type' => 'select',
                                                    'name' => 'sub_category_id',
                                                    'label' => 'Select Sub Category',
                                                    'options' => [],
                                                    'col' => 'col-12',
                                                    'id' => 'sub_category_id',
                                                ],                                                
                                                [
                                                    'type' => 'select',
                                                    'name' => 'status',
                                                    'label' => 'Status',
                                                    'options' => [
                                                        'approved' => 'Approved',
                                                        'pending' => 'Pending'
                                                    ],
                                                    'value' => auth()->user()?->role === 'admin' ? 'approved' : 'pending',
                                                    'default' => auth()->user()?->role === 'admin' ? 'approved' : 'pending',
                                                    'col' => auth()->user()?->role === 'admin' ? 'col-6' : 'col-6 d-none'
                                                ],   
                                                [
                                                    'type' => 'select',
                                                    'name' => 'is_featured',
                                                    'label' => 'Featured',                                                    
                                                    'options' => [
                                                        0 => 'No',
                                                        1 => 'Yes'
                                                    ],
                                                    'value' => 1,
                                                    'default' => 1,
                                                    'col' => 'col-6'
                                                ],
                                            ],
                                        ],
                                        [
                                            'title' => 'Discriptions',
                                            'fields' => [
                                                
                                                [
                                                    'type' => 'textarea',
                                                    'name' => 'short_description',
                                                    'label' => 'Short Description',                                                    
                                                    'placeholder' => 'Short Description',
                                                    'rows' => '2',
                                                    'col' => 'col-12'
                                                ],
                                                [
                                                    'type' => 'textarea',
                                                    'name' => 'description',
                                                    'label' => 'Description',                                                    
                                                    'placeholder' => 'Description',
                                                    'rows' => '4',
                                                    'col' => 'col-12'
                                                ],
                                            ],
                                        ],
                                    ],
                                ],                                                                                    
                            ]                        
                        ]
                    ],
                ],     
                                   
            'services' => $services,
            'vendorServices' => $vendorServices
        ]; 

        //$data['$serviceTotal'] = $serviceTotal;

        return view ('admin.services', $data);
    }

    public function subCategories($categoryId) {
        $subCategories = SubCategory::where('category_id', $categoryId)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $subCategories->map(function ($item) {
                return [
                    'id'   => $item->id,
                    'name' => $item->sub_category_name,
                ];
            }),
        ]);
    }

    public function service_store(Request $request){
        $validator = Validator::make($request->all(), [
            
        ]);

        if ($validator->passes()) {
            $service = new Service();
            $service->vendor_id = $request->vendor_id;
            $service->category_id = $request->category_id;
            $service->sub_category_id = $request->sub_category_id;
            $service->short_description = $request->short_description;            
            $service->description = $request->description;
            $service->is_featured = $request->is_featured;
            $service->status = $request->status;
            $service->save();

            if (!empty($request->image_array)) {
                $categoryName = Str::slug($service->category?->category_name ?? 'category');
                $subCategoryName = Str::slug($service->subCategory?->sub_category_name ?? 'sub-category');
                $vendorId = $service->vendor_id;

                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);

                    if (!$tempImageInfo) {
                        continue;
                    }

                    $ext = pathinfo($tempImageInfo->name, PATHINFO_EXTENSION);

                    // Create service image record first
                    $serviceImage = new ServiceImage();
                    $serviceImage->service_id = $service->id;
                    $serviceImage->image = "NULL";
                    $serviceImage->save();

                    // Create image name
                    $imageName = $vendorId                        
                        . '-' . $categoryName
                        . '-' . $subCategoryName
                        . '-' . $serviceImage->id                        
                        . '.' . $ext;

                    $serviceImage->image = $imageName;
                    $serviceImage->save();

                    // Source image
                    $sourcePath = public_path('temp/' . $tempImageInfo->name);

                    // Large Image
                    $destPath = public_path('uploads/services/large/' . $imageName);

                    $manager = new ImageManager(new Driver());

                    $image = $manager->read($sourcePath);
                    $image->cover(700, 700);
                    $image->save($destPath);

                    // Thumbnail
                    $destPath = public_path('uploads/services/small/' . $imageName);

                    $manager = new ImageManager(new Driver());

                    $image = $manager->read($sourcePath);
                    $image->cover(144, 144);
                    $image->save($destPath);
                }
            }
                 
            $request->session()->flash('success', 'Service added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Service added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }  

    public function service_update($serviceId, Request $request) {
        $service = Service::find($serviceId);

        if (!$service) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Service not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'is_featured' => 'required|in:0,1',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $service->category_id = $request->category_id;
        $service->sub_category_id = $request->sub_category_id;
        $service->short_description = $request->short_description;
        $service->description = $request->description;
        $service->is_featured = $request->is_featured;
        $service->status = $request->status;
        $service->save();

        $request->session()->flash('success', 'Service updated successfully');

        return response()->json([
            'status' => true,
            'message' => 'Service updated successfully'
        ]);
    }


          
    public function service_destroy($id, Request $request) {
        $service = Service::find($id);

        if (!$service) {
            $request->session()->flash('error', 'Service not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
        }

        // Get service images
        $serviceImages = ServiceImage::where('service_id', $service->id)->get();

        if ($serviceImages->isNotEmpty()) {
            foreach ($serviceImages as $serviceImage) {
                // Delete large image
                $largePath = public_path(
                    'uploads/services/large/' . $serviceImage->image
                );

                if (File::exists($largePath)) {
                    File::delete($largePath);
                }

                // Delete small image
                $smallPath = public_path(
                    'uploads/services/small/' . $serviceImage->image
                );

                if (File::exists($smallPath)) {
                    File::delete($smallPath);
                }
            }

            // Delete service image records
            ServiceImage::where('service_id', $service->id)->delete();
        }

        // Delete service
        $service->delete();

        $request->session()->flash(
            'success',
            'Service deleted successfully'
        );

        return response()->json([
            'status' => true,
            'message' => 'Service deleted successfully',
        ]);
    }


    public function getServices(Request $request){
        $tempProduct = [];

        if($request->term != ""){
            $products = Product::where('title','like','%'.$request->term.'%')->get();

            if ($products != null){
                foreach ($products as $product){
                    $tempProduct[] = array(
                        'id' => $product->id,
                        'text' => $product->title,
                    );
                }
            }
        }

        return response()->json([
            'tags' => $tempProduct,
            'status' => true,
        ]);
    }
}
