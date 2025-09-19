@extends('users.maindesign') {{-- Use your user layout here --}}

@section('content')
<div class="container mt-5">
    <h4>Daily Collections for Your Shops</h4>

    @foreach($shopsWithCollections as $shop)
        <div class="card mt-4">
            <div class="card-header">
                <strong>Shop #{{ $shop->shop_number }}</strong> - {{ $shop->name }}
            </div>
            <div class="card-body">
                @if($shop->dailyCollections->isEmpty())
                    <p>No collections found for this shop.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Shop No</th>
                                <th>Shop Name</th>
                                <th>Date</th>
                                <th>Till Time</th>
                                <th>Online Collection</th>
                                <th>Offline Collection</th>
                                <th>Total Collection</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shop->dailyCollections as $collection)
                                <tr>
                                    <td>{{ $shop->shop_number}}</td>
                                    <td>{{  $shop->name}}</td>
                                    <td>{{ \Carbon\Carbon::parse($collection->date)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($collection->till_time)->format('h:i A') }}</td>
                                    <td>₹{{ number_format($collection->online_collection, 2) }}</td>
                                    <td>₹{{ number_format($collection->offline_collection, 2) }}</td>
                                    <td>₹{{ number_format($collection->total_collection, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
