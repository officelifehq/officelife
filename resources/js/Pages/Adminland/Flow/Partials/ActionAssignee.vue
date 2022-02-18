<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';

.icon-pen {
  color: #acaa19;
  width: 16px;
  top: 2px;
}

.ball-pulse {
  right: 8px;
  top: 10px;
  position: absolute;
}

.employee-search-item:first-child {
  border-top: 1px solid #dae1e7;
}

.employee-list {
  border-bottom: 1px solid #dae1e7;
  li:last-child {
    border-bottom: 0;
  }
}
</style>

<template>
  <div class="fl w-two-thirds">
    <!-- when a choice has been made -->
    <div v-if="assigneeChosen" class="mt0 mb0 relative" @click="displayAllChoices()">
      {{ assigneeSentence }}

      <svg class="ml2 icon-pen relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
      </svg>
    </div>

    <!-- list of employees that are assigned (if applicable) -->
    <ul v-if="assignees.ids.length > 0" class="list ma0 mt2 mb2 pa0 bt br bl bb-gray br3 employee-list">
      <li v-for="employee in assignees.ids" :key="employee.id" class="ph3 pv2 relative bb bb-gray-hover bb-gray">
        {{ employee.name }}
        <a class="absolute right-1 pointer" @click="removeAssignee(employee)">
          {{ $t('app.remove') }}
        </a>
      </li>
      <li class="ph3 pv2"><a class="f6 bb b--dotted bt-0 bl-0 br-0 pointer" @click="displayEmployeeSearchBox">Add another employee</a></li>
    </ul>

    <!-- initial choice: who should be assigned -->
    <p v-if="stageInitialChoice" class="mt0 mb0 relative i gray" @click="displayAllChoices()">
      Who should be assigned?

      <svg class="ml2 icon-pen relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
      </svg>
    </p>

    <!-- edit mode -->
    <div v-if="showAllChoices">
      <!-- list of possible assignees -->
      <ul class="list ma0 pa0">
        <li class="pb1">
          <a class="bb b--dotted bt-0 bl-0 br-0 pointer" @click="setAssignee('actualEmployee')">
            {{ $t('account.flow_new_action_notification_actual_employee') }}
          </a>
        </li>
        <li class="pv1">
          <a class="bb b--dotted bt-0 bl-0 br-0 pointer" @click="displayEmployeeSearchBox">
            {{ $t('account.flow_new_action_notification_specific_employee') }}
          </a>
        </li>
        <li class="pv1">
          <a class="bb b--dotted bt-0 bl-0 br-0 pointer" @click="setAssignee('managers')">
            {{ $t('account.flow_new_action_notification_manager') }}
          </a>
        </li>
        <li class="pv1">
          <a class="bb b--dotted bt-0 bl-0 br-0 pointer" @click="setAssignee('directReports')">
            {{ $t('account.flow_new_action_notification_report') }}
          </a>
        </li>
        <li class="pv1">
          <a class="bb b--dotted bt-0 bl-0 br-0 pointer" @click="setAssignee('employeeTeam')">
            {{ $t('account.flow_new_action_notification_team_members') }}
          </a>
        </li>
        <li class="pv1">
          <a class="bb b--dotted bt-0 bl-0 br-0 pointer" @click="displayTeamSearchBox">
            {{ $t('account.flow_new_action_notification_specific_team') }}
          </a>
        </li>
      </ul>
    </div>

    <!-- Modal used to search a specific employee -->
    <div v-if="showSeachEmployeeModal" class="w-100">
      <form @submit.prevent="searchEmployee">
        <div class="mb3 relative">
          <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                 :placeholder="$t('account.flow_new_action_notification_search_hint')" class="br2 f5 w-100 dib ba b--black-40 pa2 outline-0" required
                 @keyup="searchEmployee" @keydown.esc="hideModals()"
          />
          <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
        </div>
      </form>

      <div v-if="searchDone && potentialEmployees.length > 0">
        <span class="f6 mb2 dib">
          {{ $t('employee.hierarchy_search_results') }}
        </span>
        <ul class="list ma0 pl0">
          <li v-for="employee in potentialEmployees" :key="employee.id" class="br bl bb relative pv2 ph1 bb-gray bb-gray-hover employee-search-item">
            {{ employee.name }}
            <a class="absolute right-1 pointer" @click="addAssignee(employee)">
              {{ $t('app.choose') }}
            </a>
          </li>
        </ul>
      </div>

      <div v-if="searchDone && potentialEmployees.length == 0" class="silver">
        {{ $t('app.no_results') }}
      </div>
    </div>
  </div>
</template>

<script>
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';

export default {
  components: {
    'ball-pulse-loader': BallPulseLoader.component,
  },

  emits: [
    'update'
  ],

  data() {
    return {
      stageInitialChoice: true,
      assigneeChosen: false,
      assigneeSentence: '',
      showAllChoices: false,
      processingSearch: false,
      showSeachEmployeeModal: false,
      searchDone: false,
      potentialEmployees: [],
      form: {
        searchTerm: '',
        errors: [],
      },
      assignees: {
        type: '',
        ids: [],
      },
    };
  },

  methods: {
    displayAllChoices() {
      this.showAllChoices = true;
      this.stageInitialChoice = false;
      this.assigneeSentence = '';
      this.assigneeChosen = false;
      this.assignees.ids = [];
      this.assignees.type = '';
    },

    displayEmployeeSearchBox() {
      this.assigneeChosen = false;
      this.showSeachEmployeeModal = true;
      this.showAllChoices = false;
      this.form.searchTerm = '';
    },

    setAssignee(target) {
      this.hideModals();
      this.assigneeChosen = true;
      this.assignees.type = target;

      switch(target) {
      case 'actualEmployee':
        this.assigneeSentence = this.$t('account.flow_new_action_label_actual_employee');
        break;
      case 'managers':
        this.assigneeSentence = this.$t('account.flow_new_action_label_managers');
        break;
      case 'directReports':
        this.assigneeSentence = this.$t('account.flow_new_action_label_reports');
        break;
      case 'employeeTeam':
        this.assigneeSentence = this.$t('account.flow_new_action_label_team_employee');
        break;
      case 'specificTeam':
        break;
      default:
        this.assigneeSentence = this.$t('account.flow_new_action_label_employee');
      }

      this.emitUpdate();
    },

    emitUpdate() {
      this.$emit('update', this.assignees);
    },

    hideModals() {
      this.showAllChoices = false;
      this.showSeachEmployeeModal = false;
    },

    searchEmployee: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;
          this.searchDone = false;

          axios.post('/search/employees/', this.form)
            .then(response => {
              this.potentialEmployees = response.data.data;
              this.processingSearch = false;
              this.searchDone = true;
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

    addAssignee(employee) {
      this.searchDone = false;
      this.showSeachEmployeeModal = false;
      this.assignees.ids.push(employee);
      this.assigneeChosen = true;
      this.assignees.type = 'specificEmployee';
      this.assigneeSentence = this.$t('account.flow_new_action_label_employee');

      this.emitUpdate();
    },

    removeAssignee(employee) {
      this.assignees.ids.splice(this.assignees.ids.findIndex(i => i.id === employee.id), 1);
      this.emitUpdate();

      if (this.assignees.ids.length == 0) {
        this.stageInitialChoice = true;
        this.assigneeChosen = false;
        this.assignees.type = '';
      }
    },
  }
};

</script>
