<template>
  <div class="di">
    <button :class="classes" name="save" type="submit" :data-cy="cypressSelector" :disabled="state == 'loading'"
            @click="$emit('click')"
    >
      <ball-pulse-loader v-if="state == 'loading'" color="#fff" size="7px" />
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
import 'vue-loaders/dist/vue-loaders.css';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';

export default {
  components: {
    'ball-pulse-loader': BallPulseLoader.component,
  },

  props: {
    text: {
      type: String,
      default: '',
    },
    state: {
      type: String,
      default: '',
    },
    classes: {
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
  }
};
</script>
