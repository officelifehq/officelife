<style lang="scss" scoped>
img {
  max-height: 100px;
  max-width: 100px;
}
</style>

<template>
  <div class="ph2 ph5-ns">
    <div class="mw7 center br3 mb5 bg-white box relative z-1">
      <div v-for="company in companies" :key="company.id" class="flex">
        <img :src="company.logo" :alt="company.name" />
        <div>
          <inertia-link :href="company.url">{{ company.name }}</inertia-link>
          <ul class="list pl0 ma0">
            <li class="di mr2">{{ company.location }}</li>
            <li class="di">{{ company.count }}</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    companies: {
      type: Array,
      default: null,
    },
  },
};
</script>
