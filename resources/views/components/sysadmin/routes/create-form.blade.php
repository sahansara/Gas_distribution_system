@props(['staff'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
    <div class="p-6 bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2">Schedule New Delivery Route</h3>

        <form action="{{ route('admin.routes.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="col-span-1 md:col-span-2">
                    <label class="block font-bold text-sm text-gray-700 mb-1">Route Name / Area</label>
                    <input type="text" name="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 h-10 px-3" placeholder="e.g. Route A - Colombo North" required>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-bold text-sm text-gray-700 mb-1">Vehicle Number</label>
                    <input type="text" name="vehicle_number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 h-10 px-3" placeholder="WP-XX-0000" required>
                </div>

                <div class="col-span-1 md:col-span-2 grid grid-cols-2 gap-6 bg-gray-50 p-4 rounded border">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Planned Start Time</label>
                        <input type="time" name="planned_start_time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 h-10 px-3" required>
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Planned End Time (Est.)</label>
                        <input type="time" name="planned_end_time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 h-10 px-3">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Assign Driver</label>
                    <select name="driver_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 h-10 px-3" required>
                        <option value="">-- Select Staff Member --</option>
                        @foreach($staff as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Assign Assistant (Optional)</label>
                    <select name="assistant_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 h-10 px-3">
                        <option value="">-- None --</option>
                        @foreach($staff as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mt-6 flex justify-end border-t pt-4">
                <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
                    Save Route Schedule
                </button>
            </div>
        </form>
    </div>
</div>