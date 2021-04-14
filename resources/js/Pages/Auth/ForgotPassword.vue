<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />
    </template>

    <div class="fw5 pt5">
      Forgot your password? No problem.
      Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </div>

    <div v-if="status" class="mt3 ba br3 pa3">
      {{ status }}
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
          Email Password Reset Link
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
