<style lang="scss" scoped>
.avatar {
  width: 35px;
}

.name {
  padding-left: 44px;
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

.reference-number {
  padding: 2px 6px;
  border-radius: 6px;
  top: -4px;
  background-color: #ddf4ff;
  color: #0969da;
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
        <div class="mb4">
          <h2 class="mr2 mt0 mb2 fw4">
            {{ jobOpening.title }}

            <span v-if="jobOpening.reference_number" class="reference-number f7 code fw4">
              {{ jobOpening.reference_number }}
            </span>
          </h2>

          <ul class="ma0 pl0 list f7 gray">
            <li v-if="jobOpening.activated_at" class="di mr3">
              Active since {{ jobOpening.activated_at }}
            </li>
            <li class="di mr3">
              <a class="" href="#">View public version</a>
            </li>
            <li class="di mr3">
              <inertia-link :href="jobOpening.url_create">{{ $t('app.edit') }}</inertia-link>
            </li>
            <li class="di">
              <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" href="#">{{ $t('app.delete') }}</a>
            </li>
          </ul>
        </div>

        <div class="flex items-center mb4">
          <!-- team -->
          <div v-if="jobOpening.team" class="mr5">
            <div class="db relative mb2">
              <svg class="icon mr1 relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              <span class="f7 gray">
                Associated team
              </span>
            </div>

            <div class="db">
              <span class="db mb0">
                <inertia-link :href="jobOpening.team.url">{{ jobOpening.team.name }}</inertia-link>
              </span>
              <span class="fw3 gray f7">
                {{ jobOpening.team.count }} team members
              </span>
            </div>
          </div>

          <!-- sponsors -->
          <div v-if="jobOpening.sponsors" class="mr5">
            <div class="db relative mb2">
              <svg class="icon mr1 relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
              </svg>
              <span class="f7 gray">
                Sponsors
              </span>
            </div>

            <div class="flex items-start">
              <div v-for="sponsor in jobOpening.sponsors" :key="sponsor.id" class="mr3 relative">
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
                Traffic
              </span>
            </div>

            <div>
              <span class="db mb1">
                432
              </span>
              <span class="fw3 gray f7">
                page views
              </span>
            </div>
          </div>
        </div>

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-20-l w-100">
            <!-- sidebar -->
            <ul class="list ma0 pl0 sidebar">
              <!-- selected candidates -->
              <li class="pa2 br3 relative f6">
                <svg class="relative mr2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="relative">Selected</span>
              </li>

              <!-- to sort candidates -->
              <li class="pa2 active br3 relative f6">
                <svg class="relative mr2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="relative">To sort</span>
              </li>

              <!-- rejected candidates -->
              <li class="pa2 br3 relative f6">
                <svg class="relative mr2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                </svg>
                <span class="relative">Rejected</span>
              </li>
            </ul>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-80-l w-100 pl4-l">
            <div class="bg-white box">
              <ul class="ma0 pl0 list">
                <li class="pa3 candidate-item bb bb-gray bb-gray-hover"><inertia-link :href="''">Tom Yorke</inertia-link></li>
                <li class="pa3 candidate-item bb bb-gray bb-gray-hover"><inertia-link :href="''">Tom Yorke</inertia-link></li>
                <li class="pa3 candidate-item bb bb-gray bb-gray-hover"><inertia-link :href="''">Tom Yorke</inertia-link></li>
              </ul>

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
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Avatar,
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
  },

  created() {
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
