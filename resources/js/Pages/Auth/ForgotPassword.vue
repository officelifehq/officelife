<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />
    </template>

    <div class="mb-4 text-sm text-gray-600">
      Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </div>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>

    <validation-errors class="mb-4" />

    <form @submit.prevent="submit">
      <div>
        <input-label for="email" value="Email" />
        <text-input id="email" v-model="form.email" type="email" class="mt-1 block w-full" required
                    autofocus
        />
      </div>

      <div class="flex items-center justify-end mt-4">
        <loading-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Email Password Reset Link
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

  props: {
    status: {
      type: String,
      default: null,
    }
  },

  data() {
    return {
      form: this.$inertia.form({
        email: ''
      })
    };
  },

  methods: {
    submit() {
      this.form.post(this.route('password.email'));
    }
  }
};
</script>
