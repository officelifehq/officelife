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

      <div class="cf mw7 center br3 mb5 tc">
        <div class="cf dib btn-group">
          <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/me'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'me')}">
            Me
          </inertia-link>
          <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'team')}" data-cy="dashboard-team-tab">
            My team
          </inertia-link>
          <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/company'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'company')}" data-cy="dashboard-company-tab">
            My company
          </inertia-link>
          <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/hr'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'hr')}" data-cy="dashboard-hr-tab">
            HR area
          </inertia-link>
        </div>
      </div>

      <my-worklogs
        :worklog-count="worklogCount"
        :employee="employee"
        class="mb5"
      />

      <my-morale
        :morale-count="moraleCount"
        :employee="employee"
      />

      <div class="cf mt4 mw7 center br3 mb3 bg-white box">
        <div class="pa3">
          <h2>Team</h2>
          <ul>
            <li>team agenda</li>
            <li>anniversaires</li>
            <li>latest news</li>
            <li>view who is at work or from home</li>
            <li>manager: view time off requests</li>
            <li>manager: view morale</li>
            <li>manager: expense approval</li>
            <li>manager: one on one</li>
            <li>revue 360 de son boss ou d'employées</li>
          </ul>
        </div>
      </div>

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
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import MyWorklogs from '@/Pages/Dashboard/MyWorklogs';
import MyMorale from '@/Pages/Dashboard/MyMorale';
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
    MyWorklogs,
    MyMorale,
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
