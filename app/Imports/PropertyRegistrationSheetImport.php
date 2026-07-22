<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserProperty;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;

class PropertyRegistrationSheetImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    public function headingRow(): int
    {
        return 4;
    }
    
    public function model(array $row)
    {
        $user = User::where('email', $row['acct_email_address'])->where('status','approved')->first();

        
        if ($user) {
            $fillIn = [];

            if (is_null($user->acct_no) && !empty($row['acct_no'])) {
                $fillIn['acct_no'] = $row['acct_no'];
            }

            if (is_null($user->name_of_account) && !empty($row['name_of_account'])) {
                $fillIn['name_of_account'] = $row['name_of_account'];
            }

            if (!empty($fillIn)) {
                $user->update($fillIn);
            }
        }

        return new UserProperty([
            'acct_email_address' => $row['acct_email_address'],
            'acct_no' => $row['acct_no'],
            'name_of_account' => $row['name_of_account'],
            'account_code' => $row['account_code'],
            'type' => $row['type'],
            'lot_no' => $row['lot_no'] ?? null,
            'brgy_name' => $row['brgy_name'],
            'lgu' => $row['lgu'],
            'date_of_registration' => $row['date_of_registration'],
            'status' => $row['status'] ?? 'active',
        ]);
    }

    public function rules(): array
    {
        return [
            'acct_email_address' => 'required|email',
            'acct_no' => 'required|integer',
            'name_of_account' => 'required|string',
            'account_code' => 'required|string',
            'type' => 'required|in:Land,Impr/Bldg',
            'brgy_name' => 'required|string',
            'lgu' => 'required|string',
            'date_of_registration' => 'required|date',
        ];
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }
}