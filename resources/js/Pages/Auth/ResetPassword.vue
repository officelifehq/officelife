<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />
    </template>

    <validation-errors class="mb-4" />

    <form @submit.prevent="submit">
      <div>
        <input-label for="email" value="Email" />
        <text-input id="email" v-model="form.email" type="email" class="mt-1 block w-full" required
                    autofocus
        />
      </div>

      <div class="mt-4">
        <input-label for="password" value="Password" />
        <text-input id="password" v-model="form.password" type="password" class="mt-1 block w-full" required
                    autocomplete="new-password"
        />
      </div>

      <div class="mt-4">
        <input-label for="password_confirmation" value="Confirm Password" />
        <text-input id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" required
                    autocomplete="new-password"
        />
      </div>

      <div class="flex items-center justify-end mt-4">
        <loading-button :classes="['btn add w-auto-ns w-100 mb2 pv2 ph3', { 'opacity-25' : form.processing }]" :state="form.processing ? 'loading' : ''" :disabled="form.processing">
          Reset Password
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
    email: {
      type: String,
      default: null,
    },
    token: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      form: this.$inertia.form({
        token: this.token,
        email: this.email,
        password: '',
        password_confirmation: '',
      })
    };
  },

  methods: {
    submit() {
      this.form.post(this.route('password.update'), {
        onFinish: () => this.form.reset('password', 'password_confirmation'),
      });
    }
  }
};
</script>
