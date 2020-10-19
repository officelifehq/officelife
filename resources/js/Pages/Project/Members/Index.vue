<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 2px;
  width: 35px;
}

.team-member {
  padding-left: 44px;
}

.project-code {
  padding-bottom: 2px;
  top: -2px;
  color: #737e91;
  border: 1px solid #b3d4ff;
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
        <h2 class="tc mb2 relative" data-cy="project-name">
          {{ localProject.name }} <span v-if="localProject.code" class="ml2 ttu f7 project-code code br3 pa1 relative fw4">
            {{ localProject.code }}
          </span>
        </h2>
        <p class="tc mt0 mb4">{{ localProject.summary }}</p>

        <!-- Menu -->
        <project-menu :project="project" :tab="'members'" />

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-20-l w-100">
            <p>Test</p>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-80-l w-100 pl4-l">
            <div class="flex justify-between items-center mb2">
              <span>This</span>
              <a v-if="!showModal" href="#" class="btn dib-l db" @click.prevent="displayAddModal()">Add a new member</a>
            </div>

            <!-- add a new member -->
            <div v-if="showModal" class="pa3 bg-white box mb3">
              <form @submit.prevent="submit">
                <div class="measure">
                  <h2>Add a member</h2>

                  <!-- employee -->
                  <select-box :id="'employee'"
                              v-model="form.employee"
                              :options="potentialMembers"
                              :name="'employee'"
                              :errors="$page.props.errors.employee"
                              :label="'Select an employee'"
                              :placeholder="'Choose someone or type a few letters'"
                              :required="true"
                              :value="form.employee"
                              :datacy="'country_selector'"
                  />
                  <p>Do you want to specify a role for this new member? You don’t have to, but it can be useful for others to understand his/her role.</p>
                  <ul class="list pl0 ma0">
                    <li>
                      <input id="no-role" type="radio" name="roles" class="mr1 relative" checked
                             @click="setNoRole()"
                      />
                      <label for="no-role" class="pointer">
                        No role
                      </label>
                    </li>
                    <li v-for="role in roles" :key="role.id" @click="showNewRoleInputField = false">
                      <input :id="'role_' + role.id" v-model="form.role" type="radio" class="mr1 relative"
                             :value="role.role" name="roles"
                      />
                      <label :for="'role_' + role.id" class="pointer">
                        {{ role.role }}
                      </label>
                    </li>
                    <li>
                      <input id="new-role" type="radio" name="roles" class="mr1 relative" value="new"
                             @click="displayNewRoleInput()"
                      />
                      <label for="new-role" class="pointer">
                        Or create a new role
                      </label>
                    </li>
                  </ul>

                  <!-- custom role -->
                  <div v-if="showNewRoleInputField" class="pl3">
                    <!-- role -->
                    <text-input :id="'role'"
                                :ref="'newRole'"
                                v-model="form.role"
                                :name="'role'"
                                :errors="$page.props.errors.role"
                                :required="true"
                    />
                  </div>
                </div>

                <!-- Actions -->
                <div class="cf flex">
                  <loading-button :classes="'btn add mr2 w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-unlock-employee-button'" />
                  <a class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2" data-cy="cancel-button" @click.prevent="showModal = false">
                    {{ $t('app.cancel') }}
                  </a>
                </div>
              </form>
            </div>

            <!-- members list -->
            <div class="bg-white box">
              <ul class="list pl0 ma0">
                <li v-for="member in localMembers" :key="member.id" class="pa3 bb bb-gray flex">
                  <div class="mr3">
                    <img :src="member.avatar" alt="avatar" class="br-100" />
                  </div>
                  <div class="">
                    <span class="mb2 dib">{{ member.name }}</span>
                    <span v-if="member.position" class="db f7 gray mb2">{{ member.position.title }}</span>
                    <span v-if="member.role" class="db f7 gray">{{ $t('project.members_index_role', { role: member.role, date: member.added_at }) }}</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import ProjectMenu from '@/Pages/Project/Partials/ProjectMenu';
import TextInput from '@/Shared/TextInput';
import SelectBox from '@/Shared/Select';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    ProjectMenu,
    TextInput,
    SelectBox,
    LoadingButton,
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
    members: {
      type: Object,
      default: null,
    },
    tab: {
      type: String,
      default: 'summary',
    },
  },

  data() {
    return {
      localMembers: null,
      showModal: false,
      showNewRoleInputField: false,
      potentialMembers: null,
      roles: null,
      loadingState: '',
      form: {
        employee: null,
        role: null,
        errors: [],
      },
    };
  },

  created() {
    this.localProject = this.project;
    this.localMembers = this.members.members;
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    setNoRole() {
      this.showNewRoleInputField = false;
      this.form.role = null;
    },

    displayNewRoleInput() {
      this.showNewRoleInputField = true;
      this.form.role = null;

      this.$nextTick(() => {
        this.$refs['newRole'].$refs['input'].focus();
      });
    },

    displayAddModal() {
      axios.get('/' + this.$page.props.auth.company.id + '/projects/' + this.project.id + '/members/search')
        .then(response => {
          this.potentialMembers = response.data.data.potential_members;
          this.roles = response.data.data.roles;
          this.showModal = true;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/projects/' + this.project.id + '/members/store', this.form)
        .then(response => {
          this.loadingState = null;
          this.form.role = null;
          this.form.employee = null;
          this.showModal = false;
          this.localMembers.unshift(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
