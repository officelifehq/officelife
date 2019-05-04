<style lang="scss" scoped>
</style>

<template>
  <div>
    <span class="number">{{ action.id }}</span>
    Notify <span class="bb b--dotted bt-0 bl-0 br-0 pointer">an employee</span> with <span class="bb b--dotted bt-0 bl-0 br-0 pointer">a message</span>
    <img src="/img/common/triple-dots.svg" class="absolute right-0 pointer actions-dots" />
  </div>
</template>

<script>

export default {
  props: {
    action: {
      type: Object,
      default: null,
    }
  },

  data() {
    return {
    }
  },

  mounted() {

  },

  methods: {
  }
}

</script>
