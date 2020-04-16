<style lang="scss" scoped>
.avatar {
  border: 2px solid #fff;
  height: 20px;
  width: 20px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 center breadcrumb relative z-0 f6 pb2" :class="{'bg-white box': teams.length == 0}">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_team_list') }}
          </li>
        </ul>
      </div>

      <div class="cf mw7 center" :class="{'bg-white box relative z-1': teams.length == 0}">
        <!-- list of teams -->
        <div v-for="team in teams" v-show="teams.length > 0" :key="team.id" class="bg-white box mb4 pa3">
          <div class="">
            <inertia-link :href="'/' + $page.auth.company.id + '/teams/' + team.id" class="">{{ team.name }}</inertia-link>
            <span>({{ team.employees.length }} members)</span>
          </div>
          <div v-html="team.parsed_description"></div>
          <ul v-show="team.employees.length > 0" class="list relative pl0 mb0">
            <li v-for="employee in team.employees" :key="employee.id" class="di relative">
              <img :src="employee.avatar" class="br-100 avatar pointer" alt="avatar" @click.prevent="load(employee)" />
            </li>
          </ul>
        </div>

        <!-- no teams yet in the account -->
        <div v-show="teams.length == 0">
          <p class="tc measure center mb4 lh-copy">
            {{ $t('team.team_list_blank') }}
          </p>
          <img class="db center mb4" alt="team" srcset="/img/company/account/blank-team-1x.png,
                                        /img/company/account/blank-team-2x.png 2x"
          />
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
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    load(employee) {
      this.$inertia.visit('/' + this.$page.auth.company.id + '/employees/' + employee.id);
    }
  }
};

</script>
