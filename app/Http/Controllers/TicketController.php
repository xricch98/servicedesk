<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('requester_id', Auth::id())
            ->with(['department', 'category', 'assignedTo'])
            ->latest()
            ->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)
            ->with('categories')
            ->orderBy('name')
            ->get();

        return view('tickets.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'department_id' => ['required', 'exists:departments,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $department = Department::findOrFail($validated['department_id']);

        $ticket = Ticket::create([
            ...$validated,
            'reference' => Ticket::generateReference($department),
            'requester_id' => Auth::id(),
            'created_by_id' => Auth::id(),
            'status' => 'new',
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', "Ticket {$ticket->reference} created.");
    }

    public function show(Ticket $ticket)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $canView = $ticket->requester_id === $user->id
        || $ticket->created_by_id === $user->id
        || $user->hasPermissionTo('view_all_tickets')
        || ($user->hasPermissionTo('view_department_tickets')
            && $ticket->department_id === $user->department_id);

    abort_unless($canView, 403);

    $ticket->load(['department', 'category', 'requester', 'assignedTo']);

    $assignableUsers = $user->hasPermissionTo('assign_ticket')
    ? User::where('department_id', $ticket->department_id)
        ->where('is_active', true)
        ->orderBy('name')
        ->get()
    : collect();

    return view('tickets.show', compact('ticket', 'assignableUsers'));

    return view('tickets.show', compact('ticket'));
}
}
