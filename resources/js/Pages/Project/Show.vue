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
          {{ localProject.name }} <span v-if="localProject.code" class="ml2 ttu f7 project-code br3 pa1 relative fw4">
            {{ localProject.code }}
          </span>
        </h2>
        <p class="tc mt0 mb4">{{ localProject.summary }}</p>

        <!-- Menu -->
        <project-menu :tab="tab" />

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-70-l w-100">
            <!-- Project description -->
            <description :project="project" />

            <div>
              <div class="mb2 fw5 relative flex justify-between items-center">
                <div>
                  <span class="mr1">
                    👩‍🏫
                  </span> {{ $t('project.summary_status') }}
                </div>

                <inertia-link :href="'/' + $page.props.auth.company.id + '/projects/' + project.id + '/status'" class="btn f5" data-cy="add-recent-ship-entry">Update status</inertia-link>
              </div>

              <div class="bg-white box mb4 pa3">
                <h3 class="ttc f7 gray mt0 mb2 fw4">
                  Written by Michael Scott on Oct 4th
                </h3>
                <p class="lh-copy mt0 mb0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.</p>
              </div>
            </div>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <!-- actions -->
            <status :project="project" />

            <div class="bg-white box mb2">
              <!-- lead by -->
              <project-lead :project="project" />

              <!-- links -->
              <project-links :project="project" />
            </div>

            <ul class="list pl0">
              <li class="mb2 pl2"><inertia-link :href="localProject.url_edit" data-cy="project-edit" class="f6 gray">{{ $t('project.summary_edit') }}</inertia-link></li>
              <li class="pl2"><inertia-link :href="localProject.url_delete" data-cy="project-delete" class="f6 gray">{{ $t('project.summary_delete') }}</inertia-link></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import ProjectMenu from '@/Pages/Project/Partials/ProjectMenu';
import Description from '@/Pages/Project/Partials/Description';
import Status from '@/Pages/Project/Partials/Status';
import ProjectLead from '@/Pages/Project/Partials/ProjectLead';
import ProjectLinks from '@/Pages/Project/Partials/ProjectLinks';

export default {
  components: {
    Layout,
    ProjectMenu,
    Description,
    Status,
    ProjectLead,
    ProjectLinks,
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
    tab: {
      type: String,
      default: 'summary',
    },
  },

  data() {
    return {
    };
  },

  created() {
    this.localProject = this.project;
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
