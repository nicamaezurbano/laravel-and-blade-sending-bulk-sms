<x-primary-button
    x-data=""
    x-on:click.prevent="$dispatch('open-modal', 'edit_contactForm_{{ $contact->id }}')"
>
    {{ __('Edit') }}
</x-primary-button>

<x-modal name="edit_contactForm_{{ $contact->id }}" :show="$errors->{'contactUpdate_' . $contact->id}->isNotEmpty()">
    <form method="post" action="{{ route('contacts.update', ['contact_id' => $contact->id]) }}" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('patch')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update contact') }}
        </h2>

        <div class="mt-6">
            <x-input-label for="contact_number" value="{{ __('Contact Number') }}" class="sr-only" />

            <x-text-input
                id="contact_number"
                name="contact_number"
                value="{{ $contact->number }}"
                type="text"
                class="mt-1 block w-full"
                placeholder="{{ __('Contact Number') }}"
                :is_invalid="$errors->{'contactUpdate_' . $contact->id}->has('contact_number')"
            />

            <x-input-error :messages="$errors->{'contactUpdate_' . $contact->id}->get('contact_number')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-input-label for="contact_name" value="{{ __('Contact Name') }}" class="sr-only" />

            <x-text-input
                id="contact_name"
                name="contact_name"
                value="{{ $contact->name }}"
                type="text"
                class="mt-1 block w-full"
                placeholder="{{ __('Contact Name') }}"
                :is_invalid="$errors->{'contactUpdate_' . $contact->id}->has('contact_name')"
            />

            <x-input-error :messages="$errors->{'contactUpdate_' . $contact->id}->get('contact_name')" class="mt-2" />
        </div>

        <div class="mt-6">
            <p>
                Uploaded file:
                @if(!empty($contact->file_path))
                    <a href="{{asset('storage/'.$contact->file_path)}}">
                        {{ $contact->file_path }}
                    </a>
                @else
                    None
                @endif
            </p>
            <x-input-label for="fileAttachment" value="{{ __('Contact Name') }}" class="sr-only" />

            <x-file-upload
                id="fileAttachment"
                name="fileAttachment"
                type="file"
                class="mt-1 block w-full"
                placeholder="{{ __('File attachment') }}"
                :is_invalid="$errors->{'contactUpdate_' . $contact->id}->has('fileAttachment')"
            />

            <x-input-error :messages="$errors->{'contactUpdate_' . $contact->id}->get('fileAttachment')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3">
                {{ __('Save') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
