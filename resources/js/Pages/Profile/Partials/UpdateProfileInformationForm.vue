<template>
  <form-section @submitted="updateProfileInformation">
    <template #title>
      {{ $t('profile.update_profile_title') }}
    </template>

    <template #description>
      {{ $t('profile.update_profile_description') }}
    </template>

    <template #form>
      <!-- First name -->
      <text-input v-model="form.first_name"
                  :name="'first_name'"
                  :errors="form.errors.first_name"
                  :label="$t('employee.edit_information_firstname')"
                  :required="true"
                  :autofocus="true"
      />

      <!-- Last name -->
      <text-input v-model="form.last_name"
                  :name="'last_name'"
                  :errors="form.errors.last_name"
                  :label="$t('employee.edit_information_lastname')"
                  :required="true"
                  :autofocus="true"
      />

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
        first_name: this.user.first_name,
        last_name: this.user.last_name,
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
          this.flash(this.$t('app.saved'), 'success');
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
