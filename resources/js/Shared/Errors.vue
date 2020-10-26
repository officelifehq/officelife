<style lang="scss" scoped>
.border-red {
  background-color: #fff5f5;
  border-color: #fc8181;
  color: #c53030;
}
</style>

<template>
  <div>
    <div v-if="localErrors.length > 0" class="border-red ba br3 pa3" :class="classes">
      <p class="mv0 fw6">{{ $t('app.error_title') }}</p>
      <p class="mb0">{{ localErrors[0] }}</p>
      <template v-if="localErrors[1]">
        <p v-for="item in localErrors[1]" :key="item.index" class="mb0 mt2">{{ item[0] }}</p>
      </template>
    </div>
    <template v-if="localErrors.message">
      <div class="border-red ba br3 pa3" :class="classes">
        <p class="mv0 fw6">{{ localErrors.message }}</p>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    classes: {
      type: String,
      default: '',
    },
    errors: {
      type: Array,
      default: () => [],
    }
  },

  data() {
    return {
      localErrors: [],
    };
  },

  watch: {
    errors(value) {
      this.localErrors = value;
    }
  },

  mounted() {
    this.localErrors = this.errors;
  },
};
</script>
