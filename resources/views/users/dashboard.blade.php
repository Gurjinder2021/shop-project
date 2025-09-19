@extends('users.maindesign') {{-- Extend your admin layout --}}

@section('content')
<div class="container mt-4">
    <h2>Welcome, {{ auth()->user()->name }} 👋</h2>
    <p>This is your user dashboard. More features coming soon!</p>
</div>
@endsection
