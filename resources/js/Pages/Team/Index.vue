<style scoped>
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $page.auth.company.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_team_list') }}
          </li>
        </ul>
      </div>

      <div class="cf mw7 center">
        <!-- LEFT COLUMN -->

        <div v-for="team in teams" :key="team.id" class="bg-white box mb4 pa3">
          <inertia-link :href="'/' + $page.auth.company.id + '/teams/' + team.id" class="">{{ team.name }}</inertia-link>
          <div v-html="team.parsed_description"></div>
          <div class="fl">
            <img v-for="employee in employees" :key="employee.id" :src="employee.avatar" />>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
    };
  },

  mounted() {
    if (localStorage.success) {
      this.$snotify.success(localStorage.success, {
        timeout: 2000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true,
      });
      localStorage.clear();
    }
  },

  methods: {
  }
};

</script>
