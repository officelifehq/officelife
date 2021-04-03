<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />

      <h2 class="fw5 tc pt5">
        {{ $t('auth.login_salute') }}
      </h2>

      <p class="tc mb4">🥳 {{ $t('auth.login_title') }}</p>
    </template>

    <!-- Form Errors -->
    <errors :errors="errors" :classes="'mb3'" />

    <form @submit.prevent="submit">
      <text-input v-model="form.email"
                  :name="'email'"
                  :errors="$page.props.errors.email"
                  :label="$t('auth.login_email')"
                  :required="true"
                  :type="'email'"
                  :autofocus="true"
      />
      <text-input v-model="form.password"
                  :name="'password'"
                  :errors="$page.props.errors.password"
                  type="password"
                  :label="$t('auth.login_password')"
                  :required="true"
      />

      <!-- Actions -->
      <div class="flex-ns justify-between">
        <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.login_cta')" />
      </div>
    </form>

    <template #footer>
      <inertia-link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
        Forgot your password?
      </inertia-link>
      <p class="f6">
        {{ $t('auth.login_no_account') }}
        <inertia-link :href="route('register')">{{ $t('auth.login_register') }}</inertia-link>
      </p>
    </template>
  </authentication-card>
</template>

<script>
import AuthenticationCard from '@/Shared/AuthenticationCard';
import AuthenticationCardLogo from '@/Shared/AuthenticationCardLogo';
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';

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
  },

  data() {
    return {
      form: this.$inertia.form({
        email: '',
        password: '',
        remember: true
      }),
      errors: [],
      loadingState: '',
      errorTemplate: Error,
    };
  },

  mounted() {
    document.title = 'Login';
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      this.form
        .transform(data => ({
          ... data,
          remember: this.form.remember ? 'on' : ''
        }))
        .post(this.route('login'), {
          onFinish: () => {
            this.loadingState = null;
            this.form.reset('password');
          },
          onError: () => {
            this.loadingState = null;
            //              this.errors = error.response.data;
          }
        });
    },
  }
};
</script>
