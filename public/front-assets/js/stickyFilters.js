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


$(document).ready(function () {
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
        let cityMatch = path.match(/\/properties\/buy\/([^/]+)\/([^/]+)/);
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


$(document).ready(function () {
 $(document).on('submit', '#filtersBottom', function (e) {
    e.preventDefault();

    let baseRoute = '/properties';

    let urlParts = window.location.pathname.split('/').filter(Boolean);

    let currentCategory = urlParts[1] || 'buy';
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
        // --- Get selected values ---
        let categorySlug = $('input[name="category"]:checked').val() || 'buy';
        let citySlug = $('#citySelect').val() || 'ahmedabad';
        let keyword = $('input[name="keyword"]').val()?.trim() || '';

        let filters = [];

        // Property Type
        let selectedType = $('input[name="propertytypes"]:checked').val();
        if (selectedType) filters.push('propertytypes_' + selectedType);

        // Rooms
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
});



$(document).on('input', '#keyword_m', function () {
    let query = $(this).val().trim();
    let citySlug = $('#citySelect').val() || '{{ $defaultCity->slug ?? "ahmedabad" }}';
    let categorySlug = $('#categorySelect').val() || 'buy';

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