<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />
    </template>

    <div class="fw5 pt5">
      <template v-if="! recovery">
        Please confirm access to your account by entering the authentication code provided by your authenticator application.
      </template>

      <template v-else>
        Please confirm access to your account by entering one of your emergency recovery codes.
      </template>
    </div>

    <validation-errors class="mb-4" />

    <form class="pt2" @submit.prevent="submit">
      <div v-if="! recovery">
        <text-input v-model="form.code"
                    :name="'code'"
                    :errors="$page.props.errors.code"
                    :label="'Code'"
                    :required="true"
                    inputmode="numeric"
                    autofocus
                    autocomplete="one-time-code"
        />
      </div>

      <div v-else>
        <text-input v-model="form.recovery_code"
                    :name="'recovery_code'"
                    :errors="$page.props.errors.recovery_code"
                    :label="'Recovery Code'"
                    :required="true"
                    inputmode="numeric"
                    autocomplete="one-time-code"
        />
      </div>

      <div class="flex-ns justify-between">
        <button type="button" class="mb2 btn w-auto-ns w-100 pv2 ph3" @click.prevent="toggleRecovery">
          <template v-if="! recovery">
            Use a recovery code
          </template>

          <template v-else>
            Use an authentication code
          </template>
        </button>

        <loading-button :class="'add mb2'" :state="form.processing">
          Log in
        </loading-button>
      </div>
    </form>
  </authentication-card>
</template>

<script>
import AuthenticationCard from '@/Shared/Layout/AuthenticationCard';
import AuthenticationCardLogo from '@/Shared/Layout/AuthenticationCardLogo';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import ValidationErrors from '@/Shared/ValidationErrors';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
    AuthenticationCard,
    AuthenticationCardLogo,
    LoadingButton,
    TextInput,
    ValidationErrors,
  },

  data() {
    return {
      recovery: false,
      form: useForm({
        code: '',
        recovery_code: '',
      })
    };
  },

  methods: {
    toggleRecovery() {
      this.recovery ^= true;

      this.$nextTick(() => {
        if (this.recovery) {
          this.$refs.recovery_code.focus();
          this.form.code = '';
        } else {
          this.$refs.code.focus();
          this.form.recovery_code = '';
        }
      });
    },

    submit() {
      this.form.post(this.route('two-factor.login'));
    }
  }
};
</script>
