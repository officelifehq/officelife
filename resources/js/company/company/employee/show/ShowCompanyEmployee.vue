<style scoped>
.avatar {
  width: 80px;
  height: 80px;
  top: 19%;
  left: 50%;
  margin-top: -40px; /* Half the height */
  margin-left: -40px; /* Half the width */
}
</style>

<template>
  <layout title="Home" :user="user" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw7 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a>
          </li>
          <li class="di">
            <a :href="'/' + company.id + '/employees'">{{ $t('app.breadcrumb_employee_list') }}</a>
          </li>
          <li class="di">
            {{ employee.name }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw9 center br3 mb4 bg-white box relative z-1">
        <div class="pa3 relative pt5">
          <!-- EDIT BUTTON -->
          <img v-show="user.permission_level <= 200 || user.user_id == employee.user.id" src="/img/menu_button.svg" class="box-edit-button absolute br-100 pa2 bg-white pointer" data-cy="edit-profile-button" @click="profileMenu = true" />

          <!-- EDIT MENU -->
          <div v-if="profileMenu" v-click-outside="toggleProfileMenu" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
            <ul class="list ma0 pa0">
              <li v-show="user.permission_level <= 200" class="pv2">
                <a class="pointer" data-cy="add-manager-button">Edit</a>
              </li>
              <li v-show="user.permission_level <= 200" class="pv2">
                <a class="pointer" data-cy="add-direct-report-button">Delete</a>
              </li>
              <li v-show="user.permission_level <= 200 || user.user_id == employee.user.id" class="pv2">
                <a :href="'/' + company.id + '/employees/' + employee.id + '/logs'" class="pointer" data-cy="view-log-button">View change log</a>
              </li>
            </ul>
          </div>

          <!-- AVATAR -->
          <img :src="employee.avatar" class="avatar absolute br-100 db center" />
          <h2 class="tc normal mb1">
            {{ employee.name }}
          </h2>
          <ul class="list tc pa0 f6 mb0">
            <li class="di-l db mb0-l mb2 mr2">
              <assign-employee-position
                :company="company"
                :employee="employee"
                :user="user"
                :positions="positions"
              />
            </li>
            <li class="di-l db mb0-l mb2 mr2">
              No hire date
            </li>
            <li class="di-l db mb0-l mb2 mr2">
              No indication of status
            </li>
            <li class="di-l db mb0-l mb2">
              <assign-employee-team
                :company="company"
                :employee="employee"
                :user="user"
                :teams="teams"
              />
            </li>
          </ul>
        </div>
      </div>

      <div class="cf mw9 center">
        <!-- LEFT COLUMN -->
        <div class="fl w-40-l w-100">
          <show-company-employee-hierarchy
            :company="company"
            :employee="employee"
            :managers="managers"
            :direct-reports="directReports"
            :user="user"
          />
        </div>

        <!-- RIGHT COLUMN -->
        <div class="flex items-center justify-center flex-column mb4">
          <div class="cf dib btn-group">
            <span class="f6 fl ph3 pv2 dib pointer">
              Summary
            </span>
            <span class="f6 fl ph3 pv2 pointer dib">
              Life events
            </span>
            <span class="f6 fl ph3 pv2 dib selected">
              Logs
            </span>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import ClickOutside from 'vue-click-outside'

export default {
  directives: {
    ClickOutside
  },

  props: {
    company: {
      type: Object,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    managers: {
      type: Array,
      default: null,
    },
    directReports: {
      type: Array,
      default: null,
    },
    positions: {
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
      profileMenu: false,
    }
  },

  mounted() {
    if (localStorage.success) {
      this.$snotify.success(localStorage.success, {
        timeout: 2000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true,
      })
      localStorage.clear()
    }

    // prevent click outside event with popupItem.
    this.popupItem = this.$el
  },

  methods: {
    toggleProfileMenu() {
      this.profileMenu = false
    },
  }
}

</script>
