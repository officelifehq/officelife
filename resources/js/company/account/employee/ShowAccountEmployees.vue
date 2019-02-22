<style scoped>
</style>

<template>
  <layout title="Home">
    <div class="ph2 ph0-ns">

      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di"><a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a></li>
          <li class="di"><a :href="'/' + company.id + '/account'">Account administration</a></li>
          <li class="di">Manage employees</li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">All the employees in {{ company.name }}</h2>
          <p class="relative">
            <span class="dib mb3 di-l">{{ company.name }} has {{ employees.length }} employees.</span>
            <a :href="'/' + company.id + '/account/employees/create'" class="btn-primary br3 ph3 pv2 white no-underline tc absolute-l relative dib-l db right-0">Add an employee</a>
          </p>
          <ul class="list pl0 mt0 center">
            <li
              v-for="employee in employees" v-bind:key="employee.id"
              class="flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10">
                <img class="w2 h2 w3-ns h3-ns br-100" :src="employee.avatar" />
                <div class="pl3 flex-auto">
                  <span class="db black-70">{{ employee.name }}</span>
                  <ul class="f6 list pl0">
                    <li class="di pr2"><span class="badge f7">{{ employee.permission_level }}</span></li>
                    <li class="di pr2"><a :href="'/' + company.id + '/employees/' + employee.id">View</a></li>
                    <li class="di pr2"><a :href="'/account/employees/' + employee.id + '/permissions'">Change permission</a></li>
                    <li class="di pr2"><a :href="'/employees/' + employee.id + '/lock'">Lock account</a></li>
                    <li class="di"><a :href="'/account/employees/' + employee.id + '/destroy'">Delete</a></li>
                  </ul>
                </div>
            </li>
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
    'employees',
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
