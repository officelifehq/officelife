<style lang="scss" scoped>
.logo {
  width: 102px;
  top: -78px;
}

.demo-mode {
  box-shadow: 0 0 0 1px #e3e8ee;
  background-color: #f6fafc;
}

.auth-provider {
  width: 15px;
  height: 15px;
  margin-right: 8px;
  top: 2px;
}

.w-43 {
  width: 43%;
}

</style>

<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />

      <h2 class="fw5 tc pt5">
        {{ $t('auth.login_salute') }}
      </h2>
      <p class="tc mb4">🥳 {{ $t('auth.login_title') }}</p>
    </template>

    <div v-if="$page.props.demo_mode" class="demo-mode pa3 mb3">
      <p>{{ $t('app.demo_mode_login') }}</p>
      <p class="pl3 mt0 mb2">{{ $t('app.demo_mode_email') }}: <span class="fw6">admin@admin.com</span></p>
      <p class="pl3 ma0">{{ $t('app.demo_mode_password') }}: <span class="fw6">admin123</span></p>
    </div>

    <!-- Form Errors -->
    <errors :errors="$attrs.errorBags.default" :class="'mb3'" />
    <errors :errors="form.errors" :class="'mb3'" />

    <form @submit.prevent="submit">
      <text-input v-model="form.email"
                  :name="'email'"
                  :label="$t('auth.login_email')"
                  :required="true"
                  :type="'email'"
                  :autofocus="true"
      />
      <text-input v-model="form.password"
                  :name="'password'"
                  type="password"
                  :label="$t('auth.login_password')"
                  :required="true"
      />

      <!-- Actions -->
      <div class="flex-ns justify-between">
        <loading-button :class="'add mb2'" :state="form.processing" :text="$t('auth.login_cta')" />
      </div>
    </form>


    <div v-if="providers.length > 0 && enableExternalLoginProviders" class="db">
      <div class="b--black-10 bb mt2"></div>
      <p class="f4">
        {{ $t('auth.login_with') }}
      </p>
    </div>
    <div v-if="enableExternalLoginProviders">
      <div v-for="provider in providers" :key="provider" class="di">
        <a class="dib ph1 btn w-43 mb2 pv2 mh3 f6 relative"
           :href="route('login.provider', { driver: provider })" @click.prevent="open(provider)"
        >
          <img :src="`/img/auth/${provider}.svg`" alt="" class="auth-provider relative" />
          <template v-if="providersName[provider]">
            {{ providersName[provider] }}
          </template>
          <template v-else>
            {{ $t(`auth.login_provider_${provider}`) }}
          </template>
        </a>
      </div>
    </div>

    <template #footer>
      <languages />

      <inertia-link v-if="canResetPassword && !$page.props.demo_mode" :href="route('password.request')" class="f6">
        {{ $t('passwords.forgot_password_link') }}
      </inertia-link>
      <p v-if="$page.props.jetstream.enableSignups" class="f6">
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
import Languages from './Partials/Languages';

export default {
  components: {
    AuthenticationCard,
    AuthenticationCardLogo,
    TextInput,
    Errors,
    LoadingButton,
    Languages,
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
    providers: {
      type: Array,
      default: () => [],
    },
    providersName: {
      type: Object,
      default: () => {return {};},
    },
    enableExternalLoginProviders: {
      type: String,
      default: '',
    }
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
            this.form.errors = error.response.data;
          }
        });
    },

    open(provider) {
      const url = this.route('login.provider', { driver: provider });

      location.href = `${url}?redirect=${location.href}`;
    },
  }
};
</script>
