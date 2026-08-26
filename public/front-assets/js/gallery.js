$(document).ready(function(){
    $('.localities').slick({
        autoplay: true,
        centerMode: false,
        centerPadding: '30px',
        autoplaySpeed: 3000,
        slidesToShow: 5,
        slidesToScroll: 1,
        infinite: false,
        arrows: false,
        dots: false,
        lazyLoad: 'ondemand',
        prevArrow:'<div class="left-arrow-card arrow-card"><span class="sprites"></span></div>',
        nextArrow:'<div class="right-arrow-card arrow-card"><span class="sprites"></span></div>',
        responsive: [
        {
                breakpoint: 768, // mobile portrait and below
                settings: {
                    slidesToShow: 1.5,
                    arrows: false,                    
                }
            },
            {
                breakpoint: 992, // tablets & small landscape devices like iPhone 11 landscape
                settings: {
                slidesToShow: 5,
                }
            },
            {
                breakpoint: 1200, // medium to large desktops
                settings: {
                slidesToShow: 5,
                }
            }
        ]
    });

    $('.latest').slick({
        autoplay: true,
        centerMode: false,
        centerPadding: '30px',
        autoplaySpeed: 5000,
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        dots: false,
        lazyLoad: 'ondemand',
        prevArrow:'<div class="left-arrow-card arrow-card"><span class="sprites"></span></div>',
        nextArrow:'<div class="right-arrow-card arrow-card"><span class="sprites"></span></div>',
        responsive: [
        {
                breakpoint: 768, // mobile portrait and below
                settings: {
                    slidesToShow: 1.2,
                    arrows: false,                    
                }
            },
            {
                breakpoint: 992, // tablets & small landscape devices like iPhone 11 landscape
                settings: {
                slidesToShow: 2,
                }
            },
            {
                breakpoint: 1200, // medium to large desktops
                settings: {
                slidesToShow: 3,
                }
            }
        ]
    });
    
    $('.latest').on('lazyLoaded', function(event, slick, image){
        let wrapper = $(image).closest('.lazy-wrapper');
        wrapper.find('.lazy-spinner').fadeOut(300);    
    });
    
    $('.types').slick({
        autoplay: false,
        centerMode: false,
        centerPadding: '0',
        slidesToShow: 3,
        infinite: false,
        variableWidth: true,
        arrows: true,
        dots: false,
        prevArrow:'<div class="left-radio arrow-radio"><span class="sprites"></span></div>',
        nextArrow:'<div class="right-radio arrow-radio"><span class="sprites"></span></div>',
    });
    
    $('.types2').slick({
        autoplay: false,
        autoplaySpeed: 4000,
        centerMode: false,
        centerPadding: '0',
        slidesToShow: 1.1,
        infinite: false,
        variableWidth: true,
        arrows: true,
        dots: false,
        prevArrow:'<div class="left-radio2 arrow-radio"><span class="sprites"></span></div>',
        nextArrow:'<div class="right-radio2 arrow-radio"><span class="sprites"></span></div>',
    });

    $('.types_rooms').slick({
        autoplay: false,
        autoplaySpeed: 4000,
        centerMode: false,
        centerPadding: '0',
        slidesToShow: 1.1,
        infinite: false,
        variableWidth: true,
        arrows: true,
        dots: false,
        prevArrow:'<div class="left-rooms arrow-radio"><span class="sprites"></span></div>',
        nextArrow:'<div class="right-rooms arrow-radio"><span class="sprites"></span></div>',
    });


    $('.multiple-items').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3
    });
    
    $('.plan').slick({
        autoplay: false,
        centerMode: false,
        centerPadding: '30px',
        slidesToShow: 3,
        infinite: false,
        variableWidth: true,
        arrows: true,
        dots: true,
        prevArrow:'<div class="left-radio arrow-radio"><i class="fas fa-caret-left"></i></div>',
        nextArrow:'<div class="right-radio arrow-radio"><i class="fas fa-caret-right"></i></div>',
        responsive: [
        {
                breakpoint: 768, // mobile portrait and below
                settings: { slidesToShow: 1.2, arrows: true,
                }
            },
            {
                breakpoint: 992, // tablets & small landscape devices like iPhone 11 landscape
                settings: {
                slidesToShow: 2,
                }
            },
            {
                breakpoint: 1200, // medium to large desktops
                settings: {
                slidesToShow: 3,
                }
            }
        ]
    });
    
    $('.radio-move').slick({
        autoplay: false,
        centerMode: false,
        centerPadding: '0',
        slidesToShow: 1,
        infinite: false,
        arrows: true,
        dots: false,
        prevArrow:'<div class="left-radio arrow-radio"><i class="fas fa-caret-left"></i></div>',
        nextArrow:'<div class="right-radio arrow-radio"><i class="fas fa-caret-right"></i></div>',
    });
    
    $('.diamond').slick({
        autoplay: true,
        centerMode: false,
        centerPadding: '',
        autoplaySpeed: 3000,
        slidesToShow: 3.5,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        dots: false,
        lazyLoad: 'ondemand',
        prevArrow:'<div class="left-arrow-card arrow-card"><span class="sprites"></span></div>',
        nextArrow:'<div class="right-arrow-card arrow-card"><span class="sprites"></span></div>',
        responsive: [
            {
                breakpoint: 768, // mobile portrait and below
                settings: {
                    slidesToShow: 1.2,
                    arrows: false,
                }
            },
            {
                breakpoint: 992, // tablets & small landscape devices like iPhone 11 landscape
                settings: {
                slidesToShow: 2,
                }
            },
            {
                breakpoint: 1200, // medium to large desktops
                settings: {
                slidesToShow: 3,
                }
            }
        ]
    });
    
    $('.diamond').on('lazyLoaded', function(event, slick, image){    
        let wrapper = $(image).closest('.top');
        wrapper.find('.lazy-spinner').fadeOut(300);
    });
    
    let totalLazyImages = $('.edit-images img[data-lazy]').length;
    let loadedCount = 0;
    
    $('.edit-images').slick({
        autoplay: true,    
        autoplaySpeed: 3000,
        slidesToShow: 4,
        slidesToScroll: 4,
        infinite: false,
        arrows: true,
        dots: false,
        lazyLoad: 'ondemand',
        prevArrow:'<div class="left-edit-arrow arrow-radio"><i class="fas fa-caret-left"></i></div>',
        nextArrow:'<div class="right-edit-arrow arrow-radio"><i class="fas fa-caret-right"></i></div>',
        responsive: [
            {
                breakpoint: 768, // mobile portrait and below
                settings: {
                    slidesToShow: 2,
                    arrows: true,
                }
            },
            {
                breakpoint: 992, // tablets & small landscape devices like iPhone 11 landscape
                settings: {
                slidesToShow: 2,
                }
            },
            {
                breakpoint: 1200, // medium to large desktops
                settings: {
                slidesToShow: 3,
                }
            }
        ]
    });

    $('.listing-gallery').slick({
        autoplay: true,
        centerMode: false,
        centerPadding: '0',
        autoplaySpeed: 5000,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        dots: true,
        lazyLoad: 'ondemand',
        prevArrow:'<div class="left-listing-arrow arrow-card"><span class="sprites"></span></div>',
        nextArrow:'<div class="right-listing-arrow arrow-card"><span class="sprites"></span></div>',
        responsive: [
            {
                breakpoint: 768, // mobile portrait and below
                settings: {
                slidesToShow: 1,
                }
            },
            {
                breakpoint: 992, // tablets & small landscape devices like iPhone 11 landscape
                settings: {
                slidesToShow: 2,
                }
            },
            {
                breakpoint: 1200, // medium to large desktops
                settings: {
                slidesToShow: 3,
                }
            }
        ]
    });

    $('.property_details').slick({
        autoplay: false,
        centerMode: false,
        centerPadding: '30px',
        autoplaySpeed: 5000,
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        dots: false,
        lazyLoad: 'ondemand',
        prevArrow:'<div class="left-arrow-card arrow-card"><span class="sprites"></span></div>',
        nextArrow:'<div class="right-arrow-card arrow-card"><span class="sprites"></span></div>',
        responsive: [
        {
                breakpoint: 768, // mobile portrait and below
                settings: {
                    slidesToShow: 1.1,
                    arrows: false,
                }
            },
            {
                breakpoint: 992, // tablets & small landscape devices like iPhone 11 landscape
                settings: {
                slidesToShow: 2,
                }
            },
            {
                breakpoint: 1200, // medium to large desktops
                settings: {
                slidesToShow: 3,
                }
            }
        ]
    });

    $('.property_details').on('lazyLoaded', function(event, slick, image){
        let wrapper = $(image).closest('.lazy-wrapper');
        wrapper.find('.lazy-spinner').fadeOut(300);    
    });
        
    
    $('.listing-gallery').on('lazyLoaded', function(event, slick, image){
        let wrapper = $(image).closest('.lazy-wrapper');
        wrapper.find('.lazy-spinner').fadeOut(300);    
    });

    //Slick gallery started
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: true,
        prevArrow:'<div class="left-arrow-carousal arrow-carousal"><span class="sprites"></span></div>',
        nextArrow:'<div class="right-arrow-carousal arrow-carousal"><span class="sprites"></span></div>',
        asNavFor: '.slider-nav'
    });
 
    $('.slider-for').on('lazyLoaded', function(event, slick, image){
        let wrapper = $(image).closest('.top');
        wrapper.find('.lazy-spinner').fadeOut(300);
    });
 
    $('.slider-nav').slick({
        slidesToShow: 7,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: false,
        arrows: true,
        centerMode: false,
        focusOnSelect: true,
        infinite: false,
        prevArrow:'<div class="left-radio3 arrow-radio"><span class="sprites"></span></div>',
        nextArrow:'<div class="right-radio3 arrow-radio"><span class="sprites"></span></div>',
        responsive: [
            {
                breakpoint: 768, // for mobile screens
                settings: {
                    slidesToShow: 3,  // show only 2 slides on mobile
                    arrows: false,    // optional: hide arrows on mobile
                    centerMode: false
                }
            }
        ]
    });
    
    $('.sidebar-gallery').slick({
        centerMode: true,
        centerPadding: '0',   // smaller padding
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        infinite: false,
        // prevArrow: '<i class="icon-left-arrow right-arrow arrow"></i>',
        // nextArrow: '<i class="icon-right-arrow left-arrow arrow"></i>',
        //lazyLoad: 'progressive' 
    });
    
    $('.edit-images').on('lazyLoaded', function(event, slick, image){
        loadedCount++;
    
        let wrapper = $(image).closest('.inner');
        wrapper.find('.lazy-spinner').fadeOut(300);
    
        $(image).addClass('loaded_image');
        
        if (loadedCount === totalLazyImages) {
            $('.inner').addClass('loaded');
        }
    });
    
    $('.featured-properties-slider').slick({
            slidesToShow: 1, // show one image+info at a time
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            arrows: true,
            fade: true,
            dots: false,
            infinite: true,
            prevArrow:'<div class="right-arrow arrow"><span class="sprites"></span></div>',
            nextArrow:'<div class="left-arrow arrow"><span class="sprites"></span></div>',
        });
    
    $(".responsive").slick({
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
    }); //Slick gallery end
});