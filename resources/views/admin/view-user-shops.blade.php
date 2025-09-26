@extends('admin.maindesign')

@section('content')
<div class="container mt-4">

    <!-- Success/Error Messages -->
       @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
            All Shops
        @endif
    </h4>

    @if($shops->count())
    <table class="table table-bordered mt-2">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Shop Number</th>
                <th>Shop Name</th>
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
        <p class="text-muted">No shops found.</p>
    @endif
</div>

<!-- Minimal Custom Modal -->
<div id="editModal" class="custom-modal" style="display:none;">
    <div class="custom-modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-2">
                <label>Shop Number</label>
                <input type="text" name="shop_number" id="modal_shop_number" required class="form-control">
            </div>
            <div class="mb-2">
                <label>Shop Name</label>
                <input type="text" name="name" id="modal_shop_name" required class="form-control">
            </div>
            <button type="submit" class="btn btn-success btn-sm">Update</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- Minimal JS for modal -->
<script>
function openModal(id, shop_number, name) {
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('modal_shop_number').value = shop_number;
    document.getElementById('modal_shop_name').value = name;
    document.getElementById('editForm').action = '/shops/' + id; // adjust route if needed
}
function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>
<script>
    setTimeout(() => {
        let alert = document.querySelector('.alert');
        if (alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 2000);
</script>
@endsection
