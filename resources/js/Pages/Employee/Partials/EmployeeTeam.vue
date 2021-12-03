<style lang="scss" scoped>
.teams-list {
  max-height: 150px;
}

.popover {
  width: 300px;
}

.popupmenu {
  left: 0;
  top: 33px;
}

.c-delete:hover {
  border-bottom-width: 0;
}

.existing-teams li:not(:last-child) {
  margin-right: 5px;
}

.team-item:not(:first-child):before {
  content: '/';
  color: gray;
  margin-right: 10px;
}
</style>

<template>
  <div class="di relative">
    <!-- list of teams -->
    <div v-if="localEmployeeTeams" class="di">
      <ul class="di list ma0 pa0 existing-teams">
        <li v-for="team in localEmployeeTeams" :key="team.id" class="di team-item">
          <inertia-link :href="team.url" class="mr1">{{ team.name }}</inertia-link>
          <template v-if="team.team_leader">
            <span v-if="team.team_leader.id == employee.id">
              ({{ $t('employee.team_leader') }})
            </span>
          </template>
        </li>
        <li v-if="permissions.can_manage_teams" data-cy="open-team-modal" class="bb b--dotted bt-0 bl-0 br-0 pointer di f7" @click.prevent="showPopover()">
          {{ $t('app.edit') }}
        </li>
      </ul>
    </div>

    <!-- Action when there is no team defined -->
    <div v-if="!localEmployeeTeams" class="di">
      <a v-if="permissions.can_manage_teams" class="bb b--dotted bt-0 bl-0 br-0 pointer f7" data-cy="open-team-modal-blank" @click.prevent="showPopover()">
        {{ $t('employee.team_no_team_yet_with_right') }}
      </a>

      <!-- no permission to manage teams -->
      <span v-else>
        {{ $t('employee.team_no_team_yet') }}
      </span>
    </div>

    <!-- Modal -->
    <div v-if="showPopup" v-click-outside="hidePopover" class="absolute popupmenu popover bg-white z-max">
      <!-- Shown when there is at least one team in the account -->
      <div v-if="teamsInCompany.length != 0">
        <p class="pa2 ma0 bb bb-gray">
          {{ $t('employee.team_modal_title') }}
        </p>

        <form @submit.prevent="search">
          <div class="relative pv2 ph2 bb bb-gray">
            <input id="search" v-model="search" type="text" name="search"
                   :placeholder="$t('employee.team_modal_filter')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                   @keydown.esc="hidePopover"
            />
          </div>
        </form>

        <!-- List of teams in modal -->
        <ul class="pl0 list ma0 overflow-auto relative teams-list">
          <li v-for="team in filteredList" :key="team.id" :data-cy="'list-team-' + team.id">
            <!-- case if the team is selected -->
            <div v-if="isAssigned(team.id)" class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative" @click="reset(team)">
              {{ team.name }}

              <img loading="lazy" src="/img/check.svg" class="pr1 absolute right-1" alt="check symbol" />
            </div>

            <!-- case if the team is not yet selected -->
            <div v-else class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative" @click="assign(team)">
              {{ team.name }}
            </div>
          </li>
        </ul>
      </div>

      <!-- Shown if there is no teams setup in the account yet -->
      <div v-else>
        <p class="pa2 tc lh-copy" data-cy="modal-blank-state-copy">
          {{ $t('employee.team_modal_blank_title') }} <inertia-link :href="'/' + $page.props.auth.company.id + '/account/teams'" data-cy="modal-blank-state-cta">
            {{ $t('employee.team_modal_blank_cta') }}
          </inertia-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import vClickOutside from 'click-outside-vue3';

export default {
  directives: {
    clickOutside: vClickOutside.directive
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    permissions: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      teamsInCompany: null,
      modal: false,
      search: '',
      localEmployeeTeams: null,
      showPopup: false,
    };
  },

  computed: {
    filteredList() {
      // filter the list when searching
      // also, sort the list by name
      if (this.teamsInCompany) {
        var list = this.teamsInCompany.filter(team => {
          return team.name.toLowerCase().includes(this.search.toLowerCase());
        });

        return _.sortBy(list, ['name']);
      }

      return false;
    }
  },

  mounted() {
    this.localEmployeeTeams = this.employee.teams;
  },

  methods: {
    hidePopover: function() {
      this.showPopup = false;
    },

    showPopover: function() {
      this.load();
      this.showPopup = true;
    },

    load() {
      if (! this.teamsInCompany) {
        axios.get(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/team`)
          .then(response => {
            this.teamsInCompany = response.data.data;
          })
          .catch(error => {
            this.form.errors = error.response.data;
          });
      }
    },

    assign(team) {
      axios.post(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/team`, team)
        .then(response => {
          this.flash(this.$t('employee.team_modal_assign_success'), 'success');

          this.localEmployeeTeams = response.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    reset(team) {
      axios.delete(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/team/${team.id}`)
        .then(response => {
          this.flash(this.$t('employee.team_modal_unassign_success'), 'success');

          this.localEmployeeTeams = response.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    isAssigned: function(id) {
      if (this.localEmployeeTeams) {
        for(let team of this.localEmployeeTeams){
          if (team.id === id) {
            return true;
          }
        }
      }
      return false;
    }
  }
};

</script>
