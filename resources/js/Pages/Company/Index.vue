<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ $page.auth.company.name }}
        </h2>
      </div>

      <!-- central content -->
      <div class="cf mw9 center">
        <div class="fl w-third-l w-100">
          <company-news :news="latestNews" />

          <questions :questions="latestQuestions" />
        </div>

        <div class="fl w-third-l w-100 pl4-l">
          <recent-ships :ships="latestShips" />
          <recent-skills :skills="latestSkills" />
        </div>

        <div class="fl w-third-l w-100 pl4-l">
          <guess-employee-game v-if="game" :game="game" />

          <birthdays :birthdays="birthdaysThisWeek" />

          <new-hires :hires="newHiresThisWeek" />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Questions from '@/Pages/Company/Partials/Questions';
import GuessEmployeeGame from '@/Pages/Company/Partials/GuessEmployeeGame';
import Birthdays from '@/Pages/Company/Partials/Birthdays';
import CompanyNews from '@/Pages/Company/Partials/CompanyNews';
import NewHires from '@/Pages/Company/Partials/NewHires';
import RecentShips from '@/Pages/Company/Partials/RecentShips';
import RecentSkills from '@/Pages/Company/Partials/RecentSkills';

export default {
  components: {
    Layout,
    Questions,
    GuessEmployeeGame,
    Birthdays,
    CompanyNews,
    NewHires,
    RecentShips,
    RecentSkills,
  },

  props: {
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
  },
};
</script>
