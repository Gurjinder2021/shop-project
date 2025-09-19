@extends('admin.maindesign') {{-- Extend your admin layout --}}

@section('content')
<div class="container-fluid mt-3">
    <h2>Add New User</h2>

    {{-- Show success message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Show validation errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Add User Form --}}
    <form method="POST" action="{{ url('/adduser') }}">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"
                   class="form-control" value="{{ old('username') }}" required>
        </div>

        <div class="form-group mt-3">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="form-group mt-3">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-4">Add User</button>
    </form>
</div>
@endsection
