<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProperty;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return $this->admin();
        }

        return $this->resident($user);
    }

    /**
     * Resident dashboard: their own account info plus the properties
     * an admin has linked to their account.
     */
    protected function resident(User $user)
    {
        $properties = $user->properties()
            ->orderByDesc('date_of_registration')
            ->get();

        return view('dashboard', [
            'user' => $user,
            'properties' => $properties,
        ]);
    }

    /**
     * Admin overview: what needs attention right now, plus quick links
     * into the two admin actions (approvals, property uploads/linking).
     */
    protected function admin()
    {
        $stats = [
            'pendingAccounts' => User::where('role', 'user')->where('status', 'pending')->count(),
            'approvedAccounts' => User::where('role', 'user')->where('status', 'approved')->count(),
            'totalProperties' => UserProperty::count(),
            'unlinkedProperties' => UserProperty::where(function ($q) {
                $q->whereNull('acct_email_address')->orWhere('acct_email_address', '');
            })->count(),
        ];

        $recentAccounts = User::where('role', 'user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard-admin', [
            'stats' => $stats,
            'recentAccounts' => $recentAccounts,
            'pendingCount' => $stats['pendingAccounts'],
        ]);
    }
}
