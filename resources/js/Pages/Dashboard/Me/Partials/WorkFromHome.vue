<template>
  <div>
    <div class="cf mw7 center mb2 fw5">
      🏡 {{ $t('dashboard.work_from_home_title') }}
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <errors :errors="form.errors" :classes="'mb2'" />

        <checkbox
          :id="'home'"
          v-model="form.content"
          :datacy="'log-from-work-home-cta'"
          :label="$t('dashboard.work_from_home_cta')"
          :extra-class-upper-div="'mb0'"
          :required="true"
          @change="updateStatus($event)"
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

export default {
  components: {
    Checkbox,
    Errors
  },

  props: {
    employee: {
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
      updatedEmployee: null,
      successMessage: false,
    };
  },

  created: function() {
    this.updatedEmployee = this.employee;
    this.form.content = this.updatedEmployee.has_worked_from_home_today;
  },

  methods: {
    updateStatus(payload) {
      this.form.errors = [];
      this.form.content = payload;

      axios.post('/' + this.$page.auth.company.id + '/dashboard/workFromHome', this.form)
        .then(response => {
          flash(this.$t('dashboard.work_from_home_success'), 'success');
        })
        .catch(error => {
          this.successMessage = false;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};
</script>
