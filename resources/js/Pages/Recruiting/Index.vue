<style lang="scss" scoped>
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

.dot {
  height: 11px;
  width: 11px;
  top: 3px;
}

.active {
  .dot {
    background-color: #56bb76;
  }
}

.inactive {
  .dot {
    background-color: #c8d7cd;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns mt5">
      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-20-l w-100">
            <!-- sidebar menu -->
            <ul class="list ma0 pl0 sidebar">
              <!-- open job openings -->
              <li class="pa2 active br3 relative f6">
                <svg class="relative mr2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="relative">{{ $tc('dashboard.job_opening_index_sidebar_open', jobOpenings.open_job_openings.length, {count: jobOpenings.open_job_openings.length}) }}</span>
              </li>

              <!-- fulfilled job openings -->
              <li class="pa2 br3 relative f6">
                <svg class="relative mr2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                </svg>
                <span class="relative">
                  <inertia-link :href="jobOpenings.fulfilled_job_openings.url">{{ $tc('dashboard.job_opening_index_sidebar_fulfilled', jobOpenings.fulfilled_job_openings.count, {count: jobOpenings.fulfilled_job_openings.count}) }}</inertia-link>
                </span>
              </li>
            </ul>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-80-l w-100 pl4-l">
            <!-- cta -->
            <p class="mt0 db fw5 mb2 flex justify-between items-center">
              <span>
                {{ $t('dashboard.job_opening_index_title') }}

                <help :url="$page.props.help_links.project_messages" :top="'3px'" />
              </span>
              <inertia-link :href="jobOpenings.url_create" class="btn f5">{{ $t('dashboard.job_opening_index_cta') }}</inertia-link>
            </p>

            <div class="bg-white box mb4">
              <!-- list of job opening -->
              <ul v-if="jobOpenings.open_job_openings.length > 0" class="list ma0 pl0">
                <li v-for="jobOpening in jobOpenings.open_job_openings" :key="jobOpening.id" class="pa3 job-opening-item bb bb-gray bb-gray-hover flex items-center">
                  <!-- status -->
                  <span :class="jobOpening.active ? 'active' : 'inactive'" class="pv1 ph2 br3 gray mr3">
                    <span class="dib dot br-100 relative">&nbsp;</span>
                  </span>

                  <!-- title and ref number -->
                  <div>
                    <div :class="jobOpening.team ? 'mb2': ''" class="db">
                      <inertia-link :href="jobOpening.url" class="mr2">{{ jobOpening.title }}</inertia-link>
                      <span v-if="jobOpening.reference_number" class="reference-number f7 code fw4">{{ jobOpening.reference_number }}</span>
                    </div>

                    <p v-if="jobOpening.team" class="ma0 f7 gray"><inertia-link :href="jobOpening.team.url">{{ jobOpening.team.name }}</inertia-link></p>
                  </div>
                </li>
              </ul>

              <!-- blank state -->
              <div v-else>
                <p class="tc measure center mb4 lh-copy">
                  {{ $t('dashboard.job_opening_index_blank') }}
                </p>

                <img loading="lazy" src="/img/streamline-icon-nomad-freelance-2-5@140x140.png" alt="add email symbol" class="db center mb4" height="80"
                     width="80"
                />
              </div>
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
