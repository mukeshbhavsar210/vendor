$(document).ready(function(){
    $('#faq-nav a').click(function(e){
        e.preventDefault();

        let target = $(this).attr('href');

        // Remove active from all
        $('#faq-nav a').removeClass('active');
        $('.faq-section').addClass('d-none');        

        // Add active to clicked
        $(this).addClass('active');
        $(target).removeClass('d-none');

        // Smooth scroll
        $('.faq-content').animate({
            scrollTop: $(target).position().top + $('.faq-content').scrollTop()
        }, 400);
    });

    // 👉 On scroll (sync left menu)
    $('.faq-content').on('scroll', function(){
        let scrollTop = $(this).scrollTop();

        $('.faq-section').each(function(){
            let sectionTop = $(this).position().top;
            let sectionId = $(this).attr('id');

            if(sectionTop <= 100){
                $('#faq-nav a').removeClass('active');
                $('#faq-nav a[href="#'+sectionId+'"]').addClass('active');

                $('.faq-section').removeClass('active');
                $(this).addClass('active');
            }
        });
    });

    $('.nav-item.dropdown').hover(
        function(){
            $('body').addClass('active');
            $(this).addClass('active_menu');
        },
        function(){
            $('body').removeClass('active');
            $(this).removeClass('active_menu');
        }
    );

    $(window).on("scroll", function(){
        if($(window).scrollTop() > 100){
            $("header").addClass("sticky");            
        }else{
            $("header").removeClass("sticky");
        }
    });

    function checkValue(element){
        if($(element).val() !== ''){
            $(element).closest('.form-group').addClass('active');
        } else {
            $(element).closest('.form-group').removeClass('active');
        }
    }

    // On Focus
    $(document).on('focus', '.form-control', function(){        
        $(this).closest('.form-group').addClass('active');
    });

    // On Blur
    $(document).on('blur', '.form-control', function(){
        checkValue(this);
    });

    // On Page Load (Edit Mode / Prefilled)
    $('.form-control').each(function(){
        checkValue(this);
    });

    function updateActive() {
        $('.default-card').removeClass('delivery-address');

        $('.address-radio:checked')
            .closest('.default-card')
            .addClass('delivery-address');
    }

    $(document).on('change', '.address-radio', function(){
        updateActive();
    });

    // Run on page load
    updateActive();

     $('.menu-toggle').on('click', function (e) {
        e.preventDefault();

        var parent = $(this).parent('.menu-item');

        parent.toggleClass('active');

        // Optional: Close other open menus
        parent.siblings().removeClass('active');
    });

     // all filters left
    $('.form-check-input:checked').each(function () {
        $(this).closest('.form-check .link').addClass('active-check');
    });
    
    $('.form-check-input').on('change', function () {
        $(this).closest('.form-check .link').toggleClass('active-check', this.checked);
    });

    //Discount coupon radio button
    $(document).on('change', 'input[name="coupon_id"]', function () {
        $('.coupon-box').removeClass('active');
        $(this).closest('.coupon-box').addClass('active');
    });
    

    var lazyLoadInstance = new LazyLoad({elements_selector:"img.lazy, video.lazy, div.lazy, section.lazy, header.lazy, footer.lazy,iframe.lazy"});
    let bannerHeight = $(window).height();

    $("#menuLink").mouseenter(function(){
        $("#megaDiv").stop(true, true).slideDown(200);
    });

    $("#menuLink, #megaDiv").mouseleave(function(){
        $("#megaDiv").stop(true, true).slideUp(200);
    });

    $('.product-slider').each(function(){
        var $slider = $(this);

        $slider.slick({
            dots: false,
            arrows: true,
            infinite: true,
            speed: 400,
            slidesToShow: 5,
            slidesToScroll: 1,
            autoplay: false,
            autoplaySpeed: 2000
        });

        // Start autoplay on hover
        $slider.closest('.product-card').on('mouseenter', function () {
            $slider.slick('slickPlay');
        });

        // Stop autoplay on leave
        $slider.closest('.product-card').on('mouseleave', function () {
            $slider.slick('slickPause');
            $slider.slick('slickGoTo', 0); // optional → reset to first image
        });
    });

    $(".services-gallery").not('.slick-initialized').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        arrows: true,
        dots: false,
        infinite: false,
        centerMode: false,
        centerPadding: '20px',
        prevArrow:'<div class="arrow-left"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="#0F0F0F" viewBox="0 0 16 16"><path fill="#0F0F0F" fill-rule="evenodd" d="M6.47 2.97l-4.5 4.5a.75.75 0 000 1.06l4.5 4.5 1.06-1.06-3.22-3.22h9.19v-1.5H4.31l3.22-3.22-1.06-1.06z" clip-rule="evenodd"></path></svg></div>',
        nextArrow:'<div class="arrow-right"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="#0F0F0F" viewBox="0 0 16 16"><path fill="#0F0F0F" fill-rule="evenodd" d="M11.69 8.75H2.5v-1.5h9.19L8.47 4.03l1.06-1.06 4.5 4.5a.75.75 0 010 1.06l-4.5 4.5-1.06-1.06 3.22-3.22z" clip-rule="evenodd"></path></svg></div>',
        responsive: [{
            breakpoint: 1200,
            settings: {
                centerMode: false,
                centerPadding: '0px',
                slidesToShow: 5,
                slidesToScroll: 1,
                
            }
        },{
            breakpoint: 1300,
            settings: {
                centerMode: false,
                slidesToShow: 3,
                slidesToScroll: 1,
            }
        },{
            breakpoint: 1200,
            settings: {
                centerMode: false,
                slidesToShow: 3,
                slidesToScroll: 1,
            }
        },{
            breakpoint: 1024,
            settings: {
                centerMode: false,
                slidesToShow: 2,
                slidesToScroll: 1,
            }
        },{
            breakpoint: 992,
            settings: {
                centerMode: false,
                slidesToShow: 2,
                slidesToScroll: 1,
            }
        },{
            breakpoint: 576,
            settings: {
                centerMode: false,
                slidesToShow: 1,
                slidesToScroll: 1,      
            }
        }]     
    });
    

    $('.slider').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        arrows: true,
        dots: false,
        infinite: true,
        responsive: [
            {
                breakpoint: 1200,
                settings: { slidesToShow: 5 }
            },
            {
                breakpoint: 992,
                settings: { slidesToShow: 4 }
            },
            {
                breakpoint: 768,
                settings: { slidesToShow: 3 }
            },
            {
                breakpoint: 576,
                settings: { slidesToShow: 2 }
            }
        ]
    });
   
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: false,
        asNavFor: '.slider-nav',
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    asNavFor: null 
                }
            }
        ]
    });

    $('.slider-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: false,
        focusOnSelect: true,
        vertical: true,
        verticalSwiping: true,
        arrows: true,
        responsive: [
            {
                breakpoint: 768,
                dots: true,
                settings: "unslick" 
            }
        ]
    });
    
    

    // Show / Hide button
    $(window).scroll(function() {
        if ($(this).scrollTop() > 200) {
            $('#backToTop').fadeIn();
        } else {
            $('#backToTop').fadeOut();
        }
    });

    // Scroll to top
    $('#backToTop').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 600);
        return false;
    });

    $("#isShippingDiffernt").click(function(){
        if ($(this).is(':checked') == true) {
            $("#shippingForm").removeClass('d-none');
        } else {
            $("#shippingForm").addClass('d-none');
        }
    });
});

$('.subcategory-thumb').on('click', function () {    
    let id = $(this).data('id');
    $('.subcategory-thumb').removeClass('anchor-active');
    $(this).addClass('anchor-active');
    $('.subcategory-thumb').removeClass('anchor-active');
    $('.subcategory-thumb[data-id="' + id + '"]').addClass('anchor-active');

    $('.subcategory-right').removeClass('anchor-active');    
    $('.subcategory-right').removeClass('anchor-active');
    $('.subcategory-right[data-id="' + id + '"]').addClass('anchor-active');
});