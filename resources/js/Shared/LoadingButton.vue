<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';
</style>

<template>
  <div class="di">
    <button name="save" type="submit" :data-cy="cypressSelector" :disabled="state == 'loading'"
            v-bind="$attrs"
            @click="$emit('click')"
    >
      <ball-pulse-loader v-if="state == 'loading'" color="#fff" />
      <span v-if="state != 'loading'">
        <span v-if="emoji" class="mr2">
          {{ emoji }}
        </span>
        {{ text }}
      </span>
    </button>
  </div>
</template>

<script>
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';

export default {

  components: {
    'ball-pulse-loader': BallPulseLoader.component,
  },
  inheritAttrs: false,

  props: {
    text: {
      type: String,
      default: '',
    },
    state: {
      type: String,
      default: '',
    },
    emoji: {
      type: String,
      default: null,
    },
    cypressSelector: {
      type: String,
      default: '',
    },
  },

  emits: [
    'click'
  ]
};
</script>
