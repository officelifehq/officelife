<style scoped>
.dummy {
  right: 40px;
  bottom: 20px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ $page.auth.company.name }}
        </h2>
      </div>

      <dashboard-menu :employee="employee" />

      <rate-your-manager
        :employee="employee"
        :rate-your-manager-answers="rateYourManagerAnswers"
      />

      <work-from-home
        :employee="employee"
        class="mb5"
      />

      <worklogs
        :worklog-count="worklogCount"
        :employee="employee"
        class="mb5"
      />

      <morale
        :morale-count="moraleCount"
        :employee="employee"
      />

      <question
        :employee="employee"
      />

      <task
        :employee="employee"
        :tasks="tasks"
      />

      <expense
        :employee="employee"
        :categories="categories"
        :expenses="expenses"
        :currencies="currencies"
        :default-currency="defaultCurrency"
      />

      <div class="cf mt4 mw7 center br3 mb3 bg-white box">
        <div class="pa3">
          <h2>Me</h2>
          <ul>
            <li>View holidays</li>
            <li>Book time off</li>
            <li>Log morale</li>
            <li>Reply to what you've done this week</li>
            <li>Log an expense</li>
            <li>View one on ones</li>
            <li>View all my tasks</li>
            <li>quizz hebdo pour apprendre à connaitre</li>
            <li>donner des statistics sur le nombre de femmes/hommes avec un poste à responsabilité</li>
            <li>donner le ratio homme/femme par poste dans l'onglet Positions</li>
            <li>rechercher par "position"</li>
            <li>gestion du materiel informatique</li>
            <li>Onboarding questionaire</li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Worklogs from '@/Pages/Dashboard/Me/Partials/Worklogs';
import Morale from '@/Pages/Dashboard/Me/Partials/Morale';
import WorkFromHome from '@/Pages/Dashboard/Me/Partials/WorkFromHome';
import Question from '@/Pages/Dashboard/Me/Partials/Question';
import Task from '@/Pages/Dashboard/Me/Partials/Task';
import Expense from '@/Pages/Dashboard/Me/Partials/Expense';
import RateYourManager from '@/Pages/Dashboard/Me/Partials/RateYourManager';
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';

export default {
  components: {
    Layout,
    Worklogs,
    Morale,
    Question,
    Task,
    Expense,
    WorkFromHome,
    RateYourManager,
    DashboardMenu,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    worklogCount: {
      type: Number,
      default: 0,
    },
    moraleCount: {
      type: Number,
      default: 0,
    },
    notifications: {
      type: Array,
      default: null,
    },
    ownerPermissionLevel: {
      type: Number,
      default: 0,
    },
    tasks: {
      type: Array,
      default: null,
    },
    categories: {
      type: Array,
      default: null,
    },
    currencies: {
      type: Array,
      default: null,
    },
    expenses: {
      type: Array,
      default: null,
    },
    rateYourManagerAnswers: {
      type: Array,
      default: null,
    },
    defaultCurrency: {
      type: Object,
      default: null,
    },
  },
};
</script>
