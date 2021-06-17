<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';

.icon-pen {
  color: #acaa19;
  width: 16px;
  top: 2px;
}
</style>

<template>
  <div>
    <!-- blank state -->
    <p v-if="!editMode && !form.content" class="mt0 mb0 relative i gray" @click="editMode = true">
      Who should be assigned?

      <svg class="ml2 icon-pen relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
      </svg>
    </p>

    <!-- non blank state -->
    <p v-if="!editMode && form.content" class="lh-copy mt0 mb0 relative" @click="editMode = true">
      {{ form.content }}

      <svg class="ml2 icon-pen relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
      </svg>
    </p>

    <!-- edit mode -->
    <div v-if="editMode" class="">
      <!-- list of possible assignees -->
      <ul class="list ma0 pa0">
        <li class="pb1">
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
        <li class="pv1">
          <a class="pointer" @click.prevent="displayConfirmationModal">
            No one, actually
          </a>
        </li>
      </ul>

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
    </div>
  </div>
</template>

<script>
export default {
  components: {
    'ball-pulse-loader': BallPulseLoader.component,
  },

  emits: [
    'destroy'
  ],

  data() {
    return {
      editMode: false,
      processingSearch: false,
      searchEmployees: [],
      showSeachEmployeeModal: false,
      form: {
        content: null,
        errors: [],
      },
    };
  },

  methods: {
    showEditMode() {
      this.editMode = true;

      this.$nextTick(() => {
        this.$refs.editText.focus();
      });
    },

    save() {
      this.editMode = false;
    }
  }
};

</script>
