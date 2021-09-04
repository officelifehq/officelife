<style lang="scss" scoped>
.dot {
  height: 11px;
  width: 11px;
  top: 0px;
  background-color: #5a45ff;
}

.candidate-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.candidate-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}

.icon {
  color: #6a73a4;
  width: 15px;
  top: 2px;
}

.stage-list {
  li + li:before {
    content: '>';
    padding-left: 5px;
    padding-right: 5px;
  }
}

.stage-icon {
  width: 13px;
  top: 2px;

  &.passed {
    color: #3ebf46;
  }

  &.rejected {
    color: #ea5757;
  }
}

.party {
  background-color: #e6ffe6;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb
        :root-url="'/' + $page.props.auth.company.id + '/recruiting/job-openings'"
        :root="$t('app.breadcrumb_hr_job_openings_active')"
        :has-more="false"
      >
        {{ $t('app.breadcrumb_job_opening_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <!-- candidate that fulfilled the job -->
        <div v-if="jobOpening.fulfilled" class="box pa3 relative mb3 party tc">
          <span class="mr1">
            🎉
          </span>

          <p v-if="jobOpening.employee.id" class="ma0 di" v-html="$t('dashboard.job_opening_show_candidate_employee', { url: jobOpening.employee.url, name: jobOpening.employee.name })"></p>
          <p v-else class="ma0 di">{{ $t('dashboard.job_opening_show_candidate_employee_no_name', { name: jobOpening.employee.name }) }}</p>
        </div>

        <!-- header -->
        <information :job-opening="jobOpening" />

        <!-- teams, sponsors and stats -->
        <div class="flex items-center mb5">
          <!-- position -->
          <div v-if="jobOpening.position" class="mr5">
            <div class="db relative mb2">
              <svg class="icon mr1 relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              <span class="f7 gray">
                {{ $t('dashboard.job_opening_show_position') }}
              </span>
            </div>

            <div class="db">
              <span class="db mb0">
                <inertia-link :href="jobOpening.position.url">{{ jobOpening.position.title }}</inertia-link>
              </span>
              <span class="fw3 gray f7">
                {{ $tc('dashboard.job_opening_show_position_count', jobOpening.position.count_employees, {count: jobOpening.position.count_employees} ) }}
              </span>
            </div>
          </div>

          <!-- team -->
          <div v-if="jobOpening.team" class="mr5">
            <div class="db relative mb2">
              <svg class="icon mr1 relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              <span class="f7 gray">
                {{ $t('dashboard.job_opening_show_team') }}
              </span>
            </div>

            <div class="db">
              <span class="db mb0">
                <inertia-link :href="jobOpening.team.url">{{ jobOpening.team.name }}</inertia-link>
              </span>
              <span class="fw3 gray f7">
                {{ $tc('dashboard.job_opening_show_team_count', jobOpening.team.count, {count: jobOpening.team.count} ) }}
              </span>
            </div>
          </div>

          <!-- sponsors -->
          <sponsors :sponsors="sponsors" />

          <!-- page views -->
          <div class="">
            <div class="db relative mb2">
              <svg class="icon mr1 relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
              </svg>
              <span class="f7 gray">
                {{ $t('dashboard.job_opening_show_traffic') }}
              </span>
            </div>

            <div>
              <span class="db mb1">
                {{ jobOpening.page_views }}
              </span>
              <span class="fw3 gray f7">
                {{ $t('dashboard.job_opening_show_traffic_help') }}
              </span>
            </div>
          </div>
        </div>

        <!-- menu -->
        <tabs :stats="stats" :tab="tab" />
      </div>

      <div class="mw7 center br3 mb5 relative z-1">
        <div class="bg-white box">
          <ul v-if="candidates.length > 0" class="ma0 pl0 list">
            <li v-for="candidate in candidates" :key="candidate.id" class="pa3 candidate-item bb bb-gray bb-gray-hover relative">
              <div class="flex justify-between">
                <div>
                  <span v-if="tab == 'to_sort'" class="dib relative mr2 br-100 dot"></span>
                  <inertia-link :href="candidate.url">{{ candidate.name }}</inertia-link>
                </div>
                <span class="f7 gray">{{ candidate.received_at }}</span>
              </div>

              <ul v-if="candidate.stages" class="db ma0 pl0 mt2 f7 gray stage-list">
                <li v-for="stage in candidate.stages" :key="stage.id" class="di relative">
                  <svg v-if="stage.status == 'passed'" :class="stage.status" class="stage-icon relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                       fill="currentColor"
                  >
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>

                  <svg v-if="stage.status == 'rejected'" :class="stage.status" class="stage-icon relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                       fill="currentColor"
                  >
                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                  </svg>

                  {{ stage.name }}
                </li>
              </ul>
            </li>
          </ul>

          <div v-else>
            <p class="tc measure center mb4 lh-copy">
              {{ $t('dashboard.job_opening_show_blank') }}
            </p>

            <img loading="lazy" src="/img/streamline-icon-detective-1-5@140x140.png" alt="add email symbol" class="db center mb4" height="120"
                 width="120"
            />
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Information from '@/Pages/Recruiting/Partials/Information';
import Sponsors from '@/Pages/Recruiting/Partials/Sponsors';
import Tabs from '@/Pages/Recruiting/Partials/Tabs';

export default {
  components: {
    Layout,
    Breadcrumb,
    Information,
    Sponsors,
    Tabs,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    jobOpening: {
      type: Object,
      default: null,
    },
    sponsors: {
      type: Object,
      default: null,
    },
    stats: {
      type: Object,
      default: null,
    },
    candidates: {
      type: Object,
      default: null,
    },
    tab: {
      type: String,
      default: 'to_sort',
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
