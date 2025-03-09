<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Exports\DuplicatesExport;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $duplicates = [];
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $email = $row['email'];

            // Check if user already exists
            if (User::where('email', $email)->exists()) {
                $this->duplicates[] = $row;
            } else {
                User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => bcrypt('password123'),
                ]);
            }
        }
        if (!empty($this->duplicates)) {
            $this->exportDuplicates();
        }
    }

    public function rules(): array
    {
        return [
            '*.email' => 'required|email',
            '*.name'  => 'required|string',
        ];
    }

    private function exportDuplicates()
    {
        $filePath = 'duplicates/duplicate_users_' . now()->timestamp . '.xlsx';
        Excel::store(new DuplicatesExport($this->duplicates), $filePath, 'local');

        session(['duplicate_file' => $filePath]);
    }
    public function model(array $row)
    {
        return new User([
            //
        ]);
    }
}
