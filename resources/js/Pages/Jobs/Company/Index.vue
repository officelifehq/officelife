<style lang="scss" scoped>
img {
  max-height: 100px;
  max-width: 100px;
}
</style>

<template>
  <div class="ph2 ph5-ns">
    <div class="mw7 center br3 mb5 bg-white box relative z-1">
      <div class="flex">
        <img :src="data.company.logo" :alt="data.company.name" />
        <div>
          <h3>{{ data.company.name }}</h3>
          <ul class="list pl0 ma0">
            <li class="di mr2">{{ data.company.location }}</li>
            <li class="di">{{ data.company.count }}</li>
          </ul>
        </div>
      </div>

      <div v-for="jobOpening in data.job_openings" :key="jobOpening.id">
        <h2>{{ jobOpening.title }} <span>{{ jobOpening.reference_number }}</span></h2>
        <p>{{ jobOpening.team.name }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      default: null,
    },
  },
};
</script>
