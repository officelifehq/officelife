<template>
  <form-section @submitted="updateLocale">
    <template #title>
      {{ $t('profile.update_locale_title') }}
    </template>

    <template #description>
      {{ $t('profile.update_locale_description') }}
    </template>

    <template #form>
      <select-box v-model="form.locale"
                  :options="localesFiltered"
                  :required="true"
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
import SelectBox from '@/Shared/Select';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
    LoadingButton,
    FormSection,
    SelectBox,
  },

  props: {
    user: {
      type: Object,
      default: null,
    },
    locales: {
      type: Array,
      default: () => [],
    }
  },

  data() {
    return {
      form: useForm({
        locale: '',
      }),
    };
  },

  computed: {
    localesFiltered: function() {
      return _.map(this.locales, locale => {
        return {
          value: locale.lang,
          label: locale['name-orig'] + (locale['name-orig'] !== locale.name ? ' (' + locale.name + ')' : ''),
        };
      });
    }
  },

  mounted() {
    this.form.locale = this.$page.props.auth.user.locale;
  },

  methods: {
    updateLocale() {
      Promise.all([
        this.loadLanguage(this.form.locale, true),
        this.form.post('/locale', {
          preserveScroll: true,
          onSuccess: () => {
            this.flash(this.$t('app.saved'), 'success');
          },
        })
      ]);
    },
  },
};
</script>
