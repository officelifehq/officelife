<style lang="scss" scoped>
.content {
  background-color: #f3f9fc;
  padding: 1px 10px;
}

.worklog-item:last-child {
  margin-bottom: 0;
}

.parsed-content {
  p:last-child {
    margin-bottom: 0;
  }
}

.week-item {
  &:last-child {
    border-right: 0;
  }

  &.selected {
    border-bottom: 0;
  }
}

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

  &.green {
    background-color: #34c08f;
  }
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        🔨
      </span> {{ $t('employee.worklog_title') }}

      <help :url="$page.props.help_links.worklogs" />
    </span>

    <!-- LIST OF WORKLOGS -->
    <div class="br3 bg-white box z-1">
      <!-- Blank state -->
      <template v-if="localWorklog.length == 0">
        <p class="lh-copy ma0 f6 tc pa3">{{ $t('employee.worklog_blank') }}</p>
      </template>

      <!-- worklogs -->
      <div>
        <!-- weeks -->
        <div class="cf w-100">
          <div v-for="week in weeks" :key="week.id" :class="week.start_of_week_date == localWorklog.current_week ? 'selected' : ''" class="pointer tc fl w-25 pa3 bb bb-gray br week-item" @click="getWeek(week)">
            <span class="db mb2">
              {{ week.label }}
            </span>
            <span class="f7 gray">
              {{ week.range.start }} → {{ week.range.end }}
            </span>
          </div>
        </div>

        <!-- days -->
        <div class="flex-ns justify-around pa0 tc mb3 pb3 pt3">
          <div v-for="day in localWorklog.days" :key="day.id" :class="day.selected + ' ' + day.status" class="dib-ns worklog-item relative pointer br2 db ba bb-gray" @click="get(day)">
            <span class="dot br-100 dib absolute" :class="day.worklog_done_for_this_day"></span>
            <span class="db f6 mb1">
              {{ day.day }}
            </span>
            <span class="f7">
              ({{ day.day_number }})
            </span>
          </div>
        </div>

        <!-- content -->
        <div v-if="localWorklog.worklog_parsed_content" class="parsed-content pa3 w-70 center" v-html="localWorklog.worklog_parsed_content"></div>
        <div v-if="localWorklog.worklog_parsed_content && permissions.can_delete_worklog" class="tc pb3">
          <a class="f6 gray bb b--dotted bt-0 bl-0 br-0 pointer di c-delete" href="#" @click.prevent="destroy(localWorklog.id)">{{ $t('app.delete') }}</a>
        </div>

        <!-- case of no content for the day -->
        <div v-if="!localWorklog.worklog_parsed_content" class="tc pa3">
          {{ $t('employee.worklog_blank') }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';

export default {
  components: {
    Help,
  },

  props: {
    permissions: {
      type: Object,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    worklog: {
      type: Object,
      default: null,
    },
    weeks: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      localWorklog: false,
    };
  },

  mounted() {
    this.localWorklog = this.worklog;
  },

  methods: {
    get(worklog) {
      axios.get(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/work/worklogs/week/${this.localWorklog.current_week}/day/${worklog.date}`)
        .then(response => {
          this.localWorklog = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    getWeek(week) {
      axios.get(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/work/worklogs/week/${week.start_of_week_date}/day`)
        .then(response => {
          this.localWorklog = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(worklogId) {
      axios.delete(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/work/worklogs/${worklogId}`)
        .then(response => {
          this.localWorklog.worklog_parsed_content = null;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    }
  }
};
</script>
