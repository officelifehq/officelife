<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
  }
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5">
      🤟 {{ $t('dashboard.team_hired_at_title') }}

      <help :url="$page.props.help_links.employee_hiring_date" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <div v-for="employee in newHires" :key="employee.id" class="pa3 w-100" data-cy="new-hires-list">
        <span class="pl3 db relative team-member">
          <avatar :avatar="employee.avatar" :size="35" :class="'br-100 absolute avatar'" />

          <!-- normal mode -->
          <inertia-link :href="employee.url" class="mb2">
            {{ employee.name }}
          </inertia-link>

          <!-- position -->
          <span v-if="employee.position" class="title db f7 mt1">
            {{ $t('dashboard.team_hired_at_date_with_position', { date: employee.hired_at, position: employee.position.title }) }}
          </span>
          <span v-else class="title db f7 mt1">
            {{ $t('dashboard.team_hired_at_date', { date: employee.hired_at }) }}
          </span>
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';
import Help from '@/Shared/Help';

export default {
  components: {
    Avatar,
    Help,
  },

  props: {
    newHires: {
      type: Array,
      default: () => [],
    },
  },
};
</script>
