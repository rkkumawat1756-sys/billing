@extends('layout.app')
@section('title', 'Edit User')
@section('admins')
<div class="card card-orange-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Edit User</h4>
        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="{{ route('users.index') }}"><i class="fa fa-list"></i> Users List</a>
        </div>
    </div>

    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="row">

                {{-- Full Name --}}
                <div class="col-md-3 mb-3">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="form-control" placeholder="Full Name">
                    @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                 <div class="col-md-3 mb-3">
                    <label>Mobile number</label>
                    <input type="text" name="mobile" class="form-control" placeholder="Mobile Number"  value="{{ old('mobile', $user->mobile) }}">
                                        @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror

                </div>

             
  <div class="col-md-3 mb-3">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control"  value="{{ old('address', $user->address) }}" placeholder="Address">
                                        @error('address') <small class="text-danger">{{ $message }}</small> @enderror

                </div>
                {{-- Email --}}
                <div class="col-md-3 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" placeholder="example@mail.com">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                   {{-- Username --}}
                <div class="col-md-3 mb-3">
                    <label>Username <span class="text-danger">*</span></label>
                    <input type="text" name="user_name" value="{{ old('user_name', $user->user_name) }}" class="form-control" placeholder="Username">
                    @error('user_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Password --}}
                <div class="col-md-3 mb-3">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()" title="Show/Hide Password">
                            <i id="passwordToggleIcon" class="fa fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="generatePassword()" title="Generate Password">
                            <i class="fa fa-magic"></i>
                        </button>
                    </div>
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Profile Photo --}}
                <div class="col-md-3 mb-3">
                    <label>Profile Photo</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    @if ($user->photo)
                        <img src="{{ asset($user->photo) }}" width="60" class="mt-2 rounded">
                    @endif
                </div>

                {{-- Status --}}
                <div class="col-md-3 mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Role --}}
                <div class="col-md-3 mb-3">
                    <label>Role <span class="text-danger">*</span></label>
                    <select name="role_id" class="form-control" required>
                        <option value="">Select Role</option>
                        @foreach($role as $r)
                            <option value="{{ $r->id }}" {{ $user->role_id == $r->id ? 'selected' : '' }}>
                                {{ $r->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

            </div>
        </div>

        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection

@section('demo')
<script>
    function generatePassword(length = 8) {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
        let password = "";
        for (let i = 0; i < length; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('password').value = password;
    }

    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('passwordToggleIcon');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }
</script>
@endsection
