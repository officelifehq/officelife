<template>
  <div>
    <div class="bg-white box mb4">
      <!-- title + status -->
      <div class="pv3 ph3 fw5 flex justify-between items-center">
        <!-- if a status is already defined -->
        <div v-if="project.latest_update">
          <p v-if="project.latest_update.status == 'on_track'" class="ma0"><span class="mr1">😇</span> {{ $t('project.summary_project_latest_update_on_track') }}</p>
          <p v-if="project.latest_update.status == 'at_risk'" class="ma0"><span class="mr1">🥴</span> {{ $t('project.summary_project_latest_update_at_risk') }}</p>
          <p v-if="project.latest_update.status == 'late'" class="ma0"><span class="mr1">🙀</span> {{ $t('project.summary_project_latest_update_late') }}</p>
        </div>

        <!-- if no status yet -->
        <p v-else>{{ $t('project.summary_project_latest_update_no_status') }}</p>

        <inertia-link v-if="permissions.can_edit_latest_update" :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/status'" class="btn f5">{{ $t('project.summary_project_latest_update_cta') }}</inertia-link>
      </div>

      <!-- description + author -->
      <div v-if="project.latest_update" class="pa3 bt bb-gray">
        <h2 class="fw4 mt0 mb2">
          {{ project.latest_update.title }}
        </h2>
        <div class="parsed-content lh-copy mt0 mb0" v-html="project.latest_update.description"></div>
        <h3 v-if="project.latest_update.author" class="ttc f7 gray mt2 mb2 fw4">
          {{ $t('project.summary_project_latest_update_written', { name: project.latest_update.author.name, date: project.latest_update.written_at }) }}
        </h3>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    project: {
      type: Object,
      default: null,
    },
    permissions: {
      type: Object,
      default: null,
    },
  },
};

</script>
