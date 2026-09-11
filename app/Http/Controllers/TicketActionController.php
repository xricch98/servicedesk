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
    public function comment(Request $request, Ticket $ticket)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $isAgent = $user->hasPermissionTo('reply_to_ticket')
        && $this->sharesDepartment($user, $ticket);

    $isRequester = $ticket->requester_id === $user->id;

    abort_unless($isAgent || $isRequester, 403);

    $validated = $request->validate([
        'body' => ['required', 'string', 'max:5000'],
        'is_internal' => ['nullable', 'boolean'],
    ]);

    $internal = $isAgent && $request->boolean('is_internal');

    $ticket->comments()->create([
        'user_id' => $user->id,
        'body' => $validated['body'],
        'is_internal' => $internal,
    ]);

    if ($isAgent && ! $internal && ! $ticket->first_response_at) {
        $ticket->update(['first_response_at' => now()]);
    }

    return back()->with('success', 'Reply added.');
}
    public function close(Ticket $ticket)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $canClose = $ticket->requester_id === $user->id
        || ($user->hasPermissionTo('change_ticket_status')
            && $this->sharesDepartment($user, $ticket));

    abort_unless($canClose, 403);
    abort_unless($ticket->status === 'resolved', 422);

    $ticket->update([
        'status' => 'closed',
        'closed_at' => now(),
    ]);

    return back()->with('success', 'Ticket closed. Thank you.');
}

public function reopen(Request $request, Ticket $ticket)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $canReopen = $ticket->requester_id === $user->id
        || ($user->hasPermissionTo('change_ticket_status')
            && $this->sharesDepartment($user, $ticket));

    abort_unless($canReopen, 403);
    abort_unless(in_array($ticket->status, ['resolved', 'closed']), 422);

    $validated = $request->validate([
        'body' => ['required', 'string', 'max:5000'],
    ]);

    $ticket->comments()->create([
        'user_id' => $user->id,
        'body' => $validated['body'],
        'is_internal' => false,
    ]);

    $ticket->update([
        'status' => 'assigned',
        'resolved_at' => null,
        'closed_at' => null,
        'reopen_count' => $ticket->reopen_count + 1,
    ]);

    return back()->with('success', 'Ticket reopened.');
}
}
