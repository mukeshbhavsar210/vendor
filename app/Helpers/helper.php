<?php

use App\Models\Category;
use App\Models\Service;
use App\Models\State;
use App\Models\Wishlist;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Page;
use App\Models\ProductImage;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;    

    function homeCategories() {
        return Category::with([
            'subCategories' => function ($query) {
                $query->whereNotNull('image')
                    ->where('image', '!=', '')
                    ->with([
                        'subSubCategories',
                        'services'
                    ]);
            }
        ])
        ->withCount('services')
        ->where('status', 1)
        ->orderBy('menu_order', 'asc')
        ->orderBy('id', 'DESC')
        ->take(20)
        ->get();
    }

    function getCategories() {
        return Category::with(['subCategories'])->with('ratings')->where('showHome', 'yes')->orderBy('menu_order', 'ASC')->take(20)->get();
    }

    // function getServices() {
    //     return Service::with(['category','subCategories','ratings'])->take(20)->get();
    // }

    function getSubCategories() {
        return SubCategory::where('status', 1)->take(20)->get();
    }

    function getModalCategories() {
        return Category::where('showHome', 'no')->where('status', 1)
            ->orderBy('menu_order', 'ASC')
            ->take(6)
            ->get();
    }
   

    function getProductImage($productId){
        return ProductImage::where('product_id',$productId)->first();
    }

    function wishlistCount() {
        if (Auth::check()) {
            return Wishlist::where('user_id', Auth::id())->count();
        }
        return 0;
    }

    function timeAgo($date) {
        $diff = Carbon::now()->diffInSeconds($date);

        if ($diff < 60) return $diff . ' sec';
        if ($diff < 3600) return floor($diff / 60) . ' min';
        if ($diff < 86400) return floor($diff / 3600) . ' hr';
        if ($diff < 2592000) return floor($diff / 86400) . ' day';

        return $date->format('d M Y');
    }

    function orderEmail($orderId, $userType="customer"){
        $order = Order::where('id',$orderId)->with('items')->first();

        if($userType == 'customer'){
            $subject = 'Thanks for your order';
            $email = $order->email;
        } else {
            $subject = 'You have received an order';
            $email = env('ADMIN_EMAIL');
        }

        $mailData = [
            'subject' => $subject,
            'order' => $order,
            'userType' => $userType,
        ];

        //Mail::to($email)->send(new OrderEmail($mailData));
    }

    function getStateInfo($id){
        return State::where('id',$id)->first();
    }

    function staticPages(){
        $pages = Page::orderBy('menu_order','ASC')->get();
        return $pages;
    }


    if (!function_exists('currentUserName')) {
        function currentUserName()
        {
            return Auth::check() ? Auth::user()->name : null;
        }
    }
    
?>