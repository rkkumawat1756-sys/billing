@extends('layout.app')
@section('title'){{ 'Settings' }}@endsection

@section('admins')
<div class="card card-orange-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Setting</h4>
    </div>

    <form action="{{ route('settings') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <!-- Name -->
                <div class="col-md-3 mb-2">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name', $setting->name ?? '') }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Title -->
                <div class="col-md-3 mb-2">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $setting->title ?? '') }}" placeholder="Title">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Mobile -->
                <div class="col-md-3 mb-2">
                    <label for="mobile">Mobile</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" value="{{ old('mobile', $setting->mobile ?? '') }}" placeholder="Mobile Number">
                    @error('mobile')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-3 mb-2">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $setting->email ?? '') }}" placeholder="example@gmail.com">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Address -->
                <div class="col-md-3 mb-2">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $setting->address ?? '') }}" placeholder="Address">
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Logo Upload -->
                <div class="col-md-3 mb-2">
                    <label for="logo">Logo</label>
                    <input type="file" class="form-control" name="logo" id="logo" accept="image/png, image/jpg, image/jpeg">
                    @error('logo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Logo Preview -->
                <div class="col-md-1 mb-2">
                    <img id="previewImage_logo"
                         src="{{ asset($setting->logo ?? 'schoolimage/default/user_image.jpg') }}"
                         width="60px" height="60px"
                         onerror="this.src='{{ asset('schoolimage/default/user_image.jpg') }}'">
                </div>

                <!-- Background Image -->
                <div class="col-md-3 mb-2">
                    <label for="background_image">Favicon Image</label>
                    <input type="file" class="form-control" name="background_image" id="background_image" accept="image/png, image/jpg, image/jpeg">
                    @error('background_image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Background Image Preview -->
                <div class="col-md-1 mb-2">
                    <img id="previewImage_background_image"
                         src="{{ asset($setting->background_image ?? 'schoolimage/default/user_image.jpg') }}"
                         width="60px" height="60px"
                         onerror="this.src='{{ asset('schoolimage/default/user_image.jpg') }}'">
                </div>
            </div>
        </div>

        <div class="card-footer text-center">
            <button type="submit" class="btn btn-secondary">Save</button>
        </div>
    </form>
</div>
@endsection
