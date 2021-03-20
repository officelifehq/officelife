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
        <p class="ma0">{{ $t('company.teams_total', { count: statistics.number_of_teams }) }}</p>
      </div>

      <!-- random teams -->
      <div v-for="team in teams.random_teams" :key="team.id" class="pa3 bb bb-gray">
        <div class="flex items-center justify-between relative tr">
          <inertia-link :href="team.url" class="ma0">{{ team.name }}</inertia-link>
          <div class="flex items-center">
            <div class="di">
              <img v-for="employee in team.employees" :key="employee.id" :src="employee.avatar" alt="avatar" class="br-100 small-avatar pointer"
                   width="32" height="32" @click="navigateTo(employee)"
              />
            </div>

            <div v-if="team.total_remaining_employees > 0" class="pl2 f7 more-members di relative gray">
              {{ $t('company.total_remaining_employees', { count: team.total_remaining_employees }) }}
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
export default {
  props: {
    statistics: {
      type: Object,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
  },

  methods: {
    navigateTo(employee) {
      this.$inertia.visit(employee.url);
    },
  },
};
</script>
