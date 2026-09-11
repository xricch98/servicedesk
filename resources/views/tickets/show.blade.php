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
                        @if ($ticket->requester_id === auth()->id() && in_array($ticket->status, ['resolved', 'closed']))
                <div class="mt-6 bg-white shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    @if ($ticket->status === 'resolved')
                        <h3 class="text-sm font-semibold text-gray-900">This has been marked as fixed</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            If the problem is sorted, please confirm. If not, tell us what's still wrong.
                        </p>

                        <form method="POST" action="{{ route('tickets.close', $ticket) }}" class="mt-4 inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white text-xs font-semibold uppercase rounded-md hover:bg-green-700">
                                Confirm fixed
                            </button>
                        </form>
                    @else
                        <h3 class="text-sm font-semibold text-gray-900">This ticket is closed</h3>
                        <p class="mt-1 text-sm text-gray-600">If the problem has come back, you can reopen it.</p>
                    @endif

                    <form method="POST" action="{{ route('tickets.reopen', $ticket) }}" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <textarea name="body" rows="2" required
                                class="block w-full border-gray-300 rounded-md shadow-sm text-sm"
                                placeholder="What's still wrong?"></textarea>
                        <button type="submit"
                                class="mt-2 px-4 py-2 border border-gray-300 text-xs font-semibold uppercase rounded-md hover:bg-gray-50">
                            Reopen ticket
                        </button>
                    </form>
                </div>
            @endif
            @can('change_ticket_status')
                <div class="mt-6 bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Agent actions</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        @can('assign_ticket')
                            <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
                                @csrf
                                @method('PATCH')
                                <label class="block text-sm text-gray-600 mb-1">Assign to</label>
                                <div class="flex gap-2">
                                    <select name="assigned_to_id" required
                                            class="flex-1 border-gray-300 rounded-md shadow-sm text-sm">
                                        <option value="">Select an agent</option>
                                        @foreach ($assignableUsers as $candidate)
                                            <option value="{{ $candidate->id }}"
                                                @selected($ticket->assigned_to_id === $candidate->id)>
                                                {{ $candidate->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit"
                                            class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase rounded-md hover:bg-gray-700">
                                        Assign
                                    </button>
                                </div>
                            </form>
                        @endcan

                        <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                            @csrf
                            @method('PATCH')
                            <label class="block text-sm text-gray-600 mb-1">Change status</label>
                            <div class="flex gap-2">
                                <select name="status" required
                                        class="flex-1 border-gray-300 rounded-md shadow-sm text-sm">
                                    @foreach (['assigned' => 'Assigned', 'in_progress' => 'In progress', 'on_hold' => 'On hold', 'resolved' => 'Resolved'] as $value => $label)
                                        <option value="{{ $value }}" @selected($ticket->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                        class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase rounded-md hover:bg-gray-700">
                                    Update
                                </button>
                            </div>
                        </form>



                    </div>
                </div>
            @endcan
            @php
    $canSeeInternal = auth()->user()->hasPermissionTo('reply_to_ticket');
    $visibleComments = $canSeeInternal
        ? $ticket->comments
        : $ticket->comments->where('is_internal', false);
@endphp

<div class="mt-6 bg-white shadow-sm sm:rounded-lg p-6">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Conversation</h3>

    @forelse ($visibleComments as $comment)
        <div class="mb-4 pb-4 border-b border-gray-100 last:border-0">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</span>
                <span class="text-xs text-gray-500">{{ $comment->created_at->format('d M Y, H:i') }}</span>
                @if ($comment->is_internal)
                    <span class="text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded">Internal note</span>
                @endif
            </div>
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $comment->body }}</p>
        </div>
    @empty
        <p class="text-sm text-gray-500 mb-4">No replies yet.</p>
    @endforelse

    <form method="POST" action="{{ route('tickets.comment', $ticket) }}" class="mt-4">
        @csrf
        <textarea name="body" rows="3" required
                  class="block w-full border-gray-300 rounded-md shadow-sm text-sm"
                  placeholder="Write a reply"></textarea>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />

        <div class="mt-3 flex items-center justify-between">
            @if ($canSeeInternal)
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300">
                    Internal note — not visible to the requester
                </label>
            @else
                <span></span>
            @endif

            <button type="submit"
                    class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase rounded-md hover:bg-gray-700">
                Send
            </button>
        </div>
    </form>
</div>

        </div>
    </div>
</x-app-layout>
