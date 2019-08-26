<style scoped>
</style>

<template>
  <div>
    <div class="cf mw7 center mb2 fw5">
      🙃 {{ $t('dashboard.morale_title') }}
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <div class="flex-ns justify-center">
          <a href="" class="btn mr3-ns mb0-ns mb2 dib-l db">😡 I've had a bad day</a>
          <a href="" class="btn mr3-ns mb0-ns mb2 dib-l db">😌 It is normal day</a>
          <a href="" class="btn dib-l db mb0-ns">🥳 Best day ever</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    teams: {
      type: Array,
      default: null,
    },
    worklogCount: {
      type: Number,
      default: 0,
    },
  },

  data() {
    return {
      showEditor: false,
      form: {
        content: null,
        errors: [],
      },
      updatedWorklogCount: 0,
      updatedEmployee: null,
      loadingState: '',
      successMessage: false,
    };
  },

  created: function() {
    this.updatedWorklogCount = this.worklogCount;
    this.updatedEmployee = this.$page.auth.employee;
  },

  methods: {
    updateText(text) {
      this.form.content = text;
    },

    store() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/dashboard/worklog', this.form)
        .then(response => {
          this.$snotify.success(this.$t('dashboard.worklog_success_message'), {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          });
          this.updatedWorklogCount = this.updatedWorklogCount + 1;
          this.updatedEmployee = response.data.data;
          this.showEditor = false;
          this.loadingState = null;
          this.successMessage = true;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};
</script>
