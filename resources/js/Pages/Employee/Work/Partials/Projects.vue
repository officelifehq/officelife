<style lang="scss" scoped>
.project-avatar {
  img {
    width: 17px;
  }

  text-decoration: none;
  border-bottom: none;
}

.projects-list:last-child {
  border-bottom: 0;
}

.projects-list:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.projects-list:last-child:hover {
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
}

.project-code {
  padding-bottom: 2px;
  top: -2px;
  color: #737e91;
  border: 1px solid #b3d4ff;
}

.project-stat:not(:last-child):after {
  content: '/';
  color: gray;
  padding-left: 10px;
  padding-right: 5px;
}
</style>

<template>
  <div>
    <span class="db fw5 mb2">
      <span class="mr1">
        👨‍💻
      </span> {{ $t('employee.projects_title', { name: employee.name }) }}

      <help :url="$page.props.help_links.team_recent_ship" :top="'2px'" />
    </span>

    <div class="mb4 bg-white box cf">
      <!-- list of projects -->
      <div v-show="projects.length > 0">
        <div v-for="project in projects" :key="project.id" class="pa3 bb bb-gray bb-gray-hover w-100 flex justify-between items-center projects-list">
          <!-- project information -->
          <div class="">
            <span class="dib mb2">
              {{ project.name }}
              <span v-if="project.code" class="ml2 ttu f7 project-code code br3 pa1 relative fw4">
                {{ project.code }}
              </span>
            </span>
            <ul class="list ma0 pl0 f7 gray">
              <li v-if="project.role" class="di project-stat">{{ $t('employee.projects_role', { role: project.role }) }}</li>
              <li class="di project-stat">{{ $tc('employee.projects_messages_written', project.messages_count, { count: project.messages_count }) }}</li>
              <li class="di project-stat">{{ $tc('employee.projects_tasks_assigned', project.tasks_count, { count: project.tasks_count }) }}</li>
            </ul>
          </div>

          <!-- details -->
          <div class="">
            <inertia-link :href="project.url" class="ma0 pa0 di f6" :data-cy="'project-list-item-' + project.id">{{ $t('app.view') }}</inertia-link>
          </div>
        </div>
      </div>

      <!-- blank state -->
      <div v-show="projects.length == 0" class="pa3" data-cy="recent-ships-list-blank-state">
        <p class="mb0 mt0 lh-copy f6">{{ $t('employee.projects_blank') }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';

export default {
  components: {
    Help
  },

  props: {
    projects: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
  },
};

</script>
