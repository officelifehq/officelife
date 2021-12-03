<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';

.list-no-line-bottom {
  li:last-child {
    border-bottom: 0;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;
  }
}

.deciders {
  padding-left: 34px;

  .avatar {
    left: 1px;
    top: -2px;
    width: 23px;
  }
}

.actions-dots {
  top: 24px;
  right: 10px;
}

.action-menu {
  right: 4px;
  top: 37px;
}

.icon-delete {
  top: 2px;
}

.ball-pulse {
  right: 8px;
  top: 37px;
  position: absolute;
}

.plus-button {
  padding: 2px 7px 4px;
  margin-right: 4px;
  border-color: #60995c;
  color: #60995c;
}

.popupmenu {
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
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb :has-more="false"
                  :previous-url="route('projects.index', { company: $page.props.auth.company.id})"
                  :previous="$t('app.breadcrumb_project_list')"
      >
        {{ $t('app.breadcrumb_project_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <!-- Menu -->
        <project-menu :project="localProject" :tab="tab" />
      </div>

      <div class="mw6 center br3 mb5 relative z-1">
        <p class="db fw5 mb2 flex justify-between items-center">
          <span>
            <span class="mr1">🗞</span> {{ $t('project.decision_index_title') }}

            <help :url="$page.props.help_links.project_decisions" :top="'3px'" />
          </span>
          <a href="#" class="btn f5" data-cy="add-decision" @click.prevent="showAddMode()">{{ $t('project.decision_index_cta') }}</a>
        </p>

        <!-- log a new decision -->
        <div v-if="addMode" class="bg-white box pa3 mb3">
          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Title -->
            <text-input :id="'title'"
                        :ref="'newDecision'"
                        v-model="form.title"
                        :name="'title'"
                        :datacy="'decision-title-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('project.decision_index_add_decision')"
                        :help="$t('project.decision_index_add_decision_help')"
                        :required="true"
                        @esc-key-pressed="addMode = false"
            />

            <!-- list of people who decided -->
            <p v-if="!showDeciders && form.employees.length == 0" class="bt bb-gray pt3 pointer" data-cy="decision-add-deciders" @click.prevent="showDeciders = true"><span class="ba br-100 plus-button">+</span> {{ $t('project.decision_index_add_decider') }}</p>
            <p v-if="!showDeciders && form.employees.length > 0" class="bt bb-gray pt3 pointer" data-cy="decision-add-deciders" @click.prevent="showDeciders = true"><span class="ba br-100 plus-button">+</span> {{ $t('project.decision_index_add_decider_additional') }}</p>

            <div v-if="showDeciders == true" class="bb bb-gray bt pt3">
              <form class="relative" @submit.prevent="search">
                <text-input :id="'name'"
                            v-model="form.searchTerm"
                            :name="'name'"
                            :datacy="'decision-employees'"
                            :errors="$page.props.errors.name"
                            :label="$t('team.recent_ship_new_credit')"
                            :placeholder="$t('team.recent_ship_new_credit_help')"
                            :required="false"
                            @keyup="search"
                            @update:model-value="search"
                            @esc-key-pressed="showDeciders = false"
                />
                <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
              </form>

              <!-- search results -->
              <ul v-show="potentialMembers.length > 0" class="list pl0 ba bb-gray bb-gray-hover">
                <li v-for="employee in potentialMembers" :key="employee.id" class="relative pa2 bb bb-gray">
                  {{ employee.name }}
                  <a href="" class="fr f6" :data-cy="'employee-id-' + employee.id" @click.prevent="add(employee)">{{ $t('app.add') }}</a>
                </li>
              </ul>

              <!-- no results found -->
              <ul v-show="potentialMembers.length == 0 && form.searchTerm" class="list pl0 ba bb-gray bb-gray-hover">
                <li class="relative pa2 bb bb-gray">
                  {{ $t('team.members_no_results') }}
                </li>
              </ul>
            </div>

            <div v-show="form.employees.length > 0" class="ba bb-gray mb3 mt4">
              <div v-for="employee in form.employees" :key="employee.id" class="pa2 db bb-gray bb" data-cy="members-list">
                <span class="pl3 db relative deciders">
                  <avatar :avatar="employee.avatar" :size="22" :class="'br-100 absolute avatar'" />

                  {{ employee.name }}

                  <!-- remove -->
                  <a href="#" class="db f7 mt1 c-delete dib fr" :data-cy="'remove-employee-' + employee.id" @click.prevent="detach(employee)">
                    {{ $t('app.remove') }}
                  </a>
                </span>
              </div>
            </div>

            <!-- Actions -->
            <div class="mt4">
              <div class="flex-ns justify-between">
                <div>
                  <a href="#" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addMode = false">
                    {{ $t('app.cancel') }}
                  </a>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'submit-add-decision-button'" />
              </div>
            </div>
          </form>
        </div>

        <!-- list of decisions -->
        <div v-if="decisions.length > 0" class="bg-white box">
          <ul class="list pl0 mv0 list-no-line-bottom">
            <li v-for="decision in localDecisions" :key="decision.id" :data-cy="'decision-' + decision.id" class="bb bb-gray pa3 relative">
              <!-- decision title -->
              <p class="ma0 mb3 fw5">{{ decision.title }}</p>

              <!-- date + decided by -->
              <div class="flex">
                <!-- date -->
                <span class="f7 mr4">
                  <span class="db gray mb2">{{ $t('project.decision_index_date') }}</span>
                  <span>{{ decision.decided_at }}</span>
                </span>

                <!-- Decided by -->
                <div v-if="decision.deciders.length > 0">
                  <span class="db mb2 gray f7">{{ $t('project.decision_index_decider') }}</span>
                  <ul class="list pl0">
                    <li v-for="decider in decision.deciders" :key="decider.id" class="di mr2">
                      <small-name-and-avatar
                        :name="decider.name"
                        :avatar="decider.avatar"
                        :class="'f4 fw4'"
                        :top="'0px'"
                        :margin-between-name-avatar="'29px'"
                      />
                    </li>
                  </ul>
                </div>
              </div>

              <!-- Image to trigger actions -->
              <img :data-cy="'decision-display-menu-' + decision.id" loading="lazy" src="/img/common/triple-dots.svg" alt="triple dot symbol" class="absolute right-0 pointer actions-dots"
                   @click="showActionModal(decision)"
              />

              <!-- Actions available -->
              <div v-if="decision.id == decisionToDelete" v-click-outside="hideAction" class="popupmenu action-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal">
                <ul class="list ma0 pa0">
                  <li v-show="!deleteActionConfirmation" class="pv2 relative">
                    <icon-delete :class="'icon-delete relative'" :width="15" :height="15" />
                    <a :data-cy="'decision-delete-' + decision.id" class="pointer ml1 c-delete" @click.prevent="deleteActionConfirmation = true">
                      {{ $t('project.decision_index_delete') }}
                    </a>
                  </li>
                  <li v-show="deleteActionConfirmation" class="pv2">
                    {{ $t('app.sure') }}
                    <a :data-cy="'decision-delete-confirmation-' + decision.id" class="c-delete mr1 pointer" @click.prevent="destroy(decision)">
                      {{ $t('app.yes') }}
                    </a>
                    <a class="pointer" @click.prevent="deleteActionConfirmation = false">
                      {{ $t('app.no') }}
                    </a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>

        <!-- blank state -->
        <div v-if="decisions.length == 0" data-cy="decision-blank-state" class="bg-white box pa3 tc">
          <img loading="lazy" src="/img/streamline-icon-factory-engineer-3@140x140.png" width="140" height="140" alt="meeting"
               class=""
          />
          <p class="lh-copy">{{ $t('project.decision_index_blank') }}</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import ProjectMenu from '@/Pages/Company/Project/Partials/ProjectMenu';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import vClickOutside from 'click-outside-vue3';
import IconDelete from '@/Shared/IconDelete';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';
import LoadingButton from '@/Shared/LoadingButton';
import Help from '@/Shared/Help';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    ProjectMenu,
    SmallNameAndAvatar,
    IconDelete,
    TextInput,
    Errors,
    LoadingButton,
    'ball-pulse-loader': BallPulseLoader.component,
    Help,
  },

  directives: {
    clickOutside: vClickOutside.directive
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    project: {
      type: Object,
      default: null,
    },
    decisions: {
      type: Array,
      default: null,
    },
    tab: {
      type: String,
      default: 'summary',
    },
  },

  data() {
    return {
      localDecisions: null,
      loadingState: '',
      addMode: false,
      decisionToDelete: 0,
      deleteActionConfirmation: false,
      showDeciders: false,
      potentialMembers: [],
      processingSearch: false,
      form: {
        title: null,
        searchTerm: null,
        employees: [],
        errors: [],
      },
    };
  },

  created() {
    this.localProject = this.project;
    this.localDecisions = this.decisions;
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    showAddMode() {
      this.addMode = true;
      this.potentialMembers = [];
      this.showDeciders = false;
      this.form.searchTerm = null;

      this.$nextTick(() => {
        this.$refs.newDecision.focus();
      });
    },

    showActionModal(decision) {
      this.decisionToDelete = decision.id;
    },

    hideAction() {
      this.decisionToDelete = 0;
      this.deleteActionConfirmation = false;
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.localProject.id}/decisions/search`, this.form)
            .then(response => {
              this.potentialMembers = _.filter(response.data.data, employee => _.every(this.form.employees, e => employee.id !== e.id));
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
            });
        } else {
          this.potentialMembers = [];
        }
      }, 500),

    add(employee) {
      var id = this.form.employees.findIndex(x => x.id === employee.id);

      if (id == -1) {
        this.form.employees.push(employee);
        this.potentialMembers = [];
        this.showDeciders = false;
        this.form.searchTerm = null;
      }
    },

    detach(employee) {
      var id = this.form.employees.findIndex(member => member.id === employee.id);
      this.form.employees.splice(id, 1);
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.localProject.id}/decisions/store`, this.form)
        .then(response => {
          localStorage.success = this.$t('project.decision_index_add_success');
          this.localDecisions.unshift(response.data.data);
          this.addMode = false;
          this.loadingState = '';
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroy(decision) {
      axios.delete(`/${this.$page.props.auth.company.id}/company/projects/${this.localProject.id}/decisions/${decision.id}`)
        .then(response => {
          var id = this.localDecisions.findIndex(x => x.id == decision.id);
          this.localDecisions.splice(id, 1);
          this.deleteActionConfirmation = false;

          this.flash(this.$t('project.decision_index_destroy_success'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
