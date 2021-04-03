<style scoped>
.dummy {
  right: 40px;
  bottom: 20px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <dashboard-menu :employee="employee" />

      <projects
        v-if="projects.length > 0"
        :projects="projects"
      />

      <e-coffee
        v-if="eCoffee"
        :employee="employee"
        :e-coffee="eCoffee"
      />

      <contract-renewal-date
        v-if="contractRenewal"
        :employee="employee"
        :contract-renewal="contractRenewal"
      />

      <one-on-one-with-manager
        v-if="oneOnOnes.length > 0"
        :employee="employee"
        :one-on-ones="oneOnOnes"
      />

      <rate-your-manager
        :employee="employee"
        :rate-your-manager-answers="rateYourManagerAnswers"
      />

      <work-from-home
        :employee="employee"
        class="mb5"
      />

      <worklogs
        :worklog-count="worklogCount"
        :employee="employee"
        class="mb5"
      />

      <morale
        :morale-count="moraleCount"
        :employee="employee"
      />

      <question
        :employee="employee"
      />

      <task
        :employee="employee"
        :tasks="tasks"
      />

      <expense
        :employee="employee"
        :categories="categories"
        :expenses="expenses"
        :currencies="currencies"
        :default-currency="defaultCurrency"
      />
    </div>
  </layout>
</template>

<script>
import Worklogs from '@/Pages/Dashboard/Me/Partials/Worklogs';
import Morale from '@/Pages/Dashboard/Me/Partials/Morale';
import WorkFromHome from '@/Pages/Dashboard/Me/Partials/WorkFromHome';
import Question from '@/Pages/Dashboard/Me/Partials/Question';
import Task from '@/Pages/Dashboard/Me/Partials/Task';
import Expense from '@/Pages/Dashboard/Me/Partials/Expense';
import RateYourManager from '@/Pages/Dashboard/Me/Partials/RateYourManager';
import OneOnOneWithManager from '@/Pages/Dashboard/Me/Partials/OneOnOneWithManager';
import ContractRenewalDate from '@/Pages/Dashboard/Me/Partials/ContractRenewalDate';
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';
import ECoffee from '@/Pages/Dashboard/Me/Partials/ECoffee';
import Projects from '@/Pages/Dashboard/Me/Partials/Projects';

export default {
  components: {
    Layout,
    Worklogs,
    Morale,
    Question,
    Task,
    Expense,
    WorkFromHome,
    RateYourManager,
    OneOnOneWithManager,
    ContractRenewalDate,
    DashboardMenu,
    ECoffee,
    Projects,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    worklogCount: {
      type: Number,
      default: 0,
    },
    moraleCount: {
      type: Number,
      default: 0,
    },
    notifications: {
      type: Array,
      default: null,
    },
    ownerPermissionLevel: {
      type: Number,
      default: 0,
    },
    tasks: {
      type: Array,
      default: null,
    },
    categories: {
      type: Array,
      default: null,
    },
    currencies: {
      type: Array,
      default: null,
    },
    expenses: {
      type: Array,
      default: null,
    },
    rateYourManagerAnswers: {
      type: Array,
      default: null,
    },
    oneOnOnes: {
      type: Array,
      default: null,
    },
    contractRenewal: {
      type: Array,
      default: null,
    },
    defaultCurrency: {
      type: Object,
      default: null,
    },
    eCoffee: {
      type: Object,
      default: null,
    },
    projects: {
      type: Object,
      default: null,
    },
  },
};
</script>
