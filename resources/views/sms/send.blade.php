<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Send SMS') }}
        </h2>
    </x-slot>

    <form action="{{route('sms.send')}}" method="post" x-data="{ selectedContactsArray: [] }">
        @csrf
        <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-1/2">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h3 class="mt-12">Select recipients:</h3>
                </div>

                <div class="pb-12 pt-8">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg h-80 overflow-y-auto">
                            <div class="p-6 text-gray-900 dark:text-gray-100 table-responsive">
                                <table class="table table-sm table-hover mx-auto min-w-fit">
                                    <thead>
                                        <tr>
                                            <th class="w-60">{{ __("Contact Number") }}</th>
                                            <th class="w-60">{{ __("Contact Name") }}</th>
                                            <th class="w-60">{{ __("Action") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $contact)
                                            <tr>
                                                <td>{{ $contact["number"] }}</td>
                                                <td>{{ $contact["name"] }}</td>
                                                <td>
                                                    <input 
                                                        class="form-check-input" 
                                                        type="checkbox" 
                                                        id="checkbox_{{$contact['id']}}" 
                                                        value="{{ $contact['id'] }}"
                                                        x-on:click="selectContact({{$contact['id']}}, $data)"
                                                    >
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                                
                        <x-input-error :messages="$errors->sendingSMS->get('selectedRecipients')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="w-full md:w-1/2">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h3 class="mt-12">Message:</h3>
                </div>
                <div class="pb-12 pt-8">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class=" text-gray-900 dark:text-gray-100">
                            <div>
                                <x-input-label for="sms_message" value="{{ __('Message') }}" class="sr-only" />
                                <x-input-text-area
                                    id="sms_message"
                                    name="sms_message"
                                    class="mt-1 block w-full h-52"
                                    placeholder="{{ __('Message') }}"
                                    :is_invalid="$errors->sendingSMS->has('sms_message')"
                                />
                                <x-input-error :messages="$errors->sendingSMS->get('sms_message')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                            
                    <input 
                        type="hidden" 
                        name="selectedRecipients" 
                        x-model="selectedContactsArray"
                    >

                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="mt-4 flex justify-end justify-items-end">
                            <x-primary-button>
                                Send
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if(session('response'))
        <x-modal name="sms_response" :show="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('SMS Sending Status') }}
                </h2>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Contact Number</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($a=0; $a < count(session('response')); $a++)
                            <tr>
                                <td>
                                    {{ session('recipients')[$a] }}
                                </td>
                                <td>
                                    @if(session('response')[$a]['success'])
                                        Sent Successful
                                    @else
                                        Sent Failed
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Close') }}
                    </x-secondary-button>
                </div>
            </div>
        </x-modal>
    @endif
</x-app-layout>

<script>
    function selectContact(contactID, {selectedContactsArray})
    {
        let ckBox = document.getElementById("checkbox_"+contactID+"");
        if(ckBox.checked)
        {
            // Contact ID will be inserted to the array
            selectedContactsArray.push(contactID);
        }
        else
        {
            // Contact ID will be removed to the array
            for(let i=0; i < selectedContactsArray.length; i++)
            {
                if (contactID == selectedContactsArray[i]) 
                {
                    selectedContactsArray.splice(i,1);
                    i = i-1;
                    break;
                }
            }
        }
        return selectedContactsArray;
    }
</script>