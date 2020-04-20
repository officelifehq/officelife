<style lang="scss" scoped>
.months {
  color: #718096;

  .selected {
    font-weight: 600;
    text-decoration: none;
    border-bottom: 0;
    padding-left: 10px;
  }
}

.years {
  .selected {
    border-bottom: 0;
    text-decoration: none;
    color: #4d4d4f;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/employees/' + employee.id" data-cy="breadcrumb-employee">{{ employee.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_employee_workfromhome') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <h2 class="pa3 mt2 center tc normal mb2">
          {{ $t('employee.work_from_home_title_details') }}
        </h2>

        <!-- case when there are work from home entries -->
        <template v-if="entries.length > 0">
          <!-- list of years -->
          <ul class="list years tc" data-cy="worklog-year-selector">
            <li class="di">{{ $t('employee.worklog_year_selector') }}</li>
            <li v-for="singleYear in years" :key="singleYear.number" class="di mh2">
              <inertia-link :href="'/' + $page.auth.company.id + '/employees/' + employee.id + '/workfromhome/' + singleYear.number" :class="{ selected: currentYear == singleYear.number }">{{ singleYear.number }}</inertia-link>
            </li>
          </ul>

          <div class="cf w-100">
            <!-- left column -->
            <div class="fl-ns w-third-ns pa3">
              <!-- list of months -->
              <p class="f6 mt0 silver">{{ $t('employee.worklog_filter_month') }}</p>
              <ul class="pl0 list months f6">
                <li class="mb2"><inertia-link :href="'/' + $page.auth.company.id + '/employees/' + employee.id + '/workfromhome/' + year">All</inertia-link></li>
                <li v-for="month in months" :key="month.month" class="mb2" :data-cy="'worklog-month-selector-' + month.month">
                  <!-- we are viewing a specific month, so we need to highlight the proper month in the UI -->
                  <template v-if="currentMonth">
                    <inertia-link v-if="month.occurences != 0" :href="'/' + $page.auth.company.id + '/employees/' + employee.id + '/workfromhome/' + year + '/' + month.month" :class="{ selected: currentMonth == month.month }">{{ month.translation }} ({{ month.occurences }})</inertia-link>
                    <span v-if="month.occurences == 0">{{ month.translation }} ({{ month.occurences }})</span>
                  </template>

                  <template v-else>
                    <inertia-link v-if="month.occurences != 0" :href="'/' + $page.auth.company.id + '/employees/' + employee.id + '/workfromhome/' + year + '/' + month.month">{{ month.translation }} ({{ month.occurences }})</inertia-link>
                    <span v-if="month.occurences == 0">{{ month.translation }} ({{ month.occurences }})</span>
                  </template>
                </li>
              </ul>
            </div>

            <!-- right columns -->
            <div class="fl-ns w-two-thirds-ns pa3">
              <!-- list of work from home entries -->
              <div v-for="entry in entries" :key="entry.id">
                <p class="mt0 f6 mb1 silver">{{ entry.localized_date }}</p>
                <div class="tc mb3 green">
                  ~
                </div>
              </div>

              <!-- blank state -->
              <p v-if="entries.length == 0" class="tc mt5">{{ $t('employee.worklog_blank_state_for_month') }}</p>
            </div>
          </div>
        </template>

        <!-- case of no work from home entries -->
        <template v-else>
          <p class="tc pa3" data-cy="blank-worklog-message">{{ $t('employee.work_from_home_blank_state_for_month') }}</p>
        </template>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
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
    entries: {
      type: Array,
      default: null,
    },
    year: {
      type: Number,
      default: null,
    },
    years: {
      type: Object,
      default: null,
    },
    months: {
      type: Array,
      default: null,
    },
    currentYear: {
      type: Number,
      default: null,
    },
    currentMonth: {
      type: Number,
      default: null,
    }
  },

  data() {
    return {
    };
  },

  created() {
  },

  methods: {
  },
};

</script>
