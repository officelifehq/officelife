<style lang="scss" scoped>
.box-bottom {
  border-bottom-left-radius: 11px;
  border-bottom-right-radius: 11px;
}

.warning {
  box-shadow: 0 0 0 1px #e3e8ee;
  background-color: #f7fafc;
}

.badge {
  padding: 1px 6px;
  border-radius: 4px;

  &.active {
    background-color: #cbf4c9;
  }

  &.closed {
    background-color: #e3e8ee;
  }
}

.sidebar {
  svg {
    width: 20px;
    top: 7px;
    color: #9da3ae;

    &.rejected {
      color: #ea5757;
    }

    &.passed {
      color: #3ebf46;
    }
  }

  span {
    top: -2px;
  }

  li.active,
  li:hover {
    background-color: #e5e7ea;

    svg {
      color: #6c727f;

      &.rejected {
        color: #ea5757;
      }

      &.passed {
        color: #3ebf46;
      }
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
        <div class="box bg-white mb4">
          <div class="pa3 flex justify-between items-center bb bb-gray">
            <!-- name + summary -->
            <div class="">
              <h2 class="mt0 mb2 relative fw4">
                {{ candidate.name }}
              </h2>

              <ul class="list pa0 ma0 f7 gray">
                <li class="di mr3">
                  Applied {{ candidate.created_at }}
                </li>
                <li class="di mr3">{{ candidate.email }}</li>
                <li class="di">telephone number</li>
              </ul>
            </div>
          </div>

          <div class="bg-gray pa3 f7 box-bottom">
            <p class="ma0">Job opening: <inertia-link :href="jobOpening.url">{{ jobOpening.title }}</inertia-link></p>
          </div>
        </div>

        <!-- has the candidate applied to other jobs in the company -->
        <div class="warning pa3 br3 mb4">
          <p class="ma0 mb3 f6"><span class="mr1">⚠️</span> Has likely applied to 3 other jobs (based on the email address used)</p>
          <ul class="list ma0 pl0">
            <li class="mb2 mr2"><inertia-link>Finance</inertia-link> <span class="badge f7 active">active</span></li>
            <li class="mr2"><inertia-link>Finance</inertia-link> <span class="badge f7 closed">closed</span></li>
          </ul>
        </div>

        <div class="center br3 mb5 tc">
          <div class="cf dib btn-group">
            <inertia-link :href="''" class="f6 fl ph3 pv2 dib pointer no-underline">
              Curriculum vitae
            </inertia-link>
            <inertia-link :href="''" class="f6 fl ph3 pv2 dib pointer no-underline">
              Recruiting process
            </inertia-link>
          </div>
        </div>

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-20-l w-100">
            <!-- sidebar -->
            <ul class="list ma0 pl0 sidebar">
              <li v-for="stage in candidate.stages" :key="stage.id" class="pa2 br3 relative f6 flex items-start mb2">
                <!-- passed -->
                <svg v-if="stage.status == 'passed'" :class="stage.status" xmlns="http://www.w3.org/2000/svg" class="relative mr2" viewBox="0 0 20 20"
                     fill="currentColor"
                >
                  <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>

                <!-- rejected -->
                <svg v-if="stage.status == 'rejected'" :class="stage.status" xmlns="http://www.w3.org/2000/svg" class="relative mr2" viewBox="0 0 20 20"
                     fill="currentColor"
                >
                  <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                </svg>

                <!-- pending -->
                <svg v-if="stage.status == 'pending'" xmlns="http://www.w3.org/2000/svg" class="relative mr2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>

                <div class="">
                  <span class="mb2 db f7 gray fw5">Stage {{ stage.position }}</span>
                  <span class="relative">{{ stage.name }}</span>
                </div>
              </li>
            </ul>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-80-l w-100 pl4-l">
            <div class="bg-white box">
              <!-- actions -->
              <div class="pa3 bb bb-gray">
                <div class="tc">
                  <img loading="lazy" src="/img/streamline-icon-gavel@100x100.png" width="100" height="100" alt="meeting"
                       class="mb3"
                  />
                </div>
                <p class="mt0 mb3 f4 tc">
                  Do you consider this candidate to be qualified for the next stage?
                </p>
                <div class="list ma0 pl0 tc">
                  <loading-button :class="'btn w-auto-ns w-100 pv2 ph3 mb0-ns mb2 mr3 destroy'" :state="loadingStateReject" :text="'Reject'" @click="reject()" />
                  <loading-button :class="'btn w-auto-ns w-100 pv2 ph3 mb0-ns mb2 add'" :state="loadingStateAccept" :text="'Qualifies for next stage'" @click="accept()" />
                </div>
              </div>

              <div class="pa3 bb bb-gray">
                <!-- employees -->
                <h3 class="mt0 fw5 f6">
                  Employees needed to be talked to
                </h3>
                <div class="flex items-center">
                  <div class="mr3 relative" data-v-cff706f6="">
                    <span class="db ma0" data-v-cff706f6="">
                      <a href="https://officelife.test/1/employees/1" data-v-cff706f6="">Michael Scott</a>
                    </span>
                  </div>
                </div>
                <span>Ask employee to assist for this stage</span>
              </div>

              <!-- add a note -->
              <div class="pa3 bb bb-gray">
                <text-area v-model="form.content"
                           :label="$t('account.company_news_new_content')"
                           :datacy="'news-content-textarea'"
                           :required="true"
                           :rows="10"
                           :help="$t('account.company_news_new_content_help')"
                />
              </div>

              <div class="pa3 bb bb-gray">
                <div class="lh-copy">
                  Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?
                </div>

                <!-- poster information -->
                <p class="flex justify-between f7 gray mb1">
                  <span>Submitted by Regis Freyd</span>
                  <span>3:30pm</span>
                </p>
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
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';

export default {
  components: {
    Layout,
    LoadingButton,
    TextArea,
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
    candidate: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        accepted: true,
        errors: [],
      },
      loadingStateReject: '',
      loadingStateAccept: '',
    };
  },

  created() {
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    accept() {
      this.loadingStateAccept = 'loading';
      this.form.accepted = true;

      axios.post(`${this.$page.props.auth.company.id}/dashboard/hr/job-openings/${this.jobOpening.id}/candidates/${this.candidate.id}`, this.form)
        .then(response => {
          //localStorage.success = this.$t('dashboard.job_opening_new_success');
          //this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.loadingStateAccept = null;
          this.form.errors = error.response.data;
        });
    },

    reject() {
      this.loadingStateReject = 'loading';
      this.form.accepted = false;

      axios.post(`${this.$page.props.auth.company.id}/dashboard/hr/job-openings/${this.jobOpening.id}/candidates/${this.candidate.id}`, this.form)
        .then(response => {
          // localStorage.success = this.$t('dashboard.job_opening_new_success');
          // this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.loadingStateReject = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
