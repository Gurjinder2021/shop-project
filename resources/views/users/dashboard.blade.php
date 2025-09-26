@extends('users.maindesign') {{-- Extend your main layout --}}

@section('content')
<div class="container mt-4">
    <h2>Welcome, {{ auth()->user()->name }} 👋</h2>
    <p>This is your user dashboard.</p>
</div>
@endsection
