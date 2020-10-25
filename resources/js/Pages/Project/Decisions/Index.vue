<style lang="scss" scoped>
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
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/projects'">{{ $t('app.breadcrumb_project_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_project_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <!-- Menu -->
        <project-menu :project="localProject" :tab="tab" />
      </div>

      <div class="mw6 center br3 mb5 relative z-1">
        <p class="mt0 mb3 tr">
          <a href="#" class="btn f5" data-cy="add-recent-ship-entry" @click.prevent="showAddMode">Log a new decision</a>
        </p>

        <!-- log a new decision -->
        <div class="bg-white box pa3 mb3">
          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Title -->
            <text-input :id="'title'"
                        v-model="form.title"
                        :name="'title'"
                        :datacy="'recent-ship-title-input'"
                        :errors="$page.props.errors.title"
                        :label="'What’s the decision?'"
                        :help="'Everyone in the company will be able to read this decision.'"
                        :required="true"
            />

            <!-- list of people who decided -->
            <p v-if="!showDeciders && form.employees.length == 0" class="bt bb-gray pt3 pointer" data-cy="ship-add-employees" @click.prevent="showDeciders = true"><span class="ba br-100 plus-button">+</span> Who made the decision?</p>
            <p v-if="!showDeciders && form.employees.length > 0" class="bt bb-gray pt3 pointer" data-cy="ship-add-employees" @click.prevent="showDeciders = true"><span class="ba br-100 plus-button">+</span> Add an additional decider</p>

            <div v-if="showDeciders == true" class="bb bb-gray bt pt3">
              <form class="relative" @submit.prevent="search">
                <text-input :id="'name'"
                            v-model="form.searchTerm"
                            :name="'name'"
                            :datacy="'ship-employees'"
                            :errors="$page.props.errors.name"
                            :label="$t('team.recent_ship_new_credit')"
                            :placeholder="$t('team.recent_ship_new_credit_help')"
                            :required="false"
                            @keyup="search"
                            @input="search"
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
                  <img loading="lazy" :src="employee.avatar" class="br-100 absolute avatar" alt="avatar" />

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
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/projects/' + localProject.id" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'submit-add-ship-button'" />
              </div>
            </div>
          </form>
        </div>

        <!-- list of decisions -->
        <div v-if="decisions.length > 0" class="bg-white box">
          <ul class="list pl0 mv0 list-no-line-bottom">
            <li v-for="decision in localDecisions" :key="decision.id" class="bb bb-gray pa3 relative">
              <!-- decision title -->
              <p class="ma0 mb3 fw5">{{ decision.title }}</p>

              <!-- date + decided by -->
              <div class="flex">
                <!-- date -->
                <span class="f7 mr4">
                  <span class="db gray mb2">Decided on</span>
                  <span>{{ decision.decided_at }}</span>
                </span>

                <!-- Decided by -->
                <div v-if="decision.deciders.length > 0">
                  <span class="db mb2 gray f7">Decided by</span>
                  <ul class="list pl0">
                    <li v-for="decider in decision.deciders" :key="decider.id" class="di mr2">
                      <small-name-and-avatar
                        :name="decider.name"
                        :avatar="decider.avatar"
                        :classes="'f4 fw4'"
                        :top="'0px'"
                        :margin-between-name-avatar="'29px'"
                      />
                    </li>
                  </ul>
                </div>
              </div>

              <!-- Image to trigger actions -->
              <img loading="lazy" src="/img/common/triple-dots.svg" alt="triple dot symbol" class="absolute right-0 pointer actions-dots" @click="showActionModal(decision)" />

              <!-- Actions available -->
              <div v-if="decision.id == decisionToDelete" v-click-outside="hideAction" class="popupmenu action-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal">
                <ul class="list ma0 pa0">
                  <li v-show="!deleteActionConfirmation" class="pv2 relative">
                    <icon-delete :classes="'icon-delete relative'" :width="15" :height="15" />
                    <a class="pointer ml1 c-delete" @click.prevent="deleteActionConfirmation = true">
                      Delete decision
                    </a>
                  </li>
                  <li v-show="deleteActionConfirmation" class="pv2">
                    {{ $t('app.sure') }}
                    <a class="c-delete mr1 pointer" @click.prevent="destroy(decision)">
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
        <div v-if="decisions.length == 0" class="bg-white box pa3 tc">
          <img loading="lazy" src="/img/streamline-icon-factory-engineer-3@140x140.png" width="140" height="140" alt="meeting"
               class=""
          />
          <p class="lh-copy">Keeping a log of decisions is beneficial to improve communication between members and stakeholders of the project, and employees of the company in general.</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import ProjectMenu from '@/Pages/Project/Partials/ProjectMenu';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import vClickOutside from 'v-click-outside';
import IconDelete from '@/Shared/IconDelete';
import 'vue-loaders/dist/vue-loaders.css';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    ProjectMenu,
    SmallNameAndAvatar,
    IconDelete,
    TextInput,
    Errors,
    LoadingButton,
    'ball-pulse-loader': BallPulseLoader.component,
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
      editMode: false,
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
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    showActionModal(decision) {
      this.decisionToDelete = decision.id;
    },

    hideAction() {
      this.decisionToDelete = 0;
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post('/' + this.$page.props.auth.company.id + '/projects/' + this.localProject.id + '/decisions/search', this.form)
            .then(response => {
              let searchResults = response.data.data;

              // filter out the employees that are already in the list of employees
              // there is probably a much better way to do this, but i don't know how
              for (let index = 0; index < this.form.employees.length; index++) {
                const employee = this.form.employees[index];
                let found = false;
                let otherIndex = 0;

                for (otherIndex = 0; otherIndex < searchResults.length; otherIndex++) {
                  if (employee.id == searchResults[otherIndex].id) {
                    found = true;
                    break;
                  }
                }

                if (found == true) {
                  searchResults.splice(otherIndex, 1);
                }
              }
              this.potentialMembers = searchResults;
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = _.flatten(_.toArray(error.response.data));
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

      axios.post('/' + this.$page.props.auth.company.id + '/projects/' + this.localProject.id + '/decisions/store', this.form)
        .then(response => {
          localStorage.success = this.$t('team.recent_ship_create_success');
          this.localDecisions.unshift(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    destroy(decision) {
      this.loadingState = 'loading';

      axios.delete('/' + this.$page.props.auth.company.id + '/projects/' + this.localProject.id + '/decisions/' + decision.id)
        .then(response => {
          var id = this.localDecisions.findIndex(x => x.id == decision.id);
          this.localDecisions.splice(id, 1);

          flash(this.$t('project.members_index_add_success'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
