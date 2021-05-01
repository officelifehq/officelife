<style lang="scss" scoped>
.avatar {
  border: 1px solid #e1e4e8 !important;
  padding: 6px;
  background-color: #fff;
  border-radius: 7px;
  transform: rotate(-3deg);
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id">{{ employee.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_employee_whatsup') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3">
          <!-- header -->
          <div class="flex">
            <avatar :avatar="employee.avatar" :size="100" :class="'avatar mr3'" />

            <div>
              <h2 class="mt1">
                {{ employee.name }} <span v-if="employee.pronoun">
                  ({{ employee.pronoun.label }})
                </span>
              </h2>

              <div class="flex">
                <!-- Current position -->
                <div class="mr4">
                  <p class="mb1 f6 gray">Current position</p>
                  <p v-if="employee.position" class="mt0">{{ employee.position.title }}</p>
                </div>

                <!-- Hiring date -->
                <div>
                  <p class="mb1 f6 gray">Hired on</p>
                  <p v-if="employee.hired_at" class="mt0">{{ employee.hired_at.full }}</p>
                </div>
              </div>
            </div>
          </div>

          <ul v-if="employee.status">
            <li>Contract: {{ employee.status.name }}</li>
            <li v-if="external.contract_renews_in_timeframe">Contract will renew in this time period</li>
            <li v-if="employee.contract_renewed_at">Contract renewed on {{ employee.contract_renewed_at }}</li>
            <li v-if="employee.contract_rate">Rate {{ employee.contract_rate.currency }} {{ employee.contract_rate.rate }}</li>
            <li v-if="employee.contract_rate">
              <span v-if="employee.contract_rate.previous_rate">
                Previous rate {{ employee.contract_rate.currency }} {{ employee.contract_rate.previous_rate }}
              </span>
            </li>
          </ul>

          <p class="mb2 gray">Everything that happened to Michael in</p>

          <ul class="list pl0 ma0 f6">
            <li v-for="year in years" :key="year" class="di mr3">
              <inertia-link v-if="!year.selected" :href="year.url">{{ year.year }}</inertia-link>
              <span v-if="year.selected">{{ year.year }}</span>
            </li>
          </ul>

          <h3>WORK</h3>

          <!-- projects -->
          <p>Worked on {{ projects.length }} timesheets projects</p>
          <ul>
            <li v-for="project in projects" :key="project.id" class="pa3 bb bb-gray bb-gray-hover w-100">
              <div class="flex">
                <span class="dib mb2">
                  <inertia-link :href="project.url">{{ project.name }}</inertia-link>

                  <span v-if="project.code" class="ml2 ttu f7 project-code code br3 pa1 relative fw4">
                    {{ project.code }}
                  </span>
                </span>
              </div>
            </li>
          </ul>

          <!-- worklogs -->
          <p>Has submitted {{ worklogs.number_worklogs }} worklogs</p>
          <p>That's {{ worklogs.percent_completion }}% of the time</p>

          <h3>ADMINISTRATION</h3>

          <!-- one on ones -->
          <p>Has had {{ oneOnOnes }} one on ones </p>

          <!-- timesheets -->
          <p>Has filled {{ timesheets.data.length }} timesheets</p>
          <p>Has worked {{ timesheets.average_hours_worked }} hours per week on average</p>

          <!-- work from home -->
          <p>Has worked {{ workFromHome.number_times_work_from_home }}x from home</p>
          <p>That's {{ workFromHome.percent_work_from_home }}% of the time</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Avatar,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    oneOnOnes: {
      type: Object,
      default: null,
    },
    projects: {
      type: Object,
      default: null,
    },
    timesheets: {
      type: Object,
      default: null,
    },
    external: {
      type: Object,
      default: null,
    },
    workFromHome: {
      type: Object,
      default: null,
    },
    worklogs: {
      type: Object,
      default: null,
    },
    years: {
      type: Object,
      default: null,
    },
  }
};

</script>
