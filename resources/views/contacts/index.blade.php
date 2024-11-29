<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-row justify-content-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Contacts') }}
            </h2>

            @include('contacts.forms.create')

        </div>
    </x-slot>

    <div class="max-w-7xl mt-4 mx-auto sm:px-6 lg:px-8">
        <div class="row mx-0">
            <div class="col col-12 col-sm-6 col-md-4 col-lg-3">
                <form action="{{ route('contacts.index') }}" method="get" id="form_search">
                    <x-search-field
                        id="search"
                        name="search"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="{{ __('Search') }}"
                        value="{{ isset($data['search']) ? $data['search'] : '' }}"
                    >
                    </x-search-field>
                    <x-text-input
                        id="row"
                        name="row"
                        type="hidden"
                        value="{{ $data['record_per_page'] }}"
                    />
                </form>
            </div>
        </div>
    </div>

    <div class="pt-6 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 table-responsive">
                    <table class="table table-sm table-hover mx-auto" style="width: 1000px;">
                        <thead>
                            <tr>
                                <th class="w-60">{{ __("Contact Number") }}</th>
                                <th class="w-60">{{ __("Contact Name") }}</th>
                                <th class="w-60">{{ __("File attachment") }}</th>
                                <th class="w-60">{{ __("Action") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data["contacts"] as $contact)
                            <tr>
                                <td>{{ $contact->number }}</td>
                                <td>{{ $contact->name }}</td>
                                <td><a href="{{asset('storage/'.$contact->file_path)}}">{{$contact->file_path}}</a></td>
                                <td>
                                    @include('contacts.forms.update')
                                    @include('contacts.forms.delete')
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Links -->
                <div class="flex flex-col sm:flex-row justify-between items-center p-6 mx-auto" style="width: 1000px;">
                    <!-- <x-pagination-show-records :lastPage="$data['contacts']->lastPage()" /> -->
                    <form x-data="" action="{{ route('contacts.index') }}" method="get" id="form_showRecords">
                        <x-pagination-show-records 
                            :value="$data['record_per_page']"
                            :searchValue="$data['search']"
                            routeTo="contacts.index"
                        />
                    </form>
                    {{ $data["contacts"]->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>