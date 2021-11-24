<style lang="scss" scoped>
.employees-list {
  li:last-child {
    border-bottom: 0;
  }
}

.case-list {
  li:first-child:hover {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  li:last-child {
    border-bottom: 0;

    &:hover {
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="false"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/dashboard/hr'"
                  :previous="$t('app.breadcrumb_hr')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_dashboard_hr_discipline_cases') }}
      </breadcrumb>

      <!-- BODY -->
      <h2 class="pa3 mt2 mb3 center tc normal">
        {{ $t('dashboard.hr_discipline_cases_index_tile') }}

        <help :url="$page.props.help_links.discipline" />
      </h2>

      <div class="tc mb4">
        <div class="cf dib btn-group">
          <inertia-link :href="data.url.open" class="selected f6 fl ph3 pv2 dib pointer no-underline">
            {{ $t('dashboard.hr_discipline_cases_open_cases', {count: data.open_cases_count}) }}
          </inertia-link>
          <inertia-link :href="data.url.closed" class="f6 fl ph3 pv2 dib pointer no-underline">
            {{ $t('dashboard.hr_discipline_cases_closed_cases', {count: data.closed_cases_count}) }}
          </inertia-link>
        </div>
      </div>

      <!-- add new case -->
      <div class="mw7 center">
        <div v-if="!showModal" class="tr mb2">
          <a href="#" class="btn dib-l db mb3 mb0-ns" @click.prevent="displayAddModal()">Open a case</a>
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
              <p class="mt0 relative">{{ $t('dashboard.hr_discipline_cases_create_case_label') }} <a href="#" class="absolute right-0 f6" @click.prevent="showModal = false">{{ $t('app.cancel') }}</a></p>
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
            <ul v-if="potentialEmployees.length > 0" class="ba bb-gray list ma0 pl0 employees-list">
              <li v-for="potentialEmployee in potentialEmployees" :key="potentialEmployee.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
                {{ potentialEmployee.name }}
                <a class="absolute right-1 pointer" @click.prevent="store(potentialEmployee)">
                  {{ $t('dashboard.hr_discipline_cases_create_case') }}
                </a>
              </li>
            </ul>
          </div>
          <div v-if="potentialEmployees.length == 0" class="silver">
            {{ $t('app.no_results') }}
          </div>
        </div>
      </div>

      <!-- list of cases -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <ul v-if="localCases.length > 0" class="ma0 pl0 case-list">
          <li v-for="localCase in localCases" :key="localCase.id" class="flex items-center justify-between pa3 bb bb-gray bb-gray-hover">
            <!-- avatar + name + position -->
            <div class="flex items-center">
              <avatar :avatar="localCase.employee.avatar" :url="localCase.employee.url" :size="40" :class="'br-100 mr2'" />
              <div>
                <inertia-link :href="localCase.url.show" class="dib mb1">{{ localCase.employee.name }}</inertia-link>
                <span class="gray f6 db">{{ localCase.employee.position }}</span>
              </div>
            </div>

            <!-- number of events + date + reporter -->
            <div class="f6 gray">
              <span class="db mb1">{{ $t('dashboard.hr_discipline_cases_opened_at', {date: localCase.opened_at}) }}</span>
              <inertia-link v-if="localCase.author.id" :href="localCase.author.url">{{ $t('dashboard.hr_discipline_cases_by', {name: localCase.author.name }) }}</inertia-link>
              <span v-else>{{ $t('dashboard.hr_discipline_cases_by', {name: localCase.author.name }) }}</span>
            </div>
          </li>
        </ul>

        <!-- BLANK STATE -->
        <div v-if="localCases.length == 0">
          <img loading="lazy" class="db center mb4 mt3" alt="no timesheets to validate" src="/img/streamline-icon-prisoner-2-5@100x100.png" height="80"
               width="80"
          />

          <p class="fw5 mt3 tc">{{ $t('dashboard.hr_discipline_cases_summary_blank') }}</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    Help,
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
      localCases: [],
      form: {
        searchTerm: null,
        employee: null,
        errors: [],
      },
    };
  },

  mounted() {
    this.localCases = this.data.open_cases;
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

    store(employee) {
      this.form.employee = employee.id;

      axios.post(this.data.url.store, this.form)
        .then(response => {
          this.showModal = false;
          this.localCases.unshift(response.data.data);

          this.flash(this.$t('dashboard.hr_discipline_cases_creation_success'), 'success');
          this.form.employee = null;
          this.form.searchTerm = null;
          this.potentialEmployees = [];
        })
        .catch(error => {
        });
    },
  },
};
</script>
