{{-- create input component with interactive label  --}}

<div class="relative w-full">
    <label for="{{ $slot }}" class="">{{ $slot }}</label>
    <input {{ $attributes }} placeholder="{{ $slot }}" name="{{ $slot }}"
        class="w-full rounded-lg border border-gray-300 p-2 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" />
    @error('{{ $slot }}')
        <div class="text-sm text-red-500">
            {{ $message }}
        </div>
    @enderror
</div>
