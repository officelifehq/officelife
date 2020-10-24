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
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        🔨
      </span> {{ $t('employee.worklog_title') }}
    </span>

    <!-- LIST OF WORKLOGS -->
    <div class="br3 bg-white box z-1">
      <!-- Blank state -->
      <template v-if="worklogs.length == 0">
        <p class="lh-copy ma0 f6 tc pa3">{{ $t('employee.worklog_blank') }}</p>
      </template>

      <!-- worklogs -->
      <div v-show="worklogs.length != 0" data-cy="list-worklogs">
        <ul class="list mv0 pa3">
          <li v-for="worklog in worklogs.worklogs_collection" :key="worklog.id" class="mb3 relative worklog-item">
            <template v-if="worklog.worklog_parsed_content">
              <div class="parsed-content mb1" v-html="worklog.worklog_parsed_content"></div>
            </template>
            <template v-else>
              <div>
                <p class="i mt0 mb1">
                  {{ $t('employee.worklog_no_worklog') }}
                </p>
              </div>
            </template>
            <ul class="f7 mb1 list pl0">
              <li class="di">
                {{ worklog.friendly_date }}
              </li>
              <li v-if="worklog.morale" class="di">
                – {{ worklog.morale }}
              </li>
            </ul>
          </li>
        </ul>
        <div v-if="permissions.can_see_work_log_history" class="ph3 pv2 tc f6 bt bb-gray">
          <inertia-link :href="worklogs.url" data-cy="view-all-worklogs">{{ $t('employee.worklog_view_all') }}</inertia-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    permissions: {
      type: Object,
      default: null,
    },
    worklogs: {
      type: Object,
      default: null,
    },
  },
};
</script>
