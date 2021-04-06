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
  <div>
    <!-- case of no one working from home -->
    <h4 v-show="workFromHomes.length == 0" class="db fw5 mb3 flex justify-between items-center">
      {{ $t('dashboard.team_work_from_home_blank') }}
    </h4>

    <div v-show="workFromHomes.length != 0">
      <h3 class="db fw5 mb3 flex justify-between items-center">
        🏡 {{ $t('dashboard.team_work_from_home_title') }}
      </h3>

      <div class="mb4 bg-white box cf">
        <!-- all people working from homes -->
        <div v-for="employee in workFromHomes" :key="employee.id" class="pa3 fl w-third-l w-100" data-cy="work-from-home-list">
          <span class="pl3 db relative team-member">
            <img loading="lazy" :src="employee.avatar" class="br-100 absolute avatar" alt="avatar" />

            <!-- normal mode -->
            <inertia-link :href="employee.url" class="mb2">
              {{ employee.name }}
            </inertia-link>

            <!-- position -->
            <span v-if="employee.position" class="title db f7 mt1">
              {{ employee.position.title }}
            </span>
            <span v-else class="title db f7 mt1">
              {{ $t('app.no_position_defined') }}
            </span>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    workFromHomes: {
      type: Array,
      default: () => [],
    },
  },
};
</script>
