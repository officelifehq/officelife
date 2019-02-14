<template>
  <div class="di">
    <button :class="classes" name="save" type="submit">
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
  props: ['text', 'state', 'classes'],

  methods: {
    submit() {
      axios.post('/signup', this.form)
        .then(response => {
          Turbolinks.visit('/home')
        })
        .catch(error => {
          if (typeof error.response.data === 'object') {
            this.form.errors = _.flatten(_.toArray(error.response.data))
          } else {
            this.form.errors = [this.$t('app.error_try_again')]
          }
        })
    },
  }
}
</script>
