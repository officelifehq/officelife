<style scoped>
.content {
  background-color: #f3f9fc;
  padding: 1px 10px;
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
          <li v-for="worklog in worklogs" :key="worklog.id" class="mb3 relative">
            <p class="f7 mb1">
              {{ worklog.created_at }}
            </p>
            <div class="content" v-html="worklog.content"></div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import 'vue-loaders/dist/vue-loaders.css';
import * as VueLoaders from 'vue-loaders';
Vue.use(VueLoaders);

export default {

  props: {
    company: {
      type: Object,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
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
