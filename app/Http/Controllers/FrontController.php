<?php

namespace App\Http\Controllers;

use App\Models\DealStockNotification;
use App\Models\Order;
use App\Models\Page;
use App\Models\Service;
use App\Models\StockNotification;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller {
    public function index(){
        
        $getCategories = Category::with(['subCategories'])->where('showHome', 'outside')->orderBy('menu_order', 'ASC')->take(20)->get();
        $modalCategories = Category::where('showHome', 'inside')->where('status', 1)->orderBy('menu_order', 'ASC')->get()->groupBy('category_modal');
        $modalCategories2 = Category::where('status', 1)->orderBy('menu_order', 'ASC')->get();
        $allServices = Category::where('category_modal', '!=', 'services')->orderBy('menu_order', 'ASC')->where('status', 1)->get();        

        function getServices($categorySlug) {
            return SubCategory::with(['category','ratings'])
            ->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('category_slug', $categorySlug);
            })->where('status', 1)->get()->groupBy('category_id');
        }

        $most_booked = getServices('womens-salon-spa');
        $new_and_noteworthy = getServices('new_and_noteworthy');
        $women_spa = getServices('womens-salon-spa');
        $cleaning = getServices('cleaning');
        $appliances = getServices('ac-appliance-repair');
        $installation = getServices('home-repair-&-installation');
        $men_spa = getServices('mens-salon-massage');

        $data['getCategories'] = $getCategories;
        $data['modalCategories'] = $modalCategories;
        $data['modalCategories2'] = $modalCategories2;
        $data['allServices'] = $allServices;
        $data['most_booked'] = $most_booked;
        $data['new_and_noteworthy'] = $new_and_noteworthy;
        $data['women_spa'] = $women_spa;
        $data['cleaning'] = $cleaning;
        $data['men_spa'] = $men_spa;
        $data['appliances'] = $appliances;
        $data['installation'] = $installation;              

        return view("front.home.index",$data);
    }


    public function addToWishlist(Request $request){
        if(Auth::check() == false){
            session(['url.intended' => url()->previous() ]);
            return response()->json([
                'status' => false,
            ]);
        }

        $service = Service::find($request->id);

        if ($service == null){
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product not found.</div>'
            ]);
        }

        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'service_id' => $request->id,
            ],
            [
                'user_id' => Auth::user()->id,
                'service_id' => $request->id,
            ],
        );

        return response()->json([
            'status' => true,
            'message' => $service->title.' added in yout wishlist!'
        ]);
    }

    public function page($slug){
        $page = Page::where('slug', $slug)->first();

        if($page == null){
            abort(404);
        }

        return view('front.page', [
            'page' => $page
        ]);
    }
    
    public function sendContactEmail(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required|min:10',
        ]);

        if($validator->passes()){

        } else {
            return response()->json([
                'status'=> false,
                'errors' => $validator->errors()
            ]);
        }
    }    

    public function faqs() {
        return view('front.faqs');
    }

    public function orderStatus(Request $request) {
        $orderId = $request->message;

        $order = Order::where('id', $orderId)->first();

        if (!$order) {
            return response()->json([
                'reply' => '❌ Order not found. Please check your Order ID.'
            ]);
        }

        return response()->json([
            'reply' => "
                📦 Order {$order->id} {$order->status} on {$order->created_at->format('d, M Y')}                
            "
        ]);

        // return response()->json([
        //     'reply' => "📦 Order #{$order->id} is currently: {$order->status}"
        // ]);
    }


    public function searchCategories() {
        $categories = Category::where('status', 1)->orderBy('menu_order')->where('showHome', 'outside')->orderBy('id', 'DESC')->get();
        return response()->json([
            'categories' => $categories
        ]);
    }

    public function searchSubCategories(Category $category) {
        $parentCategory = Category::where('id', $category->id)->where('status', 1)->where('showHome', 'outside')->firstOrFail();
        
        $childCategory = Category::where('status', 1)
            ->where('showHome', 'inside')->where('category_modal', $parentCategory->category_modal)
            ->first();

        // SubCategories belonging to this category
        $subCategories = SubCategory::where('category_id', $parentCategory->id)->where('status', 1)
            ->with([
                'services' => function ($query) {
                    $query->where('status', 'approved')
                        ->with('ratings');
                }
            ])->orderBy('sort_order')->get();

        $data = collect();        

        if ($childCategory) {
            $data->push([
                'type' => 'category',
                'id' => $childCategory->id,
                'name' => $childCategory->category_name,
                'slug' => $childCategory->category_slug,
                'image' => $childCategory->image ? asset('uploads/category/' . $childCategory->image) : null,
                'rating' => null,
                'rating_count' => 0,                
                'parent' => $parentCategory->category_name,
                'url' => url('/category/' . $childCategory->category_slug),                
            ]);
        }

        $subCategories->each(function ($subcategory) use ($data, $parentCategory) {

        $services = $subcategory->services;

        $ratings = $services
            ->flatMap(fn ($service) => $service->ratings);
                $data->push([
                    'type' => 'subcategory',
                    'id' => $subcategory->id,
                    'name' => $subcategory->sub_category_name,
                    'slug' => $subcategory->sub_category_slug,
                    'price' => $subcategory->price,
                    'image' => $subcategory->image ? asset('uploads/subcategory/' . $subcategory->image) : null,
                    'rating' => round($ratings->avg('ratings') ?? 0, 1),
                    'rating_count' => $ratings->count(),                    
                    'parent' => $parentCategory->category_name,                       
                    'url' => route('search.services',$subcategory->id),
                ]);
            });

        return response()->json([
            'category' => [
                'id' => $parentCategory->id,
                'name' => $parentCategory->category_name,
            ],

            'items' => $data->values(),
        ]);
    }

    public function searchServices(SubCategory $subcategory) {
        $service = $subcategory->services()->where('status', 'approved')->first();

        if (!$service) {
            abort(404);
        }

        return redirect()->route(
            'front.category',
            $service->category->category_slug
        );
    }
}