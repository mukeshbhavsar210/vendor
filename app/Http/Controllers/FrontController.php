<?php

namespace App\Http\Controllers;

use App\Models\DealStockNotification;
use App\Models\Order;
use App\Models\Page;
use App\Models\Service;
use App\Models\StockNotification;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller {
    public function index(){        
        $services = Service::with(['category', 'subCategories', 'ratings'])
            ->where('status', 'approved')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MIN(id)')->from('services')->where('status', 'approved')->groupBy('category_id');
            })->take(20)->get();

        function getServicesByCategorySlug($categorySlug) {
            return Service::with(['category', 'subCategory', 'ratings'])
                ->whereHas('category', function ($query) use ($categorySlug) {
                    $query->where('category_slug', $categorySlug);
                })
                ->where('status', 'approved')->get()->groupBy('category_id');
        }

        $women_spa = getServicesByCategorySlug('womens-salon-spa');
        $men_spa = getServicesByCategorySlug('mens-salon-massage');
        $appliances = getServicesByCategorySlug('ac-appliance-repair');
        $installation = getServicesByCategorySlug('home-repair-&-installation');

        $data['services'] = $services;
        $data['women_spa'] = $women_spa;
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