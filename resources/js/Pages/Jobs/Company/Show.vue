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

      <h3>{{ data.job_opening.title }} <span>{{ data.job_opening.reference_number }}</span></h3>
      <p>{{ data.job_opening.team.name }}</p>

      <div class="parsed-content" v-html="data.job_opening.description"></div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      default: null,
    },
  },
};
</script>
