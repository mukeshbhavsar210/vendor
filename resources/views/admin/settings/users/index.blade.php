@extends('admin.layouts.app')

@section('content')

@include('admin.message')

<div class="card mb-0">
    <div class="card-body pb-0">            
        <div class="row">                
            <div class="col-sm-7 col-12">
                <div class="page-title">
                    <h4>{{ $title }}</h4>
                    <span class="counts">{{ $total  }}</span>
                </div>
            </div>
            <div class="col-sm-5 col-12 float-end">
                <div class="flexContainer">
                    <form action="" method="get" >
                        <div class="d-flex">
                            <div class="card-title">
                                <button type="button" onclick="window.location.href='{{ $refresh }}'" class="btn btn-default btn-sm">
                                    <?xml version="1.0" encoding="utf-8"?>
                                        <svg width="20px" height="20px" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                                        <g fill="none" fill-rule="evenodd" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" transform="matrix(0 1 1 0 2.5 2.5)">
                                        <path d="m3.98652376 1.07807068c-2.38377179 1.38514556-3.98652376 3.96636605-3.98652376 6.92192932 0 4.418278 3.581722 8 8 8s8-3.581722 8-8-3.581722-8-8-8"/>
                                        <path d="m4 1v4h-4" transform="matrix(1 0 0 -1 0 6)"/>
                                        </g>
                                    </svg>
                                </button>
                            </div>
        
                            <div class="card-tools">
                                <div class="input-group input-group searchMain" >
                                    <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn">
                                            <i class="iconoir-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#{{ $modal_id }}">{{ $button_name }}</button>                            
                </div>
            </div>
        </div>                        
    </div>
</div>

<div class="card custom-card">
    @include('admin.layouts.common')

    <div class="card-body">
        <div class="table-responsive">               
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-top-0">Name</th>
                        <th class="border-top-0" width="150">Mobile</th>
                        <th class="border-top-0" width="150">Alternate Mobile</th>
                        <th class="border-top-0" width="120">Gender</th>
                        <th class="border-top-0" width="150">Birthdate</th>
                        <th class="border-top-0" width="120">Action</th>
                    </tr>
                </thead>  
                <tbody>
                    @if ($users->isNotEmpty())
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('users.edit', $user->id) }}">
                                            @if (!empty($user->image))
                                                <img src="{{ asset('uploads/user/'.$user->image) }}" height="70" class="me-3 align-self-center rounded">
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" height="70" class="me-3 align-self-center rounded">
                                            @endif
                                            {{-- <img src="{{ $user->image_url }}" height="90" class="me-3 align-self-center rounded"> --}}
                                        </a>
                                        <div class="flex-grow-1 text-truncate">
                                            <h5 class="product-title">
                                                <a href="{{ route('users.edit', $user->id) }}">{{ Str::limit($user->name, 75, '...') }} </a>
                                            </h5>
                                            <div class="small-fonts">                                                    
                                                <p class="mb-0 text-muted">{{ $user->email }}</p>
                                                <p class="mb-0 text-muted">{{ $user->id }}</p>
                                            </div>                  
                                        </div>
                                    </div>
                                </td>  
                                
                                <td>{{ $user->mobile }}</td>
                                <td>{{ $user->alternate_mobile }}</td>
                                <td>{{ $user->gender == 'male' ? 'Male' : 'Female' }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->birthdate)->format('d, M Y')  }}</td>
                                <td>
                                     <div class="flex">
                                        @if ($user->status == 1)  
                                            <span class="sprites green-tick-icon"></span>
                                        @else
                                            <span class="sprites red-tick-icon"></span>
                                        @endif

                                        <a href="{{ route('users.edit', $user->id ) }}" class="edit-icon">
                                            <span class="sprites"></span>
                                        </a>
                                        <a href="#" onclick="deleteUser({{ $user->id }})" class="delete-icon" >
                                            <span class="sprites"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">Records not found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>   

    <div class="card-footer clearfix">
        {{ $users->links() }}
    </div>

    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createUserModalLabel">Create User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('users.store') }}" method="POST" id="createUserForm" name="createUserForm">
                    @csrf                    
                    <div class="modal-body">                       
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                            <p></p>
                        </div>                    
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                            <p></p>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            <p></p>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text"  name="phone" id="phone" class="form-control" placeholder="Phone">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="mobile">Mobile</label>
                                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile">
                                    <p></p>
                                </div>
                            </div>                            
                            <div class="col-md-6 col-6">
                                <div class="form-group">
                                    <label for="birthdate">Birthdate</label>
                                    <input autocomplete="off" type="date" name="birthdate" id="birthdate" class="form-control" placeholder="Birthdate">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender" class="form-select">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>  
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                            </div>                          
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customJs')
<script>
     $(document).ready(function () {
        $("#createUserForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('users.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    var modal = bootstrap.Modal.getInstance(
                        document.getElementById('createUserModal')
                    );
                    modal.hide();
                    location.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });              
    }); 
    

    function deleteUser(id){
        var url = '{{ route("users.delete","ID") }}'
        var newUrl = url.replace("ID",id);

        if(confirm("Are you sure you want to delete?")){
            $.ajax({
                url: newUrl,
                type: 'delete',
                data: {},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    if(response["status"]){
                        window.location.href="{{ route('users.index') }}"
                    }
                }
            });
        }
    }
</script>
@endsection
