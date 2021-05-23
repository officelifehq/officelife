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
      🥳 {{ $t('dashboard.team_birthdate_title') }}
    </div>

    <div v-show="teams.length != 0" class="cf mw7 center br3 mb3 bg-white box">
      <!-- case of no birthday -->
      <template v-if="birthdays.length == 0">
        <div class="pa3 tc" data-cy="team-birthdate-blank">
          😢 {{ $t('dashboard.team_birthdate_blank') }}
        </div>
      </template>

      <!-- all birthdays -->
      <div v-for="employee in birthdays" :key="employee.id" class="pa3 fl w-third-l w-100" data-cy="birthdays-list">
        <span class="pl3 db relative team-member">
          <avatar :avatar="employee.avatar" :size="35" :class="'br-100 absolute avatar'" />

          <!-- normal mode -->
          <inertia-link :href="employee.url" class="mb2">
            {{ employee.name }}
          </inertia-link>

          <!-- birthdate information -->
          <span class="title db f7 mt1">
            {{ $t('dashboard.team_birthdate_date', { date: employee.birthdate}) }}
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
    birthdays: {
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
