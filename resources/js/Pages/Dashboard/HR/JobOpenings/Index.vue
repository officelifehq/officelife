<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 2px;
  width: 35px;
}

.job-opening-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.job-opening-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}

.reference-number {
  padding: 2px 6px;
  border-radius: 6px;
  top: -4px;
  background-color: #ddf4ff;
  color: #0969da;
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects'">{{ $t('app.breadcrumb_hr') }}</inertia-link>
          </li>
          <li class="di">
            All the job openings
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-20-l w-100">
            <div class="">
              <ul class="list ma0 pl0 sidebar">
                <li class="pa2 active br3 relative f6">
                  <svg class="relative mr2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  <span class="relative">{{ jobOpenings.open_job_openings.length }} open job opening</span>
                </li>
                <li class="pa2 relative">
                  <svg class="relative mr2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                  </svg>
                  <span class="relative">
                    <inertia-link :href="jobOpenings.fulfilled_job_openings.url">{{ jobOpenings.fulfilled_job_openings.count }} job</inertia-link>
                  </span>
                </li>
              </ul>
            </div>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-80-l w-100 pl4-l">
            <div class="bg-white box mb4">
              <ul class="list ma0 pl0">
                <li v-for="jobOpening in jobOpenings.open_job_openings" :key="jobOpening.id" class="pa3 job-opening-item bb bb-gray bb-gray-hover">
                  <div class="mb2 db">
                    <inertia-link :href="jobOpening.url" class="mr2">{{ jobOpening.title }}</inertia-link>
                    <span class="reference-number f7 code fw4">{{ jobOpening.reference_number }}</span>
                  </div>

                  <p v-if="jobOpening.team" class="ma0 f7 gray">Needed in <inertia-link :href="jobOpening.team.url">{{ jobOpening.team.name }}</inertia-link></p>
                </li>
              </ul>
            </div>
          </div>
        </div>
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
    jobOpenings: {
      type: Array,
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
