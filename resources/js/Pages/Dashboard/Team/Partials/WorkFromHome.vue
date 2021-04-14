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
      🏡 {{ $t('dashboard.team_work_from_home_title') }}
    </div>

    <div v-show="teams.length != 0" class="cf mw7 center br3 mb3 bg-white box">
      <!-- case of no one working from home -->
      <template v-if="workFromHomes.length == 0">
        <div class="pa3 tc" data-cy="team-work-from-home-blank">
          {{ $t('dashboard.team_work_from_home_blank') }}
        </div>
      </template>

      <!-- all people working from homes -->
      <div v-for="employee in workFromHomes" :key="employee.id" class="pa3 fl w-third-l w-100" data-cy="work-from-home-list">
        <span class="pl3 db relative team-member">
          <avatar :avatar="employee.avatar" :url="employee.url" :size="35" :class="'br-100 absolute avatar'" />

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
</template>

<script>
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Avatar,
  },

  props: {
    workFromHomes: {
      type: Array,
      default: () => [],
    },
    teams: {
      type: Array,
      default: () => [],
    },
  },
};
</script>
