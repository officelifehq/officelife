<style scoped>
</style>

<template>
  <div>
    <div class="cf mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <h2 class="mt0 fw5 f4">
          👨‍🌾 What have you done today?
        </h2>
        <p v-show="!showEditor && !updatedEmployee.has_logged_worklog_today" class="db">
          Let your coworkers know what you've been up to today.
          <a v-show="updatedWorklogCount != 0" href="" class="ml2">Read your previous entries</a>
        </p>
        <p v-show="!showEditor && updatedEmployee.has_logged_worklog_today" class="db mb0">
          You have already logged your work today.
          <a v-show="updatedWorklogCount != 0" href="" class="ml2">Read your previous entries</a>
        </p>
        <p v-show="!showEditor && !updatedEmployee.has_logged_worklog_today" class="ma0">
          <a class="btn btn-secondary dib" @click.prevent="showEditor = true">Log your work</a>
        </p>

        <!-- Shows the editor -->
        <div v-show="showEditor" class="">
          <editor @update="updateText($event)" />
          <p class="db lh-copy f6">
            👋 Your manager and your team members (if you are assigned to a team) will be able to read this status. Also, you won't be able to edit this status once it’s submitted.
          </p>
          <p>
            <a class="btn primary mr2" @click.prevent="store()">Submit</a>
            <a class="pointer" @click.prevent="showEditor = false">Cancel</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    company:  {
      type: Object,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
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
    };
  },

  mounted: function() {
    this.updatedWorklogCount = this.worklogCount;
    this.updatedEmployee = this.employee;
  },

  methods: {
    updateText(text) {
      this.form.content = text;
    },

    store() {
      axios.post('/' + this.company.id + '/dashboard/worklog', this.form)
        .then(response => {
          this.$snotify.success(this.$t('employee.position_modal_assign_success'), {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          });

          this.updatedWorklogCount = this.updatedWorklogCount + 1;
          this.updatedEmployee = response.data.data;
          this.showEditor = false;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};
</script>
