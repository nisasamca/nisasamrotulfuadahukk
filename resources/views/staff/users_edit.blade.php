@extends('layouts.staff')

@section('content')
    <div class="mb-4">
        <h2>Edit User Profile</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 w-75">
        <div class="card-body">
            <form action="{{ route('staff.profile.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted fw-bold">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required>
                    @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted fw-bold">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                    @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted fw-bold">Password (Leave blank if you don't want to change)</label>
                    <input type="password" class="form-control" name="password" placeholder="New Password">
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
@endsection