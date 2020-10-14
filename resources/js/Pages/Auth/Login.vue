<style lang="scss" scoped>
.logo {
  width: 152px;
  top: -70px;
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
      <div class="">
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
      </div>
    </div>
    <div class="tc">
      <p class="f6">{{ $t('auth.login_no_account') }} <inertia-link :href="registerUrl">{{ $t('auth.login_register') }}</inertia-link></p>
    </div>
  </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';

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
  },

  data() {
    return {
      form: {
        email: null,
        password: null,
      },
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

      axios.post(this.route('login.attempt').url(), _.assign({}, this.form, { remember: true}))
        .then(response => {
          this.loadingState = null;
          this.$inertia.visit(response.data.redirect);
        })
        .catch(error => {
          this.loadingState = null;
          this.errors = error.response.data.data;
        });
    },
  }
};
</script>
