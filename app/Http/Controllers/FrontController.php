<?php

namespace App\Http\Controllers;

use App\Models\DealStockNotification;
use App\Models\Order;
use App\Models\Page;
use App\Models\Service;
use App\Models\StockNotification;
use App\Models\SubCategory;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller {
    public function index(){

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

}