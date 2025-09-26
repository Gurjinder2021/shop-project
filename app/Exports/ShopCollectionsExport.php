<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ShopCollectionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $users;
    protected $allDates;

    public function __construct($users, $allDates)
    {
        $this->users = $users;
        $this->allDates = $allDates;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->users as $user) {
            foreach ($user->shops as $shop) {
                // Key collections by date
                $shopCollections = $shop->dailyCollections->keyBy(function ($c) {
                    return Carbon::parse($c->date)->format('Y-m-d');
                });

                foreach ($this->allDates as $date) {
                    $dateStr = $date->format('Y-m-d');
                    $collection = $shopCollections->get($dateStr);

                    $rows->push([
                        'user' => $user->name,
                        'shop' => $shop->shop_number.' - '.$shop->name,
                        'date' => $date->format('d-m-Y'),
                        'online_collection' => $collection ? $collection->online_collection : null,
                        'offline_collection' => $collection ? $collection->offline_collection : null,
                        'total_collection' => $collection ? $collection->total_collection : null,
                        'status' => $collection ? 'Data Entered' : 'No Data',
                        'is_missing' => $collection ? false : true, // for styling
                    ]);
                }
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'User',
            'Shop',
            'Date',
            'Online Collection',
            'Offline Collection',
            'Total Collection',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row['user'],
            $row['shop'],
            $row['date'],
            $row['online_collection'],
            $row['offline_collection'],
            $row['total_collection'],
            $row['status'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // Loop through rows to color missing data rows
        for ($row = 2; $row <= $highestRow; ++$row) {
            $statusCell = 'G'.$row; // Status column
            $status = $sheet->getCell($statusCell)->getValue();
            if ($status === 'No Data') {
                // Light red background for missing data
                $sheet->getStyle("A{$row}:G{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FADBD8'); // light red
            }
        }

        // Bold header row
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
    }
}

/*
<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ShopCollectionsExport implements FromArray, WithHeadings, WithStyles
{
    protected $users;
    protected $allDates;

    public function __construct($users, $allDates)
    {
        $this->users = $users;
        $this->allDates = $allDates;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->users as $user) {
            $rows[] = [$user->name]; // User name as a separate row
            foreach ($user->shops as $shop) {
                $shopCollections = $shop->dailyCollections->keyBy(function($c){
                    return Carbon::parse($c->date)->format('Y-m-d');
                });

                $rowCount = count($this->allDates);
                $firstRow = true;

                foreach ($this->allDates as $date) {
                    $dateStr = $date->format('Y-m-d');
                    $collection = $shopCollections->get($dateStr);

                    $row = [];

                    if ($firstRow) {
                        $row[] = $shop->shop_number.' - '.$shop->name; // merged manually in Excel via style
                        $firstRow = false;
                    } else {
                        $row[] = ''; // blank for merged cell
                    }

                    $row[] = $date->format('d-m-Y');

                    if ($collection) {
                        $row[] = $collection->online_collection;
                        $row[] = $collection->offline_collection;
                        $row[] = $collection->total_collection;
                        $row[] = 'Data Entered';
                    } else {
                        $row[] = null;
                        $row[] = null;
                        $row[] = null;
                        $row[] = 'No Data';
                    }

                    $rows[] = $row;
                }

                $rows[] = []; // empty row between shops
            }

            $rows[] = []; // empty row between users
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Shop No & Name', 'Date', 'Online Collection', 'Offline Collection', 'Total Collection', 'Status'];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        for ($row = 1; $row <= $highestRow; ++$row) {
            $statusCell = 'F'.$row;
            $status = $sheet->getCell($statusCell)->getValue();

            if ($status === 'No Data') {
                $sheet->getStyle("A{$row}:F{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FADBD8'); // light red
            }

            // Shop column always white and bold
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                                                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }

        // Bold headings
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
    }
}
 */
