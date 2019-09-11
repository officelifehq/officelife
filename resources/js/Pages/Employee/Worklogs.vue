<style lang="scss" scoped>
.content {
  background-color: #f3f9fc;
  padding: 1px 10px;
}
.worklog-item:last-child {
  margin-bottom: 0;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="tc db fw5 mb2">🔨 Work logs</span>

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
            <p class="f7 mb1">
              {{ worklog.created_at | moment("dddd, MMMM Do YYYY") }}
            </p>
            <div class="parsed-content" v-html="worklog.parsed_content"></div>
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
