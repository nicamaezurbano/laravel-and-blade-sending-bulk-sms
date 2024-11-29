@props(['value' => "", 'searchValue' => ""])

<div class="flex items-center justify-start mb-2 sm:mb-0">
    <div>
        <p class="text-sm text-gray-700 leading-5 dark:text-gray-400 pr-1.5">Records per page: </p>
    </div>
    <div>
        <x-input-label for="row" value="{{ __('Show') }}" class="sr-only" />

        <x-text-input
            id="row"
            name="row"
            type="number"
            min="1"
            class="mt-1 block w-20 text-sm"
            placeholder="{{ __('Show') }}"
            value="{{ $value }}"
            x-on:change="document.getElementById('form_showRecords').submit()"
        />
        
        <x-text-input
            id="search"
            name="search"
            type="hidden"
            value="{{ $searchValue }}"
        />
    </div>
</div>

<script>
    // function handleClick(e) {
    //     let number_of_record = document.getElementById('record').value;
    //     if(number_of_record !== "" && (number_of_record >= 1 && number_of_record <= ))
    //     {
    //         document.getElementById('form_showRecords').submit()
    //     }
    // }
</script>