<template>
  <layout title="Home" noMenu="true">
    <div class="ph2 ph0-ns">

      <!-- Blank state -->
      <div class="cf mt4 mt5-l mw7 center" v-show="employees.length == 0">
        <p>Welcome to Homas.</p>
        <div class="fl w-100 w-25-m w-50-l pr2">
          <a href="/company/create">
            <div class="br3 mb3 bg-white box pa3 tc b relative">
              Create a company
            </div>
          </a>
        </div>
        <div class="fl w-100 w-25-m w-50-l pr2">
          <a href="/company/create">
            <div class="br3 mb3 bg-white box pa3 tc b relative">
              Join a company
            </div>
          </a>
        </div>
      </div>

      <!-- List of companies -->
      <div v-show="employees.length != 0">
        <div class="mt4 mt5-l mw7 center section-btn relative">
          <p><span class="pr2">All the companies you are part of</span> <a href="/company/create" class="btn-primary absolute db-l dn br3 pv2 ph3 white no-underline bb-0">Create a company</a></p>
        </div>
        <div class="cf mt4 mw7 center">
          <div class="fl w-100 w-25-m w-third-l pr2" v-for="employee in employees" :key="employee.id">
            <a :href="'/' + employee.company.id + '/dashboard'">
              <div class="br3 mb3 bg-white box pa3 home-index-company b relative">
                {{ employee.company.name }}
                <span class="absolute normal f6">{{ employee.company.employees }} employees</span>
              </div>
            </a>
          </div>
        </div>
        <div class="w-100 dn-ns db mt2">
          <a href="/company/create" class="btn-primary br3 pa3 white no-underline bb-0 db tc">Create a company</a>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
export default {
  props: ['employees'],
}
</script>
