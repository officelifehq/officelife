<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 2px;
  width: 35px;
}

.team-member {
  padding-left: 44px;
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

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-70-l w-100">
            <!-- project status -->
            <project-updates :project="localProject" :permissions="permissions" />

            <!-- Project description -->
            <description :project="localProject" />
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <div class="bg-white box mb4">
              <!-- lead by -->
              <project-lead :project="localProject" />

              <!-- links -->
              <project-links :project="localProject" :permissions="permissions" />
            </div>

            <!-- actions -->
            <status :project="localProject" />

            <ul class="list pl0">
              <li class="mb2 pl2"><inertia-link :href="localProject.url_edit" data-cy="project-edit" class="f6 gray">{{ $t('project.summary_edit') }}</inertia-link></li>
              <li class="pl2"><inertia-link :href="localProject.url_delete" data-cy="project-delete" class="f6 gray c-delete">{{ $t('project.summary_delete') }}</inertia-link></li>
            </ul>
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
import Description from '@/Pages/Company/Project/Partials/Description';
import Status from '@/Pages/Company/Project/Partials/Status';
import ProjectLead from '@/Pages/Company/Project/Partials/ProjectLead';
import ProjectLinks from '@/Pages/Company/Project/Partials/ProjectLinks';
import ProjectUpdates from '@/Pages/Company/Project/Partials/ProjectUpdates';

export default {
  components: {
    Layout,
    Breadcrumb,
    ProjectMenu,
    Description,
    Status,
    ProjectLead,
    ProjectLinks,
    ProjectUpdates,
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
    projectDetails: {
      type: Object,
      default: null,
    },
    permissions: {
      type: Object,
      default: null,
    },
    tab: {
      type: String,
      default: 'summary',
    },
  },

  created() {
    this.localProject = this.projectDetails;
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
