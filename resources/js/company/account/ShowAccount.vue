<style scoped>
</style>

<template>
  <layout title="Home">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center br3 mb3 bg-white box">
        <div class="fn fl-ns w-50-ns pa3">
          As an account owner, you can...
          <ul>
            <li><a :href="'/' + company.id + '/account/audit'">View audit log to see who has done what</a></li>
            <li v-show="!company.has_dummy_data"><a :href="'/' + company.id + '/account/dummy'">Generate fake data</a></li>
            <li v-show="company.has_dummy_data"><a :href="'/' + company.id + '/account/dummy'">Remove fake data</a></li>
            <li>Export data from this account</li>
            <li>Change company information</li>
            <li>Upgrade to a paid account</li>
            <li>Cancel this account</li>
          </ul>

          As an administrator, you can...

          <ul>
            <li><a :href="'/' + company.id + '/account/employees'">Add/remove employees</a></li>
            <li><a :href="'/' + company.id + '/account/teams'">Add/remove teams</a></li>
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
