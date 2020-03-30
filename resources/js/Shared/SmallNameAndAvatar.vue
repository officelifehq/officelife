<style lang="scss" scoped>
img {
  height: 22px;
  top: -2px;
  width: 22px;
}
span {
  margin-left: 29px;
}
</style>

<template>
  <div class="relative">
    <img :src="avatar" class="absolute br-100" alt="avatar" />
    <span>{{ name }}</span>
  </div>
</template>

<script>
export default {
  props: {
    name: {
      type: String,
      default: null,
    },
    avatar: {
      type: String,
      default: null,
    },
  },
};
</script>
