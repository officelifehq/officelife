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
  <layout title="Home" :user="user">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw7 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a>
          </li>
          <li class="di">
            <a :href="'/' + company.id + '/employees'">All employees</a>
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
          <img v-show="user.permission_level <= 200" src="/img/menu_button.svg" class="box-edit-button absolute br-100 pa2 bg-white pointer" data-cy="edit-profile-button" @click="profileMenu = true" />

          <!-- EDIT MENU -->
          <div v-if="profileMenu" v-click-outside="toggleModals" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
            <ul class="list ma0 pa0">
              <li class="pv2">
                <a class="pointer" data-cy="add-manager-button">Edit</a>
              </li>
              <li class="pv2">
                <a class="pointer" data-cy="add-direct-report-button">Delete</a>
              </li>
              <li class="pv2">
                <a class="pointer" data-cy="add-direct-report-button">View change log</a>
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
              No current position
            </li>
            <li class="di-l db mb0-l mb2 mr2">
              No hire date
            </li>
            <li class="di-l db mb0-l mb2 mr2">
              No indication of status
            </li>
            <li class="di-l db mb0-l mb2">
              No teams
            </li>
            <li class="di-l db mb0-l mb2">
              Add direct reports
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
    managers: {
      type: Array,
      default: null,
    },
    directReports: {
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
        timeout: 5000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true,
      })
      localStorage.clear()
    }
  },
}

</script>
