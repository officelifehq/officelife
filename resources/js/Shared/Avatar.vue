<style lang="scss" scoped>
span {
  margin-left: 29px;
}
</style>

<template>
  <img :loading="loading" :class="classes" class="pointer" :src="avatar.normal" :width="size"
       :height="size"
       :srcset="avatar.normal + ' 1x,' + avatar.retina + ' 2x'" :alt="alt"
       @click="navigateTo()"
  />
</template>

<script>
export default {
  props: {
    avatar: {
      type: Object,
      default: null,
    },
    classes: {
      type: String,
      default: null,
    },
    url: {
      type: String,
      default: null,
    },
    size: {
      type: Number,
      default: 0,
    },
    loading: {
      type: String,
      default: 'lazy',
    },
    alt: {
      type: String,
      default: 'avatar',
    }
  },

  methods: {
    navigateTo() {
      if (this.url) {
        this.$inertia.visit(this.url);
      }
    },
  },
};
</script>
