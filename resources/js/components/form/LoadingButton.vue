<template>
  <div class="di">
    <button :class="classes" name="save" type="submit" :data-cy="cypressSelector">
      <ball-pulse-loader color="#218b8a" size="7px" v-if="state == 'loading'"></ball-pulse-loader>
      <span v-if="state != 'loading'">{{ text }}</span>
    </button>
  </div>
</template>

<script>

import 'vue-loaders/dist/vue-loaders.css'
import * as VueLoaders from 'vue-loaders'
Vue.use(VueLoaders)

export default {
  props: [
    'text',
    'state',
    'classes',
    'cypressSelector'
  ],

  methods: {
  }
}
</script>
