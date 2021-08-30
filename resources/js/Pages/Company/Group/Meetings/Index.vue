<style lang="scss" scoped>
.small-avatar:not(:first-child) {
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
      <breadcrumb
        :root-url="'/' + $page.props.auth.company.id + '/company/groups'"
        :root="$t('app.breadcrumb_group_list')"
        :has-more="false"
      >
        {{ $t('app.breadcrumb_group_detail') }}
      </breadcrumb>

      <div class="mw7 center br3 mb5 relative z-1">
        <!-- Menu -->
        <group-menu :group="group" :tab="tab" />
      </div>

      <div class="mw6 center br3 mb5 relative z-1">
        <!-- add a new meeting -->
        <div class="tr mb2">
          <inertia-link :href="data.url_new" class="btn dib-l db mb3 mb0-ns" data-cy="member-add-button">{{ $t('group.meeting_index_cta') }}</inertia-link>
        </div>

        <!-- list of meetings -->
        <div v-if="data.meetings.length > 0" class="br3 bg-white box z-1">
          <ul class="list pl0 ma0">
            <li v-for="meeting in data.meetings" :key="meeting.id" class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between meeting-item">
              <div class="mb1 relative">
                <inertia-link :href="meeting.url" class="employee-name db">
                  {{ meeting.happened_at }}
                </inertia-link>
              </div>
              <span v-if="meeting.preview_members" class="ma0 mb0 f7 grey">
                <div class="flex items-center relative tr">
                  <avatar v-for="member in meeting.preview_members" :key="member.id" :avatar="member.avatar" :url="member.url" :size="25"
                          :class="'br-100 small-avatar'"
                  />
                  <div v-if="meeting.remaining_members_count > 0" class="pl2 f7 more-members relative gray">
                    + {{ meeting.remaining_members_count }}
                  </div>
                </div>
              </span>
            </li>
          </ul>
        </div>

        <!-- blank state -->
        <div v-else class="br3 bg-white box z-1 pa3 tc">
          <img loading="lazy" src="/img/streamline-icon-factory-engineer-3@140x140.png" width="140" height="140" alt="meeting"
               class=""
          />
          <h3 class="fw4 f5 lh-copy">
            {{ $t('group.meeting_index_blank') }}
          </h3>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';
import GroupMenu from '@/Pages/Company/Group/Partials/GroupMenu';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
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
    data: {
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
      processingSearch: false,
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

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
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
          this.flash(this.$t('project.members_index_add_success'), 'success');
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
          this.flash(this.$t('project.members_index_remove_success'), 'success');

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
