$(document).ready(function() {        
    setTimeout(function () {
        $('.property-skeleton-wrapper').fadeOut(300, function () {
            $('.listing-card').fadeIn(300);
        });
    }, 700); 

    setTimeout(function() {
        $('.alert').fadeOut('fast');
    }, 1500);  

    $('.tagNav .nav-link').on('click', function(e) {
        e.preventDefault();

        var $nav = $(this).closest('.tagNav'); // current nav
        var target = $(this).data('target');
        var $targetDiv = $('#' + target);

        // Find the matching tag-container for this nav
        var $container = $targetDiv.closest('.tag-container');

        // Scroll container to the target div
        $container.animate({
            scrollLeft: $targetDiv.position().left + $container.scrollLeft()
        }, 400);

        // Navbar active state (only inside this nav)
        $nav.find('.nav-link').removeClass('active');
        $(this).addClass('active');

        // Div active state (only inside this container)
        $container.find('.tag').removeClass('div-active');
        $targetDiv.addClass('div-active');
    });

     // Initialize each module independently (works even if multiple exist on the page)
    $('.tag-module').each(function () {
        const $module   = $(this);
        const $nav      = $module.find('.tagNav').first();                       // scope to this module
        const $links    = $nav.find('> li > .nav-link[data-target]');            // only count intended nav items
        const total     = $links.length;

        // Set total
        $module.find('.counter-total').text(total);

        // Determine current from the active link (fallback to 1)
        let currentIdx = $links.index($links.filter('.active')) + 1;
        if (currentIdx < 1) currentIdx = 1;

        // Render initial state
        $module.find('.counter-current').text(currentIdx);
        $module.find('.slick-progress').css('width', (currentIdx / total * 100) + '%');

        // Click handler (scoped)
        $links.on('click', function (e) {
            e.preventDefault();

            // Active state on nav
            $links.removeClass('active');
            $(this).addClass('active');

            // Update counter + progress
            const index = $links.index(this) + 1;
            $module.find('.counter-current').text(index);
            $module.find('.slick-progress').css('width', (index / total * 100) + '%');
        });
    });

     $('#big-modal').on('shown.bs.modal', function () {
        $('.center').slick('setPosition');
    });
    
    $('.dropdown-menu').on('click', function (e) {
        e.stopPropagation();
    });

    $('#more-filters-tab a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });


    // Show or hide button on scroll
    $(window).scroll(function(){
      if($(this).scrollTop() > 400){
        $("#scroll-top").fadeIn();
      } else {
        $("#scroll-top").fadeOut();
      }
    });

    // Smooth scroll to top
    $("#scroll-top").click(function(){
      $("html, body").animate({scrollTop: 0}, 600); // 600ms
      return false;
    });

    //Properties listing filter open from bottom
    document.addEventListener('click',function(e){    
        if(e.target.classList.contains('hamburger-toggle')){
            e.target.children[0].classList.toggle('active');
        }
    })

    let scrollTimer;

    $(window).on('scroll', function() {
        // When scrolling, reduce opacity
        $('.filter-btm').css('opacity', '0.5');

        // Clear previous timer
        clearTimeout(scrollTimer);

        // Set a new timer to detect scroll stop (e.g., after 150ms)
        scrollTimer = setTimeout(function() {
            $('.filter-btm').css('opacity', '1');
        }, 150);
    });

    $('#openFilters').click(function() {
        $('.overlay').addClass('active');
        $('.filter-drawer').addClass('active');
    });

    $('#openDrawer').click(function() {
        $('.overlay').addClass('active');
        $('.sort-drawer').addClass('active');
    });

    // Close Drawer
    $('.closeDrawer, .overlay').click(function() {
        $('.overlay').removeClass('active');
        $('.filter-drawer').removeClass('active');
        $('.sort-drawer').removeClass('active');
    });

    $(".close-menu").click(function () {
        $('.header-menu').collapse('show');
    });
    // $('.navbar-collapse a').click(function(){
    //     $(".navbar-collapse").collapse('hide');
    // });

    const $overlay = $('.menu-overlay');
    const $navbarCollapse = $('#navbarNav');

    // When menu is shown
    $navbarCollapse.on('show.bs.collapse', function () {
        $overlay.fadeIn(200);
    });

    // When menu is hidden
    $navbarCollapse.on('hide.bs.collapse', function () {
        $overlay.fadeOut(200);
    });

    // Click on overlay closes menu
    $overlay.on('click', function () {
        $navbarCollapse.collapse('hide');
    });

    // Remove room group
    $(document).on('click', '.remove-room', function () {
        $(this).closest('.room-group').remove();
    });


    // Plan radio button changes
    $('input[name="package_id"]').change(function() {
        $('.plan-card').removeClass('active');
        $(this).closest('.plan-card').addClass('active');
    });
    $('input[name="package_id"]:checked').closest('.plan-card').addClass('active');
    
    $('.radio-btn1 input').on('change', function () {        
        $(this)
            .closest('.radio-button-group')
            .find('.radio-btn')
            .removeClass('active_form');
        $(this)
            .closest('.radio-btn')
            .addClass('active_form');
    });

    $('.radio-btn2 input').on('change', function () {        
        $(this)
            .closest('.radio-button-group')
            .find('.radio-btn')
            .removeClass('active_form');
        $(this)
            .closest('.radio-btn')
            .addClass('active_form');
    });
         

    $('.mobile-menu').on('click', function (e) {
        e.stopPropagation();

        $('.mobile-navbar').toggleClass('hidden');
        $('.mobile-navbar').toggleClass('mobile-active');

        let $svg = $(this).find('svg');

        if ($svg.hasClass('lucide-menu')) {
            $svg.replaceWith(`
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-x">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            `);
        } else {
            $svg.replaceWith(`
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-menu">
                    <path d="M4 12h16"></path>
                    <path d="M4 6h16"></path>
                    <path d="M4 18h16"></path>
                </svg>
            `);
        }
    });

    $('.menu-wrapper').on('click', function (e) {
        e.stopPropagation();
    });

    $(document).on('click', function () {
        $('.menu-wrapper').removeClass('hidden ');
    });

    $('.menu-toggle').on('click', function (e) {
        e.stopPropagation();

        let $wrapper = $(this).closest('.menu-wrapper');
        let $wrapperBtn = $(this).closest('.menu-toggle');

        // Remove active classes from other menus and their SVGs
        $('.menu-wrapper').not($wrapper).removeClass('active-menu');
        $('.menu-toggle').not($wrapperBtn).removeClass('text-blue-700');        
        $('.menu-wrapper').not($wrapper).find('> .menu-toggle > svg').removeClass('rotate-180');        

        // Toggle current menu
        $wrapper.toggleClass('active-menu');
        $wrapperBtn.toggleClass('text-blue-700');
        // $wrapperBtn.removeClass('text-gray-700');
        // $wrapperBtn.addClass('text-blue-700');
        $wrapper.find('> .menu-toggle > svg').toggleClass('rotate-180');
    });

    $('.menu-wrapper').on('click', function (e) {
        e.stopPropagation();
    });

    $(document).on('click', function () {
        $('.menu-wrapper').removeClass('active-menu');
        $('.menu-wrapper > .menu-toggle > svg').removeClass('rotate-180');
    });

    $(".accordion-header").on("click", function () {
        $(this).next(".accordion-content").slideToggle();
    });

    $(document).on('change', '.price_on_request', function () {
        let wrapper = $(this).closest('.form-group').find('.price_wrapper');

        if ($(this).is(':checked')) {
            wrapper.hide();
        } else {
            wrapper.show();
        }
    });

    function loadAreas(cityId, selectedAreaId = null) {
        if (!cityId) return;
        $.ajax({
            url: '/getareas/' + cityId,
            type: 'GET',
            success: function(data) {
                let html = '<option value="">Select Area</option>';
                data.forEach(function(area) {
                    let selected = (selectedAreaId && selectedAreaId == area.id) ? 'selected' : '';
                    html += `<option value="${area.id}" ${selected}>${area.name}</option>`;
                });
                $('#area').html(html);
            }
        });
    }     
});

    $(document).on('click', '.openModal', function () {        
        let target = $(this).data('target');
        $(target).addClass('is-open');
        $('body').addClass('modal-open');
    });

    $(document).on('click', '.modal-close, .closeBtn, .modal-overlay', function () {
        $(this).closest('.custom-modal').removeClass('is-open');
        $('body').removeClass('modal-open');
    });

    $(document).on('keyup', function (e) {
        if (e.key === 'Escape') {
            $('.custom-modal').removeClass('is-open');
            $('body').removeClass('modal-open');
        }
    });

    //Acccordion Menu
    $(function () {
        $('.accordion-item.opened_accordion .accordion-body').show();
        $(document).on('click', '.accordion-header', function () {
            var item = $(this).closest('.accordion-item');
            if (item.hasClass('opened_accordion')) {
                item.removeClass('opened_accordion');
                item.find('.accordion-body').slideUp(300);
            } else {
                $('.accordion-item').removeClass('opened_accordion');
                $('.accordion-body').slideUp(300);

                item.addClass('opened_accordion');
                item.find('.accordion-body').slideDown(300);
            }
        });
    });

    //Tabs
    $(document).on('click', '.tab-link-custom', function (e) {    
        e.preventDefault();
        var target = $(this).data('target');

        // Active tab
        $('.tab-link-custom').removeClass('bg-blue-600 text-white');
        $(this).addClass('bg-blue-600 text-white');

        // Show corresponding content
        $('.tab-content-custom').removeClass('tab-active').hide();
        $(target).addClass('tab-active').show();
    });


$('.menu-toggle-features').on('click', function () {
    $(this).toggleClass('active');

    if ($(this).hasClass('active')) {
        $('.content').css('top', '61px');
    } else {
        $('.content').css('top', '-120px');
    }

    let target = $($(this).data("target"));
    let icon = $(this).find("span");

    // target.slideToggle(300);

    // $(this).toggleClass("active");
    // target.toggleClass("active");

    // Toggle + and -
    if(icon.text() === "+"){
    icon.text("-");
    } else {
    icon.text("+");
    }
});