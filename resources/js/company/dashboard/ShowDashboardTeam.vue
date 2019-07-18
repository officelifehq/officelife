<style scoped>
.dummy {
  right: 40px;
  bottom: 20px;
}
</style>

<template>
  <layout title="Home" :user="user" :employee="employee" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ company.name }}
        </h2>
      </div>

      <div class="cf mw7 center br3 mb5 tc">
        <div class="cf dib btn-group">
          <a :href="'/' + company.id + '/dashboard/me'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(user.default_dashboard_view == 'me')}">
            Me
          </a>
          <a :href="'/' + company.id + '/dashboard/team'" class="f6 fl ph3 pv2 pointer dib" :class="{'selected':(user.default_dashboard_view == 'team')}">
            My team
          </a>
          <a :href="'/' + company.id + '/dashboard/company'" class="f6 fl ph3 pv2 dib" :class="{'selected':(user.default_dashboard_view == 'company')}">
            My company
          </a>
          <a :href="'/' + company.id + '/dashboard/hr'" class="f6 fl ph3 pv2 dib" :class="{'selected':(user.default_dashboard_view == 'hr')}">
            HR area
          </a>
        </div>
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box">
        <div class="pa3">
          <h2 class="mt0 fw5 f4">
            😀 What your team has done this week
          </h2>
          <p class="db">
            <span class="dib-ns db mb0-ns mb2">{{ $t('dashboard.worklog_placeholder') }}</span>
            <a v-show="updatedWorklogCount != 0" class="ml2-ns pointer">{{ $t('dashboard.worklog_read_previous_entries') }}</a>
          </p>
        </div>
      </div>

      <div class="cf mt4 mw7 center br3 mb3 bg-white box">
        <div class="pa3">
          <h2>Team</h2>
          <ul>
            <li>team agenda</li>
            <li>anniversaires</li>
            <li>latest news</li>
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
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
export default {
  props: {
    company: {
      type: Object,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
    worklogCount: {
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
