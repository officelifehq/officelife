<style lang="scss" scoped>
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="false"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/dashboard/manager'"
                  :previous="$t('app.breadcrumb_dashboard_manager')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_dashboard_hr_discipline_cases') }}
      </breadcrumb>

      <!-- BODY -->
      <h2 class="pa3 mt2 mb3 center tc normal">
        All the discipline cases
      </h2>

      <div class="tc mb4">
        <div class="cf dib btn-group">
          <inertia-link :href="data.url.open" class="f6 fl ph3 pv2 dib pointer no-underline">
            Open cases
          </inertia-link>
          <inertia-link :href="data.url.closed" class="f6 fl ph3 pv2 dib pointer no-underline">
            Closed cases
          </inertia-link>
        </div>
      </div>

      <!-- add new case -->
      <div class="mw7 center">
        <div v-if="!showModal" class="tr mb2">
          <a href="#" class="btn dib-l db mb3 mb0-ns" @click.prevent="displayAddModal()">Open a case</a>
          <avatar :avatar="currentEmployee.avatar" :size="64" :class="'w2 h2 w3-ns h3-ns br-100'" />
        </div>

        <!-- add a new member -->
        <div v-if="showModal" class="bg-white box mb3 pa3">
          <form @submit.prevent="search">
            <!-- errors -->
            <template v-if="form.errors.length > 0">
              <div class="cf pb1 w-100 mb2">
                <errors :errors="form.errors" />
              </div>
            </template>

            <!-- search form -->
            <div class="mb3 relative">
              <p class="mt0 relative">Choose an employee to create a case for: <a href="#" class="absolute right-0 f6" @click.prevent="showModal = false">{{ $t('app.cancel') }}</a></p>
              <div class="relative">
                <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                       :placeholder="$t('group.members_add_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required
                       @keyup="search()" @keydown.esc="showModal = false"
                />
                <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
              </div>
            </div>
          </form>

          <!-- RESULTS -->
          <div class="pl0 list ma0">
            <ul v-if="potentialEmployees.length > 0" class="list ma0 pl0">
              <li v-for="member in potentialEmployees" :key="member.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
                {{ member.name }}
                <a class="absolute right-1 pointer" :data-cy="'potential-group-member-' + member.id" @click.prevent="store(member)">
                  {{ $t('app.choose') }}
                </a>
              </li>
            </ul>
          </div>
          <div v-if="potentialEmployees.length == 0" class="silver">
            {{ $t('app.no_results') }}
          </div>
        </div>
      </div>

      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <!-- BLANK STATE -->
        <div>
          <img loading="lazy" class="db center mb4 mt3" alt="no timesheets to validate" src="/img/streamline-icon-employee-checklist-6@140x140.png" height="80"
               width="80"
          />

          <p class="fw5 mt3 tc">{{ $t('dashboard.manager_timesheet_blank_state') }}</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
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
    data: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      showModal: false,
      processingSearch: false,
      potentialEmployees: [],
      form: {
        searchTerm: null,
        employee: null,
        errors: [],
      },
    };
  },

  methods: {
    displayAddModal() {
      this.showModal = true;

      this.$nextTick(() => {
        this.$refs['search'].focus();
      });
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post(this.data.url.search, this.form)
            .then(response => {
              this.potentialEmployees = _.filter(response.data.data, employee => _.every(this.form.employees, e => employee.id !== e.id));
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
            });
        }
      }, 500),
  },
};
</script>
