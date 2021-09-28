<style lang="scss" scoped>
.action-name {
  width: 160px;
}
</style>

<template>
  <div class="relative ba bb-gray br3">
    <!-- title and menu -->
    <action-header :title="'Create a task'" :is-complete="task.complete" @destroy="destroyAction" />

    <div class="pa3">
      <div class="">
        <!-- Name of the task -->
        <div class="cf mb3">
          <div class="fl w-third dib mr3 fw6 action-name">
            Task name:
          </div>

          <action-text-input
            :placeholders="'{{employee_name}}'"
            @update="updateTitle($event)"
          />
        </div>

        <!-- Assignee -->
        <div class="cf">
          <div class="fl w-third  dib mr3 fw6 action-name">
            Assign the task to:
          </div>

          <action-assignee @update="updateAssignees($event)" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ActionTextInput from '@/Pages/Adminland/Flow/Partials/ActionTextInput';
import ActionAssignee from '@/Pages/Adminland/Flow/Partials/ActionAssignee';
import ActionHeader from '@/Pages/Adminland/Flow/Partials/ActionHeader';

export default {
  components: {
    ActionTextInput,
    ActionAssignee,
    ActionHeader,
  },

  props: {
    action: {
      type: Object,
      default: null,
    }
  },

  emits: [
    'update', 'destroy'
  ],

  data() {
    return {
      task: {
        title: '',
        assignees: {
          type: '',
          ids: [],
        },
        complete: false,
      },
    };
  },

  methods: {
    updateAssignees(assignee) {
      this.task.assignees.type = assignee.type;
      this.task.assignees.ids = assignee.ids;
      this.checkComplete();
    },

    updateTitle(title) {
      this.task.title = title;
      this.checkComplete();
    },

    // check if an action is considered "complete". If not, this will prevent
    // the form to be submitted in the parent component.
    checkComplete() {
      this.task.complete = false;

      if (this.task.title != '' && this.task.assignees.type != '') {
        this.task.complete = true;
      }
    },

    destroyAction() {
      this.$emit('destroy');
    },
  }
};

</script>
