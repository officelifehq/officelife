<style scoped>
.teams-list {
  max-height: 150px;
}

.popupmenu {
  right: 2px;
  top: 26px;
  width: 280px;
}

.c-delete:hover {
  border-bottom-width: 0;
}

.existing-teams li:not(:last-child) {
  margin-right: 5px;
}
</style>

<template>
  <div class="di relative">
    <!-- Assigning a team is restricted to HR or admin -->
    <ul v-if="$page.auth.employee.permission_level <= 200" class="ma0 pa0 di existing-teams">
      <li v-show="updatedEmployeeTeams.length != 0" class="bb b--dotted bt-0 bl-0 br-0 pointer di" data-cy="open-team-modal" @click.prevent="modal = true">
        {{ $t('employee.team_title') }}
      </li>
      <li v-for="team in updatedEmployeeTeams" :key="team.id" class="di">
        <inertia-link :href="'/' + $page.auth.company.id + '/teams/' + team.id">{{ team.name }}</inertia-link>
        <template v-if="team.team_leader">
          <span v-if="team.team_leader.id == employee.id">
            (leader)
          </span>
        </template>
      </li>
    </ul>
    <ul v-else class="ma0 pa0 existing-teams di">
      <li v-show="updatedEmployeeTeams.length != 0" class="di">
        {{ $t('employee.team_title') }}
      </li>
      <li v-for="team in updatedEmployeeTeams" :key="team.id" class="di">
        <inertia-link :href="'/' + $page.auth.company.id + '/teams/' + team.id">{{ team.name }}</inertia-link>
      </li>
    </ul>

    <!-- Action when there is no team defined -->
    <a v-show="updatedEmployeeTeams.length == 0" v-if="$page.auth.employee.permission_level <= 200" class="bb b--dotted bt-0 bl-0 br-0 pointer" data-cy="open-team-modal-blank" @click.prevent="modal = true">
      {{ $t('employee.team_modal_title') }}
    </a>
    <span v-else v-show="updatedEmployeeTeams.length == 0">
      {{ $t('employee.team_modal_blank') }}
    </span>

    <!-- Modal -->
    <div v-if="modal" v-click-outside="toggleModal" class="popupmenu absolute br2 bg-white z-max tl bounceIn faster">
      <!-- Shown when there is at least one team in the account -->
      <div v-show="teams.length != 0">
        <p class="pa2 ma0 bb bb-gray">
          {{ $t('employee.team_modal_title') }}
        </p>

        <form @submit.prevent="search">
          <div class="relative pv2 ph2 bb bb-gray">
            <input id="search" v-model="search" type="text" name="search"
                   :placeholder="$t('employee.team_modal_filter')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                   @keydown.esc="toggleModal"
            />
          </div>
        </form>

        <!-- List of teams in modal -->
        <ul class="pl0 list ma0 overflow-auto relative teams-list">
          <li v-for="team in filteredList" :key="team.id" :data-cy="'list-team-' + team.id">
            <!-- case if the team is selected -->
            <div v-if="isAssigned(team.id)" class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative" @click="reset(team)">
              {{ team.name }}

              <img src="/img/check.svg" class="pr1 absolute right-1" alt="check symbol" />
            </div>

            <!-- case if the team is not yet selected -->
            <div v-else class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative" @click="assign(team)">
              {{ team.name }}
            </div>
          </li>
        </ul>
      </div>

      <!-- Shown if there is no teams setup in the account yet -->
      <div v-show="teams.length == 0">
        <p class="pa2 tc lh-copy" data-cy="modal-blank-state-copy">
          {{ $t('employee.team_modal_blank_title') }} <inertia-link :href="'/' + $page.auth.company.id + '/account/teams'" data-cy="modal-blank-state-cta">
            {{ $t('employee.team_modal_blank_cta') }}
          </inertia-link>
        </p>
        <img class="db center mb4" alt="team" srcset="/img/company/account/blank-team-1x.png,
                                        /img/company/account/blank-team-2x.png 2x"
        />
      </div>
    </div>
  </div>
</template>

<script>
import vClickOutside from 'v-click-outside';

export default {
  directives: {
    clickOutside: vClickOutside.directive
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    employeeTeams: {
      type: Array,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      search: '',
      updatedEmployeeTeams: Array,
    };
  },

  computed: {
    filteredList() {
      // filter the list when searching
      // also, sort the list by name
      var list;
      list = this.teams.filter(team => {
        return team.name.toLowerCase().includes(this.search.toLowerCase());
      });

      function compare(a, b) {
        if (a.name < b.name)
          return -1;
        if (a.name > b.name)
          return 1;
        return 0;
      }

      return list.sort(compare);
    }
  },

  created() {
    this.updatedEmployeeTeams = this.employeeTeams;
  },

  methods: {
    toggleModal() {
      this.modal = false;
    },

    assign(team) {
      axios.post('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/team', team)
        .then(response => {
          flash(this.$t('employee.team_modal_assign_success'), 'success');

          this.updatedEmployeeTeams = response.data;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    reset(team) {
      axios.delete('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/team/' + team.id)
        .then(response => {
          flash(this.$t('employee.team_modal_unassign_success'), 'success');

          this.updatedEmployeeTeams = response.data;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    isAssigned: function(id) {
      for(var i=0; i < this.updatedEmployeeTeams.length; i++){
        if (this.updatedEmployeeTeams[i].id == id) {
          return true;
        }
      }
      return false;
    }
  }
};

</script>
