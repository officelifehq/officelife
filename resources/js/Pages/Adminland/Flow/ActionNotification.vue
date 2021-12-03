<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';

.actions-dots {
  top: 15px;
}

.employee-modal {
  top: 30px;
  left: -120px;
  right: 290px;
}

.confirmation-menu {
  top: 30px;
  left: -160px;
  right: initial;
  width: 310px;
}

.action-menu {
  right: -6px;
  top: 31px;
}

.icon-delete {
  top: 2px;
}

.ball-pulse {
  right: 8px;
  top: 10px;
  position: absolute;
}
</style>

<template>
  <div class="relative pr3 lh-copy">
    Notify <span class="bb b--dotted bt-0 bl-0 br-0 pointer" @click="displayModal = true">
      {{ who }}
    </span> with <span class="bb b--dotted bt-0 bl-0 br-0 pointer" @click="displayEditMessageTextarea">
      {{ message }}
    </span>

    <!-- Modal to display the first step of "An employee" -->
    <div v-show="displayModal" v-click-outside="toggleModals" class="popupmenu employee-modal absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
      <ul class="list ma0 pa0">
        <li class="pv1">
          <a class="pointer" @click.prevent="setTarget('actualEmployee')">
            {{ $t('account.flow_new_action_notification_actual_employee') }}
          </a>
        </li>
        <li class="pv1">
          <a class="pointer" @click.prevent="displayEmployeeSearchBox">
            {{ $t('account.flow_new_action_notification_specific_employee') }}
          </a>
        </li>
        <li class="pv1">
          <a class="pointer" @click.prevent="setTarget('managers')">
            {{ $t('account.flow_new_action_notification_manager') }}
          </a>
        </li>
        <li class="pv1">
          <a class="pointer" @click.prevent="setTarget('directReports')">
            {{ $t('account.flow_new_action_notification_report') }}
          </a>
        </li>
        <li class="pv1">
          <a class="pointer" @click.prevent="setTarget('employeeTeam')">
            {{ $t('account.flow_new_action_notification_team_members') }}
          </a>
        </li>
        <li class="pv1">
          <a class="pointer" @click.prevent="displayTeamSearchBox">
            {{ $t('account.flow_new_action_notification_specific_team') }}
          </a>
        </li>
        <li class="pv1">
          <a class="pointer" @click.prevent="displayConfirmationModal">
            {{ $t('account.flow_new_action_notification_everyone') }}
          </a>
        </li>
      </ul>
    </div>

    <!-- Modal about confirming everyone in the company -->
    <div v-show="showEveryoneConfirmationModal" v-click-outside="toggleModals" class="popupmenu confirmation-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
      <p class="lh-copy">
        {{ $t('account.flow_new_action_notification_confirmation') }}
      </p>
      <ul class="list ma0 pa0 pb2">
        <li class="pv2 di relative mr2">
          <a class="pointer ml1" @click.prevent="setTarget('everyone')">
            {{ $t('app.yes_sure') }}
          </a>
        </li>
        <li class="pv2 di">
          <a class="pointer" @click.prevent="showEveryoneConfirmationModal = false; displayModal = true">
            {{ $t('app.no') }}
          </a>
        </li>
      </ul>
    </div>

    <!-- Modal showing the search a specific employee -->
    <div v-show="showSeachEmployeeModal" v-click-outside="toggleModals" class="popupmenu confirmation-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
      <form @submit.prevent="searchEmployee">
        <div class="mb3 relative">
          <p>{{ $t('account.flow_new_action_notification_search_employees') }}</p>
          <div class="relative">
            <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                   :placeholder="$t('account.flow_new_action_notification_search_hint')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required
                   @keyup="searchEmployee" @keydown.esc="toggleModals()"
            />
            <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
          </div>
        </div>
      </form>
      <ul class="pl0 list ma0">
        <li class="fw5 mb3">
          <span class="f6 mb2 dib">
            {{ $t('employee.hierarchy_search_results') }}
          </span>
          <ul v-if="searchEmployees.length > 0" class="list ma0 pl0">
            <li v-for="employee in searchEmployees" :key="employee.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
              {{ employee.name }}
              <a class="absolute right-1 pointer" data-cy="potential-manager-button" @click.prevent="assignEmployee(employee)">
                {{ $t('app.choose') }}
              </a>
            </li>
          </ul>
          <div v-else class="silver">
            {{ $t('app.no_results') }}
          </div>
        </li>
      </ul>
    </div>

    <!-- Modal showing the search a specific team -->
    <div v-show="showSeachTeamModal" v-click-outside="toggleModals" class="popupmenu confirmation-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
      <form @submit.prevent="searchTeam">
        <div class="mb3 relative">
          <p>{{ $t('account.flow_new_action_notification_search_teams') }}</p>
          <div class="relative">
            <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                   :placeholder="$t('account.flow_new_action_notification_search_hint')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required
                   @keyup="searchTeam" @keydown.esc="toggleModals()"
            />
            <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
          </div>
        </div>
      </form>
      <ul class="pl0 list ma0">
        <li class="fw5 mb3">
          <span class="f6 mb2 dib">
            {{ $t('employee.hierarchy_search_results') }}
          </span>
          <ul v-if="searchTeams.length > 0" class="list ma0 pl0">
            <li v-for="team in searchTeams" :key="team.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
              {{ team.name }}
              <a class="absolute right-1 pointer" data-cy="potential-manager-button" @click.prevent="assignTeam(team)">
                {{ $t('app.choose') }}
              </a>
            </li>
          </ul>
          <div v-else class="silver">
            {{ $t('app.no_results') }}
          </div>
        </li>
      </ul>
    </div>

    <!-- Image to trigger actions -->
    <img loading="lazy" src="/img/common/triple-dots.svg" alt="triple dot symbol" class="absolute right-0 pointer actions-dots" @click="actionsModal = true" />

    <!-- Actions available -->
    <div v-if="actionsModal" v-click-outside="toggleModals" class="popupmenu action-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal">
      <ul class="list ma0 pa0">
        <li v-show="!deleteActionConfirmation" class="pv2 relative">
          <icon-delete :class="'icon-delete relative'" :width="15" :height="15" />
          <a class="pointer ml1 c-delete" @click.prevent="deleteActionConfirmation = true">
            {{ $t('account.flow_new_action_remove') }}
          </a>
        </li>
        <li v-show="deleteActionConfirmation" class="pv2">
          {{ $t('app.sure') }}
          <a class="c-delete mr1 pointer" @click.prevent="destroyAction">
            {{ $t('app.yes') }}
          </a>
          <a class="pointer" @click.prevent="deleteActionConfirmation = false">
            {{ $t('app.no') }}
          </a>
        </li>
      </ul>
    </div>

    <!-- Set message -->
    <div v-show="showEditMessage" class="mt2">
      <p class="mb1 f6">
        {{ charactersLeft }}
      </p>
      <textarea v-model="updatedMessage" cols="30" rows="3" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" maxlength="255"></textarea>
      <div class="mv1">
        <div class="flex-ns justify-between">
          <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click="showEditMessage = false">
            {{ $t('app.cancel') }}
          </a>
          <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click="setMessage(updatedMessage)">
            {{ $t('app.save') }}
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';
import IconDelete from '@/Shared/IconDelete';
import vClickOutside from 'click-outside-vue3';

export default {
  components: {
    IconDelete,
    'ball-pulse-loader': BallPulseLoader.component,
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

    this.notification = this.action;
    this.who = 'an employee';
    this.setMessage(this.$t('account.flow_new_action_label_unknown_message'));
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
