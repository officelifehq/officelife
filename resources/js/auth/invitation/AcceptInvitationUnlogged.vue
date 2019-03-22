<template>
  <div class="ph2 ph0-ns">
    <div class="cf mt3 mw6 center tc">
      <h2 class="lh-title">{{ $t('auth.invitation_unlogged_title', { name: company.name }) }}</h2>
      <p v-show="!displayCreateAccount && !displaySignin">{{ $t('auth.invitation_unlogged_desc') }}</p>
      <p v-show="displayCreateAccount"><a @click="displaySignin = true; displayCreateAccount = false" class="pointer">&larr; {{ $t('auth.invitation_unlogged_create_account_instead') }}</a></p>
      <p v-show="displaySignin"><a @click="displayCreateAccount = true; displaySignin = false" class="pointer">&larr; {{ $t('auth.invitation_unlogged_login_instead') }}</a></p>
    </div>

    <!-- LINKS TO SWITCH BETWEEN SIGNIN/LOGIN -->
    <div class="cf mt3 mw6 center br3 mb3 bg-white box pa3 pointer" @click="displayCreateAccount = true" v-show="!displayCreateAccount && !displaySignin">
      <p class="fw5">{{ $t('auth.invitation_unlogged_choice_account_title') }}</p>
      <p>{{ $t('auth.invitation_unlogged_choice_account_desc') }}</p>
    </div>

    <div class="cf mt3 mw6 center br3 mb3 bg-white box pa3 pointer" @click="displaySignin = true" v-show="!displayCreateAccount && !displaySignin">
      <p class="fw5">{{ $t('auth.invitation_unlogged_choice_login_title') }}</p>
      <p>{{ $t('auth.invitation_unlogged_choice_login_desc') }}</p>
    </div>

    <!-- CREATE AN ACCOUNT -->
    <div class="cf mw6 center br3 mb3 bg-white box" v-show="displayCreateAccount">
      <div class="pa3">
        <h2 class="tc f4">{{ $t('auth.invitation_unlogged_choice_account') }}</h2>
        <!-- Form Errors -->
        <errors :errors="form.errors"></errors>

        <form @submit.prevent="submit">
          <!-- Email -->
          <div class="">
            <label class="db fw4 lh-copy f6" for="email">{{ $t('auth.register_email') }}</label>
            <input type="email" name="email" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" v-model="form.email" required>
            <p class="f7 mb4 lh-title">{{ $t('auth.register_email_help') }}</p>
          </div>

          <!-- Password -->
          <div class="mb4">
            <label class="db fw4 lh-copy f6" for="password">{{ $t('auth.register_password') }}</label>
            <input type="password" name="password" v-model="form.password" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required>
          </div>

          <!-- Actions -->
          <div class="">
            <div class="flex-ns justify-between">
              <div>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.register_cta')"></loading-button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- LOGIN TO EXISTING ACCOUNT -->
    <div class="cf mw6 center br3 mb3 bg-white box" v-show="displaySignin">
      <div class="pa3">
        <h2 class="tc f4">{{ $t('auth.invitation_unlogged_choice_login') }}</h2>

        <!-- Form Errors -->
        <errors :errors="form.errors"></errors>

        <form @submit.prevent="submit">
          <!-- Email -->
          <div class="">
            <label class="db fw4 lh-copy f6" for="email">{{ $t('auth.register_email') }}</label>
            <input type="email" name="email" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" v-model="form.email" required>
          </div>

          <!-- Password -->
          <div class="mb4">
            <label class="db fw4 lh-copy f6" for="password">{{ $t('auth.register_password') }}</label>
            <input type="password" name="password" v-model="form.password" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required>
          </div>

          <!-- Actions -->
          <div class="">
            <div class="flex-ns justify-between">
              <div>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.login_cta')"></loading-button>
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

  props: [
    'company',
    'employee',
    'invitation_link',
  ],

  data() {
    return {
      displayCreateAccount: false,
      displaySignin: false,
      form: {
        email: null,
        password: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    }
  },

  mounted() {
    this.form.email = this.employee.email
  },

  methods: {
    submit() {
      this.loadingState = 'loading'

      axios.post('/invite/employee/' + this.invitation_link + '/join', this.form)
        .then(response => {
          Turbolinks.visit('/home')
        })
        .catch(error => {
          this.loadingState = null
          if (typeof error.response.data === 'object') {
            this.form.errors = _.flatten(_.toArray(error.response.data))
          } else {
            this.form.errors = [this.$t('app.error_try_again')]
          }
        })
    },
  }
}
</script>
