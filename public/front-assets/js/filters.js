$(window).on('scroll', function () {

    

    var scrollPos = $(this).scrollTop();
   
    if (scrollPos > 50) {
        $('header').addClass('is-sticky');
    } else {
        $('header').removeClass('is-sticky');
    }

    if (scrollPos > 500) {
        $('.project-nav').slideDown(600);
    } else {
        $('.project-nav').slideUp(600);        
    }

    var headerHeight     = $('header').outerHeight(true) || 500;
    var projectNavHeight = $('.project-nav').outerHeight(true) || 500;
    var offset = headerHeight + projectNavHeight;

    $('#anchorNav .link[href^="#"]').each(function () {
        var currLink = $(this);
        var ref = $(currLink.attr('href'));

        if (
            ref.length &&
            ref.offset().top - offset <= scrollPos &&
            ref.offset().top + ref.outerHeight() > scrollPos
        ) {
            $('#anchorNav .link[href^="#"]').removeClass('active');
            $('.rh-content-wrapper').removeClass('active-wrapper');

            currLink.addClass('active');
            ref.addClass('active-wrapper');
        }
    });
});


let nearbyPlaces = [];

$(document).ready(function() {
    //NEAR BY
    let existing = $('#nearby_places_json').val();

    if (existing) {
        try {
            nearbyPlaces = JSON.parse(existing);
        } catch (e) {
            nearbyPlaces = [];
        }
    }
    updateNearbyInput();

    function updateNearbyInput() {
        $('#nearby_places_json').val(JSON.stringify(nearbyPlaces));

        let list = $('#nearby-list');
        list.empty();

        $.each(nearbyPlaces, function(index, place) {
            list.append(`
                <li>
                    <div class="details">
                        <p class="place-name">${place.type}</p>
                        <p class="title-small">${place.name} - ${place.distance} km</p>
                    </div>
                    <button type="button" class="btn-delete" data-index="${index}" >
                        <span class="sprites"></span>
                    </button>
                </li>
            `);
        });

        $('#nearbyCounts').text(`(${nearbyPlaces.length})`);
    }


    // Add Nearby Place
    $('#add-nearby').on('click', function() {
        let type = $('#nearby-type').val();
        let name = $('#nearby-name').val();
        let distance = $('#nearby-distance').val();

        if (!type || !name || !distance) {
            alert('Please select type, enter name, and choose distance.');
            return;
        }

        nearbyPlaces.push({ type, name, distance });
        updateNearbyInput();

        $('#nearby-type').val('');
        $('#nearby-name').val('');
        $('#nearby-distance').val('');
    });

    // Remove Nearby Place (delegated event)
    $('#nearby-list').on('click', '.btn-delete', function() {
        let index = $(this).data('index');
        nearbyPlaces.splice(index, 1);
        updateNearbyInput();
    });

    $('.addJson').on('click', '.btn-delete', function () {
        let btn = $(this);
        let index = $(this).data('index');
        let propertyId = $(this).data('property');

        $.ajax({
            url: `/property/${propertyId}/nearby-place/delete/${index}`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { _method: 'DELETE' },
            success: function (res) {
                btn.closest('li').remove();
            }
        });
    });


    //Deposit select show field start
    toggleCustomDeposit();

    $('#deposit').on('change', function () {
        toggleCustomDeposit();
    });

    function toggleCustomDeposit() {
        if ($('#deposit').val() === 'custom') {
            $('#custom_deposit_input').removeClass('d-none');
        } else {
            $('#custom_deposit_input').addClass('d-none');
            $('#custom_deposit_input').val('');
        }
    }
    //Deposit select show field end

    $('form').on('submit', function (e) {
        const btn = $('.animate');
        btn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Submitting')
           .prop('disabled', true);
    });

    // $('form').on('submit', function (e) {
    //     const btn = $('.animate_login');
    //     btn.html('Login to Account').prop('disabled', true);
    // });

    $('form').on('submit', function (e) {
        const btn = $('.animate_search');
        btn.html('Search...').prop('disabled', true);
    });

    // $('form').on('submit', function (e) {
    //     const btn = $('.animate_contact');
    //     btn.html('Submitting your details...').prop('disabled', true);
    // });

    $('#description_blog').summernote({
        placeholder: 'Enter blog description...',
        tabsize: 2,
        height: 200
    });

    if ($(window).width() <= 768) {
        $(".accordion-content").hide();
    }

    $(".accordion-header").on("click", function () {
        $(this).next(".accordion-content").slideToggle();
    });

    $("#customDropdownBtn").click(function (e) {
        e.stopPropagation();
        $("#notificationList").toggle();
    });

    // Close when clicking outside
    $(document).click(function () {
        $("#notificationList").hide();
    });        

    $(document).on('change', 'input[name="category"]', function() {
        $('.category_ul label').removeClass('active');
        $(this).closest('label').addClass('active');
    });

    $('.category_ul input[name="category"]:checked').closest('label').addClass('active');
    $('.custom-radio input[name="residencetypes"]:checked').closest('label').addClass('active');    

    let keyFeatures = [];
    function updateKeyInput() {
        $('#key_features_json').val(JSON.stringify(keyFeatures));

        let list = $('#key-list');
        list.empty();

        $.each(keyFeatures, function (index, feature) {
            list.append(`
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${feature}
                    <button type="button" class="btn btn-sm btn-danger remove-key" data-index="${index}">
                        Remove
                    </button>
                </li>
            `);
        });
    }

    // Add key feature
    $('#add-key').on('click', function () {
        let keyType = $('#key-type').val();
        if (!keyType) {
            alert('Please select a key feature');
            return;
        }
        keyFeatures.push(keyType);
        updateKeyInput();

        // Reset dropdown
        $('#key-type').val('');
    });

    // Remove key feature
    $('#key-list').on('click', '.remove-key', function () {
        let index = $(this).data('index');
        keyFeatures.splice(index, 1);
        updateKeyInput();
    });

    $(document).on('change', '.price_on_request', function () {
        let wrapper = $(this).closest('.form-group').find('.price_wrapper');
        if ($(this).is(':checked')) {
            wrapper.hide();
        } else {
            wrapper.show();
        }
    });

        
    //Load area started
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
    //Load area end

    // Load default city areas on page load
    let defaultCityId = $('#city').val();
    let selectedAreaId = "{{ isset($property) ? $property->area_id : '' }}";
    if (defaultCityId) {
        loadAreas(defaultCityId, selectedAreaId);
    }  

    //Custom deposit radio button
    $('.deposit-radio').on('change', function() {
        if ($('#deposit_custom').is(':checked')) {
            $('#custom_deposit_input').removeClass('d-none').focus();
        } else {
            $('#custom_deposit_input').addClass('d-none').val('');
        }
    });
   
    $('[data-bs-toggle="tooltip"]').tooltip();
    
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
   
    $(".toggle-btn").click(function(){
        let target = $($(this).data("target"));
        let icon = $(this).find("span");

        target.slideToggle(300);

        $(this).toggleClass("active");
        target.toggleClass("active");

        // Toggle + and -
        if(icon.text() === "+"){
        icon.text("-");
        } else {
        icon.text("+");
        }
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


   
    

    // When user selects a city
    $('#city-list input[name="city"]').change(function () {
        let cityId = $(this).val();
        loadAreas(cityId);
    });

    $(".property").hover(
        function() {
            $(this).find(".overlay").css("opacity", "1");
            $(this).find(".info").css("transform", "translateY(0)");
        },
        function() {
            $(this).find(".overlay").css("opacity", "0");
            $(this).find(".info").css("transform", "translateY(100%)");
        }
    );

    
    $(document).on("click", ".property-card", function (e) {
        // prevent triggering if clicking inside inputs (e.g., price field)
        if ($(e.target).is("input, label")) return;

        let $card = $(this);
        let $checkbox = $card.find(".select-room");

        // toggle checked state
        $checkbox.prop("checked", !$checkbox.prop("checked"));

        // toggle highlight class
        if ($checkbox.is(":checked")) {
            $card.addClass("chooseProperty");
        } else {
            $card.removeClass("chooseProperty");
        }
    });

    //While create new property with selected radio button like Rent or Sell
    $(document).on("change", "input[name='category']", function () {
        let selected = $(this).val();
        let $priceGroup = $(".form-group.required-field");
        let $priceLabel = $priceGroup.find("label");
        let $priceInput = $priceGroup.find("input[name='rooms[1][price]']");

        if (selected === "rent") {
            $priceLabel.html('Rent amount<span class="req">*</span>');
            $priceInput.attr("placeholder", "Rent");
        } else if (selected === "sale") {
            $priceLabel.html('Price<span class="req">*</span>');
            $priceInput.attr("placeholder", "Price");
        } else if (selected === "commercial") {
            $priceLabel.html('Price<span class="req">*</span>');
            $priceInput.attr("placeholder", "Price");
        } else if (selected === "pg") {
            $priceLabel.html('PG/Co-living Rent<span class="req">*</span>');
            $priceInput.attr("placeholder", "Rent Amount");
        }
    });

    //Login and Register tabs
    $('input[name="optionRadio"]').change(function() {
        var selected = $(this).val();

        // Show/hide tab panes
        $('.tab-pane').removeClass('show active');
        $('#div' + selected).addClass('show active');

        // Update button classes
        $('input[name="optionRadio"]').each(function() {
            var label = $('label[for="' + $(this).attr('id') + '"]');
            if ($(this).is(':checked')) {
                label.removeClass('btn-secondary').addClass('btn-primary');
            } else {
                label.removeClass('btn-primary').addClass('btn-secondary');
            }
        });
    });

    $(document).on("change", "input[name='residencetypes']", function () {
        // Labels
        $("input[name='residencetypes']").each(function () {
            $("label[for='" + this.id + "']").removeClass("active");
        });
        $("label[for='" + this.id + "']").addClass("active");

        // Content toggle
        if (this.value === "residential") {
            $(".residencyProperty").show().removeClass("d-none");
            $(".commercialProperty").hide().addClass("d-none");
        } else if (this.value === "commercial") {
            $(".residencyProperty").hide().addClass("d-none");
            $(".commercialProperty").show().removeClass("d-none");
        }
    });

    $(function () {
        $("input[name='residence_types']:checked").each(function () {
            $("label[for='" + this.id + "']").addClass("active");
            
        });
    });

    $(".logoFirst").hover(
        function() {
            $(this).find(".rollover-details").fadeIn(200);
        }, 
        function() {
            $(this).find(".rollover-details").fadeOut(200);
        }
    );

    $(".user").hover(
        function() {
            $(this).find(".rollover-user").fadeIn(200);
        }, 
        function() {
            $(this).find(".rollover-user").fadeOut(200);
        }
    );

    function updateProgress() {
        let role = $(".btn-next").data("role"); // "user" or "builder"
        let sections = $(`.form-section.${role}-section`);
        let totalSections = sections.length;
        let totalCompleted = 0;

        sections.each(function () {
            let step = $(this).data("step");
            let $section = $("#step-" + step);
            let fields = $(this).find(".required-field");
            let filledCount = 0;
            let totalFields = 0;

            // Unique groups for radios/checkboxes
            let radioNames = new Set();
            let checkboxNames = new Set();

            fields.each(function () {
                if ($(this).is(":radio")) {
                    radioNames.add($(this).attr("name"));
                } else if ($(this).is(":checkbox")) {
                    checkboxNames.add($(this).attr("name"));
                } else {
                    totalFields++;
                    if ($(this).val().trim() !== "") filledCount++;
                }
            });

            // Count once per radio group
            radioNames.forEach(name => {
                totalFields++;
                if ($(`input[name='${name}']:checked`).length > 0) filledCount++;
            });

            // Count once per checkbox group
            checkboxNames.forEach(name => {
                totalFields++;
                if ($(`input[name='${name}']:checked`).length > 0) filledCount++;
            });

            let percent = totalFields ? Math.round((filledCount / totalFields) * 100) : 0;
            let statusClass = filledCount === totalFields ? "completed" : filledCount > 0 ? "in-progress" : "pending";
            let statusText = 
                filledCount === 0 ? "Pending" :
                filledCount < totalFields ? `In Progress (${filledCount}/${totalFields})` : "Completed";

            $section.removeClass("pending in-progress completed").addClass(statusClass);
            $section.find(".status").text(statusText);
            $(this).find(".section-progress-bar").css("width", percent + "%");

            if (filledCount === totalFields) totalCompleted++;
        });

        // Overall progress (role-specific)
        let overallPercent = totalSections > 0 ? Math.round((totalCompleted / totalSections) * 100) : 0;
        $(".progress-bar").css("width", overallPercent + "%").text(overallPercent + "%");
    }

    // Trigger update on any change
    $(document).on("change", ".required-field, .required-group input", updateProgress);
        updateProgress();

        $(document).on("click", ".btn-next", function (e) {            
            e.preventDefault();

            let role = $(this).data("role"); 
            let $currentPane = $(".tab-content-custom.tab-active");
            let $nextPane = $currentPane.next(".tab-content-custom");
            let $currentTab = $(".nav-pills .nav-link.active");
            let $nextTab = $currentTab.closest("li").next("li").find(".nav-link");
            let validations = [];

            // 🔸 COMMON VALIDATIONS (Tab 1)
            if ($currentPane.hasClass(role + "Tab1")) {
                if (mode === 'edit') {
                    validations = [
                        { $el: $(".areaError"),value: $("#area_id").val(),message: "Please select an area." }
                    ];                    
                } else {
                    validations = [
                        { $el: $(".residenceError"), value: $("input[name='propertytypes']:checked").val(), message: "Please select a Property Type" },
                        { $el: $(".categoryError"), value: $("input[name='category']:checked").val(), message: "Please select a Category." },
                        { $el: $(".areaError"),value: $("#area").val(),message: "Please select an area." }
                    ];
                }                
            }

            // 🔸 BUILDER SPECIFIC (Tab 2)
            if (["builder", "admin"].includes(role) && $currentPane.hasClass("builderTab2")) {
                let isValid = true;
                let titleVal    = $("input[name='title']").val();
                let locationVal = $("input[name='location']").val();
                let priceVal    = $("input[name='rooms[1][price]']").val();
                let roomVal     = $("select[name='rooms[1][room]']").val();

                // Field validations
                isValid &= showError(
                    $("input[name='title']"),
                    "Property name must be at least 5 characters long",
                    titleVal.length < 5
                );

                isValid &= showError(
                    $("input[name='location']"),
                    "Property Location must be at least 5 characters long",
                    locationVal.length < 5
                );

                // Price on request logic
                let priceOnRequest = $("#price_on_request").is(":checked");

                if (!priceOnRequest) {
                    isValid &= showError(
                        $(".price1Error"),
                        "Price/Rent min 4 digits",
                        priceVal.length < 4
                    );
                } else {
                    showError($(".price1Error"), "", false);
                }

                isValid &= showError(
                    $(".roomError"),
                    "Select Bhk",
                    !roomVal
                );

                if (!isValid) {
                    e.preventDefault();
                    $currentPane.removeClass('tab-active');
                    $('.builderTab2').addClass('tab-active');
                    $('#pills-properties-tab').addClass('tab-active');
                    return false;
                }
            }    
            
            // 🔸 USER SPECIFIC (Tab 2)
            if (role === "user" && $currentPane.hasClass("userTab2")) {
                let titleVal = $("input[name='title']").val();
                let locationVal = $("input[name='location']").val();
                let builderValue = $("#builder_property_id").val();                                
                let priceVal = $("input[name='rooms[1][price]']").val()?.trim();
                let roomVal = $("input[name='rooms[1][room]']").val();

                validations = [{ $el: $(".price2Error"), value: priceVal, message: "Enter Price/Rent" }];

                if (builderValue === "existing") {
                    validations.push(
                        { $el: $("input[name='title']"), value: titleVal.length >= 5, message: "Property name must be at least 5 characters long" },
                        { $el: $("input[name='location']"), value: locationVal.length >= 5, message: "Location must be at least 5 characters long" },
                        //{ $el: $(".price1Error"), value: priceVal.length >= 4, message: "Price/Rent at least 4 digits" },
                        { $el: $(".price1Error"), value: $("#price_on_request").is(":checked") || priceVal.length >= 4, message: "Price/Rent at least 4 digits" },
                        { $el: $(".roomError"), value: $("select[name='rooms[1][room]']").val(), message: "Select Room" },                        
                    );
                } else {
                    validations.push({
                        $el: $(".roomSelectError"),
                        value: $(".select-room:checked").length > 0,
                        message: "Select at least one property and Enter Price/Rent"
                    });
                }
            }

            // 🔸 VALIDATION EXECUTION
            let valid = true;
            validations.forEach(v => {
                if (!showError(v.$el, v.message, !v.value)) valid = false;
            });
            if (!valid) return;

            // 🔸 PROCEED TO NEXT TAB
            if ($nextPane.length && $nextTab.length) {
                $currentPane.removeClass("tab-active");
                $nextPane.addClass("tab-active");
                $currentTab.removeClass("tab-active");
                $nextTab.addClass("tab-active");
                updateProgress(); 
            }
        });


    $('#contactForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function () {
                $('#contactModal').modal('hide');
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;

                $('.error-text').remove();

                $.each(errors, function (field, message) {
                    const $input = $('[name="' + field + '"]');

                    // Add red border
                    $input.addClass('is-invalid');

                    // Show error message
                    $input.after(
                        '<small class="text-danger error-text">' + message[0] + '</small>'
                    );
                });
            }
        });
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

    // In case of AJAX form submission, you can re-enable the button like this:
    $(document).ajaxComplete(function(event, xhr, settings) {
        $('button[type="submit"], input[type="submit"]').each(function() {
            const $btn = $(this);
            if ($btn.prop('disabled')) {
                const originalText = $btn.data('original-text');
                if (originalText) {
                    if ($btn.is('button')) $btn.html(originalText);
                    else $btn.val(originalText);
                }
                $btn.prop('disabled', false);
            }
        });
    });

    // Limit to max 3 visible notifications
    let $list = $('#notificationList');
    if ($list.children('li').length > 10) {
        $list.children('li').slice(10).remove(); // keep latest 3
    }

    // When user clicks a notification
    $('.mark-read').on('click', function(e) {
        e.preventDefault();
        let link = $(this);
        let li = link.closest('li');
        let url = link.attr('href');

        // Mark as read via AJAX
        $.get(url, function() {
            // Remove this notification from view
            li.fadeOut(300, function() {
                li.remove();

                // If more than 3 unread exist (new ones might be fetched later), remove oldest
                let total = $list.children('li').length;
                if (total > 3) {
                    $list.children('li').last().remove(); // remove oldest (bottom)
                }

                // Optionally update the unread count badge
                let countEl = $('#notificationDropdown');
                let count = parseInt(countEl.text().match(/\d+/)) || 0;
                count = Math.max(0, count - 1);
                countEl.html('🔔 Notifications (' + count + ')');
            });
        });
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

    // Common validation helper
    function showError($el, message, condition) {
        let $group = $el.closest(".form-group");
        let $error = $group.find(".error"); // expects .error inside form-group

        $error.text(""); // clear previous message
        $group.removeClass("invalid-class");

        if (condition) {
            $error.text(message);
            $group.addClass("invalid-class");
            return false; // invalid
        }
        return true; // valid
    }
      
    function updateDropdownButtonCount($btn) {
        let count = $btn.siblings('ul').find('label.active').length;
        let $countSpan = $btn.find('.counts');

        if (count > 0) {
            $countSpan.text(count);
        } else {
            $countSpan.text('');
        }
    }

    function restoreFilters() {
        $('.filterAuto').each(function() {
            let name = $(this).attr('name');
            let value = $(this).val();
            let type = $(this).attr('type');
            let stored = sessionStorage.getItem(name);

            if (type === 'checkbox') {
                if (stored) {
                    let selectedValues = JSON.parse(stored);
                    if (selectedValues.includes(value)) {
                        $(this).prop('checked', true);
                        $(this).closest('label').addClass('active');                        
                        $(this).closest('.dropdown').addClass('activeFilter');
                    }
                }
            } else if (type === 'radio') {
                if (stored === value) {
                    $(this).prop('checked', true);
                    $(this).closest('label').addClass('active');
                    $(this).closest('.dropdown').addClass('activeFilter');
                }
            }
        });

        // Store default button text if not already stored
        $('button[data-bs-toggle="dropdown"]').each(function() {
            let $btn = $(this);
            if (!$btn.data('default-text')) {
                $btn.data('default-text', $btn.text());
            }
            updateDropdownButtonCount($btn);
        });
    }

    restoreFilters();

    // On change: update sessionStorage, classes, and button count
    $(document).on('change', '.filterAuto', function() {
        let $input = $(this);
        let name = $input.attr('name');
        let type = $input.attr('type');

        // Update sessionStorage
        if (type === 'checkbox') {
            let selected = [];
            $(`input[name="${name}"]:checked`).each(function() {
                selected.push($(this).val());
            });
            sessionStorage.setItem(name, JSON.stringify(selected));
        } else if (type === 'radio') {
            sessionStorage.setItem(name, $input.val());
        }

        // Update label classes
        if ($input.is(':checked')) {
            $input.closest('label').addClass('active');
        } else {
            $input.closest('label').removeClass('active');
        }

        // Remove active from unchecked siblings
        if (type === 'checkbox') {
            $(`input[name="${name}"]:not(:checked)`).each(function() {
                $(this).closest('label').removeClass('active');
            });
        } else if (type === 'radio') {
            $(`input[name="${name}"]:not(:checked)`).each(function() {
                $(this).closest('label').removeClass('active');
            });
        }
        
        $('button[data-bs-toggle="dropdown"]').each(function() {
            updateDropdownButtonCount($(this));
        });
    });

    // ✅ Reset Filters Button
    $('#resetFilters').on('click', function() {
        $('.filterAuto').each(function() {
            sessionStorage.removeItem($(this).attr('name'));
        });
        $('.filterAuto').prop('checked', false).closest('label').removeClass('active');
        $('button[data-bs-toggle="dropdown"]').removeClass('activeFilter');
    });

    //Highlight class for filters started
    highlightActiveFilters();

    function highlightActiveFilters() {
        let path = window.location.pathname;

        // --- Reset all ---
        //$('input[type="radio"]').prop('checked', false);
        //$('label').removeClass('active');

        // --- Property Type ---
        let propertyMatch = path.match(/propertytypes_([^/]+)/);
        if (propertyMatch) {
            let value = propertyMatch[1];
            let input = $('input[name="propertytypes"][value="' + value + '"]');
            input.prop('checked', true).closest('label').addClass('active');
        }

        // --- Rooms ---
        let roomMatch = path.match(/rooms_([^/]+)/);
        if (roomMatch) {
            let value = roomMatch[1];
            let input = $('input[name="rooms"][value="' + value + '"]');
            input.prop('checked', true).closest('label').addClass('active');
        }

        // --- possession ---
        let possessionMatch = path.match(/possession_([^/]+)/);
        if (possessionMatch) {
            let value = possessionMatch[1];
            let input = $('input[name="possession"][value="' + value + '"]');
            input.prop('checked', true).closest('label').addClass('active');
        }

        // --- City (if radio-based) ---
        let cityMatch = path.match(/\/properties\/sale\/([^/]+)\/([^/]+)/);
        if (cityMatch) {
            let citySlug = cityMatch[2];
            let cityInput = $('input[name="city"][data-slug="' + citySlug + '"]');
            cityInput.prop('checked', true).closest('label').addClass('active');
        }

        // --- Keyword ---
        let params = new URLSearchParams(window.location.search);
        if (params.has('keyword')) {
            $('input[name="keyword"]').val(params.get('keyword'));
        }

        // --- Price Min/Max ---
        if (params.has('price_min')) {
            $('#priceMinSelect').val(params.get('price_min'));
        }
        if (params.has('price_max')) {
            $('#priceMaxSelect').val(params.get('price_max'));
        }
    }

    // Optional: live UI updates when clicked
    $(document).on('change', 'input[type="radio"]', function () {
        $(this).closest('ul').find('label').removeClass('active');
        $(this).closest('label').addClass('active');
    });
});  
//Document Ready end    


// When selecting a suggested area (mobile)
$(document).on('click', '.area-item-m', function () {
    let name = $(this).data('slug'); // or item.name if you prefer
    $('#keyword_m').val(name);
    $('#areaSuggestions_m').hide();
});

$(document).on('click', function (e) {
    if (!$(e.target).closest('#keyword_m, #areaSuggestions_m').length) {
        $('#areaSuggestions_m').hide();
    }
});

//ROOMS Started
let roomId = $(this).data("id");
if (roomId) {
    $("#rooms-container").append(`
        <input type="hidden" name="deleted_rooms[]" value="${roomId}">
    `);
}

$(document).on("click", ".remove-room-btn", function () {
    let parent = $(this).closest(".accordion-item");
    parent.remove();
});

$(document).on("click", ".remove-room-btn", function () {
    let parent = $(this).closest(".accordion-item");
    let roomId = $(this).data("id");

    if (roomId) {
        // mark for delete on server
        $("#rooms-container").append(`
            <input type="hidden" name="deleted_rooms[]" value="${roomId}">
        `);
    }
    parent.remove();
});

//let roomIndex = $(".accordion-item").length;
let roomIndex = $("#rooms-container .accordion-item").length + 1;

function loadRoomFields(index, bhkValue, storedRoomDetails = {}, storedCommonDetails = {}) {
    let wrapper = $(`#rooms_wrapper_${index}`);
    wrapper.empty();

    let roomsCount = {
        "1 RK": 1,
        "1_bhk": 1,
        "2_bhk": 2,
        "3_bhk": 3,
        "4_bhk": 4,
        "5_bhk": 5
    }[bhkValue] || 0;

    if (!roomsCount) return;

    let sliderHtml  = `<div class="types_rooms mt-4">`;

    for (let i = 1; i <= roomsCount; i++) {
        sliderHtml  += `                
            <div class="slide room-slide">
                <div class="form-group fix-big">
                    <label class="light-label">Room ${i}</label>
                    <div class="input-group">
                        <input type="text" name="rooms[${index}][details][${i}][room]" class="form-control" 
                            value="${storedRoomDetails[i]?.room ?? ''}" placeholder="Room" >

                        <input type="text" name="rooms[${index}][details][${i}][bathroom]" class="form-control" 
                            value="${storedRoomDetails[i]?.bathroom ?? ''}" placeholder="Bathroom">
                    </div>
                </div>
            </div>`;
    }        
    
    sliderHtml  += `        
            <div class="slide room-slide">
                <div class="form-group fix-big">
                    <label class="light-label">Common Bathroom</label>
                    <input type="text" name="rooms[${index}][common][common_bathroom]" class="form-control" placeholder="Common Bathroom" value="${storedCommonDetails.common_bathroom ?? ''}">
                </div>                
            </div>        
            <div class="slide room-slide">
                <div class="form-group fix-medium">
                    <label class="light-label">Tower</label>                    
                    <select name="rooms[${index}][common][tower]" class="form-select">
                        <option value="">Select</option>
                        ${'ABCDEFGHIJK'.split('').map(l =>
                            `<option ${storedCommonDetails.tower === l ? 'selected' : ''}>${l}</option>`
                        ).join('')}
                    </select>                    
                </div>
            </div>
            <div class="slide room-slide">
                <div class="form-group fix-medium">
                    <label class="light-label">Lift</label>
                    <select name="rooms[${index}][common][lift]" class="form-select">
                        <option value="">Select</option>
                        ${Array.from({length:15},(_,i)=>i+1).map(n =>
                            `<option ${storedCommonDetails.lift == n ? 'selected' : ''}>${n}</option>`
                        ).join('')}
                    </select>
                </div>
            </div>
            <div class="slide room-slide">
                <div class="form-group fix-medium">
                    <label class="light-label">Units on floor</label>                    
                    <select name="rooms[${index}][common][units_on_floor]" class="form-select">
                        <option value="">Select</option>
                        ${Array.from({length:5},(_,i)=>i+1).map(n =>
                            `<option ${storedCommonDetails.units_on_floor == n ? 'selected' : ''}>${n}</option>`
                        ).join('')}                                
                    </select>                    
                </div>
            </div>
            <div class="slide room-slide">
                <div class="form-group fix-medium">
                    <label class="light-label">Storey</label>                                            
                    <select name="rooms[${index}][common][storey]" class="form-select">
                        <option value="">Select</option>
                        ${Array.from({length:5},(_,i)=>i+1).map(n =>
                            `<option ${storedCommonDetails.storey == n ? 'selected' : ''}>${n}</option>`
                        ).join('')} 
                    </select>                    
                </div>
            </div>
            <div class="slide room-slide">
                <div class="form-group fix-medium">
                    <label class="light-label">Kitchen</label>
                    <input type="text" name="rooms[${index}][common][kitchen]" class="form-control" placeholder="Kitchen"
                            value="${storedCommonDetails.kitchen ?? ''}">
                </div>                
            </div>
            <div class="slide room-slide">
                <div class="form-group fix-medium">                        
                    <label class="light-label">Living</label>
                    <input type="text" name="rooms[${index}][common][living]" class="form-control" placeholder="Living"
                            value="${storedCommonDetails.living ?? ''}">
                </div>
            </div>
            <div class="slide room-slide">
                <div class="form-group fix-medium">
                    <label class="light-label">Balcony</label>
                    <input type="text" name="rooms[${index}][common][balcony]" class="form-control" placeholder="Balcony"
                        value="${storedCommonDetails.balcony ?? ''}">                    
                </div>
            </div>
        </div>`;        

        wrapper.html(sliderHtml);

        const slider = wrapper.find('.types_rooms');

        if (slider.hasClass('slick-initialized')) {
            slider.slick('unslick');
        }

        slider.slick({
            autoplay: false,
            centerMode: false,
            centerPadding: '0',
            slidesToShow: 3,
            infinite: false,
            variableWidth: true,
            arrows: true,
            dots: false,
            prevArrow:'<div class="left-rooms arrow-radio"><span class="sprites"></span></div>',
            nextArrow:'<div class="right-rooms arrow-radio"><span class="sprites"></span></div>',
        });
}

// CHANGE EVENT
$(document).on("change", ".bhk_selector", function () {
    let accordion = $(this).closest(".accordion-item");
    let index = accordion.data("index");
    let selectedBHK = $(this).val();
    let storedRoom = accordion.data("room_details") || {};
    let storedCommon = accordion.data("common_details") || {};

    loadRoomFields(index, selectedBHK, storedRoom, storedCommon);
});

// Trigger change on page load (for EDIT mode)
setTimeout(function () {
    $(".bhk_selector").each(function () {
        if ($(this).val()) $(this).trigger("change");
    });
}, 200);

$(document).on('click', '.remove-room', function () {
    $(this).closest('.accordion-item').remove();
});

$(".add-room").on("click", function () {
    let roomIndex = $("#rooms-container .accordion-item").length + 1;
    addNewRoomAccordion(roomIndex);
});

document.querySelectorAll('.floorGallery').forEach(function (el) {
    initFloorDropzone(el);
});

function addNewRoomAccordion(index) {        
    let html = `
        <div class="accordion-item" data-index="${index}">
            <div class="accordion-header">
                <p>Bhk details</p>                
                <button type="button" class="btn-delete remove-room">
                    <span class="sprites"></span>
                </button>                
            </div>
            
            <div class="accordion-body">                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-2">
                    <div class="lg:col-span-2">
                        <div class="form-group roomError">                              
                            <label class="light-label">Select Bhk<span class="req">*</span></label>
                            <select name="rooms[${index}][room]" class="form-select bhk_selector">
                                <option value="">Select Bhk</option>   
                                <option value="1 RK">1rk</option>
                                <option value="1_bhk">1 Bhk</option>
                                <option value="2_bhk">2 Bhk</option>
                                <option value="3_bhk">3 Bhk</option>
                                <option value="4_bhk">4 Bhk</option>
                                <option value="5_bhk">5 Bhk</option>                                 
                            </select>
                            <small class="error full-width roomError"></small>
                        </div>
                    </div>                            
                    <div class="lg:col-span-3">
                        <div class="form-group price1Error">
                            <label class="light-label">
                                <input type="hidden" name="rooms[${index}][price_on_request]" value="No">
                                <input type="checkbox" class="price_on_request" name="rooms[${index}][price_on_request]" value="Yes">Price on Request <span class="req">*</span>
                            </label>
                            <div class="price_wrapper">                            
                                <input type="number" id="price" name="rooms[${index}][price]"
                                class="form-control required-field" placeholder="Enter Price"
                                value="{{ old('rooms.' . $index . '.price', $room->price ?? '') }}">
                            </div>
                            <small class="error full-width"></small> 
                            <input type="hidden" name="rooms[${index}][id]" value="">
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="form-group">
                            <label class="light-label">Carpet Area</label>                                    
                            <input type="text" name="rooms[${index}][carpet]" class="form-control" placeholder="Carpet" value="" >                                        
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="form-group">
                            <label class="light-label">Format</label>                                                                            
                            <select name="rooms[${index}][format]" class="form-select">
                                <option value="sq.ft.">sq.ft.</option>
                                <option value="sq.yd.">sq.yd.</option>
                            </select>                                    
                        </div>
                    </div>
                    <div class="lg:col-span-3">                        
                        <div class="form-group">
                            <label class="light-label">Floor Map</label>
                            <div class="dropzone floorGallery dz-clickable" data-room-index="${index}">
                                <div class="dz-message needsclick">Image upload</div>
                            </div>                                
                        </div>
                    </div>
                </div>
                <div id="rooms_wrapper_${index}" ></div>
                <input type="hidden" name="rooms[__INDEX__][id]" value="">
            </div>
        </div>`;        
    $("#rooms-container").append(html);

    // 🔥 initialize dropzone for this room only
    let el = document.querySelector(
        `.floorGallery[data-room-index="${index}"]`
    );

    initFloorDropzone(el);
}
//Rooms end

//Search btn from home    
$(document).on('submit', '#searchHome', function (e) {    
    e.preventDefault();
        
    let categorySlug = $('input[name="category"]:checked').val() || 'sale';
    let citySlug = $('#citySelect').val() || 'all';    
    let areaSlugs = [];    

    $('input[name="areas[]"]:checked').each(function () {
        areaSlugs.push($(this).data('slug'));
    });

    if (areaSlugs.length === 0) {
        $('#areaWrapper').addClass('invalid-class');
        $('#areaError').removeClass('d-none');
        return false;
    } else {
        $('#areaWrapper').removeClass('invalid-class');
        $('#areaError').addClass('d-none');
    }

    let keyword = $('#keyword').val().trim().toLowerCase().replace(/\s+/g, '-');

    // --- Choose correct route ---
    let baseRoute = keyword
        ? $('#filtersBottom').data('first-route') // keyword-based route
        : $('#filtersBottom').data('second-route'); // normal filters route

    // --- Clean route to avoid duplication ---
    baseRoute = baseRoute.replace(/\/sale\/ahmedabad\/all.*/, '');

    // --- Construct base URL ---
    let areaSlug = areaSlugs.length > 0 ? areaSlugs.join('+') : 'all';
    let url = `/properties/${categorySlug}/${citySlug}/${areaSlug}`;

    if (keyword) {
        url += `/keyword_${keyword}`;
    }

    // Save checked areas before redirect
    $.post('/save-areas', {
        areas: areaSlugs,
        _token: $('meta[name="csrf-token"]').attr('content')
    }, function () {
        window.location.href = url;
    });
});

//Filters bottom stared
 $(document).on('submit', '#filtersBottom', function (e) {
    e.preventDefault();

    let baseRoute = '/properties';
    let urlParts = window.location.pathname.split('/').filter(Boolean);
    let currentCategory = urlParts[1] || 'sale';
    let currentCity     = urlParts[2] || 'ahmedabad';
    let currentArea     = urlParts[3] || 'all';
    let selectedCategory = $('input[name="category"]:checked').val();
    let selectedCity     = $('#citySelect_m').val();
    let selectedArea     = $('#selectedAreaSlug').val();
    let categorySlug = selectedCategory || currentCategory;
    let citySlug     = selectedCity || currentCity;    
    let areaSlug     = selectedArea || currentArea;

    // IMPORTANT FIX 🔥
    // Use SAME field name as your suggestion box (#keyword_m)
    let areaKeyword = $('#keyword_m').val()?.trim() || "";

    // Convert to slug
    let areaSlugFromKeyword = areaKeyword.toLowerCase().replace(/\s+/g, '-');

    // If user typed OR selected an area — override areaSlug
    if (areaKeyword.length > 0) {
        areaSlug = areaSlugFromKeyword;
    }

    let filters = [];

    let selectedType = $('input[name="propertytypes"]:checked').val();
    if (selectedType) filters.push('propertytypes_' + selectedType);

    let selectedRooms = $('input[name="rooms"]:checked').val();
    if (selectedRooms) filters.push('rooms_' + selectedRooms);

    let selectedPossession = $('input[name="possession"]:checked').val();
    if (selectedPossession) filters.push('possession_' + selectedPossession);

    // Build final URL
    let finalPath = `${baseRoute}/${categorySlug}/${citySlug}/${areaSlug}`;

    if (filters.length > 0) {
        finalPath += '/' + filters.join('/');
    }

    let params = new URLSearchParams();
    let priceMin = $('#priceMinSelect').val();
    let priceMax = $('#priceMaxSelect').val();

    if (priceMin && priceMin !== 'selected') params.set('price_min', priceMin);
    if (priceMax && priceMax !== 'selected') params.set('price_max', priceMax);

    let qs = params.toString();
    if (qs) finalPath += '?' + qs;

    window.location.href = finalPath;
});

function applyFilters(baseRoute) {        
    let categorySlug = $('input[name="category"]:checked').val() || 'sale';
    let citySlug = $('#citySelect').val() || 'ahmedabad';
    let keyword = $('input[name="keyword"]').val()?.trim() || '';

    let filters = [];
    
    let selectedType = $('input[name="propertytypes"]:checked').val();
    if (selectedType) filters.push('propertytypes_' + selectedType);

    let selectedRooms = $('input[name="rooms"]:checked').val();
    if (selectedRooms) filters.push('rooms_' + selectedRooms);

    // Construction
    let selectedPossession = $('input[name="possession"]:checked').val();
    if (selectedPossession) filters.push('possession_' + selectedPossession);

    // --- Build path dynamically ---
    let finalPath = `${baseRoute}/${categorySlug}/${citySlug}`;
    if (keyword) finalPath += `/${encodeURIComponent(keyword)}`;
    if (filters.length) finalPath += '/' + filters.join('/');

    // --- Query Params for price ---
    let params = new URLSearchParams();
    let priceMin = $('#priceMinSelect').val() || $('#min').val();
    let priceMax = $('#priceMaxSelect').val() || $('#max').val();

    if (priceMin && priceMin !== 'selected') params.set('price_min', priceMin);
    if (priceMax && priceMax !== 'selected') params.set('price_max', priceMax);

    let qs = params.toString();
    if (qs) finalPath += '?' + qs;

    // Redirect
    window.location.href = finalPath;
}
//Filters bottom end


//Radio button on checked add class to body
function updateMessage() {
    let selected = $("input[name='category']:checked").val();
    $(".category-message").hide();
    //$(".category-message-r").hide();
    $("." + selected + "-message").show();
    $("." + selected + "-message-r").show();
}
updateMessage();
$(document).on("change", "input[name='category']", function () {
    updateMessage();
});

let selectedCategory = $('input[name="category"]:checked').val(); 

//HOME PAGE
$(document).on('change', '#areas input[name="areas[]"]', function() {
    let count = $('#areas input[name="areas[]"]:checked').length;
    let button = $('#areaDropdown');
    if (count > 0) {
        button.text('Selected Area ' + count);
    } else {
        button.text('Select Area');
    }

    let selected = [];
    $('#areas input[name="areas[]"]:checked').each(function() {
        selected.push($(this).closest('label').text().trim());
    });
    
     let container = $('#showSelected');
    container.empty(); 

    $('#areas input[name="areas[]"]:checked').each(function() {
        let checkbox = $(this);
        let labelText = checkbox.closest('label').text().trim();
        let value = checkbox.val();
        let span = $('<span class="selected-item" data-value="' + value + '">' + labelText + ' </span>');
        container.append(span);
    });

    if ($('#areas input[name="areas[]"]:checked').length === 0) {
        container.text('Select Area'); // Default text
    }
});

let defaultCityId = $('#city-list input[name="city"]:checked').val();
if (defaultCityId) {
    loadAreas(defaultCityId);
}


$(document).on('input', '#keyword', function () {
    let query = $(this).val().trim();
    let citySlug = $('#citySelect').val() || '{{ $defaultCity->slug ?? "ahmedabad" }}';
    let categorySlug = $('#categorySelect').val() || 'sale';

    if (query.length < 2) {
        $('#areaSuggestions').hide();
        return;
    }

    $.ajax({
        url: `/get-areas/${citySlug}`,
        type: 'GET',
        data: { q: query },
        success: function (data) {
            if (data.length > 0) {
                let html = data.map(item => `
                    <li class="area-item list-group-item list-group-item-action"
                        style="cursor:pointer;"
                        data-type="${item.type}"
                        data-slug="${item.slug}"
                        data-area="${item.area_slug || ''}"                         
                        data-city="${item.city_slug || citySlug}"
                        data-category="${categorySlug}">

                        ${
                            item.type === 'area'
                            ? `
                                <span class="area-icon sprites"></span>
                            `
                            : `
                                <span class="project-icon sprites"></span>                               
                            `
                        }

                        <div class="label">
                            ${item.name}
                            ${
                                item.area_slug && item.area_slug.toLowerCase() !== item.name.toLowerCase()
                                    ? `, ${item.area_slug.replace(/-/g, ' ')}`
                                    : ''
                            }
                                                    
                         </div>
                    </li>
                `).join('');
                $('#areaSuggestions').html(html).show();
            } else {
                $('#areaSuggestions').hide();
            }
        }
    });

    //✅ Handle click redirects based on type
    $(document).on('click', '.area-item', function () {
        let type = $(this).data('type');
        let slug = $(this).data('slug');
        let city = $(this).data('city');
        let category = $('input[name="category"]:checked').val() || 'sale';

        let cleanSlug = (slug || '').toString().trim().toLowerCase().replace(/\s+/g, '-');

        let redirectUrl = '';
        let filter = '';

        if (type === 'area') {
            redirectUrl = `/properties/${category}/${city}/${cleanSlug}`;
        } 
        else if (type === 'project' || type === 'builder') {
            filter = `${cleanSlug}`;
            redirectUrl = `/properties/${category}/${city}/${filter}`;
        } 
        else {
            filter = `${cleanSlug}`;
            redirectUrl = `/properties/${category}/${city}/${filter}`;
        }

        window.location.href = redirectUrl;
    });

    // Hide suggestions on outside click
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#keyword, #areaSuggestions').length) {
            $('#areaSuggestions').hide();
        }
    });
});

function initRangeSlider(config) {
    const element = $(config.element);
    
    // 1️⃣ Get values from URL
    const urlParams = new URLSearchParams(window.location.search);
    const urlMin = urlParams.get(config.queryMin);
    const urlMax = urlParams.get(config.queryMax);

    // 2️⃣ Default to provided array values
    const defaultMin = config.values[0];
    const defaultMax = config.values[config.values.length - 1];

    // 3️⃣ Use URL values OR default values
    const from = urlMin ? parseInt(urlMin) : defaultMin;
    const to   = urlMax ? parseInt(urlMax) : defaultMax;

    // Set hidden inputs (if exists)
    $(config.minInput).val(from);
    $(config.maxInput).val(to);

    // 4️⃣ Create slider
    element.ionRangeSlider({
        type: "double",
        skin: "flat",
        grid: true,
        grid_snap: true,
        values: config.values,
        from: config.values.indexOf(from),
        to: config.values.indexOf(to),
        prettify: function (num) {
            return config.labels[config.values.indexOf(num)] || num;
        },
        onFinish: function (data) {
            const selectedMin = config.values[data.from];
            const selectedMax = config.values[data.to];

            // Update hidden inputs
            $(config.minInput).val(selectedMin);
            $(config.maxInput).val(selectedMax);

            // 5️⃣ Update URL params
            let url = new URL(window.location.href);
            url.searchParams.set(config.queryMin, selectedMin);
            url.searchParams.set(config.queryMax, selectedMax);

            window.location.href = url.toString();
        }
    });
}

// PRICE RANGE SLIDER
initRangeSlider({
    element: "#priceSlider",
    labels: ["0", "25L", "50L", "75L", "1Cr", "2Cr", "3Cr", "4Cr", "5Cr", "6Cr", "7Cr+"],
    values: [0, 2500000, 5000000, 7500000, 10000000, 20000000, 30000000, 40000000, 50000000, 60000000, 70000000],
    minInput: "#price_min",
    maxInput: "#price_max",
    queryMin: "price_min",
    queryMax: "price_max"
});

// SIZE RANGE SLIDER
initRangeSlider({
    element: "#sizeSlider",
    labels: ["0", "500", "1000", "2000", "3000", "4000", "5000", "6000", "7000+"],
    values: [0, 500, 1000, 2000, 3000, 4000, 5000, 6000, 7000],
    minInput: "#size_min",
    maxInput: "#size_max",
    queryMin: "size_min",
    queryMax: "size_max"
});

$(document).on('change', '.filterAuto', function () {
    let filterType = $(this).data('type') || $(this).attr('name');
    if (!filterType) return;

    filterType = filterType.replace(/\[\]$/, '');

    // Collect only values belonging to this filter
    let selectors = [
        `input[data-type="${filterType}"]:checked`,
        `input[name="${filterType}[]"]:checked`,
        `input[name="${filterType}"]:checked`
    ];

    // AREA special case
    if (filterType === 'areas') {
        selectors = [`input[name="areas[]"]:checked`];
    }

    let values = [];
    selectors.forEach(sel => {
        $(sel).each(function () {
            let v = $(this).val();
            if (v) values.push(encodeURIComponent(v));
        });
    });

    let parts = window.location.pathname.split('/').filter(Boolean);
    // structure = properties / sale / city / area(optional) / filters…

    let base = parts.slice(0, 3);  // properties/sale/ahmedabad
    let existingArea = parts[3] || null;
    let existingFilters = parts.slice(existingArea ? 4 : 3);

    // Remove old filterType tokens
    existingFilters = existingFilters.filter(t => !t.startsWith(filterType + '_'));

    if (filterType === 'areas') {
        // Replace area segment
        if (values.length) {
            base.push(values.join('+'));  // chandkheda+gota+charodi
        }
    } else {
        // Preserve existing area
        if (existingArea) {
            base.push(existingArea);
        }
        // Add new filter
        if (values.length) {
            existingFilters.push(filterType + '_' + values.join('+'));
        }
    }

    // Build final URL
    let finalUrl = '/' + base.join('/');
    if (existingFilters.length) finalUrl += '/' + existingFilters.join('/');

    window.location.href = finalUrl;
});

$(document).on('click', '#resetFilters', function(e) {
    e.preventDefault();
    // Uncheck checkboxes & radios
    $('input[type="checkbox"]').prop('checked', false);
    $('input[type="radio"]').prop('checked', false);
    $('label').removeClass('active click');

    // Clear range inputs
    $('input[name="price_min"], input[name="price_max"]').val('');
    $('input[name="size_min"], input[name="size_max"]').val('');

    // Get current URL path and split by '/'
    let parts = window.location.pathname.split('/').filter(Boolean);

    // Example: ["properties", "sale", "ahmedabad", "chandkheda+bopal+bapunagar", "rooms_3_bhk+4_bhk+5_bhk"]
    // Keep only up to areaSlug (4th segment)
    let baseParts = parts.slice(0, 4); 

    // Construct the base URL
    let baseUrl = '/' + baseParts.join('/');

    // Redirect
    window.location.href = baseUrl;
});

//Short properties
$('input[name="sort"]').on('change', function () {
    let sort = $(this).val();
    let params = new URLSearchParams(window.location.search);
    params.set('sort', sort); // update sort param
    window.location.href = window.location.pathname + '?' + params.toString();
});

$("#resetFiltersBtn").on("click", function () {
    $('input[name="area[]"]').prop("checked", false);
});

//Sticky filters radio
$(document).on('click', 'input[type="radio"]', function () {
    const groupName = $(this).attr('name'); // identify which filter group
    const $currentLabel = $(this).closest('label');

    // Remove .active from all radios in this group
    $(`input[name="${groupName}"]`).each(function () {
        $(this).closest('label').removeClass('active');
    });

    // Add .active to the one that was clicked
    $currentLabel.addClass('active');

    // Ensure checked state stays consistent
    $(this).prop('checked', true);
});

$(document).on('input', '#keyword_m', function () {
    let query = $(this).val().trim();
    let citySlug = $('#citySelect').val() || '{{ $defaultCity->slug ?? "ahmedabad" }}';
    let categorySlug = $('#categorySelect').val() || 'sale';

    if (query.length < 2) {
        $('#areaSuggestions_m').hide();
        return;
    }

    $.ajax({
        url: `/get-areas/${citySlug}`,
        type: 'GET',
        data: { q: query },
        success: function (data) {
            if (data.length > 0) {
                let html = data.map(item => `
                    <li class="area-item-m list-group-item list-group-item-action"
                         style="cursor:pointer;"
                        data-type="${item.type}"
                        data-slug="${item.slug}"
                        data-area="${item.area_slug || ''}"                         
                        data-city="${item.city_slug || citySlug}"
                        data-category="${categorySlug}">

                        ${
                            item.type === 'area'
                            ? `
                                <span class="area-icon sprites"></span>
                            `
                            : `
                                <span class="project-icon sprites"></span>
                            `
                        }

                        <span>
                            ${item.name}
                                ${
                                    item.area_slug && item.area_slug.toLowerCase() !== item.name.toLowerCase()
                                        ? `, ${item.area_slug.replace(/-/g, ' ')}`
                                        : ''
                                }
                                ${
                                    item.developer_name 
                                        ? ` - ${item.developer_name}`
                                        : ''
                                }                                                                              
                         </span>
                    </li>
                `).join('');
                $('#areaSuggestions_m').html(html).show();
            } else {
                $('#areaSuggestions_m').hide();
            }
        }
    });
});

// $(document).on('click', '.parent-tab', function (e) {     
//     e.preventDefault();
//     var target = $(this).data('target');

//     // Active tab
//     $('.parent-tab').removeClass('active');
//     $(this).addClass('active');

//     // Show corresponding content
//     $('.parent-content').removeClass('active').hide();
//     $(target).addClass('active').show();
// });

// $(document).on('click', '.child-tab', function (e) {     
//     e.preventDefault();
//     var target = $(this).data('target');

//     // Active tab
//     $('.child-tab').removeClass('active');
//     $(this).addClass('active');

//     // Show corresponding content
//     $('.child-content').removeClass('active').hide();
//     $(target).addClass('active').show();
// });