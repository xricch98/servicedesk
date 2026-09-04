<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $ticket->reference }}
            </h2>
            <a href="{{ route('tickets.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                Back to my tickets
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">{{ $ticket->subject }}</h3>

                <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Status</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ ucfirst($ticket->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Priority</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ ucfirst($ticket->priority) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Department</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $ticket->department->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Category</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $ticket->category?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Location</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $ticket->location }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Assigned to</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $ticket->assignedTo?->name ?? 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Raised by</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $ticket->requester->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Logged</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $ticket->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>

                <div class="mt-8 border-t border-gray-200 pt-6">
                    <dt class="text-sm text-gray-500">Description</dt>
                    <dd class="mt-2 text-sm text-gray-900 whitespace-pre-line">{{ $ticket->description }}</dd>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
