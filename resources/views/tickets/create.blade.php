<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Log a New Ticket
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('tickets.store') }}"
                      x-data="{
                          departmentId: '{{ old('department_id') }}',
                          departments: {{ Js::from($departments) }},
                          get categories() {
                              const d = this.departments.find(d => d.id == this.departmentId);
                              return d ? d.categories : [];
                          }
                      }">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="subject" value="Subject" />
                        <x-text-input id="subject" name="subject" type="text"
                                      class="mt-1 block w-full"
                                      value="{{ old('subject') }}" required autofocus />
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="department_id" value="Department" />
                        <select id="department_id" name="department_id" x-model="departmentId"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                            <option value="">Select a department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="category_id" value="Category" />
                        <select id="category_id" name="category_id"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                :disabled="!departmentId">
                            <option value="">Select a category</option>
                            <template x-for="category in categories" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="priority" value="Priority" />
                        <select id="priority" name="priority"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical — patient care affected</option>
                        </select>
                        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="location" value="Location" />
                        <x-text-input id="location" name="location" type="text"
                                      class="mt-1 block w-full"
                                      value="{{ old('location') }}"
                                      placeholder="e.g. Theatre 2, Ward B, Records Office" required />
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="5"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  required>{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Submit Ticket</x-primary-button>
                        <a href="{{ route('tickets.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
