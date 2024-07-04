<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />

      <h2 class="fw5 tc pt5">
        👋 {{ $t('auth.register_salute') }}
      </h2>

      <p class="tc mb4">{{ $t('auth.register_title') }}</p>
    </template>

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

      <checkbox
        :id="'terms'"
        v-model="form.terms"
        :datacy="'accept-terms'"
        :label="$t('auth.register_terms', {url : 'https://docs.officelife.io/documentation/officelife-beta.html' })"
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
      <languages @update:lang="form.locale = $event" />

      <p class="f6">
        {{ $t('auth.register_already_an_account') }}
        <Link :href="route('login')">{{ $t('auth.register_sign_in') }}</Link>
      </p>
    </template>
  </authentication-card>
</template>

<script>
import AuthenticationCard from '@/Shared/Layout/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Shared/Layout/AuthenticationCardLogo.vue';
import Checkbox from '@/Shared/Checkbox.vue';
import TextInput from '@/Shared/TextInput.vue';
import LoadingButton from '@/Shared/LoadingButton.vue';
import { useForm } from '@inertiajs/vue3';
import Languages from '@/Pages/Auth/Partials/Languages.vue';

export default {
  components: {
    AuthenticationCard,
    AuthenticationCardLogo,
    Checkbox,
    TextInput,
    LoadingButton,
    Languages,
  },

  props: {
    signInUrl: {
      type: String,
      default: null,
    },
    betaTermsOfUse: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      form: useForm({
        email: '',
        password: '',
        terms: false,
        locale: null,
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
