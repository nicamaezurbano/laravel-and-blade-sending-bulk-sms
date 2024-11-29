<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Received SMS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 table-responsive">
                    <table class="table table-sm table-hover mx-auto" style="width: 1000px;">
                        <thead>
                            <tr>
                                <th class="w-60">{{ __("Sender") }}</th>
                                <th class="w-60">{{ __("Message") }}</th>
                                <th class="w-60">{{ __("Receive at") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($combined_data["sms"] as $sms)
                            <tr>
                                <td>{{ $sms["sender"] }}</td>
                                <td>{{ $sms["message"] }}</td>
                                <td>{{ date_format(date_create($sms["receivedAt"]), "F m, Y | g:i A") }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Links -->
                <div class="flex flex-col sm:flex-row justify-between items-center p-6 mx-auto" style="width: 1000px;">
                    <form x-data="" action="{{ route('sms.receive') }}" method="get" id="form_showRecords">
                        <x-pagination-show-records 
                            :value="$combined_data['record_per_page']"
                            routeTo="sms.receive"
                        />
                    </form>
                        
                        {{ $combined_data["sms"]->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
