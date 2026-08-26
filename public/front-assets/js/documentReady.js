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
            dots: true,
            arrows: false,
            infinite: true,
            speed: 400,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: false,      // ❌ disabled by default
            autoplaySpeed: 2000   // 2 seconds
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
    

    $('.brand-slider').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        arrows: true,
        dots: true,
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

    $('.variant').slick({
        slidesToShow: 6,
        slidesToScroll: 3,
        autoplay: true,
        arrows: false,
        dots: true,
        infinite: true,
        responsive: [
            {
                breakpoint: 1200,
                settings: { slidesToShow: 6 }
            },
            {
                breakpoint: 992,
                settings: { slidesToShow: 6 }
            },
            {
                breakpoint: 768,
                settings: { slidesToShow: 6 }
            },
            {
                breakpoint: 576,
                settings: { slidesToShow: 4 }
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
    
    $("#related-products").not('.slick-initialized').slick({
        centerMode: false,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        prevArrow:'<i class="icon-left-arrow right-arrow arrow"></i>',
        nextArrow:'<i class="icon-right-arrow left-arrow arrow"></i>',
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