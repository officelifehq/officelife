<style scoped>
</style>

<template>
  <div>
    <div class="cf mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <h2 class="mt0 fw5 f4">
          🔨 {{ $t('dashboard.worklog_title') }}
        </h2>
        <p v-show="!showEditor && !updatedEmployee.has_logged_worklog_today" class="db">
          <span class="dib-ns db mb0-ns mb2">{{ $t('dashboard.worklog_placeholder') }}</span>
          <a v-show="updatedWorklogCount != 0" class="ml2-ns pointer">{{ $t('dashboard.worklog_read_previous_entries') }}</a>
        </p>
        <p v-show="!showEditor && updatedEmployee.has_logged_worklog_today" class="db mb0">
          <span class="dib-ns db mb0-ns mb2">{{ $t('dashboard.worklog_already_logged') }}</span>
          <a v-show="updatedWorklogCount != 0" class="ml2-ns pointer">{{ $t('dashboard.worklog_read_previous_entries') }}</a>
        </p>
        <p v-show="!showEditor && !updatedEmployee.has_logged_worklog_today" class="ma0">
          <a class="btn btn-secondary dib" @click.prevent="showEditor = true">{{ $t('dashboard.worklog_cta') }}</a>
        </p>

        <!-- Shows the editor -->
        <div v-show="showEditor" class="">
          <form @submit.prevent="store()">
            <editor @update="updateText($event)" />
            <p class="db lh-copy f6">
              👋 {{ $t('dashboard.worklog_entry_description') }}
            </p>
            <p>
              <loading-button :classes="'btn primary w-auto-ns w-100 mb2 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.save')" />
              <a class="pointer" @click.prevent="showEditor = false">{{ $t('app.cancel') }}</a>
            </p>
          </form>
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
      loadingState: '',
    };
  },

  created: function() {
    this.updatedWorklogCount = this.worklogCount;
    this.updatedEmployee = this.employee;
  },

  methods: {
    updateText(text) {
      this.form.content = text;
    },

    store() {
      this.loadingState = 'loading';

      axios.post('/' + this.company.id + '/dashboard/worklog', this.form)
        .then(response => {
          this.$snotify.success(this.$t('dashboard.worklog_added'), {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          });

          this.updatedWorklogCount = this.updatedWorklogCount + 1;
          this.updatedEmployee = response.data.data;
          this.showEditor = false;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};
</script>
