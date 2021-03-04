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
        <img v-for="member in group.members" :key="member.id" :src="member.avatar" alt="avatar" class="br-100 small-avatar"
             width="32" height="32"
        />
        <!-- <div v-if="group.other_members_counter > 0" class="pl2 f7 more-members relative gray">
          {{ $t('project.menu_other_member', { count: project.other_members_counter }) }}
        </div> -->
      </div>
    </div>

    <div class="center br3 mb5 tc">
      <div class="cf dib btn-group">
        <inertia-link :class="{'selected':(tab == 'info')}" :href="'/' + $page.props.auth.company.id + '/company/groups/' + group.id + ''" class="f6 fl ph3 pv2 dib pointer no-underline">
          Summary
        </inertia-link>
        <inertia-link :class="{'selected':(tab == 'members')}" :href="'/' + $page.props.auth.company.id + '/company/groups/' + group.id + '/members'" class="f6 fl ph3 pv2 dib pointer">
          Members
        </inertia-link>
        <inertia-link :class="{'selected':(tab == 'members')}" :href="'/' + $page.props.auth.company.id + '/company/groups/' + group.id + '/members'" class="f6 fl ph3 pv2 dib pointer">
          {{ $t('project.menu_files') }}
        </inertia-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
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
