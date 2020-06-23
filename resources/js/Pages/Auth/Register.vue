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
          👋 {{ $t('auth.register_salute') }}
        </h2>
        <p class="tc mb4">{{ $t('auth.register_title') }}</p>
      </div>
      <div class="">
        <!-- Form Errors -->
        <errors :errors="$page.errors" />

        <form @submit.prevent="submit">
          <text-input v-model="form.email"
                      :name="'email'"
                      :errors="$page.errors.email"
                      :label="$t('auth.register_email')"
                      :help="$t('auth.register_email_help')"
                      :required="true"
          />

          <text-input v-model="form.password"
                      :name="'password'"
                      :errors="$page.errors.password"
                      class="mb3"
                      type="password"
                      :label="$t('auth.register_password')"
                      :required="true"
                      :extra-class-upper-div="'mb4'"
          />

          <checkbox
            :id="'home'"
            v-model="form.terms"
            :datacy="'accept-terms'"
            :label="$t('auth.register_terms')"
            :extra-class-upper-div="'mb3 relative'"
            :required="true"
          />

          <!-- Actions -->
          <div class="flex-ns justify-between">
            <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.register_cta')" />
          </div>
        </form>
      </div>
    </div>
    <div class="tc">
      <p class="f6">{{ $t('auth.register_already_an_account') }} <inertia-link :href="signInUrl">{{ $t('auth.register_sign_in') }}</inertia-link></p>
    </div>
  </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import Checkbox from '@/Shared/Checkbox';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    TextInput,
    Errors,
    LoadingButton,
    Checkbox,
  },

  props: {
    signInUrl: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      form: {
        email: null,
        password: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      this.$inertia.post(this.route('signup.attempt'), this.form)
        .then(() =>
          this.loadingState = null
        );
    },
  }
};
</script>
