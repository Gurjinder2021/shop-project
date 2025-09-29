@extends('users.maindesign') {{-- Use your user layout here --}}

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Your Assigned Shops</h2>

    @if($shops->count())
        <div class="card-body">
            <table class="table table-bordered">
                <thead >
                    <tr>
                        <th>Sr No.</th>
                        <th>Stall No.</th>
                        <th>Stall Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shops as $index => $shop)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $shop->shop_number ?? 'N/A' }}</td>
                            <td>{{ $shop->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No shops have been assigned to you yet.</p>
    @endif
</div>
@endsection
