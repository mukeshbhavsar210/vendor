@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card mb-0">
        <div class="card-body pb-0">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Edit Page</h3>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('pages.index') }}" class="btn btn-primary float-end">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body pt-0">
            <form action="" method="post" id="pageForm" name="pageForm">              
                <div class="row">
                    <div class="col-md-10 col-8">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input value={{ $page->name }} type="text" name="name" id="name" class="form-control" placeholder="Name">
                            <input value={{ $page->slug }} type="hidden" readonly name="slug" id="slug" class="form-control" placeholder="slug">
                            <p></p>
                        </div>
                    </div>                    
                    <div class="col-md-2 col-4">
                        <div class="form-group">
                            <label for="menu_order">Menu Order</label>
                            <select name="menu_order" id="menu_order" class="form-select">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" class="summernote" cols="30" rows="10">{!! $page->content !!}</textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('customJs')
    <script>
        $("#pageForm").submit(function(event){
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route("pages.update",$page->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"] == true){

                        $('#name').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $('#slug').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        window.location.href="{{ route('pages.index') }}"

                    } else {
                        var errors = response['errors']
                        if(errors['name']){
                            $('#name').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['name']);
                        } else {
                            $('#name').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['slug']){
                            $('#slug').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['slug']);
                        } else {
                            $('#slug').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                    }

                }, error: function(jqXHR, exception) {
                    console.log("Something event wrong");
                }
            })
        });

        $('#name').change(function(){
            element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route("getSlug") }}',
                type: 'get',
                data: {title: element.val()},
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);
                    if(response["status"] == true){
                        $("#slug").val(response["slug"]);
                    }
                }
            });
        })
    </script>
@endsection
