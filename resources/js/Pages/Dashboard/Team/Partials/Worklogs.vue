<style lang="scss" scoped>
.worklog-item {
  padding-left: 28px;
  padding-top: 6px;
  padding-right: 10px;
  padding-bottom: 6px;
  border: 1px solid transparent;

  &.selected {
    background-color: #fffaf5;
    border: 1px solid #e6e6e6;
  }

  &.future {
    color: #9e9e9e;

    .dot {
      background-color: #9e9e9e;
    }
  }

  &.current {
    font-weight: 500;
  }
}

.dot {
  background-color: #ff6d67;
  height: 13px;
  width: 13px;
  left: 9px;
  top: 18px;

  @media (max-width: 480px) {
    left: -4px;
    top: 1px;
    position: relative;
  }

  &.yellow {
    background-color: #ffa634;
  }

  &.green {
    background-color: #34c08f;
  }
}

.content {
  background-color: #f3f9fc;
  padding: 4px 10px;
}

.worklog-entry {
  &:not(:first-child) {
    margin-top: 25px;
  }
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5">
      🔨 {{ $t('dashboard.team_worklog_title') }}

      <help :url="$page.props.help_links.worklogs" />
    </div>

    <div v-show="teams.length != 0" class="cf mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <!-- table showing the dates -->
        <div class="flex-ns justify-around pa0 tc mb3 bb bb-gray pb3">
          <div v-for="worklogDate in worklogDates" :key="worklogDate.friendlyDate" class="dib-ns worklog-item relative pointer br2 db" :class="[{ selected: worklogDate == currentWorklogDate }, worklogDate.status]" @click.prevent="load(worklogDate)">
            <span class="dot br-100 dib absolute" :class="worklogDate.completionRate"></span>
            <!-- Display of the day -->
            <span v-show="worklogDate.friendlyDate == currentDate" class="db-ns dib mb1 f6">
              {{ $t('dashboard.team_worklog_today') }}
            </span>
            <span v-show="worklogDate.friendlyDate != currentDate" class="db-ns dib mb1 f6">
              {{ worklogDate.day }}
            </span>

            <!-- date -->
            <span class="db0-ns f7 mb1 dib">
              {{ worklogDate.date }}
            </span>
          </div>
        </div>

        <!-- statistics -->
        <p class="f6 mt0 mb3">
          {{ $t('dashboard.team_worklog_stat') }} <span :class="currentWorklogDate.completionRate">
            {{ currentWorklogDate.numberOfEmployeesWhoHaveLoggedWorklogs }}/{{ currentWorklogDate.numberOfEmployeesInTeam }}
          </span>
        </p>

        <!-- no worklogs yet -->
        <div v-if="localWorklogEntries.length === 0" class="tc mt2">
          <span class="mr1">
            😢
          </span> {{ $t('dashboard.team_worklog_blank') }}
        </div>

        <!-- list of worklogs -->
        <div v-for="worklogEntry in localWorklogEntries" :key="worklogEntry.id" class="worklog-entry bb-gray">
          <!-- name + delete action (if allowed to) -->
          <div class="flex justify-between">
            <small-name-and-avatar
              :name="worklogEntry.name"
              :avatar="worklogEntry.avatar"
            />

            <span v-if="worklogEntry.can_delete_worklog && idToDelete !== worklogEntry.id" class="f6 gray bb b--dotted bt-0 bl-0 br-0 pointer di c-delete" @click="showDeleteMode(worklogEntry.id)">
              {{ $t('app.delete') }}
            </span>
            <span v-if="idToDelete === worklogEntry.id" class="f6">
              {{ $t('app.sure') }}
              <a class="c-delete mr1 pointer" @click.prevent="destroy(worklogEntry.id, worklogEntry.employee_id)">
                {{ $t('app.yes') }}
              </a>
              <a class="pointer" @click.prevent="hideDeleteMode()">
                {{ $t('app.no') }}
              </a>
            </span>
          </div>

          <!-- content -->
          <div class="lh-copy content mt2 br3" v-html="worklogEntry.content">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import Help from '@/Shared/Help';

export default {
  components: {
    SmallNameAndAvatar,
    Help,
  },

  props: {
    company: {
      type: Object,
      default: null,
    },
    teams: {
      type: Array,
      default: () => [],
    },
    worklogDates: {
      type: Array,
      default: () => [],
    },
    worklogEntries: {
      type: Array,
      default: () => [],
    },
    currentDate: {
      type: String,
      default: null,
    },
    currentTeam: {
      type: Number,
      default: null,
    },
  },

  data() {
    return {
      localWorklogEntries: null,
      localCurrentDate: null,
      currentWorklogDate: {},
      deleteMode: false,
      idToDelete: 0,
      form: {
        errors: [],
      },
    };
  },

  created() {
    this.localWorklogEntries = this.worklogEntries;
    this.currentWorklogDate = this.worklogDates.filter(function (item) {
      return (item.status == 'current');
    })[0];

    if (typeof this.currentWorklogDate === 'undefined') {
      // that means we are on either Saturday or Sunday, as it didn't find any
      // other day in the work week. so we need to load the Friday of the current
      // week
      this.currentWorklogDate = this.worklogDates[this.worklogDates.length - 1];
    }
    this.load(this.currentWorklogDate);
  },

  methods: {
    showDeleteMode(id) {
      this.deleteMode = true;
      this.idToDelete = id;
    },

    hideDeleteMode() {
      this.deleteMode = false;
      this.idToDelete = 0;
    },

    load(worklogDate) {
      axios.get(`/${this.company.id}/dashboard/team/${this.currentTeam}/${worklogDate.friendlyDate}`)
        .then(response => {
          this.localWorklogEntries= response.data.worklogEntries;
          this.localCurrentDate = response.data.currentDate;
          this.currentWorklogDate = worklogDate;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(worklogId, employeeId) {
      axios.delete(`/${this.company.id}/dashboard/team/${this.currentTeam}/${worklogId}/${employeeId}`)
        .then(response => {
          this.hideDeleteMode();

          var id = this.localWorklogEntries.findIndex(x => x.id === worklogId);
          this.localWorklogEntries.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    }
  }
};
</script>
