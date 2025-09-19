@extends('users.maindesign') {{-- Use your user layout here --}}

@section('content')
<div class="container mt-5">
    <h4>Daily Collection Entry</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('daily.collection.store') }}">
        @csrf

        <div class="form-group">
            <label for="shop_id">Select Shop</label>
            <select name="shop_id" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($shops as $shop)
                    <option value="{{ $shop->id }}">{{ $shop->shop_number ?? '' }} - {{ $shop->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label for="till_time">Till Time</label>
            <input type="time" name="till_time" class="form-control" value="{{ \Carbon\Carbon::now('Asia/Kolkata')->format('H:i') }}" required>
        </div>

        <div class="form-group">
            <label for="online_collection">Online Collection</label>
            <input type="number" step="0.01" name="online_collection" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="offline_collection">Offline Collection</label>
            <input type="number" step="0.01" name="offline_collection" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Total (auto)</label>
            <input type="text" id="total" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Save Collection</button>
    </form>
</div>

<script>
    const online = document.querySelector('input[name="online_collection"]');
    const offline = document.querySelector('input[name="offline_collection"]');
    const total = document.getElementById('total');

    function updateTotal() {
        const onlineVal = parseFloat(online.value) || 0;
        const offlineVal = parseFloat(offline.value) || 0;
        total.value = (onlineVal + offlineVal).toFixed(2);
    }

    online.addEventListener('input', updateTotal);
    offline.addEventListener('input', updateTotal);
</script>
@endsection
