<style lang="scss" scoped>
.logo {
  width: 102px;
  top: -78px;
}

.demo-mode {
  box-shadow: 0 0 0 1px #e3e8ee;
  background-color: #f6fafc;
}
</style>

<template>
  <div class="ph2 ph0-ns">
    <div class="cf mt6 mw6 center br3 mb4 bg-white box pa3">
      <div class="w-100 relative">
        <img loading="lazy" class="logo absolute left-0 right-0 mr-auto ml-auto" alt="officelife logo" srcset="/img/logo.png,
                                          /img/logo-2x.png 2x"
        />

        <h2 class="fw5 tc pt5">
          {{ $t('auth.login_salute') }}
        </h2>
        <p class="tc mb4">🥳 {{ $t('auth.login_title') }}</p>
      </div>

      <div v-if="$page.props.demo_mode" class="demo-mode pa3 mb3">
        <p>{{ $t('app.demo_mode_login') }}</p>
        <p class="pl3 mt0 mb2">{{ $t('app.demo_mode_email') }}: <span class="fw6">admin@admin.com</span></p>
        <p class="pl3 ma0">{{ $t('app.demo_mode_password') }}: <span class="fw6">admin</span></p>
      </div>

      <div class="">
        <!-- Form Errors -->
        <errors :errors="form.errors" :class="'mb3'" />

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
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.login_cta')" />
          </div>
        </form>
      </div>
    </div>

    <!-- link to signup -->
    <div v-if="enableSignup" class="tc">
      <p class="f6">{{ $t('auth.login_no_account') }} <inertia-link :href="registerUrl">{{ $t('auth.login_register') }}</inertia-link></p>
    </div>
  </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    registerUrl: {
      type: String,
      default: null,
    },
    enableSignup: {
      type: Boolean,
      default: false,
    }
  },

  data() {
    return {
      form: useForm({
        email: null,
        password: null,
        remember: false
      }),
      errorTemplate: Error,
    };
  },

  computed: {
    loadingState() {
      return this.form.processing ? 'loading' : '';
    }
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
          onFinish: () => this.form.reset('password'),
        });
    },
  }
};
</script>
