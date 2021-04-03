<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />
    </template>

    <div class="mb-4 text-sm text-gray-600">
      This is a secure area of the application. Please confirm your password before continuing.
    </div>

    <validation-errors class="mb-4" />

    <form @submit.prevent="submit">
      <div>
        <input-label for="password" value="Password" />
        <text-input id="password" v-model="form.password" type="password" class="mt-1 block w-full" required
                    autocomplete="current-password" autofocus
        />
      </div>

      <div class="flex justify-end mt-4">
        <loading-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Confirm
        </loading-button>
      </div>
    </form>
  </authentication-card>
</template>

<script>
import AuthenticationCard from '@/Shared/AuthenticationCard';
import AuthenticationCardLogo from '@/Shared/AuthenticationCardLogo';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import InputLabel from '@/Shared/Label';
import ValidationErrors from '@/Shared/ValidationErrors';

export default {
  components: {
    AuthenticationCard,
    AuthenticationCardLogo,
    LoadingButton,
    TextInput,
    InputLabel,
    ValidationErrors
  },

  data() {
    return {
      form: this.$inertia.form({
        password: '',
      })
    };
  },

  methods: {
    submit() {
      this.form.post(this.route('password.confirm'), {
        onFinish: () => this.form.reset(),
      });
    }
  }
};
</script>
