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

.icon-date {
  width: 15px;
  top: 2px;
}

.icon-role {
  width: 15px;
  top: 2px;
}

.information {
  flex: 1 0 128px;
}

.list-no-line-bottom {
  li:last-child {
    border-bottom: 0;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;
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
        <project-menu :project="project" :tab="tab" />

        <!-- members list -->
        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-20-l w-100">
            <div class="bg-white box mb3 db-ns dn">
              <ul class="list pl0 ma0 list-no-line-bottom">
                <li class="ph3 pv2 fw6 bb bb-gray">Current roles</li>
                <li v-for="role in localRoles" :key="role.id" class="bb bb-gray ph3 pv2 bb-gray-hover lh-copy f6">
                  {{ role.role }}
                </li>
                <li v-if="localRoles.length == 0" class="ph3 pv2 bb-gray-hover">{{ $t('project.members_index_blank_role') }}</li>
              </ul>
            </div>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-80-l w-100 pl4-l">
            <div v-if="!showModal" class="flex-ns justify-between items-center mb2">
              <span class="mb2 mb0-ns db di-ns">
                {{ $tc('project.members_index_count', localMembers.length, { count: localMembers.length }) }}
              </span>
              <a href="#" class="btn dib-l db mb3 mb0-ns" data-cy="member-add-button" @click.prevent="displayAddModal()">{{ $t('project.members_index_add_cta') }}</a>
            </div>

            <!-- add a new member -->
            <div v-if="showModal" class="bg-white box mb3">
              <form @submit.prevent="submit">
                <h2 class="mv0 fw4 f4 pa3 bb bb-gray">
                  {{ $t('project.members_index_add_title') }}
                </h2>

                <div class="pa3 bb bb-gray">
                  <div class="measure">
                    <!-- employee -->
                    <label class="db mb-2">
                      {{ $t('project.members_index_add_select_title') }}
                    </label>
                    <a-select
                      v-model:value="form.employee_id"
                      :placeholder="$t('project.members_index_add_select_placeholder')"
                      style="width: 200px"
                      :options="potentialMembers"
                      show-search
                      option-filter-prop="label"
                    />
                    <p class="lh-copy">{{ $t('project.members_index_add_role') }}</p>

                    <!-- choose role (optional) -->
                    <ul :class="showNewRoleInputField ? 'mb0' : 'mb3'" class="list pl0 ma0">
                      <!-- no role -->
                      <li>
                        <input id="no-role" type="radio" name="roles" class="mr1 relative" checked
                               @click="setNoRole()"
                        />
                        <label for="no-role" class="pointer">
                          {{ $t('project.members_index_add_role_no_role') }}
                        </label>
                      </li>
                      <!-- existing roles -->
                      <li v-for="role in localRoles" :key="role.id" @click="showNewRoleInputField = false">
                        <input :id="'role_' + role.id" v-model="form.role" type="radio" class="mr1 relative"
                               :value="role.role" name="roles"
                        />
                        <label :for="'role_' + role.id" class="pointer">
                          {{ role.role }}
                        </label>
                      </li>

                      <!-- custom role -->
                      <li>
                        <input id="new-role" type="radio" name="roles" class="mr1 relative" value="new"
                               data-cy="custom-role-field" @click="displayNewRoleInput()"
                        />
                        <label for="new-role" class="pointer">
                          {{ $t('project.members_index_add_role_create_new_one') }}
                        </label>
                      </li>
                    </ul>

                    <!-- custom role -->
                    <div v-if="showNewRoleInputField" class="pl3">
                      <!-- role -->
                      <text-input :id="'role'"
                                  :ref="'newRole'"
                                  v-model="form.role"
                                  :datacy="'customRole'"
                                  :name="'role'"
                                  :errors="$page.props.errors.role"
                                  :required="true"
                      />
                    </div>
                  </div>
                </div>

                <!-- Actions -->
                <div class="pa3 cf flex-ns">
                  <loading-button :class="'btn add mr2 w-auto-ns w-100 pv2 ph3 db dib-ns mb3 mb0-ns'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-add-member'" />
                  <a class="btn dib-ns db tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2" data-cy="cancel-button" @click.prevent="showModal = false">
                    {{ $t('app.cancel') }}
                  </a>
                </div>
              </form>
            </div>

            <!-- members list -->
            <div v-if="localMembers.length > 0" class="bg-white box">
              <ul class="list pl0 ma0 list-no-line-bottom">
                <li v-for="member in localMembers" :key="member.id" :data-cy="'member-' + member.id" class="pa3 bb bb-gray flex items-center">
                  <!-- avatar -->
                  <div class="mr3">
                    <avatar :avatar="member.avatar" :size="64" :class="'br-100'" />
                  </div>

                  <!-- name + information -->
                  <div class="information">
                    <inertia-link :href="member.url" class="mb2 dib">{{ member.name }}</inertia-link>

                    <span v-if="member.role" class="db f7 mb2 relative">
                      <!-- icon role -->
                      <svg class="relative icon-role gray" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                      </svg>
                      <span class="mr2">
                        {{ member.role }}
                      </span>

                      <!-- icon date -->
                      <span>
                        <svg class="relative icon-date gray" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                      </span>
                      <span class="gray">
                        {{ $t('project.members_index_role', { date: member.added_at }) }}
                      </span>
                    </span>
                    <span v-if="member.position && member.role" class="db f7 gray">{{ $t('project.members_index_position_with_role', { role: member.position.title }) }}</span>
                    <span v-if="member.position && !member.role" class="db f7 gray">{{ member.position.title }}</span>
                  </div>

                  <!-- actions -->
                  <div>
                    <a v-if="idToDelete != member.id" :data-cy="'member-delete-' + member.id" class="c-delete f6" href="#" @click.prevent="showDeleteModal(member)">{{ $t('app.remove') }}</a>

                    <div v-if="removeMode && idToDelete == member.id">
                      {{ $t('app.sure') }}
                      <a class="c-delete mr1 pointer" :data-cy="'list-delete-confirm-button-' + member.id" @click.prevent="remove(member.id)">
                        {{ $t('app.yes') }}
                      </a>
                      <a class="pointer" :data-cy="'list-delete-cancel-button-' + member.id" @click.prevent="removeMode = false; idToDelete = 0">
                        {{ $t('app.no') }}
                      </a>
                    </div>
                  </div>
                </li>
              </ul>
            </div>

            <!-- blank member lists -->
            <div v-if="localMembers.length == 0" class="bg-white box pa3 tc" data-cy="members-blank-state">
              <h3 class="fw4 f5">
                {{ $t('project.members_index_blank') }}
              </h3>
              <img loading="lazy" src="/img/streamline-icon-cyclist-1-4@140x140.png" width="140" height="140" alt="people hanging out"
                   class="di-ns dn top-1 left-1"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import ProjectMenu from '@/Pages/Company/Project/Partials/ProjectMenu';
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    ProjectMenu,
    TextInput,
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
      localRoles: null,
      showModal: false,
      removeMode: false,
      idToDelete: 0,
      showNewRoleInputField: false,
      potentialMembers: null,
      loadingState: '',
      form: {
        employee_id: null,
        role: null,
        errors: [],
      },
    };
  },

  created() {
    this.localMembers = this.members.members;
    this.localRoles = this.members.roles;
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
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
        this.$refs.newRole.focus();
      });
    },

    displayAddModal() {
      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/members/search`)
        .then(response => {
          this.potentialMembers = response.data.data.potential_members;
          this.showModal = true;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    showDeleteModal(member) {
      this.removeMode = true;
      this.idToDelete = member.id;
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/members`, this.form)
        .then(response => {
          this.flash(this.$t('project.members_index_add_success'), 'success');
          this.loadingState = null;
          this.form.role = null;
          this.form.employee_id = null;
          this.showModal = false;
          this.localMembers.unshift(response.data.data);
          this.updateRoles();
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    remove(employee_id) {
      axios.delete(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/members/${employee_id}`)
        .then(response => {
          this.flash(this.$t('project.members_index_remove_success'), 'success');

          var id = this.localMembers.findIndex(x => x.id == employee_id);
          this.localMembers.splice(id, 1);
          this.updateRoles();
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    updateRoles() {
      let roles = _.map(this.localMembers, member => {
        return { id: member.id, role: member.role };
      });
      this.localRoles = _.sortBy(_.uniqBy(roles, 'role'), 'role');
    },
  }
};

</script>
