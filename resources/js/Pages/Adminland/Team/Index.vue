<style lang="scss" scoped>
  .add-modal {
    border: 1px solid rgba(27,31,35,.15);
    box-shadow: 0 3px 12px rgba(27,31,35,.15);
    top: 36px;
    right: 0;
  }

  .add-modal:after,
  .add-modal:before {
    content: "";
    display: inline-block;
    position: absolute;
  }

  .add-modal:after {
    border: 7px solid transparent;
    border-bottom-color: #fff;
    left: auto;
    right: 10px;
    top: -14px;
  }

  .add-modal:before {
    border: 8px solid transparent;
    border-bottom-color: rgba(27,31,35,.15);
    left: auto;
    right: 9px;
    top: -16px;
  }

  .team-item:last-child {
    border-bottom: 0;
    padding-bottom: 0;
  }
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account'"
                  :previous="$t('app.breadcrumb_account_home')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_account_manage_teams') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <!-- WHEN THERE ARE TEAMS -->
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.teams_title', { company: $page.props.auth.company.name}) }}
          </h2>

          <!-- add a team -->
          <div class="relative mb4">
            <span v-show="localTeams.length != 0" class="dib mb3 di-l">
              {{ $tc('account.teams_number_teams', localTeams.length, { company: $page.props.auth.company.name, count: localTeams.length}) }}
            </span>
            <a data-cy="add-team-button" class="btn tc absolute-l relative dib-l db right-0" @click.prevent="displayAddModal">
              {{ $t('account.teams_cta') }}
            </a>

            <div v-if="modal == true" class="absolute add-modal br2 bg-white z-max tl pv2 ph3 bounceIn faster">
              <template v-if="form.errors.length > 0">
                <div class="cf pb1 w-100 mb2">
                  <errors :errors="form.errors" />
                </div>
              </template>

              <form @submit.prevent="submit">
                <div class="mb3">
                  <text-input :ref="'newTeam'"
                              v-model="form.name"
                              :placeholder="''"
                              :name="'name'"
                              :errors="$page.props.errors.name"
                              required
                              :label="$t('account.team_new_name')"
                              :extra-class-upper-div="'mb0'"
                              @esc-key-pressed="modal = false"
                  />
                </div>
                <div class="mv2">
                  <div class="flex-ns justify-between">
                    <div>
                      <a class="btn dib tc w-auto-ns w-100 pv2 ph3" @click="modal = false">
                        {{ $t('app.cancel') }}
                      </a>
                    </div>
                    <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.add')" data-cy="submit-add-team-button" />
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- LIST OF TEAMS -->
          <ul v-show="localTeams.length != 0" class="list pl0 mt0 center">
            <li
              v-for="team in localTeams" :key="team.id"
              class="pa3-l pa1 ph0-l bb b--black-10 team-item"
            >
              <!-- normal case (ie not rename mode) -->
              <template v-if="teamToRename.id != team.id && teamToDelete.id != team.id">
                <span class="db b mb2" :data-cy="'list-team-' + team.id">
                  {{ team.name }}
                </span>

                <!-- list of actions -->
                <ul class="f6 list pl0">
                  <li class="di pr2">
                    <inertia-link :href="team.url">{{ $t('account.team_visit_page') }}</inertia-link>
                  </li>
                  <li class="di pr2">
                    <inertia-link :href="'/' + $page.props.auth.company.id + '/account/teams/' + team.id + '/logs'">{{ $t('account.team_view_audit_logs') }}</inertia-link>
                  </li>
                  <li class="di pr2">
                    <a href="#" class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'team-rename-link-' + team.id" @click.prevent="showRenameModal(team)">{{ $t('app.rename') }}</a>
                  </li>
                  <li class="di">
                    <a href="#" class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'team-destroy-link-' + team.id" @click.prevent="showDeletionModal(team)">{{ $t('app.delete') }}</a>
                  </li>
                </ul>
              </template>

              <!-- rename modal -->
              <template v-if="teamToRename.id == team.id && renameMode">
                <template v-if="form.errors.length > 0">
                  <div class="cf pb1 w-100 mb2">
                    <errors :errors="form.errors" />
                  </div>
                </template>

                <!-- form -->
                <form class="flex" @submit.prevent="update(team)">
                  <div class="w-100 w-70-ns mb3 mb0-ns">
                    <text-input :id="'name-' + team.id"
                                :ref="'name' + team.id"
                                v-model="form.name"
                                :placeholder="'Product team'"
                                :custom-ref="'name' + team.id"
                                :datacy="'list-rename-input-name-' + team.id"
                                :errors="$page.props.errors.name"
                                required
                                :extra-class-upper-div="'mb0'"
                                @esc-key-pressed="teamToRename = 0"
                    />
                  </div>

                  <!-- actions -->
                  <div class="w-30-ns w-100 tr">
                    <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" :data-cy="'list-rename-cancel-button-' + team.id" @click.prevent="teamToRename = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-rename-cta-button-' + team.id" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </template>

              <!-- deletion modal -->
              <template v-if="teamToDelete.id == team.id && deletionMode">
                <template v-if="form.errors.length > 0">
                  <div class="cf pb1 w-100 mb2">
                    <errors :errors="form.errors" />
                  </div>
                </template>

                <!-- form -->
                <form @submit.prevent="destroy(team)">
                  <p class="lh-copy">{{ $t('account.team_confirm_deletion', {name: team.name}) }}</p>
                  <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3 mr3" :data-cy="'list-destroy-cancel-button-' + team.id" @click.prevent="teamToDelete = 0">
                    {{ $t('app.cancel') }}
                  </a>
                  <loading-button :class="'btn destroy w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-destroy-cta-button-' + team.id" :state="loadingState" :text="$t('app.delete')" />
                </form>
              </template>
            </li>
          </ul>
        </div>

        <!-- NO TEAMS -->
        <div v-show="localTeams.length == 0" class="pa3">
          <img loading="lazy" height="140" class="db center mb4" alt="no expenses to validate" src="/img/streamline-icon-designer-team-6@140x140.png" />

          <p class="tc measure center mb4 lh-copy">
            {{ $t('account.teams_blank') }}
          </p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    teams: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localTeams: [],
      modal: false,
      renameMode: false,
      deletionMode: false,
      teamToRename: Object,
      teamToDelete: Object,
      form: {
        name: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  watch: {
    teams: {
      handler(value) {
        this.localTeams = value;
      },
      deep: true
    }
  },

  mounted() {
    this.localTeams = this.teams;
  },

  methods: {
    showRenameModal(team) {
      this.form.errors = [];
      this.renameMode = true;
      this.teamToRename = team;
      this.form.name = team.name;

      this.$nextTick(() => {
        this.$refs[`name${team.id}`].focus();
      });
    },

    showDeletionModal(team) {
      this.form.errors = [];
      this.deletionMode = true;
      this.teamToDelete = team;
    },

    displayAddModal() {
      this.modal = !this.modal;
      this.form.errors = [];

      this.$nextTick(() => {
        this.$refs.newTeam.focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('account_teams.teams.store', this.$page.props.auth.company.id), this.form)
        .then(response => {
          this.flash(this.$t('account.team_creation_success'), 'success');

          this.loadingState = null;
          this.form.name = null;
          this.modal = false;
          this.localTeams.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(team) {
      axios.put(this.route('account_teams.teams.update', [this.$page.props.auth.company.id, team.id]), this.form)
        .then(response => {
          this.flash(this.$t('account.team_update_success'), 'success');

          this.teamToRename = 0;
          this.form.name = null;

          this.localTeams[this.localTeams.findIndex(x => x.id === team.id)] = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(team) {
      axios.delete(this.route('account_teams.teams.destroy', [this.$page.props.auth.company.id, team.id]))
        .then(response => {
          this.flash(this.$t('account.team_destroy_success'), 'success');

          this.teamToDelete = 0;
          var id = this.localTeams.findIndex(x => x.id === team.id);
          this.localTeams.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
