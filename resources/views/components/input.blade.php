<div class="{{ $wrapperClass }}">
  <label class="block text-gray-700 text-sm font-bold mb-2" for="{{ $name }}">
    {{ $label }}
  </label>

  <input name="{{ $name }}"
    type="{{ $type }}"
    label="$t('auth.register_email')"
    {{ $required ? 'required' : '' }}
    wire:model="{{ $model }}"
    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
  />
</div>
