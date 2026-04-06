@extends('admin.maindesign')

@section('content')
<div class="container mt-4">

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
<!-- Validation errors -->
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <h2>View Shops Assigned to a User</h2>

    <!-- User Filter Form -->
    <form method="GET" action="{{ route('view.user.shops') }}">
        <div class="form-group mb-3">
            <label>Select User</label>
            <select name="user_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- All Users --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <!-- Export Button -->
    <form method="GET" action="{{ route('user.shops.export') }}" class="mb-3">
        @if($selectedUserId)
            <input type="hidden" name="user_id" value="{{ $selectedUserId }}">
        @endif
        <button type="submit" class="btn btn-sm btn-outline-primary">
            📥 Download {{ $selectedUserId ? 'Filtered' : '' }} Excel
        </button>
    </form>

    <h4 class="mt-4">
        @if($selectedUserId)
            Shops for {{ $users->firstWhere('id', $selectedUserId)?->name }}
        @else
            All Stalls
        @endif
    </h4>

    @if($shops->count())
    <table class="table table-bordered mt-2">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Stall Number</th>
                <th>Stall Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
           @foreach($shops as $index => $shop)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $shop->user->name ?? 'N/A' }}</td>
                <td>{{ $shop->shop_number }}</td>
                <td>{{ $shop->name }}</td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-sm btn-edit"
                        onclick="openModal({{ $shop->id }}, '{{ $shop->shop_number }}', '{{ $shop->name }}')">
                        Edit
                    </button>

                    <!-- Delete Button -->
                    <form action="{{ route('shops.delete', $shop->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this shop?')">Delete</button>
                    </form>
                </td>
            </tr>
           @endforeach
        </tbody>
    </table>
    @else
        <p class="text-muted">No Stall found.</p>
    @endif
</div>

<!-- Edit Modal -->
<div id="editModal" class="custom-modal" style="display:none;">
    <div class="custom-modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="shop_id" id="modal_shop_id" value="{{ old('shop_id') }}">

            <div class="mb-2">
                <label>Stall Number</label>
                <input type="text" name="shop_number" id="modal_shop_number" required class="form-control" value="{{ old('shop_number') }}">
                {{--@error('shop_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror--}}            </div>
            <div class="mb-2">
                <label>Stall Name</label>
                <input type="text" name="name" id="modal_shop_name" required class="form-control" value="{{ old('name') }}">
            </div>
            <button type="submit" class="btn btn-success btn-sm">Update</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- JS for modal -->
<script>
function openModal(id, shop_number, name) {
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('modal_shop_id').value = id;
    document.getElementById('modal_shop_number').value = shop_number;
    document.getElementById('modal_shop_name').value = name;

    let updateUrl = "{{ url('shops') }}/" + id;
    document.getElementById('editForm').action = updateUrl;
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Auto-close alerts
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) alert.remove();
}, 2500);

// Keep modal open on validation errors
//@if($errors->any() && old('shop_id'))
//    openModal({{ old('shop_id') }}, "{{ old('shop_number') }}", "{{ old('name') }}");
//@endif
</script>
@endsection
