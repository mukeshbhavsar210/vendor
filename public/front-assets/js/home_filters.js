$(document).ready(function(){

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

    // When user selects a city
    // $('#city-list input[name="city"]').change(function () {
    //     let cityId = $(this).val();
    //     loadAreas(cityId);
    // });
});

$(document).ready(function () {
    //$(document).on('click', '#searchHome', function (e) {
    $(document).on('submit', '#searchHome', function (e) {
        
        e.preventDefault();

        let categorySlug = $('input[name="category"]:checked').val() || 'buy';
        let citySlug = $('#citySelect').val() || 'all';
        let areaSlugs = [];

        $('input[name="areas[]"]:checked').each(function () {
            areaSlugs.push($(this).data('slug'));
        });

        let keyword = $('#keyword').val().trim().toLowerCase().replace(/\s+/g, '-');

        // --- Choose correct route ---
        let baseRoute = keyword
            ? $('#filtersBottom').data('first-route') // keyword-based route
            : $('#filtersBottom').data('second-route'); // normal filters route

        // --- Clean route to avoid duplication ---
        baseRoute = baseRoute.replace(/\/buy\/ahmedabad\/all.*/, '');

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
});


$(document).on('input', '#keyword', function () {
    let query = $(this).val().trim();
    let citySlug = $('#citySelect').val() || '{{ $defaultCity->slug ?? "ahmedabad" }}';
    let categorySlug = $('#categorySelect').val() || 'buy';

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
        let category = $('input[name="category"]:checked').val() || 'buy';

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
