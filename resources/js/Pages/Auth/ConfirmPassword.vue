<template>
  <authentication-card>
    <template #logo>
      <authentication-card-logo />
    </template>

    <div class="fw5 pt5">
      {{ $t('passwords.confirm_password_message') }}
    </div>

    <validation-errors class="mb-4" />

    <form class="pt2" @submit.prevent="submit">
      <text-input v-model="form.password"
                  :name="'password'"
                  :errors="$page.props.errors.password"
                  type="password"
                  :label="$t('auth.register_password')"
                  :required="true"
      />

      <div class="flex items-center justify-end mt-4">
        <loading-button class="ml-4" :class="'add mb4'" :state="form.processing">
          ${{ $t('app.confirm') }}
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

  data() {
    return {
      form: useForm({
        password: '',
      })
    };
  },

  methods: {
    submit() {
      this.form.post(this.route('password.confirm'), {
        onFinish: () => this.form.reset(),
      });
    }
  }
};
</script>
