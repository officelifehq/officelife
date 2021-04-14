<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />

      <h2 class="fw5 tc pt5">
        👋 {{ $t('auth.register_salute') }}
      </h2>

      <p class="tc mb4">{{ $t('auth.register_title') }}</p>
    </template>

    <!-- Form Errors -->
    <errors :errors="$page.props.errors" />

    <form @submit.prevent="submit">
      <text-input v-model="form.email"
                  :name="'email'"
                  :errors="form.errors.email"
                  :label="$t('auth.register_email')"
                  :help="$t('auth.register_email_help')"
                  :required="true"
      />

      <text-input v-model="form.password"
                  :name="'password'"
                  :errors="form.errors.password"
                  class="mb3"
                  type="password"
                  :label="$t('auth.register_password')"
                  :required="true"
                  :extra-class-upper-div="'mb3'"
      />

      <checkbox :id="'terms'"
                v-model="form.terms"
                :datacy="'accept-terms'"
                :label="$t('auth.register_terms')"
                :extra-class-upper-div="'mb3 relative'"
                :required="true"
      />

      <!-- Actions -->
      <div class="flex-ns justify-between">
        <loading-button :class="'add mb2'" :state="form.processing">
          {{ $t('auth.register_cta') }}
        </loading-button>
      </div>
    </form>

    <template #footer>
      <p class="f6">
        {{ $t('auth.register_already_an_account') }}
        <inertia-link :href="route('login')">{{ $t('auth.register_sign_in') }}</inertia-link>
      </p>
    </template>
  </authentication-card>
</template>

<script>
import AuthenticationCard from '@/Shared/Layout/AuthenticationCard';
import AuthenticationCardLogo from '@/Shared/Layout/AuthenticationCardLogo';
import Checkbox from '@/Shared/Checkbox';
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
    AuthenticationCard,
    AuthenticationCardLogo,
    Checkbox,
    TextInput,
    Errors,
    LoadingButton,
  },

  data() {
    return {
      form: useForm({
        email: '',
        password: '',
        terms: false,
      }),
      errorTemplate: Error,
    };
  },

  methods: {
    submit() {
      this.form.post(this.route('register'), {
        onFinish: () => {
          this.form.reset('password');
        }
      });
    },
  }
};
</script>
