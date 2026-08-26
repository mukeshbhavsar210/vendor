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
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;

class ProductController extends Controller {
    public function index(Request $request){
        $services = Service::latest('id')->with(['service_images']);

        if ($request->get('keyword') != ""){
            $services = $services->where('name', 'like', '%'.$request->keyword.'%');
        }
        $services = $services->paginate();
        $data['services'] = $services;

        return view ('admin.services.list',$data);
    }

    public function create(){
        $data = [];
        $categories = Category::orderBy('category_name','ASC')->get();        
        $subcategories = collect();
        $subsubcategories = collect();        

        $selected_discount = $product->discount_percentage_id ?? '';
        $discount_percentages = DiscountPercentage::all();

        $data['categories'] = $categories;        
        $data['subcategories'] = $subcategories;
        $data['subsubcategories'] = $subsubcategories;
        $data['selected_discount'] = $selected_discount;
        $data['discount_percentages'] = $discount_percentages;        

        return view('admin.services.create', $data);
    }

    public function store(Request $request){
        $rules = [
            'name' => 'required',
            // 'slug' => 'required|unique:products',
            // 'price' => 'required|numeric',                        
            // 'category' => 'required|numeric',
            // 'is_featured' => 'required|in:Yes,No',
        ];        

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            $service = new Service;
            $service->name = $request->name;
            $service->slug = $request->slug;
            // $service->description = $request->description;
            // $service->short_description = $request->short_description;$service->shipping_returns = $request->shipping_returns;
            // $service->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            // $service->price = $request->price;            
            // $service->category_id = $request->category;
            // $service->sub_category_id = $request->sub_category;                                          
            // $service->average_rating = $request->average_rating;            
            $service->status = $request->status;
            $service->save();
            
            if (!empty($request->image_array)) {
                $color_id = $request->color_id; 
                foreach ($request->image_array as $temp_image_id) {  
                    $tempImageInfo = TempImage::find($temp_image_id);
                    if (!$tempImageInfo) {
                        continue;
                    }

                    $ext = pathinfo($tempImageInfo->name, PATHINFO_EXTENSION);

                    $serviceImage = new ServiceImage();
                    $serviceImage->service_id = $service->id;                    
                    $serviceImage->image = "NULL";
                    $serviceImage->save();

                    $imageName = $service->id. '-' .$service->title. '-' .$serviceImage->id.'.'.$ext;                    
                    $serviceImage->image = $imageName;
                    $serviceImage->save();

                    //Large Image
                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/services/large/'.$imageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($sourcePath);
                    $image->cover(540,720);
                    $image->save($destPath);

                    //Generate Thumnail
                    $destPath = public_path().'/uploads/services/thumb/'.$imageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($sourcePath);
                    $image->cover(144,144);
                    $image->save($destPath);
                }
            }

        $request->session()->flash('success','Service added successfully');

        // return redirect()
        //     ->route('services.index')
        //     ->with('success', 'Product updated successfully');
        // }
        
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

    public function edit($id, Request $request){
        $product = Product::with(['colors','sizes','discount'])->find($id);        
        $subcategories = SubCategory::where('category_id', $product->category_id)->get();
        $selected_subcategory = $product->sub_category_id;
        $selected_subsubcategory = $product->sub_sub_category_id;
        $productimages = ProductImage::where('product_id',$product->id)->get();
        //$subcategories = SubCategory::where('category_id',$product->category_id)->get();        
        $subsubcategories = SubSubCategory::where('sub_category_id', $product->sub_category_id)->get();        
        $selected_discount = $product->discount_percentage_id ?? '';
        //$product->load('variants');
        
        if (empty($product)) {
            return redirect()->route('services.index')->with('error','Product not found');
        }        

        //Fetch Related products
        $relatedProducts = [];
        if ($product->related_products != '') {
            $productArray = explode(',',$product->related_products);
            $relatedProducts = Product::whereIn('id',$productArray)->get();
        }

        $data = [];
        $categories = Category::orderBy('category_name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $colors = Color::orderBy('id','ASC')->get();
        $sizes  = Size::orderBy('id','ASC')->get();
        $discounts = Discount::orderBy('id','ASC')->get();
        $discount_percentages = DiscountPercentage::orderBy('percentage','ASC')->get();

        $data['categories'] = $categories;
        $data['subcategories'] = $subcategories;
        $data['subsubcategories'] = $subsubcategories;
        $data['selected_subcategory'] = $selected_subcategory;
        $data['selected_subsubcategory'] = $selected_subsubcategory;
        $data['product'] = $product;                
        $data['productimages'] = $productimages;
        $data['relatedProducts'] = $relatedProducts;        
        $data['brands'] = $brands;
        $data['colors'] = $colors;
        $data['sizes'] = $sizes;
        $data['discounts'] = $discounts;
        $data['discount_percentages'] = $discount_percentages;
        $data['selected_discount'] = $selected_discount;               
        
        return view('admin.services.edit',$data);
    }

    public function update($id, Request $request){
        $product = Product::with('sizes','colors')->find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            //'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->short_description = $request->short_description;$product->shipping_returns = $request->shipping_returns;
            $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            $product->price = $request->price;            
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->sub_sub_category_id = $request->sub_sub_category;
            $product->brand_id = $request->brand;            
            $product->is_featured = $request->is_featured;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->recommended = $request->recommended;
            $product->discount_percentage_id = $request->discount_percent;            
            $product->average_rating = $request->average_rating;
            $product->cod = $request->cod;
            $product->is_returnable = $request->is_returnable;
            $product->return_days = $request->return_days;
            $product->delivery_min_days = Carbon::now();
            $product->delivery_max_days = Carbon::now()->addDays(7);
            $product->status = $request->status;
            $product->save();

            // attach multiple IDs
            $product->colors()->sync($request->colors);
            $product->sizes()->sync($request->sizes);

            if($request->discount_percent > 0){
                Discount::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'discount_percentages_id' => $request->discount_percent,
                        'start_date' => Carbon::now(),
                        'end_date' => Carbon::now()->addDays(30),
                    ]
                );
            } else {
                Discount::where('product_id', $product->id)->delete(); // remove discount
            }

            // 3️⃣ Save Variants with color_id
            if ($request->filled('existing_variant_images')) {
                foreach ($request->existing_variant_images as $variantData) {

                    if (!empty($variantData['id'])) {
                        ProductVariant::where('id', $variantData['id'])
                            ->update([
                                'color_id' => $variantData['color_id']
                            ]);
                    }
                }
            }

            if ($request->filled('variant_image_array') && is_array($request->variant_image_array)) {
                foreach ($request->variant_image_array as $variant) {
                    $temp_image_id = $variant['image_id'] ?? null;
                    $color_id = $variant['color_id'] ?? null;

                    if (!$temp_image_id || !$color_id) {
                        continue;
                    }

                    $tempImageInfo = TempImage::find($temp_image_id);

                    if (!$tempImageInfo) {
                        continue;
                    }

                    $ext = pathinfo($tempImageInfo->name, PATHINFO_EXTENSION);

                    // Create variant record
                    $productVariant = new ProductVariant();
                    $productVariant->product_id = $product->id;
                    $productVariant->color_id = $color_id; // ✅ SAVE COLOR
                    $productVariant->image = 'temp.jpg';
                    $productVariant->save();

                    $imageName = $product->slug . '_' . $product->id . '_' . $productVariant->id . '.' . $ext;

                    $productVariant->image = $imageName;
                    $productVariant->save();

                    $sourcePath = public_path('/temp/' . $tempImageInfo->name);

                    if (!file_exists($sourcePath)) {
                        continue;
                    }

                    $manager = new ImageManager(new Driver());

                    // LARGE IMAGE
                    $largePath = public_path('/uploads/product/large/' . $imageName);
                    $image = $manager->read($sourcePath);
                    $image->cover(540, 720);
                    $image->save($largePath, quality: 100);

                    // SMALL IMAGE
                    $smallPath = public_path('/uploads/product/small/' . $imageName);
                    $image = $manager->read($sourcePath);
                    $image->cover(300, 300);
                    $image->save($smallPath, quality: 100);

                    File::delete($sourcePath);
                }
            }

            if ($request->filled('image_array') && is_array($request->image_array)) {
                foreach ($request->image_array as $imageData) {

                 if ($request->filled('color_id')) {
                    ProductImage::where('product_id', $product->id)
                        ->update([
                            'color_id' => $request->color_id
                        ]);
                }   
                
                    $temp_image_id = $imageData['image_id'] ?? null;
                    $image_id = $imageData['image_id'] ?? null;
                    $color_id = $imageData['color_id'] ?? null;
                    
                    if (!$temp_image_id) { continue; }

                    if (!$image_id) {
                        continue;
                    }

                    // ✅ UPDATE existing image
                    ProductImage::where('id', $image_id)
                        ->update([
                            'color_id' => $color_id
                        ]);

                    $tempImageInfo = TempImage::find($temp_image_id);

                    if (!$tempImageInfo) {
                        continue;
                    }

                    $ext = pathinfo($tempImageInfo->name, PATHINFO_EXTENSION);

                    // Create record
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->color_id = $color_id; 
                    $productImage->image = 'temp.jpg';
                    $productImage->save();

                    $imageName = $product->id . '-' . $product->title . '-' . $productImage->id . '.' . $ext;

                    $productImage->image = $imageName;
                    $productImage->save();

                    $sourcePath = public_path('/temp/' . $tempImageInfo->name);

                    if (!file_exists($sourcePath)) {
                        continue;
                    }

                    $manager = new ImageManager(new Driver());

                    // Large
                    $largePath = public_path('/uploads/product/large/' . $imageName);
                    $image = $manager->read($sourcePath);
                    $image->cover(540, 720);
                    $image->save($largePath, quality: 100);

                    // Small
                    $smallPath = public_path('/uploads/product/small/' . $imageName);
                    $image = $manager->read($sourcePath);
                    $image->cover(300, 400);
                    $image->save($smallPath, quality: 100);

                    File::delete($sourcePath);
                }
            }


        $users = StockNotification::where('product_id', $product->id)
        ->where('notified', 0)
        ->get();

        foreach ($users as $notify) {
            // send email / notification
            // Mail::to($notify->user->email)->send(...);

            $notify->update(['notified' => 1]);
        }
        
        $request->session()->flash('success','Product updated successfully');

        return redirect()
            ->route('services.index')
            ->with('success', 'Product updated successfully');
         }
    }

    public function destroy($id, Request $request){
        $service = Service::find($id);

        if (empty($service)) {
            $request->session()->flash('error','Service not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $serviceImages = ServiceImage::where('service_id',$id)->get();

        if (!empty($serviceImages)) {
            foreach ($serviceImages as $serviceImage) {
                File::delete(public_path('uploads/services/large/'.$serviceImage->image));
                File::delete(public_path('uploads/services/thumb/'.$serviceImage->image));
            }

            ServiceImage::where('service_id',$id)->delete();
        }

        $service->delete();

        $request->session()->flash('success','Service deleted successfully');

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
