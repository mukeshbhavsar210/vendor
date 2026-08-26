<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\AffiliateProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Auth\SocialController;
use App\Models\AffiliateProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

//Front pages routes
Route::controller(FrontController::class)->group(function() {
    Route::get('/', 'index')->name('front.home');
    Route::post('/add-to-wishlist', 'addToWishlist')->name('front.addToWishlist');
    Route::get('/page/{slug}', 'page')->name('front.page');
    Route::post('/send-contact-email', 'sendContactEmail')->name('front.sendContactEmail');

    Route::get('/faqs/', 'faqs')->name('front.faqs');
    Route::get('/deals/', 'deals')->name('front.deals');
    Route::post('/add-to-affiliate', 'addToAffiliate')->name('front.addToAffiliate');

    Route::post('/notify', 'addToNotify')->name('front.notify');
    Route::post('/affiliate_notify', 'addToAffiliateNotify')->name('front.affiliate.notify');

    Route::post('/affiliate-product/{id}/like', 'like');
    Route::post('/affiliate-product/{id}/view', 'view');

    //Chat support
    Route::post('/chat-order-status', 'orderStatus')->name('chat.order.status');
});



Route::get('/go/{id}', function($id){
    $product = AffiliateProduct::findOrFail($id);
    $product->increment('views');
    return redirect($product->url);
});

Route::post('/set-intended-url', function (Request $request) {session(['url.intended' => $request->url]);});

Route::controller(ShopController::class)->group(function() {
    Route::get('/products/{item1?}/{item2?}/{item3?}','listing')->name('front.shop');
    Route::get('/details/{item2?}-{item3?}/{slug}', 'product')->name('front.product');

    //Category
    Route::get('/category/{item1?}','category')->name('front.category');
    Route::get('/subcategory/{item2?}','subcategory')->name('front.subcategory');    
    
    //Reviews
    Route::get('/product/{id}/reviews', 'allReviews')->name('product.reviews');
    Route::post('/rate-product', 'rating_store')->name('rate.product');
});


// Route::get('/mail', function () {
//     Mail::raw('Test Email Working', function ($message) {
//         $message->to('contact@amdavadproperty.in')
//                 ->subject('Test Mail');
//     });

//     return 'Mail Sent';
// });

Route::controller(CartController::class)->group(function() {
    //Bag
    Route::get('/checkout/cart','cart')->name('front.cart');    
    Route::post('/checkout/update-cart','updateCart')->name('front.updateCart');
    Route::post('/checkout/add-to-cart','addToCart')->name('front.addToCart');
    Route::post('/checkout/wishlist-to-cart','wishlistToCart')->name('front.wishlistToCart');
    Route::post('/checkout/delete-item','deleteItem')->name('front.deleteItem.cart');
    Route::post('/move-to-wishlist', 'moveToWishlist')->name('front.moveToWishlist.cart');    
    Route::post('/cart/bulk-action', 'bulkAction')->name('cart.bulk.action');    
    Route::post('/cart/select-item','selectItem');
    Route::get('/get-product-colors/{id}', 'getProductColors');
    Route::get('/get-product-sizes/{id}', 'getProductSizes');

    //Update Color/Size/Qty in cart
    Route::post('/apply-coupon', 'applyCoupon')->name('coupon.apply');
    Route::post('/checkout/cart/common', 'updateCartOption')->name('front.updateCartOption');
    Route::post('/cart/update-item', 'updateItem')->name('front.updateCartItem');
    Route::post('/default-address', 'updateDefaultAddress')->name('address.default');    

    Route::get('/coupon/remove', 'removeCoupon')->name('coupon.remove');
    Route::post('/checkout/payment', 'payment')->name('checkout.payment');
    Route::get('/checkout/address','checkout')->name('front.checkout');
    Route::post('/removecoupon','removeCoupon')->name('front.removeCoupon');

    //Payment routes
    Route::post('/checkout/process','processCheckout')->name('front.processCheckout');
    Route::post('/checkout/verify-payment', 'verifyPayment')->name('checkout.verify.payment'); 
    Route::get('/thanks/{orderId}','thankyou')->name('front.checkout.thankyou');
    Route::post('/get-order-summary','getOrderSummary')->name('front.getOrderSummary');

    Route::post('/checkout/place-order', 'placeOrder')->name('checkout.place.order');        
    Route::get('/order-success', 'success')->name('order.success');
    Route::post('checkout/razorpay', 'razorpayPayment')->name('checkout.razorpay');
    Route::get('checkout/payment-success','razorpaySuccess')->name('checkout.success');
    Route::get('checkout/payment-failed', 'razorpayFailed')->name('checkout.failed');  
     
    Route::get('checkout/payment-failed', 'failed')->name('order.failed');
    Route::post('checkout/get-order-summary','getOrderSummary')->name('front.getOrderSummary');
});

Route::controller(CartController::class)->group(function() {
    Route::get('/razorpay','index');
    Route::post('/payment','payment')->name('payment');
});

Route::controller(SocialController::class)->group(function() {
    Route::get('auth/google', 'redirectToGoogle');
    Route::get('auth/google/callback', 'handleGoogleCallback');
    Route::get('auth/facebook', 'redirectToFacebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

//User realted
Route::group(['prefix' => 'account'], function(){
    Route::group(['middleware' => 'guest'], function(){
        Route::controller(AuthController::class)->group(function() {
            Route::get('/login','login')->name('account.login');
            Route::post('/login','authenticate')->name('account.authenticate');
            Route::get('/register','register')->name('account.register');
            Route::post('/process-register','processRegister')->name('account.processRegister');
        });
    });

    Route::group(['middleware' => 'auth'], function(){
        Route::controller(AuthController::class)->group(function() {
            Route::get('/dashboard','dashboard')->name('account.dashboard');
            Route::get('/notifications','notifications')->name('account.notifications');

            Route::get('/profile','profile')->name('account.profile');
            Route::get('/profile/edit','profileEdit')->name('account.profile.edit');
            Route::post('/update-profile','updateProfile')->name('account.updateProfile');
            Route::post('/update-address','updateAddress')->name('account.updateAddress');
            Route::get('/password','changePasswordForm')->name('account.changePassword');
            Route::post('/change-password','changePassword')->name('account.processChangePassword');

            Route::post('/address/store', 'address_store')->name('customer.address.store');
            Route::put('/address/{id}', 'address_update')->name('customer.address.update');
            Route::get('/address','address')->name('account.address');
            Route::get('/cards','cards')->name('account.cards');
            Route::get('/coupons','coupons')->name('account.coupons');
            Route::get('/delete-account','delete_account')->name('account.delete_account');
            Route::post('/delete-account','delete_account_action')->name('account.delete');

            //Orders User
            Route::get('/orders','orders')->name('account.orders');                
            Route::get('/order/details/{orderId}','orderDetail')->name('account.orderDetail');
            Route::get('/order/{order}/cancel', 'cancel')->name('account.order.cancel.form');
            Route::post('/order/{order}/cancel_order', 'cancelOrder')->name('account.order.cancel');
            Route::get('/account/order/cancelled-orders', 'cancelledOrders')->name('account.orders.cancelled');
            Route::get('/order-tracking/{id}', 'tracking')->name('account.order.tracking');
            Route::post('/reviews-store', 'review_store')->name('reviews.store');
            Route::get('/get-order-items/{id}', 'getOrderItems');

            Route::get('/wishlist','wishlist')->name('account.wishlist');
            Route::post('/remove-product-from-wishlist','removeProductFromWishlist')->name('account.removeProductFromWishlist');
            
            Route::get('/deals','dealsWishlist')->name('account.deals');
            Route::post('/remove-product-from-deals-wishlist','removeProductFromDealsWishlist')->name('account.remove.deals');
            
            Route::get('/logout','logout')->name('account.logout');
        });
    });
});

//Admin related
Route::group(['prefix' => 'admin'], function(){
    Route::group(['middleware' => 'admin.guest'], function(){
        Route::controller(AdminLoginController::class)->group(function() {
            Route::get('/login', 'index')->name('admin.login');
            Route::post('/authenticate', 'authenticate')->name('admin.authenticate');
        });
    });

    Route::group(['middleware' => 'admin.auth'], function(){
        Route::controller(HomeController::class)->group(function() {
            Route::get('/dashboard', 'index')->name('admin.dashboard');
            Route::get('/recent-orders', 'recentOrders')->name('admin.recentOrders');
            Route::get('/top-products', 'topProducts')->name('admin.top.products');
            Route::get('/charts', 'dashboardStats')->name('admin.dashboard.stats');
            Route::get('/logout', 'logout')->name('admin.logout');
        });

        //Category Routes
        Route::controller(CategoryController::class)->group(function() {
            Route::get('/category', 'index')->name('categories.index');

            //Category
            Route::post('/category/cat', 'category_store')->name('category.store');
            Route::put('/category/{category}', 'category_update')->name('category.update');
            Route::delete('/category/cat/{category}', 'category_destroy')->name('category.delete');
            
            //Sub category
            Route::post('/subcategory/subcat', 'subCategory_store')->name('subCategory.store');
            Route::put('/subcategory/{subcategory}', 'subCategory_update')->name('sub_category.update');
            //Route::put('/category/subcategory/{subCategory}', 'sub_category_update')->name('sub_category.update');
            Route::delete('/category/subcategory/{subCategory}', 'subCategory_destroy')->name('sub_category.delete');
            
            //Sub2 category
            Route::post('/subsubcategory/subsubcat', 'subSubCategory_store')->name('subSubCategory.store');            
            Route::put('/subsubcategory/{subsubcategory}', 'subSubCategory_update')->name('sub_sub_category.update');
            Route::delete('/subsubcategory/{subsubcategory}', 'subSubCategory_destroy')->name('sub_sub_category.delete');

            Route::get('/get-subcategories/{id}', 'getSubCategories')->name('get.subcategories');
        });       
        
        //Services Route
        Route::controller(ProductController::class)->group(function() {
            Route::get('/services', 'index')->name('services.index');
            Route::get('/services/create', 'create')->name('services.create');
            Route::post('/services', 'store')->name('services.store');
            Route::get('/services/{service}/edit', 'edit')->name('services.edit');
            Route::put('/services/{service}', 'update')->name('services.update');
            Route::delete('/services/{service}', 'destroy')->name('services.delete');
            Route::get('/get-services','getServices')->name('services.getServices');            
        });

        //Sub Categories Connect to main Categories
        Route::controller(ProductSubCategoryController::class)->group(function() {
            Route::get('/product-subcategories', 'index')->name('product-subcategories.index');
            Route::get('/product-subsubcategories', 'getSubSubCategories')->name('product-subcategories.extra');
        });

        //Delete Product Images Route
        Route::controller(ProductImageController::class)->group(function() {
            Route::post('/product-images/update', 'update')->name('product-images.update');
            Route::delete('/product-images', 'destroy_product')->name('product-images.destroy');
            Route::delete('/variant-images', 'destroy_variant')->name('variant-images.destroy');
        });
        
        //Orders Routes
        Route::controller(OrderController::class)->group(function() {
            Route::get('/orders', 'index')->name('orders.index');
            Route::get('/orders/{id}', 'detail')->name('orders.detail');            
            Route::post('/order/tracking/{id}', 'changeTrackStatus')->name('order.changeTrackStatus');            
            Route::post('/order/send-email/{id}', 'sendInvoiceEmail')->name('orders.sendInvoiceEmail');
            Route::get('/order_tracking/{id}', 'tracking')->name('orders.order.tracking');
        });                 

        //Temp image controller
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

        //Settings        
        Route::controller(SettingController::class)->group(function() {
            Route::get('/password', 'showChangePasswordForm')->name('admin.showChangePasswordForm');
            Route::post('/process-change-password', 'processChangePassword')->name('admin.processChangePassword');        

            //Affiliate Product
            Route::get('/settings/affiliates', 'affiliate_index')->name('affiliate_services.index');            
            Route::post('/settings/affiliates', 'affiliate_store')->name('affiliate_services.store');
            Route::get('/settings/affiliates/{affiliate}/edit', 'affiliate_edit')->name('affiliate_services.edit');
            Route::put('/settings/affiliates/{affiliate}', 'affiliate_update')->name('affiliate_services.update');
            Route::delete('/settings/affiliates/{affiliate}', 'affiliate_destroy')->name('affiliate_services.delete');

            //Brands
            Route::get('/settings/brands', 'brand_index')->name('brands.index');
            Route::post('/settings/brands', 'brand_store')->name('brands.store');            
            Route::delete('/settings/brands/{brand}', 'brand_destroy')->name('brands.delete');

            //Colors
            Route::get('/settings/colors', 'color_index')->name('colors.index');            
            Route::post('/settings/colors', 'color_store')->name('colors.store');            
            Route::delete('/settings/colors/{color}', 'color_destroy')->name('colors.delete');

            //Discount coupon
            Route::get('/settings/coupons', 'coupon_index')->name('coupons.index');            
            Route::post('/settings/coupons', 'coupon_store')->name('coupons.store');
            Route::get('/settings/coupons/{coupon}/edit', 'coupon_edit')->name('coupons.edit');
            Route::put('/settings/coupons/{coupon}', 'coupon_update')->name('coupons.update');
            Route::delete('/settings/coupons/{coupon}', 'coupon_destroy')->name('coupons.delete');

            //Pages
            Route::get('/settings/pages', 'page_index')->name('pages.index');
            Route::get('/settings/pages/create', 'page_create')->name('pages.create');
            Route::post('/settings/pages', 'page_store')->name('pages.store');
            Route::get('/settings/pages/{page}/edit', 'page_edit')->name('pages.edit');
            Route::put('/settings/pages/{page}', 'page_update')->name('pages.update');
            Route::delete('/settings/pages/{page}', 'page_destroy')->name('pages.delete');

            //Users
            Route::get('/settings/users', 'users_index')->name('users.index');            
            Route::post('/settings/users', 'users_store')->name('users.store');
            Route::get('/settings/users/{user}/edit', 'users_edit')->name('users.edit');
            Route::put('/settings/users/{user}', 'users_update')->name('users.update');
            Route::delete('/settings/users/{user}', 'users_destroy')->name('users.delete');

            //Vendors
            Route::get('/settings/vendors', 'vendors_index')->name('vendors.index');
            Route::post('/settings/vendors', 'vendors_store')->name('vendors.store');
            Route::get('/settings/vendors/{vendor}/edit', 'vendors_edit')->name('vendors.edit');
            Route::put('/settings/vendors/{vendor}', 'vendors_update')->name('vendors.update');
            Route::delete('/settings/vendors/{vendor}', 'vendors_destroy')->name('vendors.delete');

            //Shipping
            Route::get('/settings/shipping/index', 'shipping_index')->name('shipping.index');
            Route::post('/settings/shipping', 'shipping_store')->name('shipping.store');
            Route::delete('/settings/shipping/{id}', 'shipping_destroy')->name('shipping.delete');

            //Ratings
            Route::get('/settings/ratings', 'rating_index')->name('review.index');
            Route::get('/settings/review/approve/{id}', 'approve')->name('review.approve');
            Route::get('/settings/review/rejecte/{id}', 'reject')->name('review.reject');
            Route::delete('/settings/review/delete/{id}', 'review_delete')->name('review.delete');
        });  
           

        Route::get('/getSlug', function(Request $request){
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');
    });
});
