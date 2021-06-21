<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />

      <p class="fw5 pt5">
        {{ $t('passwords.reset_password_message') }}
      </p>
    </template>

    <validation-errors class="mb-4" />

    <form @submit.prevent="submit">
      <text-input v-model="form.email"
                  :name="'email'"
                  type="email"
                  :errors="$page.props.errors.email"
                  :label="$t('auth.register_email')"
                  :required="true"
      />
      <text-input v-model="form.password"
                  :name="'password'"
                  :errors="$page.props.errors.password"
                  type="password"
                  :label="$t('auth.register_password')"
                  :required="true"
                  autocomplete="new-password"
      />
      <text-input v-model="form.password_confirmation"
                  :name="'password_confirmation'"
                  type="password"
                  :label="$t('auth.register_password_confirmation')"
                  :required="true"
                  :extra-class-upper-div="'mb4'"
                  autocomplete="new-password"
      />

      <div class="flex-ns justify-between">
        <loading-button :class="'add mb2'" :state="form.processing">
          {{ $t('passwords.reset_password_action') }}
        </loading-button>
      </div>
    </form>
  </authentication-card>
</template>

<script>
import AuthenticationCard from '@/Shared/Layout/AuthenticationCard';
import AuthenticationCardLogo from '@/Shared/Layout/AuthenticationCardLogo';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import ValidationErrors from '@/Shared/ValidationErrors';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
    AuthenticationCard,
    AuthenticationCardLogo,
    LoadingButton,
    TextInput,
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
      form: useForm({
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
