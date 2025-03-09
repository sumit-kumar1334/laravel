<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class DuplicatesExport implements FromCollection,WithHeadings
{
    protected $duplicates;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $duplicates)
    {
        $this->duplicates = $duplicates;
    }
    public function collection()
    {
        return new Collection($this->duplicates);
    }
    public function headings(): array
    {
        return ['name', 'email']; // Define column headings
    }
}
