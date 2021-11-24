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
      </h2>

      <div class="tc mb4">
        <div class="cf dib btn-group">
          <inertia-link :href="data.url.open" class="f6 fl ph3 pv2 dib pointer no-underline">
            {{ $t('dashboard.hr_discipline_cases_open_cases', {count: data.open_cases_count}) }}
          </inertia-link>
          <inertia-link :href="data.url.closed" class="selected f6 fl ph3 pv2 dib pointer no-underline">
            {{ $t('dashboard.hr_discipline_cases_closed_cases', {count: data.closed_cases_count}) }}
          </inertia-link>
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
      localCases: [],
    };
  },

  mounted() {
    this.localCases = this.data.closed_cases;
  },

  methods: {
  },
};
</script>
