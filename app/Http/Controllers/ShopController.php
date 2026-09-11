<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\DiscountCoupon;
use App\Models\DiscountPercentage;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Service;
use App\Models\Size;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;

class ShopController extends Controller {

    public function listing(Request $request, $item1=null, $item2=null, $item3=null) {    
        $selected_item1 = null;
        $selected_item2 = null;
        $selected_item3 = null;

        $categoryArray = [];
        $brandsArray = [];
        $colorsArray = [];
        $sizesArray = [];
        $discountArray = [];            
        
        $products = Product::with(['category','subCategory','subSubCategory','ratings','sizes','variant_images'])->where('status',1);
        $categories = Category::orderBy("category_name","ASC")->with(['sub_category'])->where('status',1)->get();                       
        $productCount = Product::where('status', 1)->count();
        $totalProducts = $products->count();     
        $sizes  = Size::orderBy('id','ASC')->get();
        
        function applySlugFilter($slug, $model, $slugColumn, $productColumn, &$selectedItem, &$products){
            if (!empty($slug)) {
                $selectedItem = $model::where($slugColumn, $slug)->first();
                if ($selectedItem) {
                    $products->where($productColumn, $selectedItem->id);
                }
            }
        }

        applySlugFilter($item1, Category::class, 'category_slug', 'category_id', $selected_item1, $products);
        applySlugFilter($item2, SubCategory::class, 'sub_category_slug', 'sub_category_id', $selected_item2, $products);
        applySlugFilter($item3, SubSubCategory::class, 'sub_sub_category_slug', 'sub_sub_category_id', $selected_item3, $products);

        $item3 = collect();

        if ($selected_item2) {
            $item3 = SubSubCategory::where('sub_category_id', $selected_item2->id)
                ->withCount(['products' => function ($query) {
                    $query->where('status', 1);
                }])
                ->get();
        }                

        if (!empty($request->get('category'))) {
            $values = explode(',', $request->get('category'));
            $ids = SubSubCategory::whereIn('sub_sub_category_slug', $values)->pluck('id')->toArray();
            $categoryArray = $values;
            $products->whereIn('sub_sub_category_id', $ids);
        }

        $filterProducts = function ($query) use ($selected_item1, $selected_item2) {
            $query->where('status', 1);
            if ($selected_item1) {$query->where('category_id', $selected_item1->id);}
            if ($selected_item2) {$query->where('sub_category_id', $selected_item2->id);}
        };                   

        $discounts = DiscountPercentage::whereHas('products', $filterProducts) 
                ->withCount(['products as products_count' => $filterProducts])
                ->orderBy('percentage', 'ASC')->get();                         

        //Filter logic
        function applyFilter($request, $param, $model, $column, &$selectedArray, &$products, $options = []) {
            if (!empty($request->get($param))) {
                $values = explode(',', $request->get($param));
                // Get matching IDs from lookup table
                $ids = $model::whereIn($column, $values)->pluck('id')->toArray();
                $selectedArray = $values;

                // 🔥 Case 1: Pivot relation (colors, sizes, discounts)
                if (!empty($options['relation'])) {
                    $relation = $options['relation'];
                    $products->whereHas($relation, function ($q) use ($ids) {
                        $table = $q->getModel()->getTable();
                        $q->whereIn($table . '.id', $ids);
                    });

                } 
                // 🔥 Case 2: Direct column (brand_id, category_id)
                elseif (!empty($options['column'])) {
                    $products->whereIn($options['column'], $ids);
                }
            }
        }
                
        applyFilter($request,'discount',DiscountPercentage::class,'percentage',$discountArray,$products,['column' => 'discount_percentage_id']);

        // Price slider
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $min = intval($request->price_min);
            $max = intval($request->price_max);
            $products = $products->whereBetween('price', [$min, $max]);
        }        

        //Search main header
        if (!empty($request->get('search'))){
            $products = $products->where('title','like','%'.$request->get('search').'%');
        }

        //Sort filters
        if ($request->get('sort') != '') {
            switch ($request->get('sort')) {
                case 'latest':
                    $products = $products->orderBy('id', 'DESC');
                    break;

                case 'price_asc':
                    $products = $products->orderBy('price', 'ASC');
                    break;

                case 'price_desc':
                    $products = $products->orderBy('price', 'DESC');
                    break;

                case 'recommended':
                    $products = $products->orderBy('recommended', 'DESC');
                    break;

                case 'popularity':
                    $products = $products->orderBy('views', 'DESC');
                    break;

                case 'discount':
                    $products = $products->orderBy('discount_percentage', 'DESC');
                    break;

                case 'rating':
                    $products = $products->orderBy('average_rating', 'DESC');
                    break;

                default:
                    $products = $products->orderBy('id', 'DESC');
            }

        } else {
            $products = $products->orderBy('id', 'DESC');
        }

        $filtersApplied = false;

        if (            
            $request->filled('category') ||
            $request->filled('item') ||
            $request->filled('price_min') || $request->filled('price_max') ||            
            $request->filled('sort') ||
            $request->filled('discount') ||
            $request->filled('search')
        ) {
            $filtersApplied = true;
        }               

        // Coupon filter
        if ($request->coupon) {
            $products->whereHas('coupons', function ($q) use ($request) {
                $q->where('code', $request->coupon);
            });
        }
        // if ($request->coupon) {
        //     $coupon = DiscountCoupon::where('code', $request->coupon)->first();
        //     if ($coupon) {
        //         $products->whereHas('coupons', function ($q) use ($coupon) {
        //             $q->where('discount_coupons.id', $coupon->id);
        //         });
        //     } else {
        //         $products->whereRaw('0=1'); 
        //     }
        // }

        $products = $products->paginate(10); 
        
        $wishlistProductIds = [];

        if (Auth::check()) {
            $wishlistProductIds = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }     

        $data = compact(
            'products', 'wishlistProductIds','productCount','categories','categoryArray', 'discounts', 'discountArray', 'selected_item1', 'selected_item2', 'selected_item3', 'item1','item2','item3','filtersApplied','totalProducts'
        );

        $data = array_merge($data, [
            'priceMin' => $request->get('price_min', 0),
            'priceMax' => $request->get('price_max', 10000),            
            'sort'     => $request->get('sort'),
        ]);      
        
        return view('front.services.listing', $data);
    }

    public function product($item2=null, $item3=null, $slug, Request $request) {
        $product = Product::where('slug',$slug)->with(['product_images', 'variants', 'subSubCategory.subCategory.category'])->first();        

        $selectedItems = [
            'item1' => null,
            'item2' => null,
            'item3' => null,
        ];
        
        $products = Product::query();

        if($request->coupon){
            $coupon = DiscountCoupon::where('code',$request->coupon)->first();
            if($coupon){
                $products->whereHas('coupons', function($q) use ($coupon){
                    $q->where('discount_coupons.id',$coupon->id);
                });
            }
        }
                    
        $coupon = $request->coupon;        
        if($request->coupon){
            $coupon = DiscountCoupon::where('code', $request->coupon)->first();

            if(!$coupon){
                abort(404);
            }
            // check if coupon valid for this product
            if(!$product->coupons()->where('discount_coupons_id',$coupon->id)->exists()){
                abort(404);
            }
            session(['coupon'=>$coupon]);
        }

        $selectedVariant = null;

        if ($request->filled('variant')) {
            $selectedVariant = $product->variants
                                ->where('id', $request->variant)
                                ->first();
        }

        if($product == null){
            abort(404);
        }        

        //Fetch Related products
        $relatedProducts = [];
        if ($product->related_products != '') {
            $productArray = explode(',',$product->related_products);
            $relatedProducts = Product::whereIn('id',$productArray)->where('status',1)->with('product_images')->get();
        }

        $ratings = Review::where([['product_id', '=', $product->id],['status', '=', 1],])
                    ->selectRaw('rating, COUNT(*) as count')->groupBy('rating')->pluck('count', 'rating')->toArray();
        
        for ($i = 1; $i <= 5; $i++) {
            $ratings[$i] = $ratings[$i] ?? 0;
        }

        krsort($ratings);                    
        
        $totalRatings = Review::where([['product_id', '=', $product->id],['status', '=', 1],])->count();        
        $averageRating = Review::where('product_id', $product->id)->where('status', 1)->avg('rating');
        $reviews = Review::with('user')->where([['product_id', '=', $product->id],['status', '=', 1],])->latest()->take(3)->get();      
        $totalReviews = Review::where([['product_id', '=', $product->id],['status', '=', 1],])->count();      
        $productId = $product->id;
        $recommendedCount = Review::where('product_id', $productId)->where('rating', '>=', 4)->count();
        $percentage = $totalReviews > 0 
            ? round(($recommendedCount / $totalReviews) * 100)
            : 0;

        $userReviewOut = Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();
       
        $data['product'] = $product;
        $data['userReviewOut'] = $userReviewOut;
        $data['coupon'] = $coupon;
        $data['selectedVariant'] = $selectedVariant;
        $data['ratings'] = $ratings;
        $data['totalRatings'] = $totalRatings;
        $data['averageRating'] = $averageRating;
        $data['reviews'] = $reviews;
        $data['totalReviews'] = $totalReviews;
        $data['recommendedCount'] = $recommendedCount;
        $data['percentage'] = $percentage;
        $data['relatedProducts'] = $relatedProducts;              
            
        return view('front.products.details',$data);
    }

    public function allReviews($id) {
        $product = Product::findOrFail($id);
        $reviews = Review::where('product_id', $id)
                    ->latest()
                    ->paginate(10); // pagination

        $averageRating = Review::avg('rating');        
        $ratings = Review::where('product_id', $product->id)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count','rating')
            ->toArray();

        for ($i = 1; $i <= 5; $i++) {
            $ratings[$i] = $ratings[$i] ?? 0;
        }

        krsort($ratings);
        $totalRatings = Review::where('product_id', $product->id)->count();

        return view('front.products.reviews', compact('product','reviews','averageRating', 'ratings', 'totalRatings'));
    }

    public function category(Request $request, $item1 = null) {
        $services = Service::with(
                'category','subCategory','ratings',
                'process','brand','discounts.discountPercentage',
                'waranty','include','need','faqs')
                ->where('status', 'approved')->get();

        $services = $services->sortBy(function ($service) {
            return $service->subCategory?->sort_order ?? 999999;
        })->groupBy('sub_category_id');

        //$query = Service::with('ratings')->where('status', 'approved');        
        $selected_category = $item1;
        $category = Category::where('category_slug', $selected_category)->first();
        $categories = Category::with('subCategories','ratings')->where('category_slug', $selected_category)->get();

        // if ($item1) {
        //     $category = Category::where('category_slug', $item1)->firstOrFail();
        //     $query->where('category_id', $category->id);
        // }

        //$services = $query->get();    
        
        $cartContent = Cart::content();
        $appliedCouponId = session('coupon_discount.id');                                   
        $qty = Cart::count();
        $selectedIds = $request->cart_ids ?? [];
        $shipping_charge = 0;                

        $cartItems = Cart::content()->filter(function($item) use ($selectedIds){
            return in_array($item->rowId, $selectedIds);
        });

        // IMPORTANT: remove formatting to avoid string math
        $cartItems = Cart::content();

        $discount_price = $cartItems->sum(function ($item) {
            return ($item->options->discount_price ?? 0) * $item->qty;
        });
                        
        $store_discount = session()->get('coupon_discount');
        $coupon_discount = session()->get('coupon_discount.discount', 0);
        $coupon_code = session()->get('coupon_discount.code', 0);    
        
        $hasValidCoupon = DiscountCoupon::where('status', 1)
            ->whereDate('expires_at', '>=', Carbon::today())
            ->exists();

            //dd(Cart::content());

            return view('front.services.index', [
                'services'          => $services,
                'selected_category' => $selected_category,
                'category'          => $category,
                'categories'        => $categories,
                'discount_price'    => $discount_price,
                'store_discount'    => $store_discount,
                'coupon_code'       => $coupon_code,
                'coupon_discount'   => $coupon_discount,
                'cartContent'       => $cartContent,
        ]);
        
        //return view('front.services.index', compact('services','selected_category','category','categories'));
    }

    public function category_old(Request $request, $item1=null) {    
        $services = Service::with('ratings')->where('status',1);
        $selected_category = $item1;
        
        // if (!empty($item1)) {
        //     $values = explode(',', $item1);
        //     $ids = Category::whereIn('category_slug', $values)->pluck('id')->toArray();
        //     $selected_item1 = $values;
        //     $services->whereIn('category_id', $ids);
        // }        
            
        return view('front.services.index', compact('services', 'selected_category' ));
    }


    public function subcategory(Request $request, $item2=null) {            
        $subcategories = Product::with(['subCategory', 'ratings'])->where('status',1);     
        
        if (!empty($item2)) {
            $values = explode(',', $item2);
            $ids = SubCategory::whereIn('sub_category_slug', $values)->pluck('id')->toArray();
            $selected_item2 = $values;
            $subcategories->whereIn('sub_category_id', $ids);
        }
        
        $subcategories = $subcategories->paginate(10);        

        return view('front.products.subcategory', compact('subcategories', 'item2'));
    }
    

    public function store(Request $request) {
        Rating::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Thanks for your rating!'
        ]);
    }

    // public function coupon_product($slug, $coupon, $request) {
    //     $product = Product::findOrFail($slug);

    //     if($request->coupon){
    //         $coupon = DiscountCoupon::where('code',$request->coupon)
    //                         ->whereDate('expiry_date','>=',now())
    //                         ->first();
    //         if(!$coupon || !$product->coupons()->where('coupon_id',$coupon->id)->exists()){
    //             abort(404);
    //         }
    //         session(['coupon'=>$coupon]);
    //     }

    //     return view('front.services.index', compact('product'));
    // }


     // private function applyFilter($request, $param, $model, $column, $productColumn, &$selectedArray, &$products){
    //     if (!empty($request->get($param))) {
    //         $values = explode(',', $request->get($param));

    //         $ids = $model::whereIn($column, $values)->pluck('id')->toArray();

    //         $selectedArray = $values;

    //         $products->whereIn($productColumn, $ids);
    //     }
    // }

    // private function commonFilters(Request $request, $products) {
    //     // initialize (VERY IMPORTANT)
    //     $brandsArray = [];
    //     $colorsArray = [];
    //     $sizesArray = [];
    //     $discountArray = [];

    //     // apply filters
    //     $this->applyFilter($request, 'brand', \App\Models\Brand::class, 'slug', 'brand_id', $brandsArray, $products);
    //     $this->applyFilter($request, 'color', \App\Models\Color::class, 'name', 'color_id', $colorsArray, $products);
    //     $this->applyFilter($request, 'size', \App\Models\Size::class, 'name', 'size_id', $sizesArray, $products);
    //     $this->applyFilter($request, 'discount', \App\Models\DiscountPercentage::class, 'name', 'discount_percentage_id', $discountArray, $products);

    //     return [
    //         'products' => $products, // ✅ return updated query
    //         'brandsArray' => $brandsArray,
    //         'colorsArray' => $colorsArray,
    //         'sizesArray' => $sizesArray,
    //         'discountArray' => $discountArray,
    //     ];
    // }

    // private function resolveCategory(Request $request, $item1=null, $item2=null, $item3=null) {    
    //     return [
    //         'category' => $category ?? null,
    //         'subcategory' => $subcategory ?? null,
    //         'subsubcategory' => $subsubcategory ?? null,
    //     ];
    // }
}