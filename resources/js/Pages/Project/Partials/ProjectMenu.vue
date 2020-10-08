<style lang="scss" scoped>
</style>

<template>
  <div class="center br3 mb5 tc">
    <div class="cf dib btn-group">
      <inertia-link :href="'/' + $page.auth.company.id + '/projects/1'" class="f6 fl ph3 pv2 dib pointer no-underline">
        {{ $t('project.menu_summary') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.auth.company.id + '/projects/1/messages'" class="f6 fl ph3 pv2 dib pointer no-underline">
        {{ $t('project.menu_messages') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
        {{ $t('project.menu_tasks') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
        {{ $t('project.menu_calendar') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
        {{ $t('project.menu_members') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
        {{ $t('project.menu_finance') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
        {{ $t('project.menu_files') }}
      </inertia-link>
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
  },
};

</script>
