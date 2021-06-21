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
  top: -2px;
  color: #737e91;
  border: 1px solid #b3d4ff;
}
</style>

<template>
  <div>
    <h2 class="tc normal mb2 lh-copy">
      {{ group.name }}
    </h2>

    <div class="relative tc w-100 mb4">
      <div class="flex items-center justify-center">
        <avatar v-for="member in group.members" :key="member.id" :avatar="member.avatar" :size="32" :class="'br-100 small-avatar'" />
        <!-- <div v-if="group.other_members_counter > 0" class="pl2 f7 more-members relative gray">
          {{ $tc('project.menu_other_member', project.other_members_counter, { count: project.other_members_counter }) }}
        </div> -->
      </div>
    </div>

    <div class="center br3 mb5 tc">
      <div class="cf dib btn-group">
        <inertia-link :class="{'selected':(tab == 'info')}" :href="'/' + $page.props.auth.company.id + '/company/groups/' + group.id + ''" class="f6 fl ph3 pv2 dib pointer no-underline">
          {{ $t('group.index_menu_summary') }}
        </inertia-link>
        <inertia-link :class="{'selected':(tab == 'meetings')}" :href="'/' + $page.props.auth.company.id + '/company/groups/' + group.id + '/meetings'" class="f6 fl ph3 pv2 dib pointer">
          {{ $t('group.index_menu_meetings') }}
        </inertia-link>
        <inertia-link :class="{'selected':(tab == 'members')}" :href="'/' + $page.props.auth.company.id + '/company/groups/' + group.id + '/members'" class="f6 fl ph3 pv2 dib pointer">
          {{ $t('group.index_menu_members') }}
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
    group: {
      type: Object,
      default: null,
    },
    tab: {
      type: String,
      default: 'info',
    },
  },
};

</script>
