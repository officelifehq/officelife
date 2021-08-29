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
        <!-- add a new member -->
        <div v-if="!showModal" class="tr mb2">
          <a href="#" class="btn dib-l db mb3 mb0-ns" data-cy="member-add-button" @click.prevent="displayAddModal()">{{ $t('project.members_index_add_cta') }}</a>
        </div>

        <!-- add a new member -->
        <div v-if="showModal" class="bg-white box mb3 pa3">
          <form @submit.prevent="search">
            <!-- errors -->
            <template v-if="form.errors.length > 0">
              <div class="cf pb1 w-100 mb2">
                <errors :errors="form.errors" />
              </div>
            </template>

            <!-- search form -->
            <div class="mb3 relative">
              <p class="mt0 relative">{{ $t('group.members_add_cta') }} <a href="#" class="absolute right-0 f6" @click.prevent="showModal = false">{{ $t('app.cancel') }}</a></p>
              <div class="relative">
                <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                       :placeholder="$t('group.members_add_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required
                       @keyup="search()" @keydown.esc="showModal = false"
                />
                <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
              </div>
            </div>
          </form>

          <!-- RESULTS -->
          <div class="pl0 list ma0">
            <ul v-if="potentialMembers.length > 0" class="list ma0 pl0">
              <li v-for="member in potentialMembers" :key="member.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
                {{ member.name }}
                <a class="absolute right-1 pointer" :data-cy="'potential-group-member-' + member.id" @click.prevent="store(member)">
                  {{ $t('app.add') }}
                </a>
              </li>
            </ul>
          </div>
          <div v-if="potentialMembers.length == 0" class="silver">
            {{ $t('app.no_results') }}
          </div>
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
                  <a class="c-delete mr1 pointer" :data-cy="'list-delete-confirm-button-' + member.id" @click.prevent="remove(member)">
                    {{ $t('app.yes') }}
                  </a>
                  <a class="pointer" :data-cy="'list-delete-cancel-button-' + member.id" @click.prevent="idToDelete = 0">
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
            {{ $t('group.members_index_blank') }}
          </h3>
          <img loading="lazy" src="/img/streamline-icon-cyclist-1-4@140x140.png" width="140" height="140" alt="people hanging out"
               class="di-ns dn top-1 left-1"
          />
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
import 'vue-loaders/dist/vue-loaders.css';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    GroupMenu,
    'ball-pulse-loader': BallPulseLoader.component,
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
    members: {
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
