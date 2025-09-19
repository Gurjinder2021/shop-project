@extends('admin.maindesign')

@section('content')
<div class="container mt-4">
    <h2>Assign Multiple Shops to a User</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('assign.multiple.shops') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Select User</label>
            <select name="user_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <div id="shop-fields">
            <div class="shop-group border p-3 mt-3">
                <h5>Shop #1</h5>
                <input type="text" name="shops[0][number]" class="form-control mb-2" placeholder="Shop Number" required>
                <input type="text" name="shops[0][name]" class="form-control" placeholder="Shop Name" required>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mt-3" onclick="addShop()">+ Add Another Shop</button>
        <button type="submit" class="btn btn-primary mt-3">Assign Shops</button>
    </form>
</div>

<script>
    let shopIndex = 1;

    function addShop() {
        const shopFields = document.getElementById('shop-fields');
        const shopGroup = document.createElement('div');
        shopGroup.className = 'shop-group border p-3 mt-3';
        shopGroup.innerHTML = `
            <h5>Shop #${shopIndex + 1}</h5>
            <input type="text" name="shops[${shopIndex}][number]" class="form-control mb-2" placeholder="Shop Number" required>
            <input type="text" name="shops[${shopIndex}][name]" class="form-control" placeholder="Shop Name" required>
        `;
        shopFields.appendChild(shopGroup);
        shopIndex++;
    }
</script>
@endsection
