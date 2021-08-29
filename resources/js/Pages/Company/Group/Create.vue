<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 23px;
}

.team-member {
  padding-left: 34px;

  .avatar {
    top: -2px;
    left: 2px;
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
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/groups/'"
                  :previous="$t('app.breadcrumb_group_list')"
      >
        {{ $t('app.breadcrumb_group_create') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('group.create_title') }}

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
                        :label="$t('group.create_input_name')"
                        :help="$t('group.create_input_name_help')"
                        :required="true"
                        :autofocus="true"
            />

            <!-- list of people who are part of this group -->
            <p v-if="!showGroupMembers && form.employees.length == 0" class="bt bb-gray pt3 pointer" data-cy="group-add-employees" @click.prevent="showGroupMembers = true"><span class="ba br-100 plus-button">+</span> Add members to the group</p>
            <p v-if="!showGroupMembers && form.employees.length > 0" class="bt bb-gray pt3 pointer" data-cy="group-add-employees" @click.prevent="showGroupMembers = true"><span class="ba br-100 plus-button">+</span> Add additional members</p>

            <div v-if="showGroupMembers == true" class="bb bb-gray bt pt3">
              <form class="relative" @submit.prevent="search">
                <text-input :id="'name'"
                            v-model="form.searchTerm"
                            :name="'name'"
                            :datacy="'group-employees'"
                            :errors="$page.props.errors.name"
                            :label="$t('group.create_members')"
                            :placeholder="$t('group.create_members_help')"
                            :required="false"
                            @keyup="search"
                            @input="search"
                            @esc-key-pressed="showGroupMembers = false"
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

            <div v-show="form.employees.length > 0" class="ba bb-gray mb3 mt3">
              <div v-for="employee in form.employees" :key="employee.id" class="pa2 db bb-gray bb" data-cy="members-list">
                <span class="pl3 db relative team-member">
                  <avatar :avatar="employee.avatar" :size="23" :class="'avatar absolute br-100'" />

                  {{ employee.name }}

                  <!-- remove -->
                  <a href="#" class="db f7 mt1 c-delete dib fr" :data-cy="'remove-employee-' + employee.id" @click.prevent="detach(employee)">
                    {{ $t('app.remove') }}
                  </a>
                </span>
              </div>
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
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import 'vue-loaders/dist/vue-loaders.css';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';
import Help from '@/Shared/Help';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    TextInput,
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
      showGroupMembers: false,
      form: {
        name: null,
        searchTerm: null,
        employees: [],
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

      axios.post(`/${this.$page.props.auth.company.id}/company/groups`, this.form)
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

          axios.post(`/${this.$page.props.auth.company.id}/company/groups/search`, this.form)
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
        this.showGroupMembers = false;
        this.form.searchTerm = null;
      }
    },

    detach(employee) {
      var id = this.form.employees.findIndex(member => member.id === employee.id);
      this.form.employees.splice(id, 1);
    }
  }
};

</script>
