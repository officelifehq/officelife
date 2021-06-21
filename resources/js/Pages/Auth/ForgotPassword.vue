<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />
    </template>

    <div class="fw5 pt5">
      {{ $t('passwords.forgot_password_message') }}
    </div>

    <div v-if="status" class="mt3 ba br3 pa3 bb-gray">
      <span class="mr1">
        ✅
      </span> {{ status }}
    </div>

    <validation-errors class="mb-4" />

    <form class="pt2" @submit.prevent="submit">
      <text-input v-model="form.email"
                  :name="'email'"
                  :errors="$page.props.errors.email"
                  :label="$t('auth.login_email')"
                  :required="true"
                  :type="'email'"
                  :autofocus="true"
      />

      <div class="flex items-center justify-end mt-4">
        <loading-button :class="'add mb2'" :state="form.processing" :disabled="form.processing">
          {{ $t('passwords.forgot_password_action') }}
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
    ValidationErrors
  },

  props: {
    status: {
      type: String,
      default: null,
    }
  },

  data() {
    return {
      form: useForm({
        email: ''
      })
    };
  },

  methods: {
    submit() {
      this.form.post(this.route('password.email'));
    }
  }
};
</script>
