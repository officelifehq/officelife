<style lang="scss" scoped>
.small-avatar {
  margin-left: -8px;
  box-shadow: 0 0 0 2px #fff;
}

.more-members {
  height: 32px;
  top: 8px;
}

.project-code {
  padding-bottom: 2px;
  top: -4px;
  color: #737e91;
  border: 1px solid #b3d4ff;
}
</style>

<template>
  <div>
    <div class="bg-white box pa3 mb4 flex justify-between items-center">
      <!-- name + summary -->
      <div class="pl3">
        <h2 :class="project.summary ? 'mb2': 'mb0'" class="mt0 relative fw4" data-cy="project-name">
          {{ project.name }}
          <span v-if="project.code" class="ml1 ttu f7 project-code code br3 pv1 ph2 relative fw4">
            {{ project.code }}
          </span>
        </h2>
        <p v-if="project.summary" class="mt0 mb1 f6 gray">{{ project.summary }}</p>
      </div>

      <!-- avatars -->
      <div v-if="project.members.length > 0">
        <p class="mt0 mb2 f7 gray">{{ $t('project.menu_members_icons') }}</p>
        <div class="flex items-center relative tr">
          <avatar v-for="member in project.members" :key="member.id" :avatar="member.avatar" :size="32" :class="'br-100 small-avatar'" />
          <div v-if="project.other_members_counter > 0" class="pl2 f7 more-members relative gray">
            {{ $tc('project.menu_other_member', project.other_members_counter, { count: project.other_members_counter }) }}
          </div>
        </div>
      </div>
    </div>

    <div class="center br3 mb5 tc">
      <div class="cf dib btn-group">
        <inertia-link :class="{'selected':(tab == 'summary')}" :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + ''" class="f6 fl ph3 pv2 dib pointer no-underline">
          {{ $t('project.menu_summary') }}
        </inertia-link>
        <!-- <inertia-link :class="{'selected':(tab == 'boards')}" :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/boards'" class="f6 fl ph3 pv2 dib pointer no-underline">
          {{ $t('project.menu_boards') }}
        </inertia-link> -->
        <inertia-link :class="{'selected':(tab == 'decisions')}" :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/decisions'" class="f6 fl ph3 pv2 dib pointer no-underline">
          {{ $t('project.menu_decision_logs') }}
        </inertia-link>
        <inertia-link :class="{'selected':(tab == 'messages')}" :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/messages'" class="f6 fl ph3 pv2 dib pointer no-underline">
          {{ $t('project.menu_messages') }}
        </inertia-link>
        <inertia-link :class="{'selected':(tab == 'tasks')}" :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/tasks'" class="f6 fl ph3 pv2 dib pointer">
          {{ $t('project.menu_tasks') }}
        </inertia-link>
        <!-- <inertia-link :class="{'selected':(tab == 'calendar')}" :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/messages'" class="f6 fl ph3 pv2 dib pointer">
          {{ $t('project.menu_calendar') }}
        </inertia-link> -->
        <inertia-link :class="{'selected':(tab == 'members')}" :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/members'" class="f6 fl ph3 pv2 dib pointer">
          {{ $t('project.menu_members') }}
        </inertia-link>
        <!-- <inertia-link :class="{'selected':(tab == 'finance')}" :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/messages'" class="f6 fl ph3 pv2 dib pointer">
          {{ $t('project.menu_finance') }}
        </inertia-link> -->
        <inertia-link :class="{'selected':(tab == 'files')}" :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/files'" class="f6 fl ph3 pv2 dib pointer">
          {{ $t('project.menu_files') }}
        </inertia-link>
      </div>
    </div>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Avatar,
  },

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
