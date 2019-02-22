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
  <layout title="Home">
    <div class="ph2 ph0-ns">

      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di"><a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a></li>
          <li class="di"><a :href="'/' + company.id + '/employees'">All employees</a></li>
          <li class="di">{{ employee.name }}</li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 relative pt5">
          <img :src="employee.avatar" class="avatar absolute br-100 db center">
          <h2 class="tc normal mb1">{{ employee.name }}</h2>
          <ul class="list tc pa0 f6">
            <li class="di-l db mb0-l mb2 mr2">No current position</li>
            <li class="di-l db mb0-l mb2 mr2">No hire date</li>
            <li class="di-l db mb0-l mb2 mr2">No indication of status</li>
            <li class="di-l db mb0-l mb2">No teams</li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>

export default {
  props: [
    'company',
    'employee',
    'user',
  ],

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
