<template>
  <div class="ph2 ph0-ns">
    <div class="cf mt3 mw6 center tc">
      <h2 class="lh-title">
        {{ $t('auth.invitation_unlogged_title', { name: company.name }) }}
      </h2>
      <p v-show="!displayCreateAccount && !displaySignin">
        {{ $t('auth.invitation_unlogged_desc') }}
      </p>
      <p v-show="displayCreateAccount">
        <a class="pointer" @click="displaySignin = true; displayCreateAccount = false">&larr; {{ $t('auth.invitation_unlogged_create_account_instead') }}</a>
      </p>
      <p v-show="displaySignin">
        <a class="pointer" @click="displayCreateAccount = true; displaySignin = false">&larr; {{ $t('auth.invitation_unlogged_login_instead') }}</a>
      </p>
    </div>

    <!-- LINKS TO SWITCH BETWEEN SIGNIN/LOGIN -->
    <div v-show="!displayCreateAccount && !displaySignin" class="cf mt3 mw6 center br3 mb3 bg-white box pa3 pointer" @click="displayCreateAccount = true">
      <p class="fw5">
        {{ $t('auth.invitation_unlogged_choice_account_title') }}
      </p>
      <p>{{ $t('auth.invitation_unlogged_choice_account_desc') }}</p>
    </div>

    <div v-show="!displayCreateAccount && !displaySignin" class="cf mt3 mw6 center br3 mb3 bg-white box pa3 pointer" @click="displaySignin = true">
      <p class="fw5">
        {{ $t('auth.invitation_unlogged_choice_login_title') }}
      </p>
      <p>{{ $t('auth.invitation_unlogged_choice_login_desc') }}</p>
    </div>

    <!-- CREATE AN ACCOUNT -->
    <div v-show="displayCreateAccount" class="cf mw6 center br3 mb3 bg-white box">
      <div class="pa3">
        <h2 class="tc f4">
          {{ $t('auth.invitation_unlogged_choice_account') }}
        </h2>
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

    <!-- LOGIN TO EXISTING ACCOUNT -->
    <div v-show="displaySignin" class="cf mw6 center br3 mb3 bg-white box">
      <div class="pa3">
        <h2 class="tc f4">
          {{ $t('auth.invitation_unlogged_choice_login') }}
        </h2>

        <!-- Form Errors -->
        <errors :errors="form.errors" />

        <form @submit.prevent="submit">
          <!-- Email -->
          <div class="">
            <label class="db fw4 lh-copy f6" for="email">{{ $t('auth.register_email') }}</label>
            <input v-model="form.email" type="email" name="email" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required />
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
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.login_cta')" />
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
  props: {
    company: {
      type: Object,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    invitationLink: {
      type: String,
      default: '',
    },
  },

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

      axios.post('/invite/employee/' + this.invitationLink + '/join', this.form)
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
