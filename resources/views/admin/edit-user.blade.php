@extends('admin.maindesign') {{-- Extend your admin layout --}}
@section('content')
<div class="container-fluid mt-4">
    <h2>Edit User</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('update.user', $user->id) }}">
        @csrf

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="{{ old('username', $user->name) }}" class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label>New Password (optional)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group mt-3">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-success mt-4">Update User</button>
    </form>
</div>
@endsection
