<div id="alert-area">
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<div class="modal right-modal" id="{{ $modal_id }}" tabindex="-1" aria-labelledby="{{ $modal_id }}Label" aria-hidden="true" data-bs-keyboard="true">
    <div class="modal-dialog {{ $formConfig['modal_size'] ?? '' }}">
        <div class="modal-content">            
            <form action="{{ $formConfig['action'] }}" method="POST" class="ajax-form" enctype="multipart/form-data" id="{{ $form_id }}">
                @csrf                
                
                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="_method" id="{{ $method_id }}" value="POST" class="form-control">
                    
                    <div class="row">
                        @foreach($formConfig['fields'] as $field)                                                    
                            @if($field['type'] !== 'accordion')
                                <div class="{{ $field['col'] ?? 'col-md-12' }}">
                                    <div class="form-group">                                    
                                        <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                                        @if($field['type'] == 'text')
                                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" id="{{ $field['id'] ?? '' }}" value="{{ old($field['name']) }}" class="form-control {{ $field['animate_label'] ?? '' }} {{ $field['class'] ?? '' }}" 
                                                @if(isset($field['data']))
                                                    @foreach($field['data'] as $key => $value)
                                                        data-{{ $key }}="{{ $value }}"
                                                    @endforeach
                                                @endif 
                                            >

                                        @elseif($field['type'] == 'email')                                                                                    
                                            <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                            

                                        @elseif($field['type'] == 'textarea')
                                            <textarea name="{{ $field['name'] }}" class="form-control {{ $field['summer_class'] }}" rows="4"></textarea>                                                                                
                                            
                                        @elseif($field['type'] == 'color')                                        
                                            <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                            

                                        @elseif($field['type'] == 'date')                                        
                                            <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                            

                                        @elseif($field['type'] == 'file')                                        
                                            <input type="{{ $field['type'] }}" 
                                                id="{{ $field['name'] }}" 
                                                name="{{ $field['name'] }}" 
                                                class="form-control" 
                                                accept="{{ $field['accept'] ?? '*' }}"
                                                @if($field['multiple'] ?? false) multiple @endif
                                                placeholder="{{ $field['placeholder'] ?? '' }}">

                                        @elseif($field['type'] == 'select')
                                            @php
                                                $selectedValue = old(
                                                    $field['name'],
                                                    $model->{$field['name']} ?? ($field['default'] ?? null)
                                                );
                                            @endphp

                                            <select name="{{ $field['name'] }}" class="form-select" id="{{ $field['name'] }}">
                                                @foreach($field['options'] as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ (string) $selectedValue === (string) $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>                                                 
                                            
                                        @elseif($field['type'] == 'category')                                                                                
                                            <select name="sub_category_id" id="sub_category" class="form-select" >
                                                <option value="">Sub Category</option>
                                            </select>                                        
                                        
                                        @elseif($field['type'] == 'dropzone')
                                            <input type="hidden" id="{{ $field['name'] }}_id" name="{{ $field['name'] }}_id" value=" ">
                                            <div id="{{ $field['name'] }}" data-input="{{ $field['name'] }}_id" class="dropzone custom-dropzone dz-clickable">
                                                <div class="dz-message needsclick">
                                                    <br>Drop files here or click to upload.<br><br>
                                                </div>
                                            </div>                                        
                                        @endif
                                    </div>
                            @else
                                @php
                                    $accordionName = $field['name'] ?? 'accordion';
                                    $accordionWrapperId = $accordionName . '_accordion';
                                @endphp
                                
                                <div class="accordion" id="{{ $accordionWrapperId }}">
                                    @foreach($field['items'] ?? [] as $key => $accordionItem)
                                        @php
                                            $accordionId = $accordionName . '_' . $key;
                                            $collapseId = 'collapse_' . $accordionId;
                                            $headingId = 'heading_' . $accordionId;
                                        @endphp

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="{{ $headingId }}">
                                                <button class="accordion-button {{ $key != 0 ? 'collapsed' : '' }}"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#{{ $collapseId }}"
                                                    aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                                    aria-controls="{{ $collapseId }}" >
                                                    <b>{{ $accordionItem['title'] }}</b>
                                                </button>
                                            </h2>

                                            <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                                                aria-labelledby="{{ $headingId }}" data-bs-parent="#{{ $accordionWrapperId }}" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        @foreach($accordionItem['fields'] ?? [] as $innerField)
                                                            <div class="{{ $innerField['col'] ?? 'col-md-6' }}">
                                                                <div class="form-group">
                                                                    <label class="form-label">{{ $innerField['label'] }}</label>

                                                                    @if($innerField['type'] == 'text') 
                                                                        <input type="{{ $innerField['type'] }}" name="{{ $innerField['name'] }}" id="{{ $innerField['id'] ?? '' }}" value="{{ old($innerField['name']) }}" class="form-control {{ $innerField['animate_label'] ?? '' }} {{ $innerField['class'] ?? '' }}" 
                                                                            @if(isset($innerField['data']))
                                                                                @foreach($innerField['data'] as $key => $value)
                                                                                    data-{{ $key }}="{{ $value }}"
                                                                                @endforeach
                                                                            @endif
                                                                        >
                                                                    @elseif($innerField['type'] == 'textarea')
                                                                        <textarea name="{{ $innerField['name'] }}" class="form-control" rows="3" >
                                                                            {{ old(
                                                                                $innerField['name'],
                                                                                $model->{$innerField['name']} ?? ''
                                                                            ) }}
                                                                        </textarea>
                                                                    @elseif($innerField['type'] == 'file')
                                                                        <input type="file" name="{{ $innerField['name'] }}" class="form-control" id="{{ $innerField['name'] }}"
                                                                            accept="{{ $innerField['accept'] ?? '*/*' }}" >

                                                                    @elseif($innerField['type'] == 'select')
                                                                        @php
                                                                            $selectedValue = old(
                                                                                $innerField['name'],
                                                                                $model->{$innerField['name']} ?? ($innerField['default'] ?? null)
                                                                            );
                                                                        @endphp                                                                                        

                                                                        <select name="{{ $innerField['name'] }}" class="form-select" id="{{ $innerField['name'] }}">
                                                                            @foreach($innerField['options'] as $value => $label)
                                                                                <option value="{{ $value }}"
                                                                                    {{ (string) $selectedValue === (string) $value ? 'selected' : '' }}>
                                                                                    {{ $label }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>  
                                                                        
                                                                    @elseif($innerField['type'] == 'dropzone')
                                                                        <input type="hidden" id="{{ $innerField['name'] }}_id" name="{{ $innerField['name'] }}_id" value=" ">

                                                                        <div id="image" id="{{ $innerField['name'] }}" data-input="{{ $innerField['name'] }}_id"
                                                                            data-max-files="{{ $innerField['maxFiles'] ?? 1 }}"
                                                                            data-accepted-files="{{ $innerField['acceptedFiles'] ?? 'image/*' }}" 
                                                                            class="dropzone dz-clickable">
                                                                            <div class="dz-message needsclick">Drop files here or click to upload.</div>
                                                                        </div> 
                                                                        
                                                                        {{-- <div
                                                                            id="{{ $innerField['name'] }}"
                                                                            data-input="{{ $innerField['name'] }}_id"
                                                                            data-max-files="{{ $innerField['maxFiles'] ?? 1 }}"
                                                                            data-accepted-files="{{ $innerField['acceptedFiles'] ?? 'image/*' }}"
                                                                            class="dropzone dz-clickable"
                                                                        >
                                                                            <div class="dz-message needsclick">Drop files here or click to upload.</div>
                                                                        </div> --}}
                                                                                                                                                    
                                                                        @if(isset($service) && $service->images->isNotEmpty())                        
                                                                            <div id="product-gallery" class="row">                                    
                                                                                @foreach ($service->images as $index => $image)
                                                                                    <div class="col-3 uploaded-images" id="image-row-{{ $image->id }}">                                        
                                                                                        <input type="hidden" name="image_array[{{ $index }}][image_id]" value="{{ $image->id }}">
                                                                                        <img src="{{ asset('uploads/service/'.$image->image) }}" class="rounded" />

                                                                                        <a href="javascript:void(0)" class="deleteProductImg delete-icon-edit" data-id="{{ $image->id }}">
                                                                                            <span class="sprites"></span>
                                                                                        </a>
                                                                                    </div>
                                                                                @endforeach                                                            
                                                                            </div>                               
                                                                        @endif

                                                                        <div id="product-gallery"></div>
                                                                    @endif
                                                                </div>
                                                            </div>                                                                
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif                                                                                                                                                            
                        </div>
                        @endforeach
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        {{ $formConfig['button'] }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('customJs')
<script>
    $(document).on('submit', '.ajax-form', function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = new FormData(this);

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Close modal
                let modal = form.closest('.modal');
                let modalInstance = bootstrap.Modal.getInstance(modal[0]);
                modalInstance.hide();

                // Optional: Reset form
                form[0].reset();

                // Show success alert
                $('#alert-area').html(`
                    <div class="alert alert-success alert-dismissible fade show">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);

                // Auto remove after 3 seconds
                setTimeout(function(){
                    $('.alert').fadeOut();
                }, 3000);

                // Reload page OR append row dynamically
                location.reload();
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });

    $(document).on('input', '.slug-source', function () {
        let element = $(this);
        let form = element.closest('form');
        let target = element.data('target');
        let submitBtn = form.find("button[type=submit]");

        submitBtn.prop('disabled', true);

        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'GET',
            data: { title: element.val() },
            dataType: 'json',
            success: function (response) {

                submitBtn.prop('disabled', false);

                if (response.status) {
                    form.find(target).val(response.slug);
                }
            }
        });
    });

    Dropzone.autoDiscover = false;
    document.querySelectorAll('.custom-dropzone').forEach(function (el) {
        let inputId = el.getAttribute('data-input');
        new Dropzone(el, { 
            url: "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            success: function(file, response){
                document.getElementById(inputId).value = response.image_id;
            }
        });
    });

   $(document).ready(function () {        
        $(document).on('change', '#category_id', function () {            
            var categoryID = $(this).val();

            if (categoryID) {
                $('#sub_category').html('<option>Loading...</option>');

                $.ajax({
                        url: "{{ route('get.subcategories', ':id') }}".replace(':id', categoryID), type: 'GET',
                        success: function (data) {

                        $('#sub_category').html('<option value="">Select Sub Category</option>');

                        $.each(data, function (key, value) {
                            $('#sub_category').append(
                                '<option value="' + value.id + '">' + value.sub_category_name + '</option>'
                            );
                        });
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });

            } else {
                $('#sub_category').html('<option value="">Select Sub Category</option>');
            }
        });
    });

    //Store
    const store_category        = "{{ route('category.store') }}";
    const store_subcategory     = "{{ route('subCategory.store') }}";
    const store_subsubcategory  = "{{ route('subSubCategory.store') }}";
    const store_discount        = "{{ route('coupons.store') }}";

    //Update    
    const update_category       = "{{ url('admin/category') }}";
    const update_subCategory    = "{{ url('admin/subcategory/') }}";
    const update_subSubCategory = "{{ url('admin/subsubcategory/') }}";
    const update_discount       = "{{ url('admin/settings/coupons/{coupon}') }}";

    function createCategoryModal() {
        document.querySelector('#categoryModal .modal-title').innerText = 'Create Category';
        let form = document.getElementById('categoryForm');
        form.reset();
        form.action = store_category;
        document.getElementById('form_method').value = 'POST';
        document.getElementById('form_submit_btn').innerText = 'Create Category';
    }

    //Create SubCategory Modal
    function createSubCategoryModal(button) {
        let categoryId = $(button).data('category-id');
        let form = document.getElementById('subCategoryForm');

        // Reset form first
        form.reset();

        // Set create mode
        form.action = store_subcategory;        

        // Auto-select category
        document.getElementById('category_id').value = categoryId;

        $('#category_id').trigger('change');        
    }   
   
    // function editCategoryModal(button) {
    //     let id = button.dataset.id;
    //     let category_modal = button.dataset.category_modal;
    //     let category_name = button.dataset.category_name;
    //     let showHome = button.dataset.showHome;
    //     let status = button.dataset.status;        

    //     document.querySelector('#categoryModal .modal-title').innerText = 'Edit Category';
    //     let form = document.getElementById('categoryForm');

    //     // Set action
    //     form.action = `${update_category}/${id}`;
    //     document.getElementById('category_method').value = 'PUT';

    //     // Fill values
    //     document.getElementById('category_modal').value = category_modal;
    //     document.getElementById('category_name').value = category_name;
    //     document.getElementById('menu_order').value = menu_order;
    //     document.getElementById('showHome').value = showHome;
    //     document.getElementById('status').value = (status == 'Active') ? 1 : 0;        
    //     document.getElementById('form_submit_btn').innerText = 'Update Category';
    // }



    function editCategoryModal(button) {
        let id = button.dataset.id;
        let category_modal = button.dataset.categoryModal;
        let category_name = button.dataset.categoryName;
        let showHome = button.dataset.showHome;
        let menu_order = button.dataset.menuOrder;
        let status = button.dataset.status;

        document.querySelector('#categoryModal .modal-title').innerText = 'Edit Category';

        let form = document.getElementById('categoryForm');

        // Set action
        form.action = `${update_category}/${id}`;
        document.getElementById('category_method').value = 'PUT';

        // Fill values
        document.getElementById('category_modal').value = category_modal;
        document.getElementById('category_name').value = category_name;
        document.getElementById('menu_order').value = menu_order;

        // Select values
        document.getElementById('showHome').value = String(showHome);
        document.getElementById('status').value = String(status);

        document.getElementById('form_submit_btn').innerText = 'Update Category';
    }

    document.getElementById('editCategoryModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('categoryForm').reset();
    });    

    function editSubCategoryModal(button) {
        let id = button.dataset.id;

        let form = document.getElementById('subCategoryForm');

        form.action = `${update_subCategory}/${id}`;
        document.getElementById('subcategory_method').value = 'PUT';

        // Fill values
        document.getElementById('sub_category_name').value = button.dataset.sub_category_name;                    

        document.querySelector('#subCategoryModal .modal-title').innerText = 'Edit Sub Category';
        document.getElementById('form_submit_btn').innerText = 'Update Category';
    }

    document.getElementById('subCategoryModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('subCategoryForm').reset();
    });


    //Discount
    function createDiscountModal() {
        document.querySelector('#discountModal .modal-title').innerText = 'Create Discount';
        let form = document.getElementById('discountForm');
        form.reset();
        form.action = store_discount;
        document.getElementById('form_method').value = 'POST';
        document.getElementById('form_submit_btn').innerText = 'Create Discount';
    }

    function editDiscountModal(button) {
        let id = button.dataset.id;
        let form = document.getElementById('discountForm');

        form.action = `${update_discount}/${id}`;
        document.getElementById('form_method').value = 'PUT';

        // Fill values
        document.getElementById('code').value = button.dataset.code;        
        document.querySelector('#discountModal .modal-title').innerText = 'Edit Discount';
        document.getElementById('form_submit_btn').innerText = 'Update Discount';
    }

    document.getElementById('discountModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('discountForm').reset();
    });    
</script>
@endsection