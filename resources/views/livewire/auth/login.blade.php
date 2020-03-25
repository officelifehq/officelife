<div class="flex justify-center mt-16">
  <div class="w-full max-w-xs">
    <form wire:submit.prevent="submit" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <x-input :name="'email'" :type="'email'" :label="'Email'" :required="true" :model="'email'" />
      <x-input :name="'password'" :type="'password'" :label="'Password'" :required="true" :model="'password'" />
      <!-- Actions -->
      <div class="flex-ns justify-between">
        <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.login_cta')" />
        <button type="submit" wire:loading.class="bg-black" wire:loading.attr="disabled" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
      </div>
    </form>

  </div>
</div>
