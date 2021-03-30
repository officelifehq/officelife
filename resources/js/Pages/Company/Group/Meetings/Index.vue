<style lang="scss" scoped>
.small-avatar {
  margin-left: -8px;
  box-shadow: 0 0 0 2px #fff;
}

.meeting-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.meeting-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/groups'">{{ $t('app.breadcrumb_group_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_group_detail') }}
          </li>
        </ul>
      </div>

      <div class="mw7 center br3 mb5 relative z-1">
        <!-- Menu -->
        <group-menu :group="group" :tab="tab" />
      </div>

      <div class="mw6 center br3 mb5 relative z-1">
        <!-- add a new meeting -->
        <div class="tr mb2">
          <inertia-link :href="meetings.url_new" class="btn dib-l db mb3 mb0-ns" data-cy="member-add-button">Create new meeting</inertia-link>
        </div>

        <!-- list of meetings -->
        <div class="br3 bg-white box z-1">
          <ul class="list pl0 ma0">
            <li class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between meeting-item">
              <div class="mb1 relative">
                <span class="employee-name db">
                  Meeting du 20 janvier
                </span>
              </div>
              <span class="ma0 mb0 f7 grey">
                <div class="flex items-center relative tr">
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                  <div class="pl2 f7 more-members relative gray">
                    {{ $t('project.menu_other_member', { count: 3 }) }}
                  </div>
                </div>
              </span>
            </li>
            <li class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between meeting-item">
              <div class="mb1 relative">
                <span class="employee-name db">
                  Meeting du 20 janvier
                </span>
              </div>
              <span class="ma0 mb0 f7 grey">
                <div class="flex items-center relative tr">
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                </div>
              </span>
            </li>
            <li class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between meeting-item">
              <div class="mb1 relative">
                <span class="employee-name db">
                  Meeting du 20 janvier
                </span>
              </div>
              <span class="ma0 mb0 f7 grey">
                <div class="flex items-center relative tr">
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                </div>
              </span>
            </li>
            <li class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between meeting-item">
              <div class="mb1 relative">
                <span class="employee-name db">
                  Meeting du 20 janvier
                </span>
              </div>
              <span class="ma0 mb0 f7 grey">
                <div class="flex items-center relative tr">
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                  <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                       width="32" height="32"
                  />
                </div>
              </span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import GroupMenu from '@/Pages/Company/Group/Partials/GroupMenu';

export default {
  components: {
    Layout,
    GroupMenu,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    group: {
      type: Object,
      default: null,
    },
    meetings: {
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
      processingSearch: false,
      localMembers: null,
      showModal: false,
      removeMode: false,
      idToDelete: 0,
      potentialMembers: [],
      loadingState: '',
      form: {
        searchTerm: null,
        employee: null,
        role: null,
        errors: [],
      },
    };
  },

  created() {
    this.localMembers = this.members;
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    displayAddModal() {
      this.showModal = true;

      this.$nextTick(() => {
        this.$refs['search'].focus();
      });
    },

    showDeleteModal(member) {
      this.removeMode = true;
      this.idToDelete = member.id;
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.group.id}/members/search`, this.form)
            .then(response => {
              this.potentialMembers = _.filter(response.data.data, employee => _.every(this.form.employees, e => employee.id !== e.id));
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
            });
        }
      }, 500),

    store(employee) {
      this.loadingState = 'loading';
      this.form.employee = employee.id;

      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.group.id}/members/store`, this.form)
        .then(response => {
          flash(this.$t('project.members_index_add_success'), 'success');
          this.loadingState = null;
          this.form.employee = null;
          this.showModal = false;
          this.localMembers.unshift(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    remove(employee) {
      this.form.employee = employee.id;

      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.group.id}/members/remove`, this.form)
        .then(response => {
          flash(this.$t('project.members_index_remove_success'), 'success');

          var id = this.localMembers.findIndex(x => x.id == employee.id);
          this.localMembers.splice(id, 1);

          this.form.employee = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
