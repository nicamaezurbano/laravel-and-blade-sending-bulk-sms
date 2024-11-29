<x-danger-button
    x-data=""
    x-on:click.prevent="$dispatch('open-modal', 'delete_contactForm_{{ $contact->id }}')"
>
    {{ __('Delete') }}
</x-danger-button>

<x-modal name="delete_contactForm_{{ $contact->id }}">
    <form method="post" action="{{ route('contacts.destroy', ['contact_id' => $contact->id]) }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete contact') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Are you sure you want to delete your this contact?') }}
        </p>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Contact number: {{ $contact->number }}
        </p>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Contact name: {{ $contact->name }}
        </p>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3">
                {{ __('Delete Contact') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>