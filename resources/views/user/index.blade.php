


@extends('layout.app')
@section('title'){{('Users')}}@endsection
@section('admins')
    <div class="card card-orange-outline">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Users</h4>
            <div class="d-flex gap-2">
                <a class="btn btn-primary" href="{{ route('users.create') }}"><i class="fa fa-plus"></i>Add Users</a>
            </div>
        </div>

        <div class="card-body">
               <table class="table table-bordered table-striped" id="hostelTable">
                                <thead>
                <tr>
                    <th>SR</th>
                    <th>Full Name</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Photo</th>
                   
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $key=> $user)
                      <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->full_name ?? 'N/A' }}</td>
                        <td>{{ $user->user_name ?? 'N/A' }}</td>
                        <td>{{ $user->email ?? 'N/A' }}</td>

                        {{-- Profile Photo --}}
                        <td>
                            @if($user->photo && file_exists(public_path($user->photo)))
                                <img src="{{ asset($user->photo) }}" alt="User Photo" width="40" height="40" class="rounded-circle">
                            @else
                                N/A
                            @endif
                        </td>

                        {{-- Role --}}
                        <td>{{ $user->role ? $user->role->name : 'N/A' }}</td>

                        {{-- Status --}}
                        <td>
                            @if($user->status == 1)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
<a href="{{ route('users.profile', $user->id) }}" title="View Profile">
    <i class="fa fa-user-circle"></i>
</a>


                                       
                                        
                                        
                                                <a href="{{ route('users.edit', $user->id) }}" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="rgba(0,0,0,1)" viewBox="0 0 24 24">
                                        <path d="M16.7574 2.99678L9.29145 10.4627L9.29886 14.7099L13.537 14.7024L21 7.23943V19.9968C21 20.5491 20.5523 20.9968 20 20.9968H4C3.44772 20.9968 3 20.5491 3 19.9968V3.99678C3 3.4445 3.44772 2.99678 4 2.99678H16.7574ZM20.4853 2.09729L21.8995 3.5115L12.7071 12.7039L11.2954 12.7064L11.2929 11.2897L20.4853 2.09729Z"></path>
                                    </svg>
                                </a>

                                <!-- Delete -->
                                <a href="{{ route('users.destroy', $user->id) }}"
                                   onclick="return confirm('Are you sure you want to delete this bed?');"
                                   title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="rgba(243,25,25,1)">
                                        <path d="M4 8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8ZM7 5V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V5H22V7H2V5H7ZM9 4V5H15V4H9ZM9 12V18H11V12H9ZM13 12V18H15V12H13Z"></path>
                                    </svg>
                                </a>
                                
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted">No users found</td></tr>
                            @endforelse
                                </tbody>
                            </table>
        </div>
    </div>
@endsection