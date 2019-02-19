<style scoped>
.options {
  column-count: 2;
}

@media (max-width: 480px) {
  .options {
    column-count: 1;
  }
}
</style>

<template>
  <layout title="Home">
    <div class="ph2 ph0-ns">

      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di"><a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a></li>
          <li class="di">Account administration</li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb3 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">Administration of your Homas account</h2>
          <p>As an <span class="b brush-blue">administrator</span>, you can...</p>

          <ul class="options list pl0 mb5">
            <li class="pa2 pl0"><a :href="'/' + company.id + '/account/employees'">Add/remove employees</a></li>
            <li class="pa2 pl0"><a :href="'/' + company.id + '/account/teams'">Add/remove teams</a></li>
          </ul>

          <p>As an <span class="b brush-orange">account owner</span>, you can...</p>
          <ul class="options list pl0">
            <li class="pa2 pl0"><a :href="'/' + company.id + '/account/audit'" class="bb">View audit log to see who has done what</a></li>
            <li class="pa2 pl0" v-show="!company.has_dummy_data"><a :href="'/' + company.id + '/account/dummy'">Generate fake data</a></li>
            <li class="pa2 pl0" v-show="company.has_dummy_data"><a :href="'/' + company.id + '/account/dummy'">Remove fake data</a></li>
            <li class="pa2 pl0">Export data from this account</li>
            <li class="pa2 pl0">Change company information</li>
            <li class="pa2 pl0">Upgrade to a paid account</li>
            <li class="pa2 pl0">Cancel this account</li>
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
    'nbEmployees'
  ],
}
</script>
