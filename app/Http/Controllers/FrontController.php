<?php

namespace App\Http\Controllers;

use App\Models\AffiliateProduct;
use App\Models\AffiliateWishlist;
use App\Models\DealStockNotification;
use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\StockNotification;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller {
    public function index(){
        $products = Product::where('is_featured','Yes')->orderBy('id','DESC')->take(4)->where('status',1)->get();
        $latestProducts = Product::orderBy('id','DESC')->where('status',1)->take(4)->get();

        $data['latestProducts'] = $latestProducts;
        $data['featuredProducts'] = $products;    

        return view("front.products.home",$data);
    }


    public function addToWishlist(Request $request){
        if(Auth::check() == false){
            session(['url.intended' => url()->previous() ]);
            return response()->json([
                'status' => false,
            ]);
        }

        $product = Product::find($request->id);

        if ($product == null){
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product not found.</div>'
            ]);
        }

        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ],
        );

        return response()->json([
            'status' => true,
            'message' => $product->title.' added in yout wishlist!'
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

    

    public function like($id) {
        $product = AffiliateProduct::findOrFail($id);

        // Prevent multiple likes (basic using session)
        if (!session()->has('liked_product_'.$id)) {
            $product->increment('likes');
            session()->put('liked_product_'.$id, true);

            return response()->json([
                'success' => true,
                'likes' => $product->likes
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Already liked'
        ]);
    }


    public function view($id) {
        $product = AffiliateProduct::findOrFail($id);

        // Optional: prevent multiple counts within session
        if (!session()->has('viewed_product_'.$id)) {

            $product->increment('views');

            session()->put('viewed_product_'.$id, true);
        }

        return response()->json([
            'success' => true,
            'views' => $product->views
        ]);
    }


    public function addToAffiliate(Request $request){
        if(Auth::check() == false){
            session(['url.intended' => url()->previous() ]);
            return response()->json([
                'status' => false,
            ]);
        }

        $product = AffiliateProduct::find($request->id);

        if ($product == null){
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product not found.</div>'
            ]);
        }

        AffiliateWishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'affiliate_product_id' => $request->id,
            ],
            [
                'user_id' => Auth::user()->id,
                'affiliate_product_id' => $request->id,
            ],
        );

        return response()->json([
            'status' => true,
            'message' => $product->title.' added in yout Deals wishlist!'
        ]);
    }



    public function addToNotify(Request $request) {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Please login first'
            ]);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ]);
        }

        // Optional (but smart): only allow if out of stock
        if ($product->stock > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Product is already in stock'
            ]);
        }

        // prevent duplicate
        $exists = StockNotification::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'You already requested notification'
            ]);
        }

        StockNotification::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'notified' => 0
        ]);

        return response()->json([
            'status' => true,
            'message' => 'You will be notified when product is back in stock'
        ]);
    }


    public function faqs() {
        return view('front.faqs');
    }


    public function deals() {
        $affiliateProducts = AffiliateProduct::latest()->get();

        $affiliateProductIds = [];

        if (Auth::check()) {
            $affiliateProductIds = AffiliateWishlist::where('user_id', Auth::id())
                ->pluck('affiliate_product_id')
                ->toArray();
        }    

        $notifiedIds = DealStockNotification::where('user_id', Auth::id())
                    ->pluck('affiliate_product_id')
                    ->toArray();

        return view('front.affiliateDeals', compact('affiliateProducts', 'affiliateProductIds', 'notifiedIds'));
    }


    public function addToAffiliateNotify(Request $request) {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Please login first'
            ]);
        }

        $request->validate([
            'affiliate_product_id' => 'required|exists:affiliate_products,id'
        ]);

        $product = AffiliateProduct::find($request->affiliate_product_id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ]);
        }

        $exists = DealStockNotification::where('user_id', Auth::id())
            ->where('affiliate_product_id', $request->affiliate_product_id)
            ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'You already requested notification'
            ]);
        }

        DealStockNotification::create([
            'user_id' => Auth::id(),
            'affiliate_product_id' => $request->affiliate_product_id,
            'notified' => 0
        ]);

        return response()->json([
            'status' => true,
            'message' => 'You will be notified when deal is available'
        ]);
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

}