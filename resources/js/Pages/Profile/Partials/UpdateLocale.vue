<template>
  <form-section @submitted="updateLocale">
    <template #title>
      {{ $t('profile.update_locale_title') }}
    </template>

    <template #description>
      {{ $t('profile.update_locale_description') }}
    </template>

    <template #form>
      <select v-model="form.locale">
        <option value="en">
          {{ $t('app.locale_en') }}
        </option>
        <option value="fr">
          {{ $t('app.locale_fr') }}
        </option>
      </select>
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

export default {
  components: {
    LoadingButton,
    FormSection,
  },

  props: {
    user: {
      type: Object,
      default: null,
    }
  },

  data() {
    return {
      form: {
        locale: null,
      },
      loadingState: '',
    };
  },

  mounted() {
    this.form.locale = this.$page.props.auth.user.locale;
  },

  methods: {
    updateLocale() {
      axios.post('/locale', this.form)
        .then(response => {
          this.flash(this.$t('app.saved'), 'success');

          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
        });
    },
  },
};
</script>
