<style lang="scss" scoped>
.task-list:not(:last-child) {
  margin-bottom: 35px;
}

.add-list-section {
  top: 5px;
  background-color: #f5f5f5;
}

.edit-cta {
  top: -2px;
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects'">{{ $t('app.breadcrumb_project_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_project_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <!-- Menu -->
        <project-menu :project="project" :tab="tab" />
      </div>

      <div class="mw7 center br3 mb5 relative z-1">
        <p class="db fw5 mb2 flex justify-between items-center">
          <span>
            <span class="mr1">🗄</span> {{ $t('project.task_title') }}

            <help :url="$page.props.help_links.project_tasks" :top="'3px'" />
          </span>
        </p>

        <div class="bg-white box pa3">
          <!-- list of files -->
          <div class="flex flex-wrap">
            <div class="w-25">
              f
            </div>
            <div class="w-25">
              f
            </div>
            <div class="w-25">
              f
            </div>
            <div class="w-25">
              f
            </div>
            <div class="w-25">
              f
            </div>
            <div class="w-25">
              f
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import ProjectMenu from '@/Pages/Company/Project/Partials/ProjectMenu';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    ProjectMenu,
    Help,
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
      loadingState: '',
      addListMode: false,
      editListMode: false,
      listToEdit: 0,
      localTasks: null,
      localTaskLists: null,
      form: {
        title: null,
        description: null,
        errors: [],
      },
    };
  },
};

</script>
