@extends('users.maindesign')

@section('content')
<div class="container mt-5">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <h4>Daily Collection Entry</h4>

    <form id="dailyCollectionForm" method="POST" action="{{ route('daily.collection.store') }}">
        @csrf
        <input type="hidden" name="collection_id" id="collectionId">

        <div class="mb-3">
            <label class="form-label">Select Shop</label>
            <select name="shop_id" id="shopSelect" class="form-control" required>
                <option value="">-- Select Shop --</option>
                @foreach($shops as $shop)
                    <option value="{{ $shop->id }}">{{ $shop->shop_number }} - {{ $shop->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="collectionMessage" class="mb-3 fw-bold" style="color: #007bff; font-size: 1.1rem;"></div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" required readonly value="{{ $today }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Till Time</label>
            <input type="time" name="till_time" id="tillTime" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Online Collection</label>
            <input type="number" step="0.01" name="online_collection" id="onlineCollection" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Offline Collection</label>
            <input type="number" step="0.01" name="offline_collection" id="offlineCollection" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Total Collection</label>
            <input type="text" id="totalCollection" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Save Collection</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const todaysCollections = @json($todaysCollections);
    const form = document.getElementById('dailyCollectionForm');
    const shopSelect = document.getElementById('shopSelect');
    const collectionIdInput = document.getElementById('collectionId');
    const tillTime = document.getElementById('tillTime');
    const onlineInput = document.getElementById('onlineCollection');
    const offlineInput = document.getElementById('offlineCollection');
    const totalInput = document.getElementById('totalCollection');
    const messageDiv = document.getElementById('collectionMessage');

    function updateTotal() {
        const online = parseFloat(onlineInput.value) || 0;
        const offline = parseFloat(offlineInput.value) || 0;
        totalInput.value = (online + offline).toFixed(2);
    }

    onlineInput.addEventListener('input', updateTotal);
    offlineInput.addEventListener('input', updateTotal);

    shopSelect.addEventListener('change', function() {
    const shopId = this.value;

    if (!shopId) {
        collectionIdInput.value = '';
        tillTime.value = '';
        onlineInput.value = '';
        offlineInput.value = '';
        totalInput.value = '';
        messageDiv.textContent = '';
        return;
    }

    const collection = todaysCollections[shopId];
    if (collection) {
        // Format date and time nicely
        const date = new Date(collection.date);
        const formattedDate = date.toLocaleDateString('en-GB'); // dd/mm/yyyy
        const formattedTime = collection.till_time;

        messageDiv.textContent = `Data already entered today as on ${formattedDate} at ${formattedTime}. Again saving will update it.`;
        messageDiv.classList.add('mb-3 text-primary fw-bold'); // add warning color

        tillTime.value = collection.till_time;
        onlineInput.value = collection.online_collection;
        offlineInput.value = collection.offline_collection;
        totalInput.value = (parseFloat(collection.online_collection) + parseFloat(collection.offline_collection)).toFixed(2);
        collectionIdInput.value = collection.id; // set ID for update
    } else {
        messageDiv.textContent = '';
        messageDiv.classList.remove('text-warning');

        tillTime.value = "{{ \Carbon\Carbon::now()->format('H:i') }}";
        onlineInput.value = '';
        offlineInput.value = '';
        totalInput.value = '';
        collectionIdInput.value = '';
    }
});

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    const collectionId = collectionIdInput.value;

    if (collectionId) {
        // Editing existing collection
        form.action = `/user/shop-collection/${collectionId}`; // PUT route
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
    } else {
        // Creating new collection
        form.action = "{{ route('daily.collection.store') }}"; // POST route
    }

    form.submit();
});
});
</script>
@endsection
