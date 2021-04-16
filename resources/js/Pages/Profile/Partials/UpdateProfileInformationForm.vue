<template>
  <form-section @submitted="updateProfileInformation">
    <template #title>
      Profile Information
    </template>

    <template #description>
      Update your account's profile information and email address.
    </template>

    <template #form>
      <!-- Email -->
      <text-input v-model="form.email"
                  :name="'email'"
                  :errors="form.errors.email"
                  :label="$t('auth.login_email')"
                  :required="true"
                  :type="'email'"
                  :autofocus="true"
      />
    </template>

    <template #actions>
      <loading-button class="add mb3" :state="form.processing">
        Save
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

  props: {
    user: {
      type: Object,
      default: null,
    }
  },

  data() {
    return {
      form: useForm({
        _method: 'PUT',
        name: this.user.name,
        email: this.user.email,
        photo: null,
      }),

    };
  },

  methods: {
    updateProfileInformation() {
      if (this.$refs.photo) {
        this.form.photo = this.$refs.photo.files[0];
      }

      this.form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => {
          this.flash('Change saved.', 'success');
        }
      });
    },

    selectNewPhoto() {
      this.$refs.photo.click();
    },

    updatePhotoPreview() {
      const reader = new FileReader();

      reader.onload = (e) => {
        this.photoPreview = e.target.result;
      };

      reader.readAsDataURL(this.$refs.photo.files[0]);
    },

    deletePhoto() {
      this.$inertia.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => (this.photoPreview = null),
      });
    },
  },
};
</script>
