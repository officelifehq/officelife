<style lang="scss" scoped>
.task-item:not(:last-child) {
  margin-bottom: 10px;
}
</style>

<template>
  <div :class="'mb5'">
    <div class="cf mw7 center mb2 fw5">
      <span class="mr1">
        🧯
      </span> {{ $t('dashboard.task_title') }}
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box pa3">
      <ul v-if="tasks.length > 0" class="list pl0 ma0" data-cy="tasks-list">
        <li v-for="task in tasks" :key="task.id" :data-cy="'task-item-' + task.id" class="task-item">
          <checkbox
            :id="'home'"
            v-model="form.content"
            :datacy="'task-complete-cta'"
            :label="task.title"
            :extra-class-upper-div="'mb0 relative'"
            :class="'mb0'"
            :required="true"
            @update:model-value="updateStatus($event)"
          />
        </li>
      </ul>

      <!-- blank state -->
      <p>You have no active task.</p>
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
};
</script>
