<style lang="scss" scoped>
.icon-type {
  display: inline-block;
  top: 3px;
  border-radius: 3px;
}
</style>

<template>
  <span class="relative icon-type" :style="classes">
  </span>
</template>

<script>
export default {
  props: {
    width: {
      type: String,
      default: '17px',
    },
    height: {
      type: String,
      default: '17px',
    },
    backgroundColor: {
      type: String,
      default: '#fff',
    },
  },

  data() {
    return {
      classes: '',
    };
  },

  watch: {
    classes(value) {
      return this.dataReport ?? this.report;
    }
  },

  mounted() {
    this.classes = 'width: ' + this.width;
    this.classes += '; height: ' + this.height;
    this.classes += '; background-color: ' + this.backgroundColor;
  },
};
</script>
