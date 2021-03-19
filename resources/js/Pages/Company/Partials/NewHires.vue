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
        😎
      </span> {{ $t('company.new_hires_title') }}
    </span>

    <div class="br3 bg-white box z-1 pa3 relative">
      <ul v-if="hires.length != 0" class="list pl0 ma0">
        <li v-for="hire in hires" :key="hire.id" class="mb3 pl3 db relative person">
          <img loading="lazy" :src="hire.avatar" class="br-100 absolute avatar" alt="avatar" />

          <!-- normal mode -->
          <inertia-link :href="hire.url" class="mb2">
            {{ hire.name }}
          </inertia-link>

          <!-- position -->
          <span v-if="hire.position" class="title db f7 mt1 lh-copy">
            {{ $t('company.new_hires_date_with_position', { date: hire.hired_at, position: hire.position }) }}
          </span>
          <span v-else class="title db f7 mt1">
            {{ $t('company.new_hires_date', { date: hire.hired_at }) }}
          </span>
        </li>
      </ul>

      <!-- blank state -->
      <div v-if="hires.length == 0" class="tc f6">
        {{ $t('company.new_hires_blank') }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    hires: {
      type: Array,
      default: null,
    },
  },
};
</script>
