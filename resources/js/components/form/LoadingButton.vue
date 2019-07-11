<template>
  <div class="di">
    <button :class="classes" name="save" type="submit" :data-cy="cypressSelector">
      <ball-pulse-loader v-if="state == 'loading'" color="#218b8a" size="7px" />
      <span v-if="state != 'loading'">{{ text }}</span>
    </button>
  </div>
</template>

<script>

import 'vue-loaders/dist/vue-loaders.css';
import * as VueLoaders from 'vue-loaders';
Vue.use(VueLoaders);

export default {
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
    cypressSelector: {
      type: String,
      default: '',
    },
  },

  methods: {
  }
};
</script>
