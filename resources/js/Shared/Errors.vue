<style lang="scss" scoped>
.border-red {
  background-color: #fff5f5;
  border-color: #fc8181;
  color: #c53030;
}
</style>

<template>
  <div>
    <div v-if="errors.length > 0" class="border-red ba br3 pa3" :class="classes">
      <p class="mv0 fw6">{{ $t('app.error_title') }}</p>
      <p class="mb0">{{ errors[0] }}</p>
      <template v-if="errors[1]">
        <p v-for="item in errors[1]" :key="item.index" class="mb0 mt2">{{ item[0] }}</p>
      </template>
    </div>
    <template v-if="errors.message">
      <div class="border-red ba br3 pa3" :class="classes">
        <p class="mv0 fw6">{{ errors.message }}</p>
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
    }
  },

  data() {
    return {
      errors: [],
    };
  },
};
</script>
