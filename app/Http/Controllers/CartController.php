<?php

namespace App\Http\Controllers;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\OrderStatusHistory;
use App\Models\ShippingCharge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariant;
use App\Models\State;
use App\Models\Wishlist;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller {
    public function addToCart(Request $request) {
        $product = Product::with(['product_images','variants','colors','discount'])
            ->findOrFail($request->product_id);

        // ✅ Color: user selected OR fallback to first
        $color_id = $request->color_id;
        // if (empty($color_id)) {
        //     $color_id = optional($product->colors->first())->id;
        // }

        // ✅ Size (optional)
        $size_id = $request->size_id ?? null;

        // ✅ Variant (optional)
        $variantId = $request->variant_id ?? null;
        $variant = null;

        if (!empty($variantId)) {
            $variant = $product->variants->where('id', $variantId)->first();
        }

        // ✅ FIX: Color logic
        if ($variant) {
            // 👉 Priority: variant color
            $color_id = $variant->color_id;
        } else {
            // 👉 fallback: request OR first product image color
            $color_id = $request->color_id 
                ?? optional($product->product_images->first())->color_id;
        }

        // ✅ Image selection (variant > product)
        $image = ($variant && $variant->image)
            ? $variant->image
            : optional($product->product_images->first())->image;

        // ✅ Prevent duplicate (product + variant + size + color)
        $alreadyExists = false;

        foreach (Cart::content() as $item) {
            if (
                $item->id == $product->id &&
                $item->options->variant_id == $variantId &&
                $item->options->size_id == $size_id &&
                $item->options->color_id == $color_id 
            ) {
                $alreadyExists = true;
                break;
            }
        }

        if (!$alreadyExists) {
            // ✅ Discount
            $discountPercent = (int) optional($product->discount)->percentage;

            $discount_price = $product->price;
            if ($discountPercent > 0) {
                $discount_price = $product->price - ($product->price * $discountPercent / 100);
            }

            Cart::add([
                'id'      => $product->id,
                'name'    => $product->title,
                'qty'     => 1,
                'price'   => round($product->price),
                'weight'  => 0,
                'options' => [
                    'original_price'    => $product->price,
                    'discount_price'    => round($discount_price),
                    'discount_percent'  => $discountPercent,
                    'short_description' => $product->short_description,
                    'productImage'      => $image,
                    'variant_id'        => $variantId,
                    'size_id'           => $size_id,
                    'color_id'          => $color_id, 
                    'cod'               => $product->cod,
                    'return_days'       => $product->return_days,
                    'delivery_min_days' => $product->delivery_min_days,
                    'delivery_max_days' => $product->delivery_max_days,
                ]
            ]);

            $status  = true;
            $message = $product->title . ' added to Bag.';
            session()->flash('success', $message);

        } else {
            $status  = false;
            $message = $product->title . ' already added in cart';
        }

        return response()->json([
            "status"    => $status,
            "message"   => $message,
            "cartCount" => Cart::count(),
        ]);
    }

     public function cart() {
        $cartContent = Cart::content();
        $appliedCouponId = session('coupon_discount.id'); 

        $coupons = DiscountCoupon::where('status', 1)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now());
            })
            ->get();   
        
        if(auth()->check()){
            $address = auth()->user()->addresses()->with('state')->get();
            $customerAddress = Auth::user()->address;
        }else{
            $address = collect(); // empty collection
        }
        $addressTypes = CustomerAddress::pluck('address_type')->toArray();        
        $states = State::orderBy('name', 'ASC')->get(); 
        $delivery_address = CustomerAddress::where('user_id', auth()->id())
                    ->orderByDesc('default_address')
                    ->get();
        $homeExists = CustomerAddress::where('user_id', auth()->id())
            ->where('address_type', 'Home')
            ->exists();        

        $qty = Cart::count();
        $selectedIds = $request->cart_ids ?? [];
        $shipping_charge = 0;
        $customerAddress = CustomerAddress::where('user_id', Auth::id())->first();        

        if($customerAddress){
            $shippingInfo = ShippingCharge::where('state_id', $customerAddress->state_id)->first();

            if($shippingInfo && Cart::count() > 0){
                $shipping_charge = $shippingInfo->amount;
            }
        }

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
        //dd(session('coupon_discount'));                

        return view('front.checkout.cart', [
            'discount_price'        => $discount_price,                        
            'store_discount'        => $store_discount,
            'coupon_code'           => $coupon_code,
            'coupon_discount'       => $coupon_discount,
            'homeExists'            => $homeExists,            
            'delivery_address'      => $delivery_address,
            'address'               => $address,
            'addressTypes'          => $addressTypes,
            'states'                => $states,
            'cartContent'           => $cartContent,
            'coupons'               => $coupons,
            'appliedCouponId'       => $appliedCouponId,
            'shipping_charge'       => $shipping_charge,
            'hasValidCoupon'        => $hasValidCoupon
        ]);
    } 

    public function wishlistToCart(Request $request) { 
        $product = Product::with(['product_images','variants','discount'])->find($request->product_id);

        if (!$product) {
            return response()->json([
                "status" => false,
                "message" => "Product not found"
            ]);
        }

        $variantId = $request->variant_id;
        $size_id      = $request->size_id ?? null;
        $color_id     = $request->color_id ?? null;

        // Get selected variant (if exists)
        $variant = null;
        if (!empty($variantId)) {
            $variant = $product->variants->where('id', $variantId)->first();
        }

        // Determine correct image
        $image = $variant && $variant->image
                    ? $variant->image
                    : optional($product->product_images->first())->image;

        // Unique rowId check (product + variant + size)
        $alreadyExists = false;

        foreach (Cart::content() as $item) {
            if (
                $item->id == $product->id &&
                $item->options->variant_id == $variantId &&
                $item->options->size_id == $size_id &&
                $item->options->color_id == $color_id
            ) {
                $alreadyExists = true;
                break;
            }
        }

        if (!$alreadyExists) {  
            
            $discountPercent = 0;

            if ($product->discount) {
                $discountPercent = $product->discount->percentage;
            }
            // ✅ Get discount percent safely
            $discountPercent = (int) optional($product->discount)->percentage;
            //$discountPercent = optional($product->discounts->first())->percentage ?? 0;

            // ✅ Calculate discount price
            $discount_price = $product->price;

            if ($discountPercent > 0) {
                $discount_price = $product->price - ($product->price * $discountPercent / 100);
            }

            // $discountPercent = optional($product->discounts->first())->percentage ?? 0;
            // $discount_price = $product->price;
            // if ($discountPercent > 0) {
            //     $discount_price = $product->price - ($product->price * $discountPercent / 100);
            // }

            Cart::add([
                'id'      => $product->id,
                'name'    => $product->title,
                'qty'     => 1,                
                'price'   => round($product->price),                
                'weight'  => 0,
                'options' => [
                    'original_price'    => $product->price,
                    'discount_price'    => round($discount_price),
                    'discount_percent'  => $discountPercent,                                  
                    'short_description' => $product->short_description,                    
                    'productImage'      => $image,
                    'variant_id'        => $variantId,
                    'size_id'           => $size_id,
                    'color_id'          => $color_id,
                    'cod'               => $product->cod,
                    'return_days'       => $product->return_days,
                    'delivery_min_days' => $product->delivery_min_days,
                    'delivery_max_days' => $product->delivery_max_days,
                ]
            ]);

            // ✅ Remove from wishlist
            Wishlist::where('id', $request->wishlist_id)
                    ->where('user_id', auth()->id())
                    ->delete();

            $status  = true;
            $message = $product->title . ' added to Bag.';
            session()->flash('success', $message);
        } else {
            $status  = false;
            $message = $product->title.' already added in cart';
        }
        return response()->json([
            "status"    => $status,
            "message"   => $message,
            "cartCount" => Cart::count(),
        ]);
    }

    // public function processCheckout(Request $request) {
    //     // ✅ Ensure user logged in
    //     if (!auth()->check()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'User not logged in'
    //         ]);
    //     }

    //     // Step 1: Validate selected shipping address
    //     $validator = Validator::make($request->all(), [
    //         'customer_address_id' => 'required|exists:customer_addresses,id',
    //         'payment_method' => 'required|in:cod,razorpay'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Please fix the errors',
    //             'status'  => false,
    //             'errors'  => $validator->errors()
    //         ]);
    //     }

    //     $user = Auth::user();

    //     // Step 2: Get Selected Address (Security Check: must belong to user)
    //     $selectedAddress = CustomerAddress::where('id', $request->customer_address_id)
    //         ->where('user_id', $user->id)
    //         ->first();

    //     if (!$selectedAddress) {
    //         return response()->json([
    //             'message' => 'Invalid shipping address selected.',
    //             'status'  => false
    //         ]);
    //     }

    //     // Step 3: Calculate Subtotal        
    //     $subTotal = 0;
    //     foreach (Cart::content() as $item) {
    //         $price = $item->options->discount_price ?? $item->price;
    //         $subTotal += $price * $item->qty;
    //     }

    //     $discount = 0;
    //     $shipping = 0;
    //     $discountCodeId = null;
    //     $promoCode = '';

        // Step 4: Apply Coupon (if exists)
        // if(session()->has('coupon_discount')){
        //     $coupon = session('coupon_discount');

        //     if($coupon['type'] == 'percent'){
        //         $discount = ($coupon['value'] / 100) * $subTotal;
        //     }else{
        //         $discount = $coupon['value'];
        //     }

        //     $discountCodeId = $coupon['id'];
        //     $promoCode = $coupon['code'];
        // }

    //     // Step 5: Calculate Shipping Based On Selected Address State
    //     $qty = Cart::content()->sum('qty');

    //     $shippingInfo = ShippingCharge::where('state_id', $selectedAddress->state_id)->first();

    //     if ($shippingInfo) {
    //         $shipping = $shippingInfo->amount ?? 0;
    //     } else {
    //         $restState = ShippingCharge::where('state_id', 'rest_of_state')->first();
    //         $shipping = $qty * ($restState->amount ?? 0);
    //     }
            
    //     // ✅ Prepare amounts
    //     $total = ($subTotal - $discount) + $shipping;
    //     $grandTotal = ($subTotal - $discount) + $shipping;

    //     // ✅ Create order first
    //     $order = Order::create([
    //         'user_id' => auth()->id(),
    //         'product_id' => $item->id,
    //         'product_variant_id' => $item->options->variant_id ?? null,
    //         'customer_address_id' => $request->customer_address_id,
    //         'payment_method' => $request->payment_method,
    //         'payment_status' => 'pending',
    //         'subtotal' => $subTotal,
    //         'grandtotal' => $total,
    //         'status' => 'Placed'
    //     ]);

    //     //Step 7: Get entried Order Status in OrderStatusHistory
    //     OrderStatusHistory::create([
    //         'order_id' => $order->id,
    //         'courier' => 'Shadofox',
    //         'note' => 'note',
    //         'status' => 'confirmed',
    //         'date' => now()
    //     ]);

    //     //Step 8: Store Order Items + Update Stock
    //     foreach (Cart::content() as $item) {
    //         $orderItem = new OrderItem;
    //         $orderItem->order_id = $order->id;
    //         $orderItem->product_id = $item->id;
    //         $orderItem->product_variant_id = $item->options->variant_id ?? null;
    //         $orderItem->size_id = $item->options->size_id;
    //         $orderItem->color_id = $item->options->color_id;
    //         $orderItem->discounted_price = $item->options->discount_price ?? null;
    //         $orderItem->discount_percent = $item->options->discount_percent ?? null;            
    //         $orderItem->qty = $item->qty;
    //         $orderItem->price = $item->price;                                               
    //         $orderItem->discount = $discount;            
    //         $orderItem->coupon_code = $promoCode;
    //         $orderItem->coupon_code_id = $discountCodeId;    
    //         $orderItem->shipping = $shipping;        
    //         $orderItem->subTotal = $subTotal;
    //         $orderItem->grandtotal = $grandTotal;                        
    //         $orderItem->return_days = $item->options->return_days ?? null;
    //         $orderItem->delivery_min_days = $item->options->delivery_min_days ?? null;
    //         $orderItem->delivery_max_days = $item->options->delivery_max_days ?? null;
    //         $orderItem->save();

    //         // Update Variant Stock
    //         $variant = ProductVariant::find($item->id);
    //         if ($variant) {
    //             $variant->qty -= $item->qty;
    //             $variant->save();
    //         }

    //         // Update Stock
    //         $product = Product::find($item->id);
    //         if ($product && $product->track_qty == 'Yes') {
    //             $product->qty -= $item->qty;
    //             $product->save();
    //         }
    //     }

    //     if($order->coupon_id){
    //         DiscountCoupon::where('id',$order->coupon_id)->increment('used_count');
    //     }

    //     // Step 9: Send Order Confirmation Email
    //     orderEmail($order->id, 'customer');

    //     // Step 10: Clear Cart & Coupon
    //     Cart::destroy();
    //     session()->forget('coupon_discount');

    //     // Step 11: Handle COD
    //     // ✅ COD FLOW
    //     if($request->payment_method == 'cod'){
    //         return response()->json([
    //             'status' => true,
    //             'orderId' => $order->id,
    //             'payment_method' => 'cod'
    //         ]);
    //     }

    //      // ✅ RAZORPAY FLOW
    //     if ($request->payment_method == 'razorpay') {
    //         $api = new Api(
    //             config('services.razorpay.key'),
    //             config('services.razorpay.secret')
    //         );

    //         $amount = (int) ($total * 100); // paise
    //         if ($amount < 100) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Minimum amount should be ₹1'
    //             ]);
    //         }

    //         $razorpayOrder = $api->order->create([
    //             'receipt' => (string) $order->id, // ✅ string required
    //             'amount' => $amount,
    //             'currency' => 'INR'
    //         ]);

    //         return response()->json([
    //             'status' => true,
    //             'orderId' => $order->id,
    //             'razorpay_order_id' => $razorpayOrder['id'],
    //             'amount' => $amount,
    //             'key' => config('services.razorpay.key')
    //         ]);
    //     }

    //     // Setp 12: Razorpay payment
    //     $grandTotal = $request->grand_total;
        
    //     $api = new Api(config('razorpay.key'),config('razorpay.secret'));        

    //     $orderData = [
    //         'receipt'         => 'order_'.$order->id,
    //         'amount'          => $grandTotal * 100, // Razorpay uses paise
    //         'currency'        => 'INR',            
    //     ];

    //     $razorpayOrder = $api->order->create($orderData);

    //     return response()->json([
    //         'message' => 'Order placed successfully.',
    //         'orderId' => $order->id,
    //         'payment_method' => $request->payment_method,
    //         'status'  => true,
    //     ]);

    //     // fallback
    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Invalid payment method'
    //     ]);
    // }


    private function createOrder($request, $selectedAddress, $total, $subTotal, $discount, $promoCode, $shipping, $discountCodeId) {
                

        // ✅ Prepare amounts
        $total = ($subTotal - $discount) + $shipping;
        $grandTotal = ($subTotal - $discount) + $shipping;

        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_address_id' => $selectedAddress->id,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'subtotal' => $subTotal,
            'grandtotal' => $total,
            'status' => 'Placed'
        ]);

        foreach (Cart::content() as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'product_variant_id' => $item->options->variant_id ?? null,
                'size_id' => $item->options->size_id ?? null,
                'color_id' => $item->options->color_id ?? null,
                'discount' => $discount,
                'coupon_code' => $promoCode,
                'coupon_id' => $discountCodeId,
                'qty' => $item->qty,
                'price' => $item->price,
                'discount_percent' => $item->options->discount_percent ?? null,
                'discounted_price' => $item->options->discount_price ?? null,                
                'shipping' => $shipping,
                'subtotal' => $subTotal,
                'grandtotal' => $total,
                'return_days' => $item->options->return_days ?? null,
                'delivery_min_days' => $item->options->delivery_min_days ?? null,
                'delivery_max_days' => $item->options->delivery_max_days ?? null,
            ]);
        }

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'courier' => 'Shadofox',
            'note' => 'note',
            'status' => 'confirmed',
            'date' => now()
        ]);

        // Update Variant Stock
        $variant = ProductVariant::find($item->id);
        if ($variant) {
            $variant->qty -= $item->qty;
            $variant->save();
        }

        // Update Stock
        $product = Product::find($item->id);
        if ($product && $product->track_qty == 'Yes') {
            $product->qty -= $item->qty;
            $product->save();
        }

        // Step 9: Send Order Confirmation Email
        orderEmail($order->id, 'customer');

       // Step 10: Clear Cart & Coupon
        Cart::destroy();
        session()->forget('coupon_discount');        

        return $order;
    }


    public function processCheckout(Request $request){
        if (!auth()->check()) {
            return response()->json(['status' => false]);
        }

        $validator = Validator::make($request->all(), [
            'customer_address_id' => 'required|exists:customer_addresses,id',
            'payment_method' => 'required|in:cod,razorpay'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        $user = Auth::user();

        $selectedAddress = CustomerAddress::where('id', $request->customer_address_id)
            ->where('user_id', $user->id)
            ->first();

        // ✅ totals
        $subTotal = 0;
        foreach (Cart::content() as $item) {
            $price = $item->options->discount_price ?? $item->price;
            $subTotal += $price * $item->qty;
        }

        $discount = 0;
        $promoCode = null;
        $discountCodeId = null;

        if(session()->has('coupon_discount')){
            $coupon = session('coupon_discount');

            $discount = $coupon['type'] == 'percent'
                ? ($coupon['value'] / 100) * $subTotal
                : $coupon['value'];

            $promoCode = $coupon['code'];
            $discountCodeId = $coupon['id'];
        }

        $shipping = ShippingCharge::where('state_id', $selectedAddress->state_id)->value('amount') ?? 0;

        $total = ($subTotal - $discount) + $shipping;

        // ============================
        // ✅ COD
        // ============================
        if($request->payment_method == 'cod'){

            $order = $this->createOrder($request, $selectedAddress, $total, $subTotal, $discount, $promoCode, $shipping, $discountCodeId);

            session()->forget(['coupon_discount']);

            return response()->json([
                'status' => true,
                'orderId' => $order->id
            ]);
        }

        // ============================
        // ✅ Razorpay (NO ORDER)
        // ============================
        if ($request->payment_method == 'razorpay') {

            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $amount = (int) ($total * 100);

            $razorpayOrder = $api->order->create([
                'receipt' => uniqid(),
                'amount' => $amount,
                'currency' => 'INR'
            ]);

            // ✅ store everything needed
            session([
                'checkout_data' => [
                    'address_id' => $selectedAddress->id,
                    'total' => $total,
                    'subtotal' => $subTotal,
                    'discount' => $discount,
                    'shipping' => $shipping,
                    'coupon_code' => $promoCode,
                    'coupon_id' => $discountCodeId
                ]
            ]);

            return response()->json([
                'status' => true,
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => $amount,
                'key' => config('services.razorpay.key')
            ]);
        }

        return response()->json(['status' => false]);
    }


    public function verifyPayment(Request $request) {
        $data = session('checkout_data');

        if(!$data){
            return response()->json(['status' => false]);
        }

        $selectedAddress = CustomerAddress::find($data['address_id']);

        $order = $this->createOrder(
            new Request(['payment_method' => 'razorpay']),
            $selectedAddress,
            $data['total'],
            $data['subtotal'],
            $data['discount'],
            $data['coupon_code'],
            $data['shipping'],
            $data['coupon_id']
        );

        $order->update([
            'transaction_id' => $request->razorpay_payment_id,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_signature' => $request->razorpay_signature,
            'payment_status' => 'paid'
        ]);

        session()->forget(['coupon_discount','checkout_data']);

        return response()->json([
            'status' => 'success',
            'orderId' => $order->id
        ]);
    }

    public function thankyou($id){
        $order = Order::where('id', $id)
                ->where('user_id', auth()->id()) // 🔐 security
                ->with([
                    'orderItems.product',
                    'orderItems.product.images',
                    'orderItems.variant',
                    'address.state'
                ])
                ->firstOrFail();

        return view('front.checkout.thanks',[
            'id' => $id,
            'order' => $order,
        ]);
    }

    public function updateDefaultAddress(Request $request) {
        $request->validate([
            'address_id' => 'required'
        ]);
        
        $addressId = $request->address_id;
        $userId = auth()->id();

        CustomerAddress::where('user_id', $userId)->update([
            'default_address' => 0
        ]);

        CustomerAddress::where('id', $addressId)->update([
            'default_address' => 1
        ]);

        // DELETE ADDRESS
        if($request->action == 'delete'){
            $address = CustomerAddress::where('id',$addressId)
                        ->where('user_id',$userId)
                        ->first();

            $wasDefault = $address->default_address;
            $address->delete();

            // If deleted address was default
            if($wasDefault == 1){
                $otherAddress = CustomerAddress::where('user_id',$userId)->first();
                if($otherAddress){
                    $otherAddress->update([
                        'default_address' => 1
                    ]);
                }
            }

            return back()->with('success','Address deleted successfully');
        }

        return redirect()->back()->with('success','Default address updated');
    }

    public function bulkAction(Request $request) {        
        $cartIds  = $request->cart_ids ?? [];
        $action  = $request->action;

        foreach (Cart::content() as $item) {
            if (!in_array($item->rowId, $cartIds)) {
                Cart::remove($item->rowId);
            }
        }   

        if (empty($cartIds)) {
            return response()->json([
                'status' => false,
                'message' => 'No items selected.'
            ]);
        }

        // REMOVE ITEMS
        if ($action === 'remove') {
            foreach ($cartIds as $rowId) {
                Cart::remove($rowId);
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Selected items removed.',
                'cartCount' => Cart::count()
            ]);
        }

        // MOVE TO WISHLIST
        if ($action === 'wishlist') {
            foreach ($cartIds as $rowId) {
                $item = Cart::get($rowId);

                if ($item) {                    
                    $exists = Wishlist::where('user_id', auth()->id())
                        ->where('product_id', $item->id)
                        ->exists();

                    if (!$exists) {
                        Wishlist::create([
                            'user_id'   => auth()->id(),
                            'product_id'=> $item->id,
                        ]);
                    }
                    Cart::remove($rowId);
                }
            }

             return response()->json([
                'status' => true,
                'message' => 'Selected items moved to wishlist.',
                'cartCount' => Cart::count()
            ]);            
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid action'
        ]);
    }

    public function payment(Request $request) {
        $selectedIds = $request->cart_ids ?? [];

        if(empty($selectedIds)){
            return back()->with('error','Please select at least one item');
        }

        $cartItems = Cart::content()->filter(function($item) use ($selectedIds){
            return in_array($item->rowId, $selectedIds);
        });

         if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        if (!Auth::check()) {
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('front.home');
        }

        $address = auth()->user()->addresses()->with('state')->get();
        $addressTypes = CustomerAddress::pluck('address_type')->toArray();
        $customerAddress = Auth::user()->address;
        $states = State::orderBy('name', 'ASC')->get();
        $homeExists = CustomerAddress::where('user_id', auth()->id())
            ->where('address_type', 'Home')
            ->exists();

        $totalQty = Cart::count(); // total items qty
        $shipping_charge = 0;

        if ($customerAddress) {
            $shippingInfo = ShippingCharge::where('state_id', $customerAddress->state_id)->first();

            if ($shippingInfo) {
                $shipping_charge = $totalQty * $shippingInfo->amount;
            }
        }

        // IMPORTANT: remove formatting to avoid string math
        $price_total = $cartItems->sum(function($item){
            return $item->price * $item->qty;
        });

        $price_discount = $cartItems->sum(function($item){
            return $item->options->compare_price * $item->qty;
        });

        $sub_total = $price_total - $price_discount;        
        $coupon_discount = session()->get('discount', 0);
        $grand_total = max($sub_total + $shipping_charge - $coupon_discount, 0);

        return view('front.checkout.payment', [
            'homeExists'            => $homeExists,
            'states'                => $states,
            'address'               => $address,
            'addressTypes'          => $addressTypes,
            'cartItems'             => $cartItems,
            'price_total'           => $price_total,
            'price_discount'        => $price_discount,
            'coupon_discount'       => $coupon_discount,
            'shipping_charge'       => $shipping_charge,            
            'grand_total'           => $grand_total
        ]);        
    }

    public function selectItem(Request $request) {
        $selected = session()->get('checkout_items', []);

        if($request->checked == "true"){
            $selected[$request->id] = $request->id;
        }else{
            unset($selected[$request->id]);
        }

        session()->put('checkout_items', $selected);

        return response()->json(['success'=>true]);
    }

    public function updateCartOption(Request $request) {
        $rowId = $request->rowId;

        if (!$rowId) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid request.'
            ]);
        }

        $item = Cart::get($rowId);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found'
            ]);
        }

        // Only update qty
        if ($request->has('qty')) {
            $qty = (int) $request->qty;

            if ($qty <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Quantity must be greater than 0.'
                ]);
            }
            
            Cart::update($rowId, $request->qty);

            return response()->json([
                'status' => true,
                'message' => 'Quantity updated successfully'
            ]);
        }
        
        $options = $item->options->toArray();

        if ($request->has('color_id')) {
            $options['color_id'] = $request->color_id;
        }

        if ($request->has('size_id')) {
            $options['size_id'] = $request->size_id;
        }

        // Important: DO NOT overwrite whole options blindly
        Cart::update($rowId, [
            'options' => $options
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cart updated successfully.'
        ]);
    }
    
    public function updateItem(Request $request) {
        $rowId = $request->rowId;
        $item = Cart::get($rowId);

        if(!$item){
            return response()->json([
                'status' => false,
                'message' => 'Item not found'
            ]);
        }

        // Remove old item
        Cart::remove($rowId);

        // Add again with updated data
        Cart::add(
            $item->id,
            $item->name,
            $request->qty,
            $item->price,
            [
                'size_id' => $request->size_id,
                'color_id' => $request->color_id,
                'short_description' => $item->options->short_description,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Cart updated successfully'
        ]);
    }

    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);

        //check qty available in stock
        if($product->track_qty == "Yes"){
            if($qty <= $product->qty ){
                Cart::update($rowId, $qty);
                $message = 'Cart updated successfully';
                $state = true;
                session()->flash('success',$message);
            } else {
                $message = 'Requested qty('.$qty.') not available in stock.';
                $state = false;
                session()->flash('error',$message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Cart updated successfully';
            $state = true;
            session()->flash('success',$message);
        }

        return response()->json([
            "status"=> $state,
            "message"=> $message
        ]);
    }

    public function deleteItem(Request $request){
        $rowId = $request->rowId;
        $itemInfo = Cart::get($rowId);

        if($itemInfo == null ){
            $errorMessage = 'Item not found in cart.';
            session()->flash('error',$errorMessage);
            return response()->json([
                "status"=> false,
                "message"=> $errorMessage,
            ]);
        }

        Cart::remove($request->rowId);

        $success = 'Item removed from Bag.';
        session()->flash('success',$success);
        return response()->json([
            "status"=> true,
            "message"=> $success,
        ]);
    }

    public function moveToWishlist(Request $request){
        $rowId = $request->rowId;
        $itemInfo = Cart::get($rowId);

        if($itemInfo == null ){
            $errorMessage = 'Item not found in cart.';
            return response()->json([
                "status"=> false,
                "message"=> $errorMessage,
            ]);
        }

        // Prevent duplicate wishlist entry
        $alreadyExists = Wishlist::where('user_id', auth()->id())
                            ->where('product_id', $itemInfo->id)
                            ->exists();

        if(!$alreadyExists){
            Wishlist::create([
                'user_id'    => auth()->id(),
                'product_id' => $itemInfo->id,
            ]);
        }

        // Remove from cart
        Cart::remove($rowId);

        return response()->json([
            "status"=> true,
            "message"=> "Item moved to wishlist successfully.",
        ]);
    }   

    public function getOrderSummary(Request $request){
        $subTotal = Cart::subtotal(2,'.','');
        $discount = 0;
        $discountString = '';

        //Appy Discount start here
        if (session()->has('code')) {
            $code = session()->get('code');

            if($code->type == 'percent'){
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }

            $discountString = '<div class="mt-4" id="discount-response">
                <strong>'.session()->get('code')->code.'</strong>
                <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
            </div>';
        }
        //Appy Discount end here


        if($request->state_id > 0){

            $shippingInfo = ShippingCharge::where('state_id', $request->state_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item){
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {

                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge,2),
                ]);
            } else {

                $shippingInfo = ShippingCharge::where('state_id','rest_of_state')->first();
                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge,2),
                ]);
            }
        } else {
            return response()->json([
                'status' => true,
                'grandTotal' => number_format(($subTotal-$discount),2),
                'discount' => number_format($discount,2),
                'discountString' => $discountString,
                'shippingCharge' => number_format(0,2),
            ]);
        }
    }   

    public function applyCoupon(Request $request) {
        $coupon = DiscountCoupon::findOrFail($request->coupon_id);

        // Get cart subtotal as number
        $cartTotal = (float) str_replace(',', '', Cart::subtotal());

        // Coupon expiry check
        if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
            return back()->with('error', 'Coupon expired');
        }

        // Minimum cart value check
        if ($coupon->min_amount && $cartTotal < $coupon->min_amount) {
            return back()->with('error', 'Minimum cart value not reached');
        }

        // Calculate discount
        if ($coupon->type == 'percent') {
            $discount = ($cartTotal * $coupon->discount_amount) / 100;
        } else {
            $discount = $coupon->discount_amount;
        }

        // Prevent discount > cart total
        $discount = min($discount, $cartTotal);

        // Store coupon in session
        session()->put('coupon_discount', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->discount_amount,
            'discount' => $discount
        ]);

        return back()->with('success', 'Coupon applied successfully!');
    }

    public function applyDiscount(Request $request){
        $code = DiscountCoupon::where('code', $request->code)->first();

        if($code == null){
            return response()->json([
                'status' => false,
                'message' => 'Invalid discount coupon',
            ]);
        }

        //Check if coupon start date is valid or not
        $now = Carbon::now();

        if($code->starts_at != ""){
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->starts_at);

            if($now->lt($startDate)){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon',
                ]);
            }
        }

        if($code->expires_at != ""){
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->expires_at);

            if($now->gt($endDate)){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon',
                ]);
            }
        }

        //Max uses check start here
        if($code->max_uses > 0){
            $couponUsed = Order::where('coupon_code_id', $code->id)->count();

            if($couponUsed >= $code->max_uses){
                return response()->json([
                    'status' => false,
                    'message' => 'Discount code expired.',
                ]);
            }
        }

        //Max uses user check start here
        if($code->max_uses_user > 0){
            $couponUsedByUser = Order::where(['coupon_code_id' => $code->id, 'user_id' => Auth::user()->id ])->count();

            if($couponUsedByUser >= $code->max_uses_user){
                return response()->json([
                    'status' => false,
                    'message' => 'You already used this coupon!',
                ]);
            }
        }

        $subTotal = Cart::subtotal(2,'.','');

        //Min amount condition check
        if($code->min_amount > 0){
            if($subTotal < $code->min_amount){
                return response()->json([
                    'status' => false,
                    'message' => 'Your min amount must be ₹ '.$code->min_amount.'.00',
                ]);
            }
        }


        session()->put('code',$code);

        return $this->getOrderSummary($request);
    }

    // public function removeCoupon() {
    //     if(session()->has('coupon_discount')){
    //         session()->forget('coupon_discount');
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Coupon removed successfully'
    //     ]);
    // }

    //Razorpay
    // public function razorpayPayment(Request $request){
    //     if(isset($request->razorpay_payment_id) && $request->razorpay_payment_id != ''){

    //         $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
    //         $payment = $api->payment->fetch($request->razorpay_payment_id);
    //         $response = $payment->capture(array('amount'=>$payment->amount));

    //         $payment = new Payment();
    //         $payment->payment_id = $response['id'];
    //         $payment->product_name = $response['notes']['product_name'];
    //         $payment->quantity = $response['notes']['quantity'];
    //         $payment->amount = $response['amount']/1000;
    //         $payment->currency = $response['currency'];
    //         $payment->customer_name = $response['notes']['customer_name'];
    //         $payment->customer_email = $response['notes']['customer_email'];
    //         $payment->payment_status = $response['status'];
    //         $payment->payment_method = 'Razorpay';
    //         $payment->save();

    //         return redirect()->route('checkout.success');

    //     } else {
    //         return redirect()->route('checkout.failed');
    //     }
    // }

    public function razorpaySuccess(){
        return view("front.checkout.success");
    }

    public function razorpayFailed(){
        return view("front.checkout.failed");
    }

    public function failed(){
        return view("front.checkout.failed");
    }   

    public function getOrderSummary2(Request $request){
        $subTotal = Cart::subtotal(2,'.','');
        $discount = 0;
        $discountString = '';

        //Appy Discount start here
        if (session()->has('code')) {
            $code = session()->get('code');

            if($code->type == 'percent'){
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }

            $discountString = '<div id="discount-response">
                <div class="card-body p-2">
                    <strong>'.session()->get('code')->code.'</strong>
                    <a id="remove-discount"><i class="fa fa-times"></i></a>
                </div>
            </div>';
        }
        //Appy Discount end here


        if($request->country_id > 0){
            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item){
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {
                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge,2),
                ]);
            } else {
                $shippingInfo = ShippingCharge::where('country_id','rest_of_world')->first();
                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge,2),
                ]);
            }
        } else {
            return response()->json([
                'status' => true,
                'grandTotal' => number_format(($subTotal-$discount),2),
                'discount' => number_format($discount,2),
                'discountString' => $discountString,
                'shippingCharge' => number_format(0,2),
            ]);
        }
    }

    public function getProductColors($id) {
        $product = Product::with('colors')->find($id);

        return response()->json([
            'colors' => $product->colors->map(function($color){
                return [
                    'id' => $color->id,
                    'name' => $color->name,
                    'code' => $color->code // optional (hex color)
                ];
            })
        ]);
    }

    public function getProductSizes($id) {
        $product = Product::with('sizes')->find($id);

        return response()->json([
            'sizes' => $product->sizes->map(function($size){
                return [
                    'id' => $size->id,
                    'name' => $size->name,
                    'code' => $size->code 
                ];
            })
        ]);
    }

    public function removeCoupon(Request $request) {
        // Remove coupon data from session
        session()->forget('coupon');
        session()->forget('coupon_discount');

        return response()->json([
            'status' => true,
            'message' => 'Coupon removed successfully'
        ]);
    }


   

}
