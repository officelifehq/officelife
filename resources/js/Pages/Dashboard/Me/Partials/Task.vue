<style lang="scss" scoped>
.task-item:not(:last-child) {
  margin-bottom: 10px;
}
</style>

<template>
  <div>
    <div class="cf mw7 center mb2 fw5">
      🧯 {{ $t('dashboard.task_title') }}
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box pa3">
      <ul class="list pl0 ma0" data-cy="tasks-list">
        <li v-for="task in tasks" :key="task.id" :data-cy="'task-item-' + task.id" class="task-item">
          <checkbox
            :id="'home'"
            v-model="form.content"
            :datacy="'task-complete-cta'"
            :label="task.title"
            :extra-class-upper-div="'mb0 relative'"
            :classes="'mb0'"
            :required="true"
            @change="updateStatus($event)"
          />
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import Checkbox from '@/Shared/Checkbox';

export default {
  components: {
    Checkbox,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    tasks: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      form: {
        content: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created: function() {},

  methods: {
  }
};
</script>
