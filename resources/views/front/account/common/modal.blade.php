<div class="modal fade" id="{{ $form['modal_id'] }}" tabindex="-1" aria-labelledby="{{ $form['modal_id'] }}Label" aria-hidden="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered {{ $form['modal_size'] ?? '' }}" >
        <div class="modal-content">            
            <form action="{{ $form['action'] }}" method="POST" class="ajax-form" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ $form['title'] }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body {{ $form['modal_body'] ?? '' }} py-3">
                    @if($form['modal_body'])
                        <h4 class="mb-2">Contact Details</h4>    
                    @endif
                    <div class="row">
                        @foreach($form['fields'] as $field)                        
                            <div class="{{ $field['col'] ?? 'col-md-12' }}">                                
                                @if($field['type'] == 'text')
                                    <div class="form-group">
                                        <input 
                                            type="{{ $field['type'] }}" 
                                            name="{{ $field['name'] }}" 
                                            id="{{ $field['id'] ?? '' }}" 
                                            value="{{ old($field['name'], $model->{$field['name']} ?? '') }}"
                                            class="form-control {{ $field['animate_label'] ?? '' }} {{ $field['class'] ?? '' }}" 
                                            @if(isset($field['data']))
                                                @foreach($field['data'] as $key => $value)
                                                    data-{{ $key }}="{{ $value }}"
                                                @endforeach
                                            @endif 
                                        >
                                        <label class="floating-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                                    </div>

                                    @elseif($field['type'] == 'textarea')
                                        <div class="form-group">
                                            <textarea name="{{ $field['name'] }}" class="form-control" rows="4">
                                                {{ old($field['name'], $model->{$field['name']} ?? '') }}
                                            </textarea>                                        
                                            <label class="floating-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                                        </div>
                                                                           
                                    @elseif($field['type'] == 'email')
                                        <div class="form-group">
                                            <input 
                                                type="{{ $field['type'] }}" 
                                                id="{{ $field['name'] }}"                                                 
                                                name="{{ $field['name'] }}" 
                                                value="{{ old($field['name'], $model->{$field['name']} ?? '') }}"
                                                class="form-control" 
                                                placeholder="{{ $field['placeholder'] ?? '' }}">
                                            <label class="floating-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                                        </div>

                                    @elseif($field['type'] == 'date')
                                        <div class="form-group">                                            
                                            <input 
                                                type="{{ $field['type'] }}" 
                                                id="{{ $field['name'] }}" 
                                                name="{{ $field['name'] }}" 
                                                value="{{ old($field['name'], $model->{$field['name']} ?? '') }}"
                                                class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                            
                                            <label class="floating-label"  for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                                        </div>

                                    @elseif($field['type'] == 'radio')
                                        @php
                                            $selectedValue = old(
                                                $field['name'],
                                                $model->{$field['name']} ?? 'Home'
                                            );
                                        @endphp

                                        <div class="form-group">
                                            <label>{{ $field['label'] }}</label><br>
                                            <div class="btn-group flex mt-2" role="group">
                                                @foreach($field['options'] as $value => $label)
                                                    <label class="custom-radio" for="{{ $field['name'] }}_{{ strtolower($value) }}">
                                                        <input type="radio" class="btn-check"
                                                            name="{{ $field['name'] }}"
                                                            id="{{ $field['name'] }}_{{ strtolower($value) }}"
                                                            value="{{ $value }}"
                                                            {{ $selectedValue == $value ? 'checked' : '' }}
                                                            autocomplete="off">

                                                            <span class="radio-mark"></span>
                                                            {{ $label }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                    @elseif($field['type'] == 'checkbox')
                                        @php
                                            $selectedValue = old(
                                                $field['name'],
                                                $model->{$field['name']} ?? 'Select State'
                                            );
                                        @endphp
                                        
                                        <label class="form-check-label custom-checkbox" for="{{ $field['name'] }}">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                name="{{ $field['name'] }}"
                                                id="{{ $field['name'] }}"
                                                value="1"
                                                {{ $selectedValue == $value ? 'checked' : '' }}
                                                >
                                                <span class="checkmark"></span>
                                                {{ $field['label'] }}
                                        </label>                                                                          

                                    @elseif($field['type'] == 'select')
                                        @php
                                            $selectedValue = old($field['name'], $model->{$field['name']} ?? '');
                                        @endphp

                                        <div class="form-group">
                                            <label>{{ $field['label'] }}</label>
                                            <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="form-select">                                                
                                                <option value=""> Select {{ $field['label'] }}</option>
                                                @foreach($field['options'] as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ $selectedValue == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                @elseif($field['type'] == 'password')
                                    <div class="form-group">
                                        <input 
                                            type="{{ $field['type'] }}" 
                                            name="{{ $field['name'] }}" 
                                            id="{{ $field['id'] ?? '' }}"                                             
                                            class="form-control {{ $field['animate_label'] ?? '' }} {{ $field['class'] ?? '' }}" 
                                            @if(isset($field['data']))
                                                @foreach($field['data'] as $key => $value)
                                                    data-{{ $key }}="{{ $value }}"
                                                @endforeach
                                            @endif 
                                        >
                                        <label class="floating-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                                    </div>

                                    @elseif($field['type'] == 'file')
                                        <div class="form-group">
                                            <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">
                                            <label class="floating-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                                        </div>
                                @endif
                            </div>                        
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary {{ $form['button_class'] }}">{{ $form['button'] }}</button>
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
</script>
@endsection