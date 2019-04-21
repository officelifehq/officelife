<style scoped>
</style>

<template>
  <layout title="Home" :user="user">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw7 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a>
          </li>
          <li class="di">
            <a :href="'/' + company.id + '/teams'">{{ $t('app.breadcrumb_team_list') }}</a>
          </li>
          <li class="di">
            {{ team.name }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb4 bg-white box relative z-1">
        <div class="pa3 relative">
          <h2 class="normal ma0">
            {{ team.name }}
          </h2>
        </div>
      </div>

      <div class="cf mw9 center">
        <!-- LEFT COLUMN -->
        <div class="fl w-40-l w-100">
        </div>

        <!-- RIGHT COLUMN -->
        <div class="flex items-center justify-center flex-column mb4">
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import ClickOutside from 'vue-click-outside'

export default {
  directives: {
    ClickOutside
  },

  props: {
    company: {
      type: Object,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
    employees: {
      type: Array,
      default: null,
    },
    team: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
    }
  },

  mounted() {
    if (localStorage.success) {
      this.$snotify.success(localStorage.success, {
        timeout: 2000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true,
      })
      localStorage.clear()
    }
  },

  methods: {
  }
}

</script>
