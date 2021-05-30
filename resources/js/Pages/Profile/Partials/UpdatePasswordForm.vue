<template>
  <form-section @submitted="updatePassword">
    <template #title>
      {{ $t('profile.update_password_title') }}
    </template>

    <template #description>
      {{ $t('profile.update_password_description') }}
    </template>

    <template #form>
      <text-input v-model="form.current_password"
                  :name="'current_password'"
                  :errors="form.errors.current_password"
                  type="password"
                  :label="$t('profile.update_password_current_password')"
                  :required="true"
                  autocomplete="current-password"
      />

      <text-input v-model="form.password"
                  :name="'password'"
                  :errors="form.errors.password"
                  type="password"
                  :label="$t('profile.update_password_new_password')"
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
      <loading-button class="add mb3" :state="form.processing">
        {{ $t('app.save') }}
      </loading-button>
    </template>
  </form-section>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';
import FormSection from '@/Shared/Layout/FormSection';
import TextInput from '@/Shared/TextInput';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
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
        onSuccess: () => {
          this.flash(this.$t('app.saved'), 'success');
          this.form.reset();
        },
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
