<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Flash;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller {
    public function index(Request $request) {        
        $categories = Category::withCount('subCategories')
            ->with(['subCategories' => function ($q) {
                $q->withCount('subSubCategories')
                ->with('subSubCategories');
            }])
            ->paginate(10);        

        if ($request->filled('keyword')) {
            $categories->where('category_name', 'like', '%' . $request->keyword . '%');
        }        

        $categoryTotal = Category::count();        

        $data = [                                 
            'refresh'       => route('categories.index'),
            'total'         => $categoryTotal,
            'modals' => [
                'category' => [
                    'title'      => 'Create Category',
                    'modal_id'   => 'categoryModal',
                    'form_id'    => 'categoryForm',
                    'method_id'  => 'category_method',                    
                    'formConfig' => [
                        'action' => '',
                        'method' => 'POST',
                        'button' => 'Submit',
                        'fields' => [
                            [
                                'type' => 'text',
                                'name' => 'category_name',                                
                                'id' => 'category_name', 
                                'label' => 'Category Name',
                                'placeholder' => 'Enter Category name',
                                'slug_create' => 'slug-source',
                                'class' => 'slug-source',                                
                                'data'  => [
                                    'target' => '#slug'
                                ],
                                'col' => 'col-md-12 col-12'
                            ],
                            [
                                'type' => 'text',
                                'name' => 'category_slug',
                                'label' => 'Category slug',
                                'placeholder' => 'Enter Category name',                                
                                'id'    => 'slug',
                                'col' => 'col-md-12 col-12 d-none'
                            ]
                        ]
                    ]
                ],                

                // Create Sub Category
                'subcategory' => [
                    'title'      => 'Create Sub Category',
                    'modal_id'   => 'subCategoryModal',
                    'form_id'    => 'subCategoryForm',
                    'method_id'  => 'subcategory_method',                    
                    'formConfig' => [
                        'action' => '',
                        'method' => 'POST',
                        'button' => 'Submit',
                        'fields' => [                            
                            [
                                'type' => 'select',
                                'name' => 'category_id',
                                'label' => 'Select Parent Category',
                                'options' => $categories->pluck('category_name','id')->toArray(),
                                'col' => 'col-md-12 col-12'
                            ], 
                            [
                                'type' => 'text',
                                'name' => 'sub_category_name',
                                'label' => 'Sub Category Name',
                                'id' => 'sub_category_name',
                                'placeholder' => 'Enter Category name',
                                'slug_create' => 'slug-source',
                                'class' => 'slug-source',                                
                                'data'  => [
                                    'target' => '#slug_2'
                                ],
                                'col' => 'col-md-12 col-12'
                            ],                         
                            [
                                'type' => 'text',
                                'name' => 'sub_category_slug',
                                'label' => 'Category slug',
                                'placeholder' => 'Enter Category name',                                
                                'id'    => 'slug_2',
                                'col' => 'col-md-12 col-12 d-none'
                            ],
                            [
                                'type' => 'file',
                                'name' => 'image',
                                'label' => 'Sub Category Image',
                                'col' => 'col-md-12 col-6'
                            ],
                            [
                                'type' => 'select',
                                'name' => 'status',
                                'label' => 'Status',
                                'options' => [
                                    1 => 'Active',
                                    0 => 'Block'
                                ],
                                'col' => 'col-md-3 col-6 d-none'
                            ],
                        ]
                    ]
                ],
            ],     
                                   
            'categories' => $categories
        ];          

        return view('admin.category.index', $data);       
    }

    public function category_store(Request $request){
        $validator = Validator::make($request->all(), [
            //'category_name' => 'required',
            //'category_slug' => 'required|unique:sub_categories,category_slug',
            //'menu_order' => 'required|integer|unique:categories,menu_order'
        ]);

        if ($validator->passes()) {
            $category = new Category();
            $category->category_name = $request->category_name;
            $category->category_slug = $request->category_slug;            
            $category->save();

            $id = $category->id;
            $name = Str::slug($category->category_name);            
            $request->session()->flash('success', 'Category added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }    

    public function category_update($categoryId, Request $request){
        $category = Category::find($categoryId);

        if (empty($category)) {
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_slug' => 'required|unique:categories,category_slug,'.$category->id.',id',
        ]);

        if ($validator->passes()) {
            $category->category_name = $request->category_name;
            $category->category_slug = $request->category_slug;
            $category->status = $request->status;
            $category->save();           

            $request->session()->flash('success', 'Category updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function subCategory_store(Request $request){
        $validator = Validator::make($request->all(), [
            // 'category_id' => 'required',
            // 'sub_category_name' => 'required',
            // 'slug' => 'required|unique:sub_categories',
            // 'sub_category_slug' => [
            //         'required',
            //         Rule::unique('sub_categories')
            //             ->where(function ($query) use ($request) {
            //                 return $query->where('category_id', $request->category_id);
            //             }),
            //     ],            
            //'status' => 'required',
        ]);

        if ($validator->passes()) {
            $subCategory = new SubCategory();
            $category = Category::find($request->category_id);
            $subCategory->category_id = $request->category_id;
            $subCategory->sub_category_name = $request->sub_category_name;
            $subCategory->sub_category_slug = $request->sub_category_slug;            
            $subCategory->status = $request->status;
            $subCategory->save();

            $id = $subCategory->id;
            $name = Str::slug($subCategory->sub_category_name);

            // Init Image Manager
            $manager = new ImageManager(new Driver());

            // Create directory if not exists
            $path = public_path('uploads/subcategory/');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }            
            
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $id . '_' . $name . '.' . $image->getClientOriginalExtension();
                $img = $manager->read($image->getRealPath());                
                $img->resize(144, 144);
                $img->save($path.$imageName);
                $subCategory->image = $imageName;
                $subCategory->save();
            }           

            $request->session()->flash('success', 'Sub Category added successfully');

            return response([
                'status' => true,
                'message' => 'Sub Category added successfully',
            ]);

        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function subCategory_update($subCategoryId, Request $request){
        $subCategory = SubCategory::find($subCategoryId);

        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $validator = Validator::make($request->all(), [
            // 'sub_category_name' => 'required',
            // 'sub_category_slug' => 'required|unique:sub_categories,sub_category_slug,'.$subCategory->id.',id',            
        ]);

        if ($validator->passes()) {
            $category = Category::find($request->category_id);
            $subCategory->category_id = $request->category;
            $subCategory->sub_category_name = $category->category_name. ' - ' .$request->sub_category_name;
            $subCategory->sub_category_slug = $category->category_slug. '-' .$request->sub_category_slug;            
            $subCategory->status = $request->status;                        
            $subCategory->save();

            $request->session()->flash('success', 'Sub Category updated successfully');

            return response([
                'status' => true,
                'message' => 'Sub Category updated successfully',
            ]);

        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }        

    public function category_destroy($categoryId, Request $request){
        $category = Category::find($categoryId);

        if(empty($category)){
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found'
            ]);
            //return redirect()->route('categories.index');
        }

        //Delete old image
        File::delete(public_path().'/uploads/category/thumb/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);

        $category->delete();

        $request->session()->flash('success', 'Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }        

    public function getSubCategories($id) {
        return SubCategory::where('category_id', $id)->get();
    }

    public function subCategory_destroy ($id, Request $request){
        $subCategory = SubCategory::find($id);

        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $subCategory->delete();

        $request->session()->flash('success', 'Sub Category deleted successfully');

        return response([
            'status' => true,
            'message' => 'Sub Category deleted successfully',
        ]);
    }
}