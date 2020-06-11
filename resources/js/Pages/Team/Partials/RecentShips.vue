<style lang="scss" scoped>
</style>

<template>
  <div>
    <h3 class="db fw5 mb3 flex justify-between items-center">
      <span>🚀 Recent ships</span>

      <inertia-link v-if="teamMemberOrAtLeastHR()" :href="'/' + $page.auth.company.id + '/teams/' + team.id + '/ships/create'" class="btn f5" data-cy="add-recent-ship-entry">Write a new entry</inertia-link>
    </h3>

    <div class="mb4 bg-white box cf">
      <!-- blank state -->
      <div v-show="recentShips.length == 0" class="pa3 tc" data-cy="recent-ships-list-blank-state">
        <p class="mv0">This team hasn’t written any recent ship entries for now.</p>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    recentShips: {
      type: Array,
      default: null,
    },
    team: {
      type: Object,
      default: null,
    },
    userBelongsToTheTeam: {
      type: Boolean,
      default: false,
    }
  },

  methods: {
    teamMemberOrAtLeastHR() {
      if (this.$page.auth.employee.permission_level <= 200) {
        return true;
      }

      if (this.userBelongsToTheTeam == false) {
        return false;
      }

      return true;
    }
  }
};

</script>
