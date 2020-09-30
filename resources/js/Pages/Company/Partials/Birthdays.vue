<style lang="scss" scoped>
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
        <li v-for="birthday in birthdays" :key="birthday.id">
          <span class="pl3 db relative team-member">
            <img loading="lazy" :src="birthday.avatar" class="br-100 absolute avatar" alt="avatar" />

            <!-- normal mode -->
            <inertia-link :href="birthday.url" class="mb2">
              {{ birthday.name }}
            </inertia-link>

            <!-- birthdate information -->
            <span class="title db f7 mt1">
              {{ $t('dashboard.team_birthdate_date', { date: birthday.birthdate}) }}
            </span>
          </span>
        </li>
      </ul>

      <!-- blank state -->
      <div v-if="birthdays.length == 0" class="pa3 tc">
        😢 {{ $t('company.birthdays_blank') }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    birthdays: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {};
  },
};
</script>
