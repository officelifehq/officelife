<style lang="scss" scoped>
.small-avatar:not(:first-child) {
  margin-left: -8px;
  box-shadow: 0 0 0 2px #fff;
}

.more-members {
  height: 32px;
  top: 8px;
}
</style>

<template>
  <div class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        👬
      </span> {{ $t('company.teams_title') }}
    </span>

    <div class="br3 bg-white box z-1 relative">
      <!-- general stats -->
      <div class="pa3 bb bb-gray">
        <p class="ma0">{{ $tc('company.teams_total', statistics.number_of_teams, { count: statistics.number_of_teams }) }}</p>
      </div>

      <!-- random teams -->
      <div v-for="team in teams.random_teams" :key="team.id" class="pa3 bb bb-gray">
        <div class="flex items-center justify-between relative tr">
          <inertia-link :href="team.url" class="ma0">{{ team.name }}</inertia-link>
          <div class="flex items-center">
            <div class="di">
              <avatar v-for="employee in team.employees" :key="employee.id" :url="employee.url" :avatar="employee.avatar" :size="32"
                      :class="'br-100 small-avatar pointer'"
              />
            </div>

            <div v-if="team.total_remaining_employees > 0" class="pl2 f7 more-members di relative gray">
              {{ $tc('company.total_remaining_employees', team.total_remaining_employees, { count: team.total_remaining_employees }) }}
            </div>
          </div>
        </div>
      </div>

      <div class="ph3 pv2 tc f6">
        <inertia-link :href="teams.view_all_url">{{ $t('company.teams_view_all') }}</inertia-link>
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
    statistics: {
      type: Object,
      default: null,
    },
    teams: {
      type: Object,
      default: null,
    },
  },
};
</script>
