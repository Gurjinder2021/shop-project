<?php

namespace App\Exports;

use App\Models\Shop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShopsExport implements FromCollection, WithHeadings
{
    protected $userId;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function collection()
    {
        return Shop::with('user')
            ->when($this->userId, fn ($query) => $query->where('user_id', $this->userId))
            ->get()
            ->map(function ($shop) {
                return [
                    'User' => $shop->user->name ?? 'N/A',
                    'Email' => $shop->user->email ?? 'N/A',
                    'Shop Number' => $shop->shop_number,
                    'Shop Name' => $shop->name,
                ];
            });
    }

    public function headings(): array
    {
        return ['User', 'Email', 'Shop Number', 'Shop Name'];
    }
}
