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
        <project-menu :project="project" :tab="tab" />
      </div>

      <div class="mw6 center br3 mb5 relative z-1">
        <!-- members list -->
        <div class="cf center">
          test
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import ProjectMenu from '@/Pages/Project/Partials/ProjectMenu';

export default {
  components: {
    Layout,
    ProjectMenu,
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
        employee: null,
        role: null,
        errors: [],
      },
    };
  },

  created() {
    this.localProject = this.project;
    this.localMembers = this.members.members;
    this.localRoles = this.members.roles;
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
  }
};

</script>
