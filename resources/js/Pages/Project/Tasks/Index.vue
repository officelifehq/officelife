<style lang="scss" scoped>

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
        <!-- Menu -->
        <project-menu :project="project" :tab="tab" />
      </div>

      <div class="mw7 center br3 mb5 relative z-1">
        <p class="db fw5 mb2 flex justify-between items-center">
          <span>
            <span class="mr1">🪑</span> Tasks

            <help :url="$page.props.help_links.project_messages" :top="'3px'" />
          </span>
          <inertia-link :href="'/' + $page.props.auth.company.id + '/projects/' + project.id + '/messages/create'" class="btn f5" data-cy="add-message">New task list</inertia-link>
        </p>

        <!-- list of tasks without a list -->
        <div class="bg-white box pa3">
          <project-tasks :tasks="tasks.tasks_without_lists" :members="members" :project="project" />

          <div v-for="taskList in tasks.task_lists" :key="taskList.id">
            <h3 class="f4 fw5 mb2">
              {{ taskList.title }}
            </h3>
            <p v-if="taskList.description" class="f6 gray mt1">{{ taskList.description }}</p>
            <project-tasks :tasks="taskList.tasks" :task-list="taskList" :members="members" :project="project" />
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import ProjectMenu from '@/Pages/Project/Partials/ProjectMenu';
import ProjectTasks from '@/Pages/Project/Tasks/Partials/ProjectTasks';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    ProjectMenu,
    Help,
    ProjectTasks,
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
    tasks: {
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
    };
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
