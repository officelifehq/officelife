<template>
  <form-section @submitted="update">
    <template #title>
      {{ $t('employee.edit_information_timezone') }}
    </template>

    <template #description>
      {{ $t('employee.edit_information_timezone_help') }}
    </template>

    <template #form>
      <select-box v-model="form.timezone"
                  :options="timezones"
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
  },

  data() {
    return {
      form: useForm({
        timezone: '',
      }),
      timezones: [],
    };
  },

  mounted() {
    this.form.timezone = this.$page.props.auth.user.timezone;
    axios.get(this.route('user-timezone.index'))
      .then(response => {
        this.timezones = response.data;
      });
  },

  methods: {
    update() {
      this.form.put(route('user-timezone.update'), {
        errorBag: 'updateTimezone',
        preserveScroll: true,
        onSuccess: () => {
          this.flash(this.$t('app.saved'), 'success');
        },
      });
    },
  },
};
</script>
