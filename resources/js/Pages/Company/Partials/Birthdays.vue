<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.person {
  padding-left: 44px;

  &:last-child {
    margin-bottom: 0;
  }

  .avatar {
    top: 2px;
  }
}
</style>

<template>
  <div class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        🎂
      </span> {{ $t('company.birthdays_title') }}
    </span>

    <div class="br3 bg-white box z-1 pa3 relative">
      <ul v-if="birthdays.length != 0" class="list pl0 ma0">
        <li v-for="birthday in birthdays" :key="birthday.id" class="mb3 pl3 db relative person">
          <avatar :avatar="birthday.avatar" :size="35" :class="'br-100 absolute avatar'" />

          <!-- normal mode -->
          <inertia-link :href="birthday.url" class="mb2">
            {{ birthday.name }}
          </inertia-link>

          <!-- birthdate information -->
          <span class="title db f7 mt1">
            {{ $t('dashboard.team_birthdate_date', { date: birthday.birthdate}) }}
          </span>
        </li>
      </ul>

      <!-- blank state -->
      <div v-if="birthdays.length == 0" class="pa3 tc f6">
        😢 {{ $t('company.birthdays_blank') }}
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
      default: null,
    },
  },
};
</script>
