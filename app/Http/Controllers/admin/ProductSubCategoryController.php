<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller {

    public function index(Request $request) {
        $subCategories = SubCategory::where('category_id', $request->category_id)
            ->select('id', 'sub_category_name')
            ->get();

        return response()->json([
            'subCategories' => $subCategories
        ]);
    }

    public function index2(Request $request){

        if (!empty($request->category_id)) {
            $subCategories = SubCategory::where('category_id',$request->category_id)
            ->orderBy('name','ASC')
            ->get();

            return response()->json([
                'status' => true,
                'subCategories' => $subCategories
            ]);
        } else {
            return response()->json([
                'status' => true,
                'subCategories' => []
            ]);
        }
    }

    public function getSubSubCategories(Request $request) {
        $subSubCategories = SubSubCategory::where(
            'sub_category_id',
            $request->sub_category_id
        )->get();

        return response()->json([
            'subSubCategories' => $subSubCategories
        ]);
    }

}
