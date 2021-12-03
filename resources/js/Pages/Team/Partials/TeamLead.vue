<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';

.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-lead {
  padding-left: 44px;
}

.ball-pulse {
  right: 8px;
  top: 10px;
  position: absolute;
}

.team-lead-action {
  top: 22px;
}

.popupmenu {
  right: -4px;

  &:after {
    left: auto;
    right: 10px;
  }

  &:before {
    left: auto;
    right: 9px;
  }
}
</style>

<template>
  <div>
    <!-- case of team lead set -->
    <template v-if="localTeam.team_leader">
      <div class="lh-copy ma0 pa3 bb bb-gray">
        <p class="silver f6 ma0 mb1">{{ $t('team.team_lead_label') }}</p>
        <span class="pl3 db team-lead relative">
          <avatar :avatar="localTeam.team_leader.avatar" :size="35" :class="'br-100 absolute avatar'" />
          <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + localTeam.team_leader.id" class="mb2" data-cy="current-team-lead">
            {{ localTeam.team_leader.name }}
          </inertia-link>

          <span v-if="!localTeam.team_leader.position" class="db f7 mt1" data-cy="team-lead-undefined">
            {{ $t('app.no_position_defined') }}
          </span>

          <span v-if="localTeam.team_leader.position" class="db f7 mt1">
            {{ localTeam.team_leader.position.title }}
          </span>

          <img v-if="$page.props.auth.employee.permission_level <= 200" loading="lazy" src="/img/common/triple-dots.svg" class="absolute right-0 pointer team-lead-action" data-cy="display-remove-team-lead-modal"
               alt="display the menu"
               @click.prevent="removeMode = true"
          />

          <!-- REMOVE TEAM LEADER MENU -->
          <template v-if="removeMode">
            <div v-show="$page.props.auth.employee.permission_level <= 200" v-click-outside="hideRemovalMode" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn">
              <ul class="list ma0 pa0">
                <li v-show="!removalConfirmation" class="pv2 relative">
                  <icon-delete :class="'icon-delete relative'" :width="15" :height="15" />
                  <a class="pointer ml1 c-delete" data-cy="remove-team-lead-button" @click.prevent="removalConfirmation = true">
                    {{ $t('team.team_lead_remove_confirmation') }}
                  </a>
                </li>
                <li v-show="removalConfirmation" class="pv2">
                  {{ $t('app.sure') }}
                  <a data-cy="confirm-remove-team-lead" class="c-delete mr1 pointer" @click.prevent="removeTeamLead()">
                    {{ $t('app.yes') }}
                  </a>
                  <a class="pointer" @click.prevent="removalConfirmation = false">
                    {{ $t('app.no') }}
                  </a>
                </li>
              </ul>
            </div>
          </template>
        </span>
      </div>
    </template>

    <!-- team lead blank state -->
    <div v-show="!localTeam.team_leader && !editMode" class="lh-copy ma0 pa3 bb bb-gray">
      <a v-if="atLeastHR" class="bb b--dotted bt-0 bl-0 br-0 pointer" data-cy="add-team-lead-blank-state" @click.prevent="displaySearch()">{{ $t('team.team_lead_cta') }}</a>

      <span v-if="!atLeastHR" class="f6">
        {{ $t('team.team_lead_blank') }}
      </span>
    </div>

    <!-- form to add the leader -->
    <div v-show="editMode" class="pa3 bb bb-gray">
      <form @submit.prevent="search">
        <!-- errors -->
        <template v-if="form.errors.length > 0">
          <div class="cf pb1 w-100 mb2">
            <errors :errors="form.errors" />
          </div>
        </template>

        <!-- search form -->
        <div class="mb3 relative">
          <p class="mt0">Search employees <a href="#" @click.prevent="editMode = false">{{ $t('app.cancel') }}</a></p>
          <div class="relative">
            <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                   :placeholder="$t('employee.hierarchy_search_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required data-cy="search-team-lead"
                   @keyup="search()" @keydown.esc="editMode = false"
            />
            <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
          </div>
        </div>
      </form>

      <!-- RESULTS -->
      <div class="pl0 list ma0">
        <ul v-if="potentialTeamLeads.length > 0" class="list ma0 pl0">
          <li v-for="lead in potentialTeamLeads" :key="lead.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
            {{ lead.name }}
            <a class="absolute right-1 pointer" :data-cy="'potential-team-lead-' + lead.id" @click.prevent="assign(lead)">
              {{ $t('app.choose') }}
            </a>
          </li>
        </ul>
      </div>
      <div v-if="hasMadeASearch && potentialTeamLeads.length == 0" class="silver">
        {{ $t('app.no_results') }}
      </div>
    </div>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';
import Avatar from '@/Shared/Avatar';
import IconDelete from '@/Shared/IconDelete';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';
import vClickOutside from 'click-outside-vue3';

export default {
  components: {
    Errors,
    Avatar,
    IconDelete,
    'ball-pulse-loader': BallPulseLoader.component,
  },

  directives: {
    clickOutside: vClickOutside.directive
  },

  props: {
    team: {
      type: Object,
      default: null,
    },
    userBelongsToTheTeam: {
      type: Boolean,
      default: false,
    }
  },

  data() {
    return {
      processingSearch: false,
      hasMadeASearch: false,
      editMode: false,
      removeMode: false,
      removalConfirmation: false,
      potentialTeamLeads: [],
      form: {
        employeeId: 0,
        searchTerm: null,
        errors: [],
      },
      localTeam: null,
    };
  },

  computed: {
    atLeastHR() {
      return this.$page.props.auth.employee.permission_level <= 200;
    },
  },

  created: function() {
    this.localTeam = this.team;
  },

  methods: {
    displaySearch() {
      this.editMode = true;
      this.form.errors = [];

      this.$nextTick(() => {
        this.$refs.search.focus();
      });
    },

    hideRemovalMode() {
      this.removeMode = false;
      this.form.errors = [];
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.hasMadeASearch = false;
          this.processingSearch = true;

          axios.post('/' + this.$page.props.auth.company.id + '/teams/' + this.team.id + '/lead/search', this.form)
            .then(response => {
              this.potentialTeamLeads = response.data.data;
              this.processingSearch = false;
              this.hasMadeASearch = true;
              this.form.errors = [];
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
              this.hasMadeASearch = false;
            });
        }
      },
      500),

    assign(lead) {
      this.form.employeeId = lead.id;

      axios.post('/' + this.$page.props.auth.company.id + '/teams/' + this.team.id + '/lead', this.form)
        .then(response => {
          this.flash(this.$t('team.team_lead_added'), 'success');

          this.localTeam.team_leader = response.data.data;
          this.editMode = false;

          this.$emitt('lead-set', response.data.data);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    removeTeamLead() {
      axios.delete('/' + this.$page.props.auth.company.id + '/teams/' + this.team.id + '/lead/' + this.localTeam.team_leader.id)
        .then(response => {
          this.flash(this.$t('team.team_lead_removed'), 'success');

          this.localTeam.team_leader = null;
          this.removeMode = false;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
