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
      <ul class="pl0 ml0 list">
        <!-- Name of the task -->
        <li class="flex mb3">
          <div class="dib mr3 fw6 action-name">Task name:</div>
          <action-text-input
            :placeholders="'{{employee_name}}'"
          />
        </li>

        <!-- Assignee -->
        <li class="flex">
          <div class="dib mr3 fw6 action-name">Assign the task to:</div>
          <action-assignee />
        </li>
      </ul>
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
      who: '',
      message: '',
      updatedMessage: '',
      notification: {
        id: 0,
        type: '',
        target: '',
        employeeId: 0,
        teamId: 0,
        message: '',
        complete: false,
      },
      form: {
        searchTerm: null,
        errors: [],
      },
      processingSearch: false,
      searchEmployees: [],
      searchTeams: [],
      displayModal: false,
      actionsModal: false,
      showEveryoneConfirmationModal: false,
      showSeachEmployeeModal: false,
      showSeachTeamModal: false,
      showEditMessage: false,
      deleteActionConfirmation: false,
    };
  },

  computed: {
    charactersLeft() {
      var char = this.updatedMessage.length, limit = 255;

      return 'Characters remaining: ' + (limit - char) + ' / ' + limit;
    }
  },

  mounted() {
    // prevent click outside event with popupItem.
    this.popupItem = this.$el;

    // this.notification = this.action;
    // this.who = 'an employee';
    // this.setMessage(this.$t('account.flow_new_action_label_unknown_message'));
  },

  methods: {
    displayConfirmationModal() {
      this.showEveryoneConfirmationModal = true;
      this.displayModal = false;
    },

    displayEmployeeSearchBox() {
      this.displayModal = false;
      this.showSeachEmployeeModal = true;
    },

    displayTeamSearchBox() {
      this.displayModal = false;
      this.showSeachTeamModal = true;
    },

    displayEditMessageTextarea() {
      if (this.notification.message == this.$t('account.flow_new_action_label_unknown_message')) {
        this.updatedMessage = '';
      } else {
        this.updatedMessage = this.notification.message;
      }
      this.showEditMessage = true;
    },

    toggleModals() {
      this.showEveryoneConfirmationModal = false;
      this.displayModal = false;
      this.showSeachEmployeeModal = false;
      this.showSeachTeamModal = false;
      this.actionsModal = false;
      this.showEditMessage = false;
    },

    // check if an action is considered "complete". If not, this will prevent
    // the form to be submitted in the parent component.
    checkComplete() {
      if (this.notification.message != '' && this.notification.message != this.$t('account.flow_new_action_label_unknown_message') && this.notification.target) {
        this.notification.complete = true;
      }
    },

    setTarget(target) {
      this.notification.target = target;
      this.toggleModals();

      switch(target) {
      case 'actualEmployee':
        this.who = this.$t('account.flow_new_action_label_actual_employee');
        break;
      case 'everyone':
        this.who = this.$t('account.flow_new_action_label_everyone');
        break;
      case 'managers':
        this.who = this.$t('account.flow_new_action_label_managers');
        break;
      case 'directReports':
        this.who = this.$t('account.flow_new_action_label_reports');
        break;
      case 'employeeTeam':
        this.who = this.$t('account.flow_new_action_label_team_employee');
        break;
      case 'specificTeam':
        break;
      case 'specificEmployee':
        break;
      default:
        this.who = this.$t('account.flow_new_action_label_employee');
      }

      this.checkComplete();
      this.$emit('update', this.notification);
    },

    searchEmployee: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post('/search/employees/', this.form)
            .then(response => {
              this.searchEmployees = response.data.data;
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
            });
        }
      }, 500),

    searchTeam: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post('/search/teams/', this.form)
            .then(response => {
              this.searchTeams = response.data.data;
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
            });
        }
      }, 500),

    assignEmployee(employee) {
      this.notification.employeeId = employee.id;
      this.who = employee.name;
      this.setTarget('specificEmployee');
      this.toggleModals();
    },

    assignTeam(team) {
      this.notification.teamId = team.id;
      this.who = team.name;
      this.setTarget('specificTeam');
      this.toggleModals();
    },

    destroyAction() {
      this.$emit('destroy');
    },

    setMessage(message) {
      if (message == '') {
        this.notification.message = this.$t('account.flow_new_action_label_unknown_message');
      } else {
        this.notification.message = message;
      }
      this.message = this.notification.message;
      this.toggleModals();
      this.checkComplete();
      this.$emit('update', this.notification);
    }
  }
};

</script>
