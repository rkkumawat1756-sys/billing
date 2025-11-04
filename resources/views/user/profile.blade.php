@extends('layout.app')
@section('title', 'User Profile')

@section('admins')
<div class="card card-orange-outline">
    <div class="card-header">
        <h4>User Profile</h4>
    </div>

    <div class="card-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs mb-3" id="userTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab">Personal Details</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="address-tab" data-bs-toggle="tab" href="#address" role="tab">Delivery Address</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Personal Details -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <div class="row mb-3">
                    <div class="col-md-3">
                        @if($user->photo && file_exists(public_path($user->photo)))
                            <img src="{{ asset($user->photo) }}" alt="User Photo" class="img-thumbnail w-100">
                        @else
                            <img src="https://img.freepik.com/free-vector/illustration-gallery-icon_53876-27002.jpg" class="img-thumbnail w-100" alt="No Photo">
                        @endif
                    </div>
                    <div class="col-md-9">
                        <table class="table table-bordered">
                            <tr><th>Full Name</th><td>{{ $user->full_name }}</td></tr>
                            <tr><th>User Name</th><td>{{ $user->user_name }}</td></tr>
                            <tr><th>Number</th><td>{{ $user->mobile }}</td></tr>
                            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                            <tr><th>Address</th><td>{{ $user->address }}</td></tr>
                            <tr><th>Role</th><td>{{ $user->role->name ?? 'N/A' }}</td></tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($user->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="tab-pane fade" id="address" role="tabpanel">
                <div class="row">
                    @forelse($user->deliveryAddresses as $key => $address)
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm border 
                                {{ $address->status ? 'border-primary' : 'border-danger' }}">
                                <div class="card-header d-flex justify-content-between align-items-center bg-light text-dark">
                                    <strong>Address #{{ $key + 1 }}</strong>
                                    <div class="form-check form-switch mt-3">
                                        <input class="form-check-input status-switch" type="checkbox" id="statusSwitch{{ $address->id }}" data-id="{{ $address->id }}" {{ $address->status ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusSwitch{{ $address->id }}">
                                            {{ $address->status ? 'Active' : 'Inactive' }}
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body address-details">
                                    <table class="table table-sm">
                                        <tr><th>House No./Building</th><td>{{ $address->house_no }}</td></tr>
                                        <tr><th>Area / Colony</th><td>{{ $address->road_area_colony }}</td></tr>
                                        <tr><th>City</th><td>{{ $address->city }}</td></tr>
                                        <tr><th>State</th><td>{{ $address->state }}</td></tr>
                                        <tr><th>Pincode</th><td>{{ $address->pincode }}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted text-center">No delivery addresses found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Toast Container -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    <div id="statusToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastBody">
                Status updated successfully.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@section('demo')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle details button (if you have)
        const toggleButtons = document.querySelectorAll('.toggle-btn');
        toggleButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const details = this.closest('.card').querySelector('.address-details');
                details.classList.toggle('d-none');
            });
        });

        // AJAX toggle status switch
        const switches = document.querySelectorAll('.status-switch');
        switches.forEach(sw => {
            sw.addEventListener('change', function () {
                const addressId = this.dataset.id;
                const status = this.checked ? 1 : 0;

                fetch('{{ url('/delivery-addresses') }}/' + addressId + '/toggle-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Update label text
                        const label = document.querySelector(`label[for="statusSwitch${addressId}"]`);
                        if (label) {
                            label.textContent = status ? 'Active' : 'Inactive';
                        }

                        // Update card border color live
                        const card = document.querySelector(`#statusSwitch${addressId}`).closest('.card');
                        if(card){
                            if(status){
                                card.classList.remove('border-danger');
                                card.classList.add('border-primary');
                            } else {
                                card.classList.remove('border-primary');
                                card.classList.add('border-danger');
                            }
                        }

                        // Show toast
                        const toastEl = document.getElementById('statusToast');
                        const toastBody = document.getElementById('toastBody');
                        toastBody.textContent = data.message || 'Delivery address status updated successfully.';

                        const bsToast = new bootstrap.Toast(toastEl);
                        bsToast.show();
                    } else {
                        alert('Failed to update status');
                    }
                })
                .catch(() => alert('Error updating status'));
            });
        });
    });
</script>

<style>
    .card .card-header {
        font-weight: 500;
    }
</style>

@endsection
