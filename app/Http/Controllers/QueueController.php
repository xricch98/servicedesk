<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user->hasPermissionTo('view_department_tickets')
                || $user->hasPermissionTo('view_all_tickets'),
            403
        );

        $query = Ticket::with(['requester', 'category', 'assignedTo', 'department']);

        if (! $user->hasPermissionTo('view_all_tickets')) {
            $query->where('department_id', $user->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->paginate(20)->withQueryString();

        return view('queue.index', compact('tickets'));
    }
}
