<template>
  <form-section @submitted="updateLocale">
    <template #title>
      {{ $t('profile.update_locale_title') }}
    </template>

    <template #description>
      {{ $t('profile.update_locale_description') }}
    </template>

    <template #form>
      <a-select
        v-model:value="form.locale"
        :placeholder="$t('dashboard.timesheet_create_choose_project')"
        style="width: 200px;"
        :options="localesFiltered"
        show-search
        option-filter-prop="label"
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
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
    LoadingButton,
    FormSection,
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
      this.loadLanguage(this.form.locale, true).then(() => {
        this.form.post('/locale', {
          preserveScroll: true,
          onSuccess: () => {
            this.flash(this.$t('app.saved'), 'success');
          },
        });
      });
    },
  },
};
</script>
