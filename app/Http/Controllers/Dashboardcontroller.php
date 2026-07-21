<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PropertyTax;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // One row per tax bill (a property with 2 unpaid years shows twice —
        // matches how the dashboard cards are meant to read).
        $properties = PropertyTax::whereHas('property', fn ($q) => $q->where('user_id', $user->id))
            ->with('property')
            ->orderByDesc('tax_year')
            ->get()
            ->map(fn (PropertyTax $tax) => (object) [
                'id' => $tax->id, // this is the bill id — what payments.checkout should receive
                'type' => $tax->property->type,
                'lot_no' => $tax->property->lot_no,
                'barangay' => $tax->property->barangay,
                'tax_year' => $tax->tax_year,
                'amount_due' => (float) $tax->amount_due,
                'penalties' => (float) $tax->penalties,
                'total_payable' => (float) $tax->total_payable,
                'due_date' => optional($tax->due_date)->format('M d, Y'),
                'status' => $tax->status,
            ]);

        $payments = Payment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('propertyTax.property')
            ->orderByDesc('paid_at')
            ->get()
            ->map(function (Payment $payment) {
                $property = $payment->propertyTax->property;

                return (object) [
                    'id' => $payment->id,
                    'property_label' => "{$property->type}, Lot {$property->lot_no} — {$property->barangay}",
                    'amount' => (float) $payment->amount,
                    'method' => $payment->method,
                    'paid_at' => optional($payment->paid_at)->format('M d, Y'),
                    'receipt_url' => $payment->receipt_path ? asset('storage/' . $payment->receipt_path) : '#',
                ];
            });

        return view('dashboard', [
            'user' => $user,
            'properties' => $properties,
            'payments' => $payments,
        ]);
    }
}