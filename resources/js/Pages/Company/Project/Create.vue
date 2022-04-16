<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';

.avatar {
  left: 1px;
  top: 5px;
  width: 23px;
}

.team-member {
  padding-left: 34px;

  .avatar {
    top: 6px;
    left: 7px;
  }
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
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/projects/'"
                  :previous="$t('app.breadcrumb_project_list')"
      >
        {{ $t('app.breadcrumb_project_create') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('project.create_title') }}

            <help :url="$page.props.help_links.team_recent_ship_create" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Name -->
            <text-input :id="'name'"
                        v-model="form.name"
                        :name="'name'"
                        :datacy="'project-name-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('project.create_input_name')"
                        :help="$t('project.create_input_name_help')"
                        :required="true"
                        :autofocus="true"
            />

            <!-- Code -->
            <p v-if="!showCode" class="bt bb-gray pt3 pointer" data-cy="add-code" @click.prevent="showCode = true"><span class="ba br-100 plus-button">+</span> {{ $t('project.create_input_add_project_code') }}</p>
            <text-input v-if="showCode" :id="'code'"
                        v-model="form.code"
                        :name="'code'"
                        :datacy="'project-code-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('project.create_input_code')"
                        :help="$t('project.create_input_help')"
                        :maxlength="191"
                        @esc-key-pressed="showCode = false"
            />

            <!-- Short code -->
            <text-input v-if="showCode" :id="'short_code'"
                        v-model="form.short_code"
                        :name="'short_code'"
                        :errors="$page.props.errors.short_code"
                        :label="$t('project.create_input_short_code')"
                        :help="$t('project.create_input_short_code_help')"
                        :maxlength="3"
                        :required="true"
                        @esc-key-pressed="showCode = false"
            />

            <!-- Summary -->
            <p v-if="!showSummary" class="bt bb-gray pt3 pointer" data-cy="add-summary" @click.prevent="showSummary = true"><span class="ba br-100 plus-button">+</span> {{ $t('project.create_input_add_summary') }}</p>
            <text-area v-if="showSummary"
                       v-model="form.summary"
                       :label="$t('project.create_input_summary')"
                       :datacy="'project-summary-input'"
                       :required="false"
                       :rows="10"
                       :help="$t('project.create_input_summary_help')"
                       @esc-key-pressed="showSummary = false"
            />

            <p v-if="!showAssignProjectLead && !form.projectLead" class="bt bb-gray pt3 pointer" data-cy="project-assign-project-lead" @click.prevent="showAssignProjectLead = true"><span class="ba br-100 plus-button">+</span> {{ $t('project.create_input_add_project_lead') }}</p>

            <div v-if="showAssignProjectLead == true" class="bb bb-gray bt pt3">
              <form class="relative" @submit.prevent="search">
                <text-input :id="'name'"
                            v-model="form.searchTerm"
                            :name="'name'"
                            :datacy="'project-lead-search'"
                            :errors="$page.props.errors.name"
                            :label="$t('project.create_input_project_lead')"
                            :required="false"
                            autocomplete="off"
                            @keyup="search"
                            @update:model-value="search"
                            @esc-key-pressed="showAssignProjectLead = false"
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

            <div v-if="form.projectLead" class="mb3 mt2 bt bb-gray pt3">
              <p class="mt0 db fw4 lh-copy f6 mb1">Lead by</p>
              <span class="ba bb-gray br3 pa2 pl3 db relative team-member">
                <avatar :avatar="form.projectLead.avatar" :size="23" :class="'br-100 absolute avatar'" />

                {{ form.projectLead.name }}

                <!-- remove -->
                <a href="#" class="db f7 mt1 c-delete dib fr" :data-cy="'remove-project-lead-' + form.projectLead.id" @click.prevent="unassignProjectLead()">
                  {{ $t('app.remove') }}
                </a>
              </span>
            </div>

            <!-- Actions -->
            <div class="mb4 mt5">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects/'" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.create')" :cypress-selector="'submit-create-project-button'" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import TextArea from '@/Shared/TextArea';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    TextInput,
    TextArea,
    Errors,
    LoadingButton,
    'ball-pulse-loader': BallPulseLoader.component,
    Help
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      showCode: false,
      showSummary: false,
      showAssignProjectLead: false,
      form: {
        name: null,
        code: null,
        short_code: null,
        summary: null,
        description: null,
        searchTerm: null,
        projectLead: null,
        errors: [],
      },
      processingSearch: false,
      loadingState: '',
      potentialMembers: [],
      errorTemplate: Error,
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects`, this.form)
        .then(response => {
          this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post(`/${this.$page.props.auth.company.id}/company/projects/search`, this.form)
            .then(response => {
              this.potentialMembers = response.data.data;
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
      this.form.projectLead = employee;
      this.potentialMembers = [];
      this.showAssignProjectLead = false;
      this.form.searchTerm = null;
    },

    unassignProjectLead() {
      this.form.projectLead = null;
      this.potentialMembers = [];
      this.showAssignProjectLead = false;
      this.form.searchTerm = null;
    },
  }
};

</script>
