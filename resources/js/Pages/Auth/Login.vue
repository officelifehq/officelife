<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />

      <h2 class="fw5 tc pt5">
        {{ $t('auth.login_salute') }}
      </h2>
      <p class="tc mb4">🥳 {{ $t('auth.login_title') }}</p>
    </template>

    <div v-if="status" class="mt3 ba br3 pa3">
      {{ status }}
    </div>

    <!-- Form Errors -->
    <errors :errors="errors" :class="'mb3'" />

    <form @submit.prevent="submit">
      <text-input v-model="form.email"
                  :name="'email'"
                  :errors="form.errors.email"
                  :label="$t('auth.login_email')"
                  :required="true"
                  :type="'email'"
                  :autofocus="true"
      />
      <text-input v-model="form.password"
                  :name="'password'"
                  :errors="form.errors.password"
                  type="password"
                  :label="$t('auth.login_password')"
                  :required="true"
      />

      <!-- Actions -->
      <div class="flex-ns justify-between">
        <loading-button :class="'add mb2'" :state="form.processing" :text="$t('auth.login_cta')" />
      </div>
    </form>

    <template #footer>
      <inertia-link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
        {{ $t('passwords.forgot_password_link') }}
      </inertia-link>
      <p class="f6">
        {{ $t('auth.login_no_account') }}
        <inertia-link :href="route('register')">{{ $t('auth.login_register') }}</inertia-link>
      </p>
    </template>
  </authentication-card>
</template>

<script>
import AuthenticationCard from '@/Shared/Layout/AuthenticationCard';
import AuthenticationCardLogo from '@/Shared/Layout/AuthenticationCardLogo';
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
    AuthenticationCard,
    AuthenticationCardLogo,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    canResetPassword: {
      type: Boolean,
      default: false,
    },
    status: {
      type: String,
      default: '',
    },
  },

  data() {
    return {
      form: useForm({
        email: '',
        password: '',
        remember: true
      }),
      errors: [],
      errorTemplate: Error,
    };
  },

  mounted() {
    document.title = 'Login';
  },

  methods: {
    submit() {
      this.form
        .transform(data => ({
          ... data,
          remember: this.form.remember ? 'on' : ''
        }))
        .post(this.route('login'), {
          onFinish: () => {
            this.form.reset('password');
          },
          onError: (error) => {
            this.errors = error.response.data;
          }
        });
    },
  }
};
</script>
