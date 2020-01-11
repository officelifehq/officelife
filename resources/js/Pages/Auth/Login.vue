<template>
  <div class="ph2 ph0-ns">
    <div class="cf mt4 mw7 center br3 mb3 bg-white box">
      <div class="fn fl-ns w-50-ns pa3">
        Login
      </div>
      <div class="fn fl-ns w-50-ns pa3">
        <!-- Form Errors -->
        <errors :errors="$page.errors" />

        <form @submit.prevent="submit">
          <text-input v-model="form.email"
                      :name="'email'"
                      :errors="$page.errors.email"
                      :label="$t('auth.register_email')"
                      :required="true"
          />
          <text-input v-model="form.password"
                      :name="'password'"
                      :errors="$page.errors.password"
                      type="password"
                      :label="$t('auth.register_password')"
                      :required="true"
          />

          <!-- Actions -->
          <div class="flex-ns justify-between">
            <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.login_cta')" />
          </div>
        </form>
      </div>
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

  mounted() {
    document.title = 'Login';
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      this.$inertia.post(this.route('login.attempt'), this.form)
        .then(() =>
          this.loadingState = null
        );
    },
  }
};
</script>
