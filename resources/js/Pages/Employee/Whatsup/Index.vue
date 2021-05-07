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

      <!-- header -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1 pa3">
        <div class="flex">
          <avatar :avatar="employee.avatar" :size="100" :class="'avatar mr3'" />

          <div>
            <h2 class="mt2 fw4">
              {{ employee.name }} <span v-if="employee.pronoun" class="f5">
                ({{ employee.pronoun.label }})
              </span>
            </h2>

            <div class="flex">
              <!-- Current position -->
              <div class="mr5">
                <p class="mb1 f6 gray mt0">Current position</p>
                <p v-if="employee.position" class="mt0">{{ employee.position.title }}</p>
              </div>

              <!-- Hiring date -->
              <div>
                <p class="mb1 f6 gray mt0">Hired on</p>
                <p v-if="employee.hired_at" class="mt0">{{ employee.hired_at.full }}</p>
              </div>
            </div>

            <div v-if="employee.status && external" class="flex">
              <!-- Status -->
              <div class="mr5">
                <p class="mb1 f6 gray mt0">Status</p>
                <p class="mt0">{{ employee.status.name }}</p>
              </div>

              <!-- Contract renewal -->
              <div v-if="external.contract_renewed_at" class="mr5">
                <p class="mb1 f6 gray mt0">Renewal date</p>
                <p class="mt0">{{ external.contract_renewed_at }}</p>
              </div>

              <!-- Rate -->
              <div v-if="external.contract_rate" class="mr5">
                <p class="mb1 f6 gray mt0">Rate</p>
                <p class="mt0">{{ external.contract_rate.currency }} {{ external.contract_rate.rate }}</p>
              </div>

              <!-- Previous rate -->
              <div v-if="external.contract_rate.previous_rate">
                <p class="mb1 f6 gray mt0">Previous rate</p>
                <p class="mt0">{{ external.contract_rate.currency }} {{ external.contract_rate.previous_rate }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- year selector -->
      <p class="mb2 gray mt4 tc">Everything that happened to Michael in</p>
      <div class="cf mw7 center br3 mt3 mb5 tc">
        <div class="cf dib btn-group">
          <inertia-link v-for="year in years" :key="year" :href="year.url" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(currentYear == year.year)}">
            {{ year.year }}
          </inertia-link>
        </div>
      </div>

      <div>
        <div>
          <h3>WORK</h3>

          <!-- positions -->
          <p v-if="positions.length > 1">Employee has changed position this year.</p>
          <ul>
            <li v-for="position in positions" :key="position.id" class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between position-item">
              <span>{{ position.position }}</span>

              <span class="gray">
                {{ position.started_at }}
                <span v-if="position.ended_at"> - {{ position.ended_at }}</span>
                <span v-else> - {{ $t('employee.past_position_history_present') }}</span>
              </span>
            </li>
          </ul>

          <!-- projects -->
          <p>Worked on {{ projects.length }} projects</p>
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
    positions: {
      type: Object,
      default: null,
    },
    years: {
      type: Object,
      default: null,
    },
    currentYear: {
      type: String,
      default: null,
    },
  }
};

</script>
