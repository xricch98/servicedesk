<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome back, {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($isAgent)
                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Your department queue</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <a href="{{ route('queue.index') }}" class="bg-white shadow-sm rounded-lg p-5 hover:bg-gray-50">
                            <div class="text-sm text-gray-500">Unassigned</div>
                            <div class="mt-1 text-3xl font-semibold {{ $queueUnassigned > 0 ? 'text-amber-600' : 'text-gray-900' }}">
                                {{ $queueUnassigned }}
                            </div>
                        </a>
                        <a href="{{ route('queue.index') }}" class="bg-white shadow-sm rounded-lg p-5 hover:bg-gray-50">
                            <div class="text-sm text-gray-500">Assigned to you</div>
                            <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $queueMine }}</div>
                        </a>
                        <a href="{{ route('queue.index') }}" class="bg-white shadow-sm rounded-lg p-5 hover:bg-gray-50">
                            <div class="text-sm text-gray-500">Critical open</div>
                            <div class="mt-1 text-3xl font-semibold {{ $queueCritical > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $queueCritical }}
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700">Your tickets</h3>
                <a href="{{ route('tickets.create') }}"
                   class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700">
                    Create ticket
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="text-sm text-gray-500">Total</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $myTotal }}</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="text-sm text-gray-500">Active</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $myActive }}</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="text-sm text-gray-500">On hold</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $myOnHold }}</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="text-sm text-gray-500">Closed</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $myClosed }}</div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Recent tickets</h3>
                    <a href="{{ route('tickets.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View all</a>
                </div>

                @if ($myRecent->isEmpty())
                    <p class="p-6 text-sm text-gray-500">You haven't logged any tickets yet.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($myRecent as $ticket)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $ticket->subject }}</div>
                                        <div class="text-xs text-gray-500">{{ $ticket->reference }} · {{ $ticket->department->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $ticket->created_at->format('d M') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('tickets.show', $ticket) }}"
                                           class="px-3 py-1.5 border border-gray-300 rounded-md text-xs hover:bg-gray-50">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
