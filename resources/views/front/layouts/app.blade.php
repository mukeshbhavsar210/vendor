<!DOCTYPE html>
<html class="no-js" lang="en_AU" />
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
    <title>@yield('title', 'Default Title')</title>
    <meta name="description" content="@yield('meta_description')">
    <meta name="keywords" content="@yield('meta_keywords')">
	
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />

    {{-- <meta property="og:title" content="{{ $product->title }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($product->description,150) }}">
    <meta property="og:image" content="{{ asset('storage/'.$product->image) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="product"> --}}

    <link rel="canonical" href="{{ url()->current() }}">

	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick-theme.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.min.css') }}" />
	{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/ion.rangeSlider.min.css') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&family=Manrope:wght@200..800&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">	
	<link rel="shortcut icon" type="image/x-icon" href="#" />
</head>
<body data-instant-intensity="mousedown" class="{{ request()->routeIs(['front.cart']) ? 'cart-wrapper' : 'default' }}" >

@include(request()->routeIs(['front.cart','front.checkout','front.checkout.thankyou']) ? 'front.layouts.header.cart_header' : 'front.layouts.header.index')

<main>
    @yield('content')
</main>

@include(request()->routeIs(['front.cart','front.checkout','front.checkout.thankyou']) ? 'front.layouts.header.cart_footer' : 'front.layouts.header.footer')

@include('front.layouts.login_register')

<a href="javascript:0" id="backToTop" class="back-top-icon">
    <span class="sprites"></span>
</a>

<script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
<script src="{{ asset('front-assets/js/instantpages.5.1.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/slick.min.js') }}"></script>
<script src="{{ asset('front-assets/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('front-assets/js/documentReady.js') }}"></script>
<script>
    $(document).on('click', '.qty-btn', function () {
        let button = $(this);
        let rowId = button.data('rowid');
        let qtyElement = $('#qty-' + rowId);
        let currentQty = parseInt(qtyElement.text()) || 1;
        let newQty = currentQty;

        if (button.hasClass('qty-plus')) {
            newQty++;
        }

        if (button.hasClass('qty-minus')) {
            newQty--;
        }

        // Minimum quantity = 1
        if (newQty < 1) {
            return;
        }

        $.ajax({
            url: "{{ route('cart.updateQty') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                rowId: rowId,
                qty: newQty
            },

            success: function (response) {
                if (response.status == true) {
                    qtyElement.text(response.qty);
                    location.reload();
                }
            },

            error: function (xhr) {
                console.log('Status:', xhr.status);
                console.log('Response:', xhr.responseText);
                showAlert(
                    'Unable to update cart quantity.',
                    'error'
                );
            }
        });
    });

    //cart radio cards
    $(document).on('change', '.card-radio', function () {
        let target = $(this).closest('.card-details').data('target');

        $('.accordion-collapse').collapse('hide');        
        $(target).collapse('show');
    });

    $(document).on('change', 'input[name="date"]', function () {
        $('input[name="date"]').closest('.date').removeClass('selected_date');
        $(this).closest('.date').addClass('selected_date');

        // Check if selected date is today
        let selectedDate = $(this).val();
        let today = "{{ now()->format('Y-m-d') }}";

        if (selectedDate !== today) {
            $('#online-payment-message').show();
        } else {
            $('#online-payment-message').hide();
        }
    });

    $(document).on('change', 'input[name="time"]', function () {
        $('input[name="time"]').closest('.time').removeClass('selected_time');
        $(this).closest('.time').addClass('selected_time');
    });

    // Set active class for the initially checked radio
    $('input[name="date"]:checked').closest('.date').addClass('selected_date');
    $('input[name="time"]:checked').closest('.time').addClass('selected_time');


    $(document).ready(function(){                
        $('.track-order-btn').click(function(){
            let orderId = $(this).data('order-id');
            let url = "{{ route('account.order.tracking', ':id') }}";
            url = url.replace(':id', orderId);

            $.ajax({
                url: url,
                type: "GET",
                success: function(response){
                    let html = '';
                    if(response.length === 0){
                        html = '<li>No tracking available</li>';
                    }else{
                        response.forEach(function(status){
                            html += `<li class="active">
                                        <span class="sprites dark-green-tick-icon"></span>                                        
                                        <p class="text-muted tiny-font"><b>${status.status.replaceAll('_',' ')}</b><br>on ${status.date}</p>
                                    </li>
                                    `;
                                });
                    }
                    $('#trackingTimeline').html(html);
                }
            });
        });
    });

    $(document).on('click', '.search-btn', function () {
        $('.search-form').toggleClass('d-none');
        $('.bottom-form').removeClass('d-none');
        $('.close-search-icon').removeClass('d-none');
        $('.row-hide').addClass('d-none');
    });

    $(document).on('click', '.close-search-icon', function () {
        $('.row-hide').removeClass('d-none');
        $('.bottom-form').addClass('d-none');        
        $('.close-search-icon').addClass('d-none');
        $('.desktop-form').addClass('d-none');        
    });
    
    $(document).on('click', '.toggle-category', function(e) {
        e.preventDefault();
        let target = $(this).data('target');
        // close others (optional)
        $('.toggle-category').not(this).removeClass('active');
        $('.mobile-dropdown').not(target).removeClass('show');

        // toggle current
        $(this).toggleClass('active');    
        $(target).toggleClass('show');
    });

    $(document).on('click', '.toggle-subcategory', function(e) {
        e.preventDefault();
        let target = $(this).data('target');

        $('.toggle-subcategory').not(this).removeClass('active');
        $(this).closest('ul').find('.sub-dropdown').not(target).removeClass('show');

        $(this).toggleClass('active');
        $(target).toggleClass('show');
    });

    $('.retirectBack').click(function () {            
        $.ajax({
            url: "/set-intended-url",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                url: window.location.href
            }
        });
        //$('#login').modal('show');
    });

    function showAlert(message, type = 'success'){
        let toastEl = $('#commonToast');
        toastEl.removeClass('bg-success bg-danger bg-warning');

        if(type === 'error'){
            toastEl.addClass('bg-danger');
        }else{
            toastEl.addClass('bg-success');
        }

        $('#commonToastMessage').text(message);

        let toast = new bootstrap.Toast(document.getElementById('commonToast'));
        toast.show();
    }

    var scrollSpy = new bootstrap.ScrollSpy(document.querySelector('.scrollspy-example'), {
        target: '#faq-nav'
    });

    
    setTimeout(function(){
        $('.toast').fadeOut('slow');
    },4000);	

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });         
        
    
    function addToCart(id){
        let btn = event.target;        
        let urlParams = new URLSearchParams(window.location.search);        

        $.ajax({
            url: '{{ route("front.addToCart") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                service_id: id,                
            },
            dataType: 'json',

            success: function(response) {
                if (response.status == true) {
                    $('#cartCount').text(response.cartCount);
                    showAlert(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 300);
                } else {
                    showAlert(response.message, 'error');
                }
            },            

            error: function(xhr) {
                console.log('Status:', xhr.status);
                console.log('Response:', xhr.responseText);
                showAlert('Something went wrong while adding to cart.', 'error');
            }
        });
    }  

    function addToWishlist(id){        
        $.ajax({
            url: '{{ route("front.addToAffiliate") }}',
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}' 
            },
            dataType: 'json',
            success: function(response){
                if(response.status == true){
                    $("#wishlistToastBody").html(response.message);
                    showAlert(response.message,'success');                   
                } else {
                    window.location.href= "{{ route('front.home') }}";
                }
            },
            error: function(xhr){
                console.log(xhr.responseText); 
            }
        })
    }    
   
    $(document).on('click', '.move-to-cart', function(){
        let wishlistId = $(this).data('wishlist-id');
        let productId  = $(this).data('product-id');        

        wishlistToCart(wishlistId, productId);
    });

    function wishlistToCart(wishlistId, productId, ) {
        $.ajax({
            url: '{{ route("front.wishlistToCart") }}',
            type: 'POST',
            data: {
                wishlist_id: wishlistId,
                product_id: productId,                
                _token: '{{ csrf_token() }}'
            },
            success:function(response){
                if(response.status){
                    $("#wishlist-item-"+wishlistId).fadeOut(300,function(){
                        $(this).remove();
                    });
                    $(".cart-count").text(response.cartCount);
                    $(".wishlist-count").text(response.wishlistCount);

                    showAlert(response.message,'success');
                    location.reload();
                }else{
                    showAlert(response.message,'error');
                }
            }
        });
    }

    function toggleChat() {
        $("#chat-box").toggleClass("d-none");
    }

    function sendMessage() {
        let msg = $("#chatInput").val();

        if(msg.trim() == '') return;

        $("#messages").append("<div><b>You:</b> " + msg + "</div>");

        $.ajax({
            url: "{{ route('chat.order.status') }}",
            type: "POST",
            data: {
                message: msg,
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                $("#messages").append("<div><b>Bot:</b> " + res.reply + "</div>");
                $("#messages").scrollTop($("#messages")[0].scrollHeight);
            }
        });

        $("#chatInput").val('');
    }


    $("#registrationForm").submit(function(event){
        event.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $.ajax({
            url: '{{ route("account.processRegister") }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                $("button[type='submit']").prop('disabled', false);

                var errors = response.errors;

                if(response.status == false){
                    if(errors.name){
                        $("#name").siblings("p").addClass('invalid-feedback').html(errors.name);
                        $("#name").addClass('is-invalid');
                    } else {
                        $("#name").siblings("p").removeClass('invalid-feedback').html();
                        $("#name").removeClass('is-invalid');
                    }

                    if(errors.email){
                        $("#email").siblings("p").addClass('invalid-feedback').html(errors.email);
                        $("#email").addClass('is-invalid');
                    } else {
                        $("#email").siblings("p").removeClass('invalid-feedback').html();
                        $("#email").removeClass('is-invalid');
                    }

                    if(errors.password){
                        $("#password").siblings("p").addClass('invalid-feedback').html(errors.password);
                        $("#password").addClass('is-invalid');
                    } else {
                        $("#password").siblings("p").removeClass('invalid-feedback').html();
                        $("#password").removeClass('is-invalid');
                    }
                } else {
                    $("#name").siblings("p").removeClass('invalid-feedback').html();
                    $("#name").removeClass('is-invalid');
                    $("#email").siblings("p").removeClass('invalid-feedback').html();
                    $("#email").removeClass('is-invalid');
                    $("#password").siblings("p").removeClass('invalid-feedback').html();
                    $("#password").removeClass('is-invalid');

                    window.location.href="{{ route('account.login') }}"
                }
            },
            error: function(JQXHR, exception){
                console.log("Something went wrong");
            }
        })
    });
</script>

@yield('customJs')

</body>
</html>