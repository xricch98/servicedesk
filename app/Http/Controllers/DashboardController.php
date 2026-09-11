<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $mine = Ticket::where('requester_id', $user->id);

        $data = [
            'myTotal' => (clone $mine)->count(),
            'myActive' => (clone $mine)->whereIn('status', ['new', 'assigned', 'in_progress', 'resolved'])->count(),
            'myOnHold' => (clone $mine)->where('status', 'on_hold')->count(),
            'myClosed' => (clone $mine)->where('status', 'closed')->count(),
            'myRecent' => (clone $mine)->with('department')->latest()->take(5)->get(),
            'isAgent' => $user->hasPermissionTo('view_department_tickets')
                || $user->hasPermissionTo('view_all_tickets'),
        ];

        if ($data['isAgent']) {
            $queue = $user->hasPermissionTo('view_all_tickets')
                ? Ticket::query()
                : Ticket::where('department_id', $user->department_id);

            $data['queueUnassigned'] = (clone $queue)->whereNull('assigned_to_id')
                ->whereNotIn('status', ['closed'])->count();
            $data['queueMine'] = (clone $queue)->where('assigned_to_id', $user->id)
                ->whereNotIn('status', ['closed', 'resolved'])->count();
            $data['queueCritical'] = (clone $queue)->where('priority', 'critical')
                ->whereNotIn('status', ['closed', 'resolved'])->count();
        }

        return view('dashboard', $data);
    }
}
