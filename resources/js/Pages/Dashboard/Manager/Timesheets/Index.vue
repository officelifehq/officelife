<style lang="scss" scoped>
.entry-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.entry-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
  margin-bottom: 20px;
}

.direct-report-item:not(:first-child) {
  margin-top: 30px;
}

.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
  }
}

.top-1 {
  top: 20px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/dashboard/manager'"
                  :previous="$t('app.breadcrumb_dashboard_manager')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_dashboard_manager_timesheets') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <h2 class="pa3 mt2 mb3 center tc normal">
          {{ $t('dashboard.manager_timesheet_index_title') }}

          <help :url="$page.props.help_links.time_tracking" />
        </h2>

        <!-- BLANK STATE -->
        <div v-if="displayBlankState" data-cy="expense-list-blank-state">
          <img loading="lazy" class="db center mb4 mt3" alt="no timesheets to validate" src="/img/streamline-icon-employee-checklist-6@140x140.png" height="80"
               width="80"
          />

          <p class="fw5 mt3 tc">{{ $t('dashboard.manager_timesheet_blank_state') }}</p>
        </div>

        <!-- NOT BLANK STATE :-) -->
        <div v-else>
          <img loading="lazy" src="/img/streamline-icon-employee-checklist-6@140x140.png" width="90" alt="meeting" class="absolute-ns di-ns dn top-1 left-1" />

          <ul class="pl6-ns pl3 pb3 pt3 pr3 ma0">
            <li v-for="directReport in localDirectReports" :key="directReport.id" class="list ma0 direct-report-item">
              <!-- identity -->
              <div class="mb3">
                <span class="pl3 db relative team-member">
                  <avatar :avatar="directReport.avatar" :size="35" :class="'br-100 absolute avatar'" />
                  <inertia-link :href="directReport.url" class="mb2">{{ directReport.name }}</inertia-link>
                  <span class="title db f7 mt1">
                    {{ directReport.position }}
                  </span>
                </span>
              </div>

              <!-- list of timesheets -->
              <ul>
                <li v-for="timesheet in directReport.timesheets" :key="timesheet.id" class="flex justify-between items-center br bl bb bb-gray bb-gray-hover pa3 entry-item">
                  <!-- timesheet info -->
                  <div>
                    <p class="ma0 mb2 f7 grey">{{ timesheet.started_at }} → {{ timesheet.ended_at }}</p>
                    <p class="f4 ma0">{{ timesheet.duration }}</p>
                  </div>

                  <!-- timesheet actions -->
                  <div>
                    <inertia-link :href="timesheet.url" class="mr2 f7">{{ $t('dashboard.manager_timesheet_view_details') }}</inertia-link>
                    <loading-button :class="'btn w-auto-ns w-100 mr2 pv2 ph3'" :state="loadingStateReject" :text="$t('app.reject')" :cypress-selector="'reject-timesheet-' + timesheet.id" @click="reject(timesheet, directReport)" />
                    <loading-button :class="'btn w-auto-ns w-100 mr2 pv2 ph3'" :state="loadingStateApprove" :text="$t('app.approve')" :cypress-selector="'approve-timesheet-' + timesheet.id" @click="approve(timesheet, directReport)" />
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';
import Help from '@/Shared/Help';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    Help,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    directReports: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      loadingStateApprove: '',
      loadingStateReject: '',
      localDirectReports: null,
    };
  },

  computed: {
    displayBlankState() {
      if (! this.localDirectReports) {
        return false;
      }

      if (this.localDirectReports.length == 0) {
        return true;
      }

      return false;
    }
  },

  mounted() {
    this.localDirectReports = this.directReports;
  },

  methods: {
    approve(timesheet, directReport) {
      this.loadingStateApprove = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/dashboard/manager/timesheets/${timesheet.id}/approve`)
        .then(response => {
          this.flash(this.$t('dashboard.manager_timesheet_approved'), 'success');
          this.removeEntry(timesheet, directReport);
          this.loadingStateApprove = '';
        })
        .catch(error => {
          this.loadingStateApprove = null;
          this.form.errors = error.response.data;
        });
    },

    reject(timesheet, directReport) {
      this.loadingStateReject = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/dashboard/manager/timesheets/${timesheet.id}/reject`)
        .then(response => {
          this.flash(this.$t('dashboard.manager_timesheet_rejected'), 'success');
          this.removeEntry(timesheet, directReport);
          this.loadingStateReject = '';
        })
        .catch(error => {
          this.loadingStateReject = null;
          this.form.errors = error.response.data;
        });
    },

    removeEntry(timesheet, directReport) {
      var id = this.localDirectReports.findIndex(x => x.id == directReport.id);
      var timesheetId = this.localDirectReports[id].timesheets.findIndex(x => x.id == timesheet.id);
      this.localDirectReports[id].timesheets.splice(timesheetId, 1);

      // make sure that the direct report disappears from the list if all the timesheets have been approved
      if (this.localDirectReports[id].timesheets.length == 0) {
        this.localDirectReports.splice(id, 1);
      }
    }
  },
};
</script>
