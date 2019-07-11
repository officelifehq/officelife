<template>
  <div class="ph2 ph0-ns">
    <div class="cf mt4 mw7 center br3 mb3 bg-white box">
      <div class="fn fl-ns w-50-ns pa3">
        {{ $t('auth.register_title') }}
      </div>
      <div class="fn fl-ns w-50-ns pa3">
        <!-- Form Errors -->
        <errors :errors="form.errors" />

        <form @submit.prevent="submit">
          <!-- Email -->
          <div class="">
            <label class="db fw4 lh-copy f6" for="email">{{ $t('auth.register_email') }}</label>
            <input v-model="form.email" type="email" name="email" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required />
            <p class="f7 mb4 lh-title">
              {{ $t('auth.register_email_help') }}
            </p>
          </div>

          <!-- Password -->
          <div class="mb4">
            <label class="db fw4 lh-copy f6" for="password">{{ $t('auth.register_password') }}</label>
            <input v-model="form.password" type="password" name="password" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required />
          </div>

          <!-- Actions -->
          <div class="">
            <div class="flex-ns justify-between">
              <div>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.register_cta')" />
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>

export default {

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
      axios.post('/signup', this.form)
        .then(response => {
          Turbolinks.visit('/home');
        })
        .catch(error => {
          this.loadingState = null;
          if (typeof error.response.data === 'object') {
            this.form.errors = _.flatten(_.toArray(error.response.data));
          } else {
            this.form.errors = [this.$t('app.error_try_again')];
          }
        });
    },
  }
};
</script>
