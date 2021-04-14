<template>
  <form-section @submitted="updatePassword">
    <template #title>
      Update Password
    </template>

    <template #description>
      Ensure your account is using a long, random password to stay secure.
    </template>

    <template #form>
      <text-input v-model="form.current_password"
                  :name="'current_password'"
                  :errors="form.errors.current_password"
                  type="password"
                  :label="'Current password'"
                  :required="true"
                  autocomplete="current-password"
      />

      <text-input v-model="form.password"
                  :name="'password'"
                  :errors="form.errors.password"
                  type="password"
                  :label="'New password'"
                  :required="true"
                  autocomplete="new-password"
      />
      <text-input v-model="form.password_confirmation"
                  :name="'password_confirmation'"
                  :errors="form.errors.password_confirmation"
                  type="password"
                  :label="$t('auth.register_password_confirmation')"
                  :required="true"
                  :extra-class-upper-div="'mb4'"
                  autocomplete="new-password"
      />
    </template>

    <template #actions>
      <action-message :on="form.recentlySuccessful" class="mr3">
        Saved.
      </action-message>

      <loading-button class="mb3" :state="form.processing">
        Save
      </loading-button>
    </template>
  </form-section>
</template>

<script>
import ActionMessage from '@/Shared/ActionMessage';
import LoadingButton from '@/Shared/LoadingButton';
import FormSection from '@/Shared/FormSection';
import TextInput from '@/Shared/TextInput';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
    ActionMessage,
    LoadingButton,
    FormSection,
    TextInput,
  },

  data() {
    return {
      form: useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
      }),
    };
  },

  methods: {
    updatePassword() {
      this.form.put(route('user-password.update'), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => this.form.reset(),
        onError: () => {
          if (this.form.errors.password) {
            this.form.reset('password', 'password_confirmation');
            this.$refs.password.focus();
          }

          if (this.form.errors.current_password) {
            this.form.reset('current_password');
            this.$refs.current_password.focus();
          }
        }
      });
    },
  },
};
</script>
