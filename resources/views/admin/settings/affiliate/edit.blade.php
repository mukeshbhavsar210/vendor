@extends('admin.layouts.app')

@section('content')

<div class="card mb-0">
    <div class="card-body pb-0">
        <div class="row">            
            <div class="col-sm-7 col-12 d-flex">                    
                <div class="page-title">
                    <h4>Edit Affiliate</h4>
                </div>                    
            </div>
            <div class="col-sm-5 col-12">
                <a href="{{ route('affiliate_services.index') }}" class="btn btn-primary float-end">Back</a>
            </div>
        </div>        
    
        <form action="" method="post" id="affiliateForm" name="affiliateForm">            
            <div class="form-group">
                <label for="title">Product Title</label>
                <input value="{{ old('title', $affiliate->title ?? '') }}" type="text" name="title" id="title" class="form-control slug-source" placeholder="Name" data-target="#slug">
                <input value="{{ old('slug', $affiliate->slug ?? '') }}" readonly type="hidden" name="slug" id="slug" class="form-control">
                <p></p>
            </div>    
                <div class="row">
                    <div class="col-md-9 col-6">
                        <div class="form-group">
                            <label for="affiliate_url">Affiliate URL</label>
                            <input value="{{ $affiliate->affiliate_url }}" type="text" name="affiliate_url" id="affiliate_url" class="form-control" placeholder="Affiliate URL">                        
                        </div> 
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label for="affiliate_platform">Platform</label>
                            <select name="affiliate_platform" id="affiliate_platform" class="form-select">
                                <option {{ ($affiliate->affiliate_platform == 'Amazon') ? 'selected' : ' '}} value="Amazon">Amazon</option>
                                <option {{ ($affiliate->affiliate_platform == 'Flipkart') ? 'selected' : ' '}} value="Flipkart">Flipkart</option>
                                <option {{ ($affiliate->affiliate_platform == 'Meesho') ? 'selected' : ' '}} value="Meesho">Meesho</option>                            
                            </select>
                        </div>
                    </div>                                    
                    <div class="col-md-5 col-6">
                        <div class="form-group">
                            <label for="short_desc">Product Photo</label>                        
                            <input type="file" name="image" class="form-control" value="{{ $affiliate->image }}" />
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="form-group">
                            <label for="Price">Price</label>
                            <input value="{{ old('price', $affiliate->price ?? '') }}" type="text" name="price" id="price" class="form-control" placeholder="Price">
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="form-group">
                            <label for="discounted_percentage">Discount Percentage</label>
                            <input value="{{ old('discounted_percentage', $affiliate->discounted_percentage ?? '') }}" type="text" name="discounted_percentage" id="discounted_percentage" class="form-control" placeholder="discounted_percentage">
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option {{ ($affiliate->status == 1) ? 'selected' : ' '}} value="1">Active</option>
                                <option {{ ($affiliate->status == 0) ? 'selected' : ' '}} value="0">Block</option>
                            </select>
                        </div>                        
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary mt-2 mb-3">Update</button>                
            </div>
        </form>
    </div>    
</section>
@endsection

@section('customJs')
    <script>
        $("#affiliateForm").submit(function(event){
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route("affiliate_services.update",$affiliate->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"] == true){
                        $('#title').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                        
                        window.location.href="{{ route('affiliate_services.index') }}"

                    } else {
                        var errors = response['errors']
                        if(errors['title']){
                            $('#title').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['title']);
                        } else {
                            $('#title').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }                        
                    }

                }, error: function(jqXHR, exception) {
                    console.log("Something event wrong");
                }
            })
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

