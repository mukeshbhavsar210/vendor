@extends('admin.layouts.app')

@section('content')    
    <x-product_update 
        :route="route('services.store')"
        :product="$product ?? null"
        :categories="$categories"
        :subcategories="$subcategories"
        :subsubcategories="$subsubcategories"        
        :productimages="$productimages ?? null"  
        :discountpercentages="$discount_percentages"
        :selecteddiscount="$selected_discount"          
        method="POST"
        buttonText="Create Service"
        title="Create Service"
        formname="serviceFormCreate"
    />      
@endsection

@section('customJs')
<script>
    $('.related-product').select2({
        ajax: {
            url: '{{ route('services.getServices') }}',
            dataType: 'json',
            tags: true,
            multiple: true,
            minimumInputLength: 3,
            processResults: function (data) {
                return {
                    results: data.tags
                };
            }
        }
    });  


    //Product form add details in database
    $("#serviceFormCreate").submit(function(event){
        event.preventDefault();

        var formArray = $(this).serializeArray();
        $("button[type='submit']").prop('disabled',true);

        $.ajax({
            url: '{{ route("services.store") }}',
            type: 'post',
            data: formArray,
            dataType: 'json',
            success: function(response){
                $("button[type='submit']").prop('disabled',false);
                if (response['status'] == true) {
                    $(".error").removeClass('invalid-feedback').html('');
                    $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
                    window.location.href="{{ route('services.index') }}";
                } else {
                    var errors = response['errors'];
                    $(".error").removeClass('invalid-feedback').html('');
                    $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

                    $.each(errors, function(key,value){
                        $(`#${key}`).addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(value);
                    });
                }
            },

            error: function(){
                console.log("Something went wrong")
            }
        });
    });

    $("#category").change(function(){
        var category_id = $(this).val();

        $("#sub_category").find("option").not(":first").remove();

        if(category_id) {
            $.ajax({
                url: '{{ route("product-subcategories.index") }}',
                type: 'GET',
                data: { category_id: category_id },
                dataType: 'json',
                success: function(response) {
                    $.each(response.subCategories, function(key, item){
                        $("#sub_category").append(
                            `<option value="${item.id}">${item.name}</option>`
                        );
                    });
                },
                error: function(){
                    console.log("Something went wrong");
                }
            });
        }
    });

    $("#sub_category").change(function(){
        var sub_category_id = $(this).val();
        $.ajax({
            url: '{{ route("product-subcategories.extra") }}',
            type: 'get',
            data: { sub_category_id: sub_category_id },
            dataType: 'json',
            success: function(response) {
                $("#sub_sub_category").find("option").not(":first").remove();
                $.each(response["subSubCategories"], function(key, item){
                    $("#sub_sub_category").append(
                        `<option value='${item.id}'>${item.name}</option>`
                    )
                });

            },
            error: function(){
                console.log("Something went wrong");
            }
        });
    });

    Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            url:  "{{ route('temp-images.create') }}",
            maxFiles: 10,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(file, response){
                $("#image_id").val(response.image_id);
                console.log(response)

               var html = `<div class="col-md-2" id="image-row-${response.image_id}">
                    <div class="card">
                        <input type="hidden" name="image_array[]" value="${response.image_id}" >
                        <img src="${response.ImagePath}" />
                        <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="deleteCardImg">X</a>
                    </div>
                </div>`;

                $("#product-gallery").append(html);
            },
            complete: function(file){
                this.removeFile(file);
            }
        });

        function deleteImage(id){
            $("#image-row-"+id).remove();
        }

</script>
@endsection