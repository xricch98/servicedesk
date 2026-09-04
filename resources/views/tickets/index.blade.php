<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Tickets</h2>
            <a href="{{ route('tickets.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-gray-700">
                New Ticket
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                @if ($tickets->isEmpty())
                    <p class="p-6 text-sm text-gray-500">
                        You haven't logged any tickets yet.
                    </p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Reference</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Subject</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Department</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Priority</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Logged</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($tickets as $ticket)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('tickets.show', $ticket) }}"
                                           class="font-medium text-indigo-600 hover:text-indigo-900">
                                            {{ $ticket->reference }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-gray-900">{{ $ticket->subject }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $ticket->department->name }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ ucfirst($ticket->priority) }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ ucfirst($ticket->status) }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $ticket->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="px-6 py-4">
                        {{ $tickets->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
