<style lang="scss" scoped>
</style>

<template>
  <layout :notifications="notifications">
    <!-- header -->
    <header-component :statistics="statistics" />

    <div class="ph2 ph5-ns">
      <!-- central content -->
      <tab :tab="tab" />

      <div class="cf mw9 center">
        <div class="fl w-third-l w-100">
          <employees :employees="employees" :statistics="statistics" />

          <questions :questions="latestQuestions" />

          <company-news :news="latestNews" />
        </div>

        <div class="fl w-third-l w-100 pl4-l">
          <birthdays :birthdays="birthdaysThisWeek" />

          <guess-employee-game v-if="game" :game="game" />

          <new-hires :hires="newHiresThisWeek" />

          <upcoming-hiring-date-anniversaries :hiring-date-anniversaries="hiringDateAnniversaries" />
        </div>

        <div class="fl w-third-l w-100 pl4-l">
          <teams :teams="teams" :statistics="statistics" />

          <recent-skills :skills="latestSkills" />

          <recent-ships :ships="latestShips" />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout.vue';
import Tab from '@/Pages/Company/Partials/Tab.vue';
import HeaderComponent from '@/Pages/Company/Partials/Header.vue';
import Questions from '@/Pages/Company/Partials/Questions.vue';
import GuessEmployeeGame from '@/Pages/Company/Partials/GuessEmployeeGame.vue';
import Birthdays from '@/Pages/Company/Partials/Birthdays.vue';
import CompanyNews from '@/Pages/Company/Partials/CompanyNews.vue';
import NewHires from '@/Pages/Company/Partials/NewHires.vue';
import RecentShips from '@/Pages/Company/Partials/RecentShips.vue';
import RecentSkills from '@/Pages/Company/Partials/RecentSkills.vue';
import Employees from '@/Pages/Company/Partials/Employees.vue';
import Teams from '@/Pages/Company/Partials/Teams.vue';
import UpcomingHiringDateAnniversaries from '@/Pages/Company/Partials/UpcomingHiringDateAnniversaries.vue';

export default {
  components: {
    Layout,
    Tab,
    HeaderComponent,
    Questions,
    GuessEmployeeGame,
    Birthdays,
    CompanyNews,
    NewHires,
    RecentShips,
    RecentSkills,
    Employees,
    Teams,
    UpcomingHiringDateAnniversaries,
  },

  props: {
    tab: {
      type: String,
      default: null,
    },
    statistics: {
      type: Object,
      default: null,
    },
    latestQuestions: {
      type: Object,
      default: null,
    },
    birthdaysThisWeek: {
      type: Array,
      default: null,
    },
    newHiresThisWeek: {
      type: Array,
      default: null,
    },
    latestShips: {
      type: Array,
      default: null,
    },
    latestSkills: {
      type: Object,
      default: null,
    },
    latestNews: {
      type: Object,
      default: null,
    },
    game: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    employees: {
      type: Object,
      default: null,
    },
    teams: {
      type: Object,
      default: null,
    },
    hiringDateAnniversaries: {
      type: Object,
      default: null,
    },
  },
};
</script>
