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
      🎖 {{ $t('dashboard.team_hired_at_anniversaries') }}

      <help :url="$page.props.help_links.employee_work_anniversaries" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <div v-for="employee in hiringDateAnniversaries" :key="employee.id" class="pa3 w-100" data-cy="new-hires-list">
        <span class="pl3 db relative team-member">
          <avatar :avatar="employee.avatar" :size="35" :class="'br-100 absolute avatar'" />

          <!-- normal mode -->
          <inertia-link :href="employee.url" class="mb2">
            {{ employee.name }}
          </inertia-link>

          <span class="title db f7 mt1">
            {{ $t('dashboard.team_hired_at_anniversary_detail', { age: employee.anniversary_age, date: employee.anniversary_date, company: $page.props.auth.company.name }) }}
          </span>
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Avatar,
    Help,
  },

  props: {
    hiringDateAnniversaries: {
      type: Array,
      default: () => [],
    },
  },
};
</script>
