@extends('admin.maindesign')

@section('content')
<div class="container mt-5">
    <h4>Collection Report of all users</h4>

<!-- Export Button -->
<a href="{{ route('admin.collections.export') }}" class="btn btn-sm btn-outline-primary mb-3">
    📥 Download Excel Report
</a>

    @foreach($users as $user)
        <h5 class="mt-4">{{ ucwords($user->name) }}</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Stall No. and Name</th>
                    <th>Date</th>
                    <th>Online Collection</th>
                    <th>Offline Collection</th>
                    <th>Total Collection</th>
                </tr>
            </thead>
            <tbody>
    @foreach($user->shops as $shop)
        @php
            $shopCollections = $shop->dailyCollections->keyBy(function($c) {
                return \Carbon\Carbon::parse($c->date)->format('Y-m-d');
            });
            $rowCount = count($allDates); // total number of days to show
        @endphp

        @foreach($allDates as $index => $date)
            @php
                $dateStr = $date->format('Y-m-d');
                $collection = $shopCollections->get($dateStr);
                $rowBg = $collection ? 'white' : '#f8d7da'; // light red for missing data
                $rowColor = $collection ? 'black' : '#721c24';
            @endphp
            <tr style="background-color: {{ $rowBg }}; color: {{ $rowColor }}">
                @if($index === 0)
                    <!-- Shop cell always white and bold -->
                    <td rowspan="{{ $rowCount }}" style="background-color: white; font-weight: bold; color: black; text-align: center;vertical-align: middle;">
                        {{ $shop->shop_number }} - {{ $shop->name }}
                    </td>
                @endif

                <td>{{ $date->format('d-m-Y') }}</td>

                @if($collection)
                    <td>₹{{ number_format($collection->online_collection, 2) }}</td>
                    <td>₹{{ number_format($collection->offline_collection, 2) }}</td>
                    <td>₹{{ number_format($collection->total_collection, 2) }}</td>
                @else
                    <td colspan="3" class="text-center">No Data Available</td>
                @endif
            </tr>
        @endforeach
    @endforeach
</tbody>

        </table>
    @endforeach
</div>
@endsection
