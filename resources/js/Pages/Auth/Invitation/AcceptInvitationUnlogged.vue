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
        <inertia-link class="pointer" @click="displaySignin = true; displayCreateAccount = false">
          &larr; {{ $t('auth.invitation_unlogged_create_account_instead') }}
        </inertia-link>
      </p>
      <p v-show="displaySignin">
        <inertia-link class="pointer" @click="displayCreateAccount = true; displaySignin = false">
          &larr; {{ $t('auth.invitation_unlogged_login_instead') }}
        </inertia-link>
      </p>
    </div>

    <!-- LINKS TO SWITCH BETWEEN SIGNIN/LOGIN -->
    <div v-show="!displayCreateAccount && !displaySignin" class="cf mt3 mw6 center br3 mb3 bg-white box pa3 pointer" data-cy="accept-create-account" @click="displayCreateAccount = true">
      <p class="fw5">
        {{ $t('auth.invitation_unlogged_choice_account_title') }}
      </p>
      <p>{{ $t('auth.invitation_unlogged_choice_account_desc') }}</p>
    </div>

    <div v-show="!displayCreateAccount && !displaySignin" class="cf mt3 mw6 center br3 mb3 bg-white box pa3 pointer" data-cy="accept-login-account" @click="displaySignin = true">
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

        <form @submit.prevent="submit">
          <!-- Form Errors -->
          <errors :errors="form.errors" />

          <!-- Email -->
          <text-input :id="'email'"
                      v-model="form.email"
                      :type="'email'"
                      :errors="$page.props.errors.email"
                      :label="$t('auth.register_email')"
                      :help="$t('auth.register_email_help')"
          />

          <!-- Password -->
          <text-input :id="'password'"
                      v-model="form.password"
                      :type="'password'"
                      :name="'password'"
                      :errors="$page.props.errors.password"
                      :label="$t('auth.register_password')"
          />

          <!-- Actions -->
          <div class="">
            <div class="flex-ns justify-between">
              <div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'create-cta'" :state="loadingState" :text="$t('auth.register_cta')" />
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
          <text-input :id="'email'" v-model="form.email" :type="'email'" :errors="$page.props.errors.email" :label="$t('auth.register_email')" />

          <!-- Password -->
          <text-input :id="'password'"
                      v-model="form.password"
                      :type="'password'"
                      :errors="$page.props.errors.password"
                      :label="$t('auth.register_password')"
          />

          <!-- Actions -->
          <div class="">
            <div class="flex-ns justify-between">
              <div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.login_cta')" />
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';
import Errors from '@/Shared/Errors';
import TextInput from '@/Shared/TextInput';

export default {
  components: {
    TextInput,
    LoadingButton,
    Errors,
  },

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
    };
  },

  mounted() {
    this.form.email = this.employee.email;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('invitation.join', this.invitationLink), this.form)
        .then(response => {
          this.$inertia.visit('/home');
        })
        .catch(error => {
          this.loadingState = null;
          if (typeof error.response.data === 'object') {
            this.form.errors = error.response.data;
          } else {
            this.form.errors = [this.$t('app.error_try_again')];
          }
        });
    },
  }
};
</script>
