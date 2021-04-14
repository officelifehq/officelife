<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        🏡
      </span> {{ $t('dashboard.work_from_home_title') }}

      <help :url="$page.props.help_links.work_from_home" :datacy="'help-icon-work-from-home'" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <div class="pa3 relative">
        <errors :errors="form.errors" :class="'mb2'" />

        <checkbox
          :id="'home'"
          v-model="form.content"
          :datacy="'log-from-work-home-cta'"
          :label="$t('dashboard.work_from_home_cta')"
          :extra-class-upper-div="'mb0 relative'"
          :required="true"
          @update:model-value="updateStatus($event)"
        />

        <p class="f7 mv0 silver">
          {{ $t('dashboard.work_from_home_help') }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import Checkbox from '@/Shared/Checkbox';
import Errors from '@/Shared/Errors';
import Help from '@/Shared/Help';

export default {
  components: {
    Checkbox,
    Errors,
    Help,
  },

  props: {
    workFromHome: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        content: Boolean,
        errors: [],
      },
      successMessage: false,
    };
  },

  created: function() {
    this.form.content = this.workFromHome.has_worked_from_home_today;
  },

  methods: {
    updateStatus(payload) {
      this.form.errors = [];
      this.form.content = payload;

      axios.post(`${this.$page.props.auth.company.id}/dashboard/workFromHome`, this.form)
        .then(response => {
          this.flash(this.$t('dashboard.work_from_home_success'), 'success');
        })
        .catch(error => {
          this.successMessage = false;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
