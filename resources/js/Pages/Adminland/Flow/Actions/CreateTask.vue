<style lang="scss" scoped>
.action-name {
  width: 160px;
}

.title-menu {
  background-color: #e2f6f9;
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
}
</style>

<template>
  <div class="relative ba bb-gray br3">
    <!-- title and menu -->
    <div class="title-menu">
      <div class="pa3 bb bb-gray fw5">
        Create a task
      </div>
      <destroy-action @destroy="destroyAction(action)" />
    </div>

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
import vClickOutside from 'v-click-outside';
import DestroyAction from '@/Pages/Adminland/Flow/Partials/DestroyAction';
import ActionTextInput from '@/Pages/Adminland/Flow/Partials/ActionTextInput';
import ActionAssignee from '@/Pages/Adminland/Flow/Partials/ActionAssignee';

export default {
  components: {
    DestroyAction,
    ActionTextInput,
    ActionAssignee,
  },

  directives: {
    clickOutside: vClickOutside.directive
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
      deleteActionConfirmation: false,
    };
  },

  mounted() {
    // prevent click outside event with popupItem.
    this.popupItem = this.$el;
  },

  methods: {
    displayConfirmationModal() {
      this.showEveryoneConfirmationModal = true;
      this.displayModal = false;
    },

    updateAssignees(assignee) {
      this.task.assignees.type = assignee.type;
      this.task.assignees.ids = assignee.ids;
    },

    updateTitle(title) {
      this.task.title = title;
    },

    // check if an action is considered "complete". If not, this will prevent
    // the form to be submitted in the parent component.
    checkComplete() {
      if (this.notification.message != '' && this.notification.message != this.$t('account.flow_new_action_label_unknown_message') && this.notification.target) {
        this.notification.complete = true;
      }
    },

    destroyAction() {
      this.$emit('destroy');
    },
  }
};

</script>
