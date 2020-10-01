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
          <new-hires :hires="newHiresThisWeek" />

          <birthdays :birthdays="birthdaysThisWeek" />
        </div>

        <div class="fl w-third-l w-100 pl4-l">
          <guess-employee-game v-if="game" :game="game" />

          <birthdays :birthdays="birthdaysThisWeek" />
        </div>
      </div>

      <p>recent ships</p>
      <ul>
        <li v-for="ship in latestShips" :key="ship.id">{{ ship.title }}</li>
      </ul>

      <p>recent skills</p>
      <ul>
        <li v-for="skill in latestSkills.skills" :key="skill.id">{{ skill.name }}</li>
        <li><inertia-link :href="latestSkills.view_all_url">view all ({{ latestSkills.count }})</inertia-link></li>
      </ul>
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

export default {
  components: {
    Layout,
    Questions,
    GuessEmployeeGame,
    Birthdays,
    CompanyNews,
    NewHires,
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
      type: Array,
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
