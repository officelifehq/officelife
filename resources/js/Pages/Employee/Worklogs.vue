<style lang="scss" scoped>
.content {
  background-color: #f3f9fc;
  padding: 1px 10px;
}

.worklog-item:last-child {
  margin-bottom: 0;
}

.parsed-content {
  background-color: #f3f9fc;
  padding: 1px 10px;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="tc db fw5 mb2">
      🔨 Work logs
    </span>

    <!-- LIST OF WORKLOGS -->
    <div class="br3 bg-white box z-1 pa3">
      <!-- Blank state -->
      <p v-if="worklogs.length == 0" class="lh-copy ma0 f6 tc">
        {{ $t('employee.worklog_blank') }}
      </p>

      <!-- worklogs -->
      <div v-show="worklogs.length != 0" data-cy="list-worklogs">
        <ul class="list mv0 pa0">
          <li v-for="worklog in worklogs" :key="worklog.id" class="mb3 relative worklog-item">
            <template v-if="worklog.worklog_parsed_content">
              <div class="parsed-content mb1" v-html="worklog.worklog_parsed_content"></div>
            </template>
            <template v-else>
              <div class="parsed-content mb1">
                <p>No log this day</p>
              </div>
            </template>
            <ul class="f7 mb1 list pl0">
              <li class="di">
                {{ worklog.friendly_date }}
              </li>
              <li class="di">
                {{ worklog.morale }}
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    notifications: {
      type: Array,
      default: null,
    },
    worklogs: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
    };
  },

  methods: {
  }
};
</script>
