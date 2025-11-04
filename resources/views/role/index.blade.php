
@extends('layout.app')
@section('title'){{('Roles')}}@endsection
@section('admins')
    <div class="card card-orange-outline">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Roles</h4>
            <div class="d-flex gap-2">
                <a class="btn btn-primary" href="{{ url('role-add') }}"><i class="fa fa-plus"></i>Add Role</a>
            </div>
        </div>

        <div class="card-body">
               <table class="table table-bordered table-striped" id="hostelTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Role Name</th>
                                        <!--<th>Status</th>-->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roles as $key=> $role)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <!--<td>-->
                                        <!--    @if($role->status == 1)-->
                                        <!--        <span class="badge bg-success">Active</span>-->
                                        <!--    @else-->
                                        <!--        <span class="badge bg-danger">Inactive</span>-->
                                        <!--    @endif-->
                                        <!--</td>-->
                                        <td>
                                             <a href="{{ route('role.edit', $role->id) }}" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="rgba(0,0,0,1)" viewBox="0 0 24 24">
                                        <path d="M16.7574 2.99678L9.29145 10.4627L9.29886 14.7099L13.537 14.7024L21 7.23943V19.9968C21 20.5491 20.5523 20.9968 20 20.9968H4C3.44772 20.9968 3 20.5491 3 19.9968V3.99678C3 3.4445 3.44772 2.99678 4 2.99678H16.7574ZM20.4853 2.09729L21.8995 3.5115L12.7071 12.7039L11.2954 12.7064L11.2929 11.2897L20.4853 2.09729Z"></path>
                                    </svg>
                                </a>

                                <!-- Delete -->
                                <a href="{{ route('role.delete', $role->id) }}"
                                   onclick="return confirm('Are you sure you want to delete this bed?');"
                                   title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="rgba(243,25,25,1)">
                                        <path d="M4 8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8ZM7 5V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V5H22V7H2V5H7ZM9 4V5H15V4H9ZM9 12V18H11V12H9ZM13 12V18H15V12H13Z"></path>
                                    </svg>
                                </a>
                                
                                           
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No Roles Found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
        </div>
    </div>
@endsection