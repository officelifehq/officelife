<style lang="scss" scoped>
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
        <div class="pa3 tc">
          {{ message }}
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
    notifications: {
      type: Array,
      default: null,
    },
    message: {
      type: String,
      default: null,
    }
  },
};
</script>
