<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';
</style>

<template>
  <div class="di">
    <button name="save" type="submit" :data-cy="cypressSelector" :disabled="loading"
            v-bind="$attrs" :class="defaultClass"
            @click="$emit('click')"
    >
      <ball-pulse-loader v-show="loading" color="#fff" />
      <span v-show="!state">
        <span v-if="emoji" class="mr2">
          {{ emoji }}
        </span>
        {{ text }}
        <slot></slot>
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
      type: [String, Boolean],
      default: false,
    },
    defaultClass: {
      type: String,
      default: 'btn w-auto-ns w-100 pv2 ph3'
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

  emits: ['click'],

  computed: {
    loading() {
      return typeof this.state === 'string' ? this.state === 'loading' : this.state;
    }
  }
};
</script>
