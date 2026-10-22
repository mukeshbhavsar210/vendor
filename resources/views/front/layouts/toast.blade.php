@if (Session::has('success'))    
    <div class="toast toast-cart fade show" role="alert" data-bs-delay="2000">        
        {!! Session::get('success') !!}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>        
    </div>    
@endif

{{-- <div id="commonToast" class="toast toast-cart" role="alert">        
    <div id="commonToastMessage"></div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>        
</div> --}}