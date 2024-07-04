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

      <contract-renewal-date
        v-if="contractRenewal"
        :employee="employee"
        :contract-renewal="contractRenewal"
      />

      <e-coffee
        v-if="eCoffee"
        :employee="employee"
        :e-coffee="eCoffee"
      />

      <div v-if="jobOpeningsAsParticipant">
        <job-openings-as-participant
          v-if="jobOpeningsAsParticipant.length > 0"
          :job-openings="jobOpeningsAsParticipant"
        />
      </div>

      <job-openings-as-sponsor
        v-if="jobOpeningsAsSponsor.length > 0"
        :job-openings="jobOpeningsAsSponsor"
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
        v-if="workFromHome.feature_enabled"
        :work-from-home="workFromHome"
      />

      <worklogs
        :worklogs="worklogs"
      />

      <morale
        :morale="morale"
      />

      <question
        :employee="employee"
        :question="question"
      />

      <ask-me-anything
        v-if="askMeAnything"
        :session="askMeAnything"
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
import Worklogs from '@/Pages/Dashboard/Me/Partials/Worklogs.vue';
import Morale from '@/Pages/Dashboard/Me/Partials/Morale.vue';
import WorkFromHome from '@/Pages/Dashboard/Me/Partials/WorkFromHome.vue';
import Question from '@/Pages/Dashboard/Me/Partials/Question.vue';
import Expense from '@/Pages/Dashboard/Me/Partials/Expense.vue';
import RateYourManager from '@/Pages/Dashboard/Me/Partials/RateYourManager.vue';
import OneOnOneWithManager from '@/Pages/Dashboard/Me/Partials/OneOnOneWithManager.vue';
import ContractRenewalDate from '@/Pages/Dashboard/Me/Partials/ContractRenewalDate.vue';
import Layout from '@/Shared/Layout.vue';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu.vue';
import ECoffee from '@/Pages/Dashboard/Me/Partials/ECoffee.vue';
import Projects from '@/Pages/Dashboard/Me/Partials/Projects.vue';
import JobOpeningsAsSponsor from '@/Pages/Dashboard/Me/Partials/JobOpeningSponsor.vue';
import JobOpeningsAsParticipant from '@/Pages/Dashboard/Me/Partials/JobOpeningParticipant.vue';
import AskMeAnything from '@/Pages/Dashboard/Me/Partials/AskMeAnything.vue';

export default {
  components: {
    Layout,
    Worklogs,
    Morale,
    Question,
    Expense,
    WorkFromHome,
    RateYourManager,
    OneOnOneWithManager,
    ContractRenewalDate,
    DashboardMenu,
    ECoffee,
    Projects,
    JobOpeningsAsSponsor,
    JobOpeningsAsParticipant,
    AskMeAnything,
  },

  props: {
    employee: {
      type: Object,
      default: null,
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
    morale: {
      type: Object,
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
      type: Array,
      default: null,
    },
    worklogs: {
      type: Object,
      default: null,
    },
    workFromHome: {
      type: Object,
      default: null,
    },
    question: {
      type: Object,
      default: null,
    },
    jobOpeningsAsSponsor: {
      type: Object,
      default: null,
    },
    jobOpeningsAsParticipant: {
      type: Object,
      default: null,
    },
    askMeAnything: {
      type: Object,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};
</script>
