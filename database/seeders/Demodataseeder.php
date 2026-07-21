<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Property;
use App\Models\PropertyTax;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@resident.test'],
            [
                'name' => 'Rosario Dela Cruz',
                'password' => bcrypt('password'),
                'status' => 'approved',
                'email_verified_at' => now(),
            ]
        );

        $land = Property::create([
            'user_id' => $user->id,
            'type' => 'Land',
            'lot_no' => '5',
            'barangay' => 'Robles',
            'rpt_account_no' => '0421-4000-106723',
        ]);

        $building = Property::create([
            'user_id' => $user->id,
            'type' => 'Building',
            'lot_no' => '5',
            'barangay' => 'Robles',
            'rpt_account_no' => '0421-4000-106724',
        ]);

        $secondLand = Property::create([
            'user_id' => $user->id,
            'type' => 'Land',
            'lot_no' => '12',
            'barangay' => 'Poblacion',
            'rpt_account_no' => '0421-4000-108812',
        ]);

        $landTax2026 = PropertyTax::create([
            'property_id' => $land->id,
            'tax_year' => 2026,
            'amount_due' => 4500.00,
            'penalties' => 312.60,
            'total_payable' => 4812.60,
            'due_date' => '2026-09-30',
            'status' => 'unpaid',
        ]);

        PropertyTax::create([
            'property_id' => $building->id,
            'tax_year' => 2026,
            'amount_due' => 2100.00,
            'penalties' => 0,
            'total_payable' => 2100.00,
            'due_date' => '2026-09-30',
            'status' => 'unpaid',
        ]);

        PropertyTax::create([
            'property_id' => $secondLand->id,
            'tax_year' => 2025,
            'amount_due' => 3120.00,
            'penalties' => 468.00,
            'total_payable' => 3588.00,
            'due_date' => '2026-03-31',
            'status' => 'overdue',
        ]);

        $paidTax2025 = PropertyTax::create([
            'property_id' => $land->id,
            'tax_year' => 2025,
            'amount_due' => 4200.00,
            'penalties' => 0,
            'total_payable' => 4200.00,
            'due_date' => '2025-09-30',
            'status' => 'paid',
        ]);

        Payment::create([
            'property_tax_id' => $paidTax2025->id,
            'user_id' => $user->id,
            'reference_no' => 'RPT-' . $paidTax2025->id . '-20250114120000',
            'amount' => 4200.00,
            'method' => 'GCash',
            'status' => 'completed',
            'paid_at' => '2025-01-14 12:00:00',
        ]);
    }
}