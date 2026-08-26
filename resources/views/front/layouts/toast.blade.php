@if (Session::has('success'))    
    <div class="toast toast-cart fade show" role="alert" data-bs-delay="2000">        
        <div class="toast-body">{!! Session::get('success') !!}</div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>        
    </div>    
@endif

<div id="commonToast" class="toast toast-cart" role="alert">        
    <div class="toast-body" id="commonToastMessage"></div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>        
</div>

{{-- <div id="cartToast" class="toast toast-cart" role="alert" data-bs-delay="1000" data-bs-autohide="true">        
    <div class="toast-body" id="cartToastMessage">Product added to cart</div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>        
</div>  --}}

{{-- <div id="liveToast" class="toast toast-cart" role="alert" data-bs-delay="2000" data-bs-autohide="true">        
    <div class="toast-body">{{ session('success') }}</div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>    
</div> --}}

{{-- <div id="wishlistToast" class="toast toast-cart" role="alert" data-bs-delay="2000" data-bs-autohide="true">
    <div class="toast-body" id="wishlistToastBody">Product added to wishlist!</div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>    
</div> --}}

{{-- <div id="wishlistToast" class="toast toast-cart" role="alert" data-bs-delay="2000" data-bs-autohide="true">
    <div class="toast-body" id="wishlistToastBody"></div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>    
</div> --}}    