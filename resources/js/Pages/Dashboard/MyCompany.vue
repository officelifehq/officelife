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
          {{ company.name }}
        </h2>
      </div>

      <div class="cf mw7 center br3 mb3 tc">
        <div class="cf dib btn-group">
          <a :href="'/' + company.id + '/dashboard/me'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'me')}">
            Me
          </a>
          <a :href="'/' + company.id + '/dashboard/team'" class="f6 fl ph3 pv2 pointer dib" :class="{'selected':($page.auth.user.default_dashboard_view == 'team')}">
            My team
          </a>
          <a :href="'/' + company.id + '/dashboard/company'" class="f6 fl ph3 pv2 dib" :class="{'selected':($page.auth.user.default_dashboard_view == 'company')}">
            My company
          </a>
          <a :href="'/' + company.id + '/dashboard/hr'" class="f6 fl ph3 pv2 dib" :class="{'selected':($page.auth.user.default_dashboard_view == 'hr')}">
            HR area
          </a>
        </div>
      </div>

      <div v-show="employee.permission_level == ownerPermissionLevel" class="cf mw7 center br3 mb3 bg-white box">
        <div class="pa3 relative">
          <p class="b">
            Would you like to fill your account with fake data?
          </p>
          <p class="measure">
            This will let you play with an account with a lot of data. You will be able to remove them at any time to start fresh.
          </p>
          <img src="/img/company/account/fake-data.png" class="dummy w-25 absolute" alt="fake data" loading="lazy" />
          <ul class="list pa0 ma0">
            <li class="di pr2">
              <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="'generate'" />
            </li>
            <li class="di">
              <a href="">
                Dismiss this message
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box">
        <div class="pa3">
          <editor />
        </div>
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box">
        <div class="pa3">
          <ul>
            <li>
              <a :href="'/' + company.id + '/account'">
                Access Adminland
              </a>
            </li>
            <li>latest news</li>
            <li>hr: expense overview</li>
            <li>hr: view all teams</li>
            <li>view company morale</li>
            <li>view all employees</li>
            <li>menu de la semaine</li>
            <li>Mise en avant random d'un employé</li>
          </ul>
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
    employee: {
      type: Object,
      default: null,
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
