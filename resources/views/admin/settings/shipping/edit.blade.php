@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-10">
                <h1>Edit Shipping Management</h1>
            </div>
            <div class="col-sm-2">
                <a href="{{ route('shipping.create') }}" class="btn btn-primary float-end">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        @include('admin.message')

        <form action="" method="post" id="editShippingForm" name="editShippingForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <select name="state_id" id="state_id" class="form-select">
                                    <option value="">Select a State</option>
                                    @if ($states->isNotEmpty())
                                        @foreach ($states as $value)
                                            <option {{ ($shippingCharge->state_id == $value->id) ? 'selected' : '' }} value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                        <option {{ ($shippingCharge->state_id == 'rest_of_state') ? 'selected' : '' }} value="rest_of_world">Rest of the world</option>
                                    @endif
                                </select>
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="mb-3">
                                <input value={{ $shippingCharge->amount }} type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
    <script>
        $("#editShippingForm").submit(function(event){
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route("shipping.update", $shippingCharge->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"] == true){
                        window.location.href="{{ route('shipping.create') }}"

                    } else {
                        var errors = response['errors']
                        if(errors['state']){
                            $('#state').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['state']);
                        } else {
                            $('#state').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['amount']){
                            $('#amount').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['amount']);
                        } else {
                            $('#amount').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }
                    }

                }, error: function(jqXHR, exception) {
                    console.log("Something event wrong");
                }
            })
        });
    </script>
@endsection
