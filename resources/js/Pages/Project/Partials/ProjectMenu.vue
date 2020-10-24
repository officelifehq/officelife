<style lang="scss" scoped>
</style>
k
<template>
  <div class="center br3 mb5 tc">
    <div class="cf dib btn-group">
      <inertia-link :class="{'selected':(tab == 'summary')}" :href="'/' + $page.props.auth.company.id + '/projects/' + project.id + ''" class="f6 fl ph3 pv2 dib pointer no-underline">
        {{ $t('project.menu_summary') }}
      </inertia-link>
      <inertia-link :class="{'selected':(tab == 'decisions')}" :href="'/' + $page.props.auth.company.id + '/projects/' + project.id + '/messages'" class="f6 fl ph3 pv2 dib pointer no-underline">
        Decision logs
      </inertia-link>
      <inertia-link :class="{'selected':(tab == 'messages')}" :href="'/' + $page.props.auth.company.id + '/projects/' + project.id + '/messages'" class="f6 fl ph3 pv2 dib pointer no-underline">
        {{ $t('project.menu_messages') }}
      </inertia-link>
      <inertia-link :class="{'selected':(tab == 'tasks')}" :href="'/' + $page.props.auth.company.id + '/projects/' + project.id + '/messages'" class="f6 fl ph3 pv2 dib pointer">
        {{ $t('project.menu_tasks') }}
      </inertia-link>
      <inertia-link :class="{'selected':(tab == 'calendar')}" :href="'/' + $page.props.auth.company.id + '/projects/' + project.id + '/messages'" class="f6 fl ph3 pv2 dib pointer">
        {{ $t('project.menu_calendar') }}
      </inertia-link>
      <inertia-link :class="{'selected':(tab == 'members')}" :href="'/' + $page.props.auth.company.id + '/projects/' + project.id + '/members'" class="f6 fl ph3 pv2 dib pointer">
        {{ $t('project.menu_members') }}
      </inertia-link>
      <inertia-link :class="{'selected':(tab == 'finance')}" :href="'/' + $page.props.auth.company.id + '/projects/' + project.id + '/messages'" class="f6 fl ph3 pv2 dib pointer">
        {{ $t('project.menu_finance') }}
      </inertia-link>
      <inertia-link :class="{'selected':(tab == 'files')}" :href="'/' + $page.props.auth.company.id + '/projects/' + project.id + '/messages'" class="f6 fl ph3 pv2 dib pointer">
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
    tab: {
      type: String,
      default: 'summary',
    },
  },
};

</script>
