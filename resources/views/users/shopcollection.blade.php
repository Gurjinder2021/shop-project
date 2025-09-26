@extends('users.maindesign')

@section('content')
<div class="container mt-5">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shop->dailyCollections as $collection)
                                <tr>
                                    <td>{{ $shop->shop_number }}</td>
                                    <td>{{ $shop->name }}</td>
                                    <td>{{ $collection->date->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($collection->till_time)->format('H:i') }}</td>
                                    <td>₹{{ number_format($collection->online_collection, 2) }}</td>
                                    <td>₹{{ number_format($collection->offline_collection, 2) }}</td>
                                    <td>₹{{ number_format($collection->total_collection, 2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $collection->id }}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal -->
                                <div class="modal fade" id="editModal{{ $collection->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('collections.update', $collection->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Collection - Shop #{{ $shop->shop_number }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <!-- Shop Number -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Shop Number</label>
                                                        <input type="text" class="form-control" value="{{ $shop->shop_number }}" readonly>
                                                    </div>

                                                    <!-- Shop Name -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Shop Name</label>
                                                        <input type="text" class="form-control" value="{{ $shop->name }}" readonly>
                                                    </div>

                                                    <!-- Date -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Date</label>
                                                        <input type="text" class="form-control" value="{{ $collection->date->format('d-m-Y') }}" readonly>
                                                        <input type="hidden" name="date" value="{{ $collection->date->format('Y-m-d') }}">
                                                    </div>

                                                    <!-- Till Time -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Till Time</label>
                                                        <input type="time" class="form-control" name="till_time" value="{{ $collection->till_time }}" required>
                                                    </div>

                                                    <!-- Online Collection -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Online Collection</label>
                                                        <input type="number" step="0.01" class="form-control collection-input" name="online_collection" value="{{ $collection->online_collection }}" required>
                                                    </div>

                                                    <!-- Offline Collection -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Offline Collection</label>
                                                        <input type="number" step="0.01" class="form-control collection-input" name="offline_collection" value="{{ $collection->offline_collection }}" required>
                                                    </div>

                                                    <!-- Total Collection -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Total Collection</label>
                                                        <input type="number" step="0.01" class="form-control total-collection" value="{{ $collection->total_collection }}" readonly>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Collection</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection


<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.collection-input').forEach(input => {
        input.addEventListener('input', function() {
            const modalBody = input.closest('.modal-body');
            const online = parseFloat(modalBody.querySelector('input[name="online_collection"]').value) || 0;
            const offline = parseFloat(modalBody.querySelector('input[name="offline_collection"]').value) || 0;
            modalBody.querySelector('.total-collection').value = (online + offline).toFixed(2);
        });
    });
});
</script>


<script src="{{ asset('/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
