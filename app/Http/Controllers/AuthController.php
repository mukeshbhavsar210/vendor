<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\Review;
use App\Models\State;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail;
use App\Models\AffiliateWishlist;
use App\Models\DealStockNotification;
use App\Models\StockNotification;
use Carbon\Carbon;
use Illuminate\Support\Str;
use SebastianBergmann\Environment\Console;

class AuthController extends Controller {
    public function login(Request $request){
        return view('front.account.login');
    }

    public function register(Request $request){
        return view('front.account.register');
    }

    public function processRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
        ]);

        if($validator->passes()) {
            $plainPassword = $request->password;

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = Hash::make($plainPassword);            
            $colors = ['#FF5733', '#33B5E5', '#2ECC71', '#9B59B6', '#F39C12', '#E74C3C', '#1ABC9C', '#34495E'];
            $user->avatar_color = $colors[array_rand($colors)];
            $user->save();

            // ✅ Send confirmation email
            //Mail::to($user->email)->send(new UserRegisteredMail($user, $plainPassword));

            session()->flash('success', 'You have registered successfully. Check your email for login details.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    

    public function authenticate(Request $request) {
        if ($request->has('redirect')) {
            session(['url.intended' => $request->redirect]);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            if (Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
                'is_active' => 1
            ], $request->get('remember'))) {

                $request->session()->regenerate();

                return redirect()->intended(route('account.dashboard'))
                    ->with('toast_success', 'Welcome back, ' . Auth::user()->name . '!');
            } else {
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Either email/password is incorrect.');
            }

        } else {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    }

    // public function dashboard(){
    //     $userId = Auth::user()->id;        
    //     $user = User::where('id',$userId)->first();        

    //     return view('front.account.dashboard',[
    //         'user' => $user,                        
    //     ]);
    // }

    public function dashboard(){
        $userId = Auth::user()->id;        
        $user = User::where('id',$userId)->first();   
        
        $notifications = StockNotification::where('user_id', Auth::id())
            ->with('product')->latest()->get();

        $data = [
            'user' => $user,                    
            'notifications' => $notifications, 
            'profileFormConfig' => [
                'title' => 'Edit Profile',
                'modal_id' => 'editProfileModal',                
                'action' => route('account.updateProfile'),
                'modal_size' => null,
                'method' => 'POST',
                'button' => 'Update Profile',
                'button_class' => 'w-100',          
                'modal_body' => null,
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'Name',                        
                        'animate_label' => 'floating-input',
                        'col' => 'col-md-6 col-12 mt-2'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'mobile',
                        'label' => 'Mobile',                        
                        'animate_label' => 'floating-input',
                        'col' => 'col-md-6 col-12 mt-2'
                    ],
                    [
                        'type' => 'email',
                        'name' => 'email',
                        'label' => 'Email',                        
                        'animate_label' => 'floating-input',
                        'col' => 'col-md-6 col-12 mt-2'
                    ],                    
                    [
                        'type' => 'text',
                        'name' => 'alternate_mobile',
                        'label' => 'Alternate Mobile',                        
                        'animate_label' => 'floating-input',
                        'col' => 'col-md-6 col-12 mt-2'
                    ],                                   
                    [
                        'type' => 'date',
                        'name' => 'birthdate',
                        'label' => 'Birthdate',                        
                        'animate_label' => 'floating-input',
                        'col' => 'col-md-6 col-12 mt-2'
                    ],
                    [
                        'type' => 'file',
                        'name' => 'image',
                        'label' => 'User Photo',
                        'col' => 'col-md-6 col-12'
                    ],
                    [
                        'type' => 'radio',
                        'name' => 'gender',
                        'label' => 'Gender',
                        'options' => [
                            'male' => 'Male',
                            'female' => 'Female',
                        ],
                        'col' => 'col-md-6 col-12'
                    ],                         
                ]
            ],        
        ];  

        return view('front.account.dashboard', $data);
    }

    
    public function notifications(){
        $userId = Auth::user()->id;        
        $user = User::where('id',$userId)->first();   
        
        $notifications = StockNotification::where('user_id', Auth::id())
            ->with('product')->latest()->get();

        $affiliate_notifications = DealStockNotification::where('user_id', Auth::id())
            ->with('product')->latest()->get();

        $data = [
            'user' => $user,                    
            'notifications' => $notifications,
            'affiliate_notifications' => $affiliate_notifications,
        ];  

        return view('front.account.notification', $data);
    }

    public function address(){
        $userId = Auth::user()->id;
        $user = User::where('id',$userId)->first();        
        $address = auth()->user()->addresses()->with('state')->get();                
        $addressTypes = CustomerAddress::pluck('address_type')->toArray();        
        $states = State::orderBy('name', 'ASC')->get(); 
        $delivery_address = CustomerAddress::with('state')->get();
        $defaultAddress = CustomerAddress::where('user_id', auth()->id())
            ->where('default_address', 1)
            ->exists(); 
        $homeExists = CustomerAddress::where('user_id', auth()->id())
            ->where('address_type', 'Home')
            ->exists();        
        $coupons = DiscountCoupon::orderBy('name','ASC')->get();    
        
        $data = [
            'user'   => $user,       
            'states' => $states,
            'address' => $address,
            'defaultAddress' => $defaultAddress,
            'homeExists' => $homeExists,
            'addressTypes' => $addressTypes, 
            'delivery_address' => $delivery_address,
            'coupons' => $coupons,
                  
            
        //     'createAddress' => [
        //         'title' => 'Create Address',
        //         'modal_id' => 'createAddressModal',
        //         'action' => route('customer.address.store'),
        //         'modal_size' => null,
        //         'method' => 'POST',
        //         'button' => 'Create Address',
        //         'button_class' => 'w-100',
        //         'modal_body' => 'customer-address',
        //         'fields' => [
        //             [
        //                 'type' => 'text',
        //                 'name' => 'name',
        //                 'label' => 'Name',                        
        //                 'animate_label' => 'floating-input',
        //                 'col' => 'col-6 mt-2'
        //             ],
        //             [
        //                 'type' => 'text',
        //                 'name' => 'mobile',
        //                 'label' => 'Mobile',                        
        //                 'animate_label' => 'floating-input',
        //                 'col' => 'col-6 mt-2'
        //             ],
        //             [
        //                 'type' => 'textarea',
        //                 'name' => 'address',
        //                 'label' => 'address',                        
        //                 'animate_label' => 'floating-input',
        //                 'col' => 'col-12'
        //             ],
        //             [
        //                 'type' => 'text',
        //                 'name' => 'locality',
        //                 'label' => 'Locality',                        
        //                 'animate_label' => 'floating-input',
        //                 'col' => 'col-6'
        //             ],
        //             [
        //                 'type' => 'text',
        //                 'name' => 'city',
        //                 'label' => 'City',                        
        //                 'animate_label' => 'floating-input',
        //                 'col' => 'col-6'
        //             ],
        //             [
        //                 'type' => 'select',
        //                 'name' => 'state',
        //                 'label' => 'State',
        //                 'options' => $states->pluck('name','id')->toArray(),
        //                 'animate_label' => null,
        //                 'col' => 'col-6'
        //             ],
        //             [
        //                 'type' => 'text',
        //                 'name' => 'zip',
        //                 'label' => 'Pin Code',                        
        //                 'animate_label' => 'floating-input',
        //                 'col' => 'col-6'
        //             ],                    
        //             [
        //                 'type' => 'radio',
        //                 'name' => 'address_type',
        //                 'label' => 'Address Type',
        //                 'default' => 'Home',
        //                 'options' => [
        //                     'Home' => 'Home',
        //                     'Office' => 'Office',
        //                 ],
        //                 'animate_label' => null,
        //                 'col' => 'col-12'
        //             ],
        //             [
        //                 'type' => 'checkbox',
        //                 'name' => 'default_address',
        //                 'label' => 'Make this as my default Address',    
        //                 'default' => 'Select State',                    
        //                 'animate_label' => null,
        //                 'col' => 'col-12'
        //             ],
                    
        //         ]
        //     ],
        

        // 'EditAddress' => [
        //         'title' => 'Edit Address',
        //         'modal_id' => 'editAddressModal',   
        //         'action' => route('account.processChangePassword'),
        //         'method' => 'POST',
        //         'button' => 'Edit Password',
        //         'button_class' => 'w-100',   
        //         'modal_body' => 'customer-address',             
        //         'fields' => [
        //             [
        //                 'type' => 'password',
        //                 'name' => 'current_password',
        //                 'label' => 'Current Password',
        //                 'col' => 'col-md-12'
        //             ],
        //             [
        //                 'type' => 'password',
        //                 'name' => 'new_password',
        //                 'label' => 'New Password',
        //                 'col' => 'col-md-12'
        //             ],
        //             [
        //                 'type' => 'password',
        //                 'name' => 'new_password_confirmation',
        //                 'label' => 'Confirm Password',
        //                 'col' => 'col-md-12'
        //             ],
        //         ]
        //     ]
        ];  

        return view('front.account.address', $data);
    }

    public function updateAddress(Request $request){
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5',            
            'mobile' => 'required',            
            'address' => 'required|min:30',
            'city' => 'required',            
            'zip' => 'required'
        ]);

        if($validator->passes()){
            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'address_type' => $request->address_type,
                    'default_address' => $request->has('default_address') ? 1 : 0,
                    'name' => $request->name,                    
                    'mobile' => $request->mobile,
                    'email' => $request->email,
                    'address' => $request->address,
                    'locality' => $request->locality,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'state_id' => $request->state_id
                ]
            );

            session()->flash('success','Address updated successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully.'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ]);
        }
    }    

    public function profile(){
        $userId = Auth::user()->id;
        $state = State::orderBy('name','ASC')->get();
        $user = User::where('id',$userId)->first();
        $address = CustomerAddress::where('user_id',$userId)->first();

        $data = [
            'user' => $user,                    
            'profileFormConfig' => [
                'title' => 'Edit Profile',
                'modal_id' => 'editProfileModal',                
                'action' => route('account.updateProfile'),
                'modal_size' => null,
                'method' => 'POST',
                'button' => 'Update Profile',
                'button_class' => 'w-100',          
                'modal_body' => null,
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'Name',                        
                        'animate_label' => 'floating-input',
                        'col' => 'col-md-6 col-12 mt-2'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'mobile',
                        'label' => 'Mobile',                        
                        'animate_label' => 'floating-input',
                        'col' => 'col-md-6 col-12 mt-2'
                    ],
                    [
                        'type' => 'email',
                        'name' => 'email',
                        'label' => 'Email',                        
                        'animate_label' => 'floating-input',
                        'col' => 'col-md-12 col-12 mt-2'
                    ],                    
                    [
                        'type' => 'text',
                        'name' => 'alternate_mobile',
                        'label' => 'Alternate Mobile',                        
                        'animate_label' => 'floating-input',
                        'col' => 'col-md-6 col-12'
                    ],                                   
                    [
                        'type' => 'date',
                        'name' => 'birthdate',
                        'label' => 'Birthdate',                        
                        'animate_label' => 'floating-input',
                        'col' => 'col-md-6 col-12'
                    ],
                    [
                        'type' => 'file',
                        'name' => 'image',
                        'label' => 'User Photo',
                        'col' => 'col-md-6 col-12'
                    ],
                    [
                        'type' => 'radio',
                        'name' => 'gender',
                        'label' => 'Gender',
                        'options' => [
                            'male' => 'Male',
                            'female' => 'Female',
                        ],
                        'col' => 'col-md-6 col-12'
                    ],                         
                ]
            ],        

        'passwordFormConfig' => [
                'title' => 'Change Password',
                'modal_id' => 'editPasswordModal',   
                'action' => route('account.processChangePassword'),
                'modal_size' => 'modal-sm',
                'method' => 'POST',
                'button' => 'Update Password',
                'button_class' => 'w-100',
                'modal_body' => null,      
                'fields' => [
                    [
                        'type' => 'password',
                        'name' => 'current_password',
                        'label' => 'Current Password',
                        'col' => 'col-12'
                    ],
                    [
                        'type' => 'password',
                        'name' => 'new_password',
                        'label' => 'New Password',
                        'col' => 'col-12'
                    ],
                    [
                        'type' => 'password',
                        'name' => 'new_password_confirmation',
                        'label' => 'Confirm Password',
                        'col' => 'col-12'
                    ],
                ]
            ]
        ];  

        return view('front.account.profile', $data);
    }

    public function updateProfile(Request $request){
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'mobile' => 'required',
        ]);

        if($validator->passes()){
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->alternate_mobile = $request->alternate_mobile;
            $user->birthdate = $request->birthdate;
            $user->gender = $request->gender;
            $user->save();

            session()->flash('success','Profile updated successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully.'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ]);
        }
    }

    public function logout(){
        Auth::logout();

        return redirect()->route('front.home')->with('success','You successfully logged out!');;
    }

    public function orders(Request $request) {
        $user = Auth::user();

        $query = Order::with([
            'items.product',
            'items.product.color',
            'items.product.size',
            'items.color',
            'items.size',
            'statusHistories',
            'latestStatus'
        ])->where('user_id', $user->id);

        $statuses = [
            '' => 'All',
            'Out for Delivery' => 'On the way',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'returned' => 'Returned',
            'exchanged' => 'Exchanged'
        ];

        $time = [
            'anytime' => 'Anytime',
            '30_days' => 'Last 30 days',
            '6_months' => 'Last 6 months',
            '1_year' => 'Last year'
        ];

        // Status Filter
        if ($request->status) {
            $query->whereHas('latestStatus', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Time Filter
        if ($request->time == '30_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        }

        if ($request->time == '6_months') {
            $query->where('created_at', '>=', Carbon::now()->subMonths(6));
        }

        if ($request->time == '1_year') {
            $query->where('created_at', '>=', Carbon::now()->subYear());
        }
        

        // Get Orders
        $orders = $query->latest()->paginate(10);

        // Cancelled Items Count
        // $totalCancelledItems = $orders->sum(function ($order) {
        //     return $order->items->sum('qty');
        // });        

        return view('front.account.orders.index', [
            'orders' => $orders,
            'time' => $time,
            'statuses' => $statuses,
            //'totalCancelledItems' => $totalCancelledItems
        ]);
    }
    
    public function orderDetail($id) {
        $user = Auth::user();
        $order = Order::with(['orderItems','latestStatus'])->where('user_id',$user->id)->where('id',$id)->firstOrFail();
        $orderItems = OrderItem::with('product')->where('order_id',$id)->get();      

        $productIds = $orderItems->pluck('product_id');
        $userReviews = Review::whereIn('product_id', $productIds)
                        ->where('user_id', auth()->id())
                        ->get()
                        ->keyBy('product_id');

        $data['order'] = $order;
        $data['orderItems'] = $orderItems;
        $data['orderItemsCount'] = $orderItems->count();

        // Get first product from order items
        $product = $orderItems->first()->product ?? null;

        $relatedProducts = [];

        if ($product && $product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)
                ->where('status',1)
                ->with('product_images')
                ->get();
        }

        $data['relatedProducts'] = $relatedProducts;
        $data['userReviews'] = $userReviews;            

        // dd($orderItems->discounted_price);
        // dd($order);

        return view('front.account.orders.detail', $data);
    }

    // public function cancelOrder(Request $request, Order $order) {
    //     // Security check
    //     if ($order->user_id !== auth()->id()) {
    //         abort(403);
    //     }

    //     // Prevent cancelling shipped/completed orders
    //     // if (!in_array($order->status, ['pending', 'processing'])) {
    //     //     return back()->with('error', 'Order cannot be cancelled.');
    //     // }

    //     $request->validate([
    //         'cancel_reason' => 'required|string',
    //         'cancel_comments' => 'nullable|string|max:500',
    //     ]);

    //     $order->update([
    //         'status' => 'cancelled',
    //         'cancel_reason' => $request->cancel_reason,
    //         'cancel_comments' => $request->cancel_comments,
    //         'cancelled_at' => now(),
    //     ]);

    //     return redirect()
    //         ->route('account.orders')
    //         ->with('success', 'Order cancelled successfully.');
    // }


    public function cancelOrder(Request $request, Order $order) {
        // Security check
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'cancel_reason' => 'required|string',
            'cancel_comments' => 'nullable|string|max:500',
        ]);

        // Store cancellation in order_status_histories
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'cancel_reason' => $request->cancel_reason,
            'cancel_comments' => $request->cancel_comments,
            'courier' => null,
            'date' => now()
        ]);

        return redirect()
            ->route('account.orders')
            ->with('success', 'Order cancelled successfully.');
    }

    
    public function wishlist(){
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->with(['product'])->get();

        $data['wishlists'] = $wishlists;

        return view('front.account.wishlist', $data);
    }


    public function dealsWishlist(){
        $deals = AffiliateWishlist::where('user_id', Auth::id())->with('affiliate_product')->get();

        $data['deals'] = $deals;

        return view('front.account.deals', $data);
    }


    public function tracking($id) {
        $statuses = OrderStatusHistory::where('order_id', $id)
            ->orderBy('date','asc')
            ->get();

        $data = [];

        foreach($statuses as $status){
            $data[] = [
                'status' => $status->status,
                'date' => \Carbon\Carbon::parse($status->date)
                            ->format('d M Y h:i A')
            ];
        }        

        return response()->json($data);
    }


    public function removeProductFromWishlist(Request $request) {
        if (!Auth::check()) {
            return response()->json(['status' => false]);
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'status' => true,
                'message' => 'Item removed from wishlist',
                'wishlistCount' => Wishlist::where('user_id', Auth::id())->count()
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Item removed from wishlist'
        ]);
    }


    public function removeProductFromDealsWishlist(Request $request) {
        if (!Auth::check()) {
            return response()->json(['status' => false, 'message' => 'Unauthorized']);
        }

        AffiliateWishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'affiliate_product_id' => $request->id
        ]);

        $deleted = AffiliateWishlist::where('user_id', Auth::id())
            ->where('affiliate_product_id', $request->id)
            ->delete();

        if ($deleted) {
            return response()->json([
                'status' => true,
                'message' => 'Item removed from deals',
                'dealCount' => AffiliateWishlist::where('user_id', Auth::id())->count()
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Item not found in deals'
        ]);
    }

    // public function removeProductFromWishlist(Request $request){
    //     $wishlist = Wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->first();

    //     if($wishlist == null){
    //         session()->flash('error','Product already removed.');
    //         return response()->json([
    //             'status' => true,
    //         ]);
    //     } else {
    //         Wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->delete();
    //         session()->flash('success','Product removed successfully.');
    //         return response()->json([
    //             'status' => true,
    //         ]);
    //     }
    // }   

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required'
        ]);

        if ($validator->passes()) {
            $user = User::select('id','password')->where('id',Auth::user()->id)->first();

            if(!Hash::check($request->old_password,$user->password)){
                session()->flash('error','Your old password is incorrect, please try again.');
                return response()->json([
                    'status' => true,
                ]);
            }

            User::where('id',$user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            session()->flash('success','You have successfully changed your password.');
            return response()->json([
                'status' => true,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' =>  $validator->errors()
            ]);
        }
    }


    public function address_store(Request $request) {
        $validated = $request->validate([
            'address_type' => 'required',
            //'default_address' => 'required',
            'name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'locality' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'zip' => 'required',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['state_id'] = $validated['state_id'];

        // If new address is default
        if($request->default_address == 1){
            CustomerAddress::where('user_id', auth()->id())
                ->update(['default_address' => 0]);
        }

        CustomerAddress::create($validated);

        return back()->with('success', 'Shipping address added successfully');
    }
    

    public function address_update(Request $request, $id) {
        //$address = CustomerAddress::findOrFail($id);
        $address = CustomerAddress::where('user_id', auth()->id())->first();

        $validated = $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'zip' => 'required',
        ]);

        $address->update($validated);

        return back()->with('success', 'Shipping Address updated successfully');
    }


    public function cards(){
        $userId = Auth::user()->id;
        $countries = State::orderBy('name','ASC')->get();
        $user = User::where('id',$userId)->first();
        $address = CustomerAddress::where('user_id',$userId)->first();

        return view('front.account.cards',[
            'user' => $user,
            'countries' => $countries,
            'address' => $address
        ]);
    }

    public function coupons(Request $request){
        $coupons = DiscountCoupon::orderBy('name','ASC')->get();        

        $query = DiscountCoupon::query();
            switch ($request->sort) {
                case 'trending':
                    $query->orderBy('used_count', 'DESC'); 
                    break;

                case 'discount':
                    $query->orderBy('discount_amount', 'DESC');
                    break;

                case 'expiring':
                    $query->orderBy('expires_at', 'ASC');
                    break;

                default:
                    $query->orderBy('name', 'ASC'); // All
                    break;
            }

            $coupons = $query->get();

        return view('front.account.coupon',[
            'coupons' => $coupons,
        ]);
    }


    public function delete_account(){   
        return view('front.account.delete');
    }

    public function delete_account_action(Request $request) {
        $request->validate([
            'agree' => 'required'
        ]);

        $user = auth()->user();
        $user->is_active = 0;
        $user->save();

        auth()->logout();

        return redirect('/')->with('success','Your account has been deactivated.');
    }

    public function review_store(Request $request) {
        foreach ($request->rating as $product_id => $rating) {
            Review::create([
                'user_id' => auth()->id(),
                'product_id' => $product_id,
                'rating' => $rating,
                'review' => $request->review[$product_id] ?? null,
            ]);
        }

        return back()->with('success', 'Review submitted successfully!');
    }


    public function getOrderItems($id) {
        
        $orderItems = OrderItem::with('product')->where('order_id', $id)->get();
                    
        //return view('front.account.orders.index', compact('orderItems'))->render();
        return view('front.account.orders.ratings_modal', compact('orderItems'))->render();
    }
}