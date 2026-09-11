<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketActionController extends Controller
{
    public function assign(Request $request, Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user->hasPermissionTo('assign_ticket'), 403);
        abort_unless($this->sharesDepartment($user, $ticket), 403);

        $validated = $request->validate([
            'assigned_to_id' => ['required', 'exists:users,id'],
        ]);

        $assignee = User::findOrFail($validated['assigned_to_id']);

        abort_unless($assignee->department_id === $ticket->department_id, 422);

        $ticket->update([
            'assigned_to_id' => $assignee->id,
            'assigned_at' => $ticket->assigned_at ?? now(),
            'status' => $ticket->status === 'new' ? 'assigned' : $ticket->status,
        ]);

        return back()->with('success', "Assigned to {$assignee->name}.");
    }

    public function status(Request $request, Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user->hasPermissionTo('change_ticket_status'), 403);
        abort_unless($this->sharesDepartment($user, $ticket), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:assigned,in_progress,on_hold,resolved'],
        ]);

        $updates = ['status' => $validated['status']];

        if ($validated['status'] === 'resolved') {
            $updates['resolved_at'] = now();
        }

        $ticket->update($updates);

        return back()->with('success', 'Status updated.');
    }

    private function sharesDepartment(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('view_all_tickets')
            || $user->department_id === $ticket->department_id;
    }
}
