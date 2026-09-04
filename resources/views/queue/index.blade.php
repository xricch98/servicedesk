<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Department Queue
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="GET" action="{{ route('queue.index') }}" class="mb-4 flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search reference or subject"
                       class="border-gray-300 rounded-md shadow-sm text-sm w-64">

                <select name="status" class="border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="">All statuses</option>
                    @foreach (['new', 'assigned', 'in_progress', 'on_hold', 'resolved', 'closed'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
                    Filter
                </button>

                @if (request()->hasAny(['search', 'status']))
                    <a href="{{ route('queue.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Clear</a>
                @endif
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                @if ($tickets->isEmpty())
                    <p class="p-6 text-sm text-gray-500">No tickets match this view.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Reference</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Subject</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Requester</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Category</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Priority</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Assigned to</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Logged</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($tickets as $ticket)
                                <tr class="hover:bg-gray-50 @if ($ticket->priority === 'critical') bg-red-50 @endif">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('tickets.show', $ticket) }}"
                                           class="font-medium text-indigo-600 hover:text-indigo-900">
                                            {{ $ticket->reference }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-900">{{ $ticket->subject }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $ticket->requester->name }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $ticket->category?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ ucfirst($ticket->priority) }}</td>
                                    <td class="px-4 py-3 @if (! $ticket->assigned_to_id) text-amber-700 font-medium @else text-gray-500 @endif">
                                        {{ $ticket->assignedTo?->name ?? 'Unassigned' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $ticket->created_at->format('d M') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="px-4 py-3">{{ $tickets->links() }}</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
