<style lang="scss" scoped>
.avatar {
  width: 35px;
}

.name {
  padding-left: 44px;
}

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

.url-icon {
  width: 15px;
  top: 4px;
}

.sidebar {
  svg {
    width: 20px;
    top: 3px;
    color: #9da3ae;
  }

  span {
    top: -2px;
  }

  .active {
    background-color: #e5e7ea;

    svg {
      color: #6c727f;
    }

    span {
      color: #121826;
    }
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/hr/job-openings'">{{ $t('app.breadcrumb_hr_job_openings_active') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_job_opening_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
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
          <div v-if="sponsors" class="mr5">
            <div class="db relative mb2">
              <svg class="icon mr1 relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
              </svg>
              <span class="f7 gray">
                {{ $t('dashboard.job_opening_show_sponsor') }}
              </span>
            </div>

            <div class="flex items-start">
              <div v-for="sponsor in sponsors" :key="sponsor.id" class="mr3 relative">
                <avatar :avatar="sponsor.avatar" :size="35" :class="'br-100 absolute avatar'" />

                <div class="name">
                  <span class="db ma0">
                    <inertia-link :href="sponsor.url">{{ sponsor.name }}</inertia-link>
                  </span>
                  <span v-if="sponsor.position" class="fw3 gray f7">
                    {{ sponsor.position.title }}
                  </span>
                  <span v-else class="fw3 gray f7">
                    {{ $t('app.no_position_defined') }}
                  </span>
                </div>
              </div>
            </div>
          </div>

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
        <div class="center br3 mb5 tc">
          <div class="cf dib btn-group">
            <inertia-link :href="''" class="f6 fl ph3 pv2 dib pointer no-underline">
              {{ $t('dashboard.job_opening_show_tab_rejected', {count: stats.rejected}) }}
            </inertia-link>
            <inertia-link :href="''" :class="{ 'selected': tab === 'to_sort'}" class="f6 fl ph3 pv2 dib pointer no-underline">
              {{ $t('dashboard.job_opening_show_tab_to_sort', {count: stats.to_sort}) }}
            </inertia-link>
            <inertia-link :href="''" class="f6 fl ph3 pv2 dib pointer no-underline">
              {{ $t('dashboard.job_opening_show_tab_selected', {count: stats.selected}) }}
            </inertia-link>
          </div>
        </div>
      </div>

      <div class="mw7 center br3 mb5 relative z-1">
        <div class="bg-white box">
          <ul v-if="candidates.length > 0" class="ma0 pl0 list">
            <li v-for="candidate in candidates" :key="candidate.id" class="pa3 candidate-item bb bb-gray bb-gray-hover relative flex justify-between">
              <div>
                <span class="dib relative mr2 br-100 dot"></span>
                <inertia-link :href="candidate.url">{{ candidate.name }}</inertia-link>
              </div>

              <span class="f7 gray">{{ candidate.received_at }}</span>
            </li>
          </ul>

          <div v-else>
            <p class="tc measure center mb4 lh-copy">
              There are no candidates for now.
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
import Avatar from '@/Shared/Avatar';
import Information from '@/Pages/Dashboard/HR/JobOpenings/Partials/Information';

export default {
  components: {
    Layout,
    Avatar,
    Information,
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

  data() {
    return {
      deleteMode: false,
      loadingState: '',
      form: {
        name: null,
        errors: [],
      },
    };
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    destroy(stageId) {
      axios.delete('/' + this.$page.props.auth.company.id + '/dashboard/hr/job-openings/' + this.jobOpening.id)
        .then(response => {
          localStorage.success = this.$t('dashboard.job_opening_show_delete_success');
          this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  },
};

</script>
