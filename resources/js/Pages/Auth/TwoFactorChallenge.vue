<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />
    </template>

    <div class="mb-4 text-sm text-gray-600">
      <template v-if="! recovery">
        Please confirm access to your account by entering the authentication code provided by your authenticator application.
      </template>

      <template v-else>
        Please confirm access to your account by entering one of your emergency recovery codes.
      </template>
    </div>

    <validation-errors class="mb-4" />

    <form @submit.prevent="submit">
      <div v-if="! recovery">
        <input-label for="code" value="Code" />
        <text-input id="code" ref="code" v-model="form.code" type="text" inputmode="numeric"
                    class="mt-1 block w-full" autofocus autocomplete="one-time-code"
        />
      </div>

      <div v-else>
        <input-label for="recovery_code" value="Recovery Code" />
        <text-input id="recovery_code" ref="recovery_code" v-model="form.recovery_code" type="text" class="mt-1 block w-full"
                    autocomplete="one-time-code"
        />
      </div>

      <div class="flex items-center justify-end mt-4">
        <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer" @click.prevent="toggleRecovery">
          <template v-if="! recovery">
            Use a recovery code
          </template>

          <template v-else>
            Use an authentication code
          </template>
        </button>

        <loading-button :classes="['btn add w-auto-ns w-100 mb2 pv2 ph3', { 'opacity-25' : form.processing }]" :state="form.processing ? 'loading' : ''" :disabled="form.processing">
          Log in
        </loading-button>
      </div>
    </form>
  </authentication-card>
</template>

<script>
import AuthenticationCard from '@/Shared/AuthenticationCard';
import AuthenticationCardLogo from '@/Shared/AuthenticationCardLogo';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import InputLabel from '@/Shared/Label';
import ValidationErrors from '@/Shared/ValidationErrors';

export default {
  components: {
    AuthenticationCard,
    AuthenticationCardLogo,
    LoadingButton,
    TextInput,
    InputLabel,
    ValidationErrors,
  },

  data() {
    return {
      recovery: false,
      form: this.$inertia.form({
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
