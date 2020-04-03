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
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';

export default {
  components: {
    Layout,
    Worklogs,
    Morale,
    WorkFromHome,
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
  },
};
</script>
