<style lang="scss" scoped>
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

.decider {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;

  &.passed {
    background-color: #e9fbe9;
  }

  &.rejected {
    background-color: #ae20201f;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb
        :root-url="'/' + $page.props.auth.company.id + '/recruiting/job-openings'"
        :root="$t('app.breadcrumb_hr_job_openings_active')"
        :previous-url="'/' + $page.props.auth.company.id + '/recruiting/job-openings/' + jobOpening.id"
        :previous="$t('app.breadcrumb_dashboard_job_opening_detail')"
        :has-more="false"
      >
        {{ $t('app.breadcrumb_dashboard_job_opening_candidate') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <!-- information about the candidate -->
        <information :candidate="candidate"
                     :tab="tab"
                     :job-opening="jobOpening"
                     :other-job-openings="otherJobOpenings"
        />

        <!-- central part  -->
        <div class="cf center">
          <!-- left column -->
          <div class="fl w-20-l w-100">
            <!-- sidebar -->
            <ul class="list ma0 pl0 sidebar">
              <li v-for="currentStage in candidate.stages" :key="currentStage.id" :class="{ 'active': stage.id == currentStage.id }" class="pointer pa2 br3 relative f6 flex items-start mb2" @click.prevent="load(currentStage.url)">
                <!-- passed -->
                <svg v-if="currentStage.status == 'passed'" :class="currentStage.status" xmlns="http://www.w3.org/2000/svg" class="relative mr2" viewBox="0 0 20 20"
                     fill="currentColor"
                >
                  <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>

                <!-- rejected -->
                <svg v-if="currentStage.status == 'rejected'" :class="currentStage.status" xmlns="http://www.w3.org/2000/svg" class="relative mr2" viewBox="0 0 20 20"
                     fill="currentColor"
                >
                  <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                </svg>

                <!-- pending -->
                <svg v-if="currentStage.status == 'pending'" xmlns="http://www.w3.org/2000/svg" class="relative mr2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>

                <div class="">
                  <span class="mb2 db f7 gray fw5">Stage {{ currentStage.position }}</span>
                  <span class="relative">{{ currentStage.name }}</span>
                </div>
              </li>
            </ul>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-80-l w-100 pl4-l">
            <div class="bg-white box">
              <!-- actions -->
              <div v-if="!candidate.rejected && !stage.decision && !jobOpening.fulfilled" class="pa3 pb4 bb bb-gray">
                <div class="tc">
                  <img loading="lazy" src="/img/streamline-icon-gavel@100x100.png" width="100" height="100" alt="meeting"
                       class="mb3"
                  />
                </div>
                <p class="mt0 mb3 f4 tc">
                  {{ $t('dashboard.job_opening_show_stage_decision_question') }}
                </p>
                <div class="list ma0 pl0 tc">
                  <loading-button :class="'btn w-auto-ns w-100 pv2 ph3 mb0-ns mb2 mr3 destroy'" :state="loadingStateReject" :text="$t('app.reject')" @click="reject()" />
                  <loading-button :class="'btn w-auto-ns w-100 pv2 ph3 mb0-ns mb2 add'" :state="loadingStateAccept" :text="$t('dashboard.job_opening_show_stage_decision')" @click="accept()" />
                </div>
              </div>

              <!-- decider information -->
              <div v-if="stage.decision">
                <div :class="stage.status" class="pa3 bb bb-gray flex justify-between decider">
                  <!-- decision -->
                  <div>
                    <span class="db mb2 f7 gray">
                      {{ $t('dashboard.job_opening_show_stage_decision_text') }}
                    </span>
                    <span>{{ $t('dashboard.job_opening_stage_decision_' + stage.status) }}</span>
                  </div>

                  <!-- decider name -->
                  <div>
                    <span class="db mb2 f7 gray">
                      {{ $t('dashboard.job_opening_show_stage_decider') }}
                    </span>

                    <!-- case the employee still exists -->
                    <small-name-and-avatar
                      v-if="stage.decision.decider.id"
                      :name="stage.decision.decider.name"
                      :avatar="stage.decision.decider.avatar"
                      :url="stage.decision.decider.url"
                      :top="'0px'"
                      :margin-between-name-avatar="'29px'"
                    />

                    <!-- case the employee doesn't exist anymore -->
                    <span v-if="!stage.decision.decider.id">
                      {{ stage.decision.decider.name }}
                    </span>
                  </div>

                  <!-- decider date -->
                  <div>
                    <span class="db mb2 f7 gray">
                      {{ $t('dashboard.job_opening_show_stage_decided_on') }}
                    </span>
                    <span>{{ stage.decision.decided_at }}</span>
                  </div>
                </div>
              </div>

              <!-- Participants -->
              <participants :job-opening-id="jobOpening.id"
                            :candidate-id="candidate.id"
                            :stage-id="stage.id"
                            :participants="participants"
              />

              <!-- add a note -->
              <notes :job-opening-id="jobOpening.id"
                     :candidate-id="candidate.id"
                     :stage-id="stage.id"
                     :notes="notes"
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import LoadingButton from '@/Shared/LoadingButton';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import Participants from '@/Pages/Recruiting/Candidates/Partials/Participants';
import Notes from '@/Pages/Recruiting/Candidates/Partials/Notes';
import Information from '@/Pages/Recruiting/Candidates/Partials/Information';

export default {
  components: {
    Layout,
    Breadcrumb,
    LoadingButton,
    SmallNameAndAvatar,
    Participants,
    Notes,
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
    candidate: {
      type: Object,
      default: null,
    },
    otherJobOpenings: {
      type: Object,
      default: null,
    },
    stage: {
      type: Object,
      default: null,
    },
    participants: {
      type: Object,
      default: null,
    },
    notes: {
      type: Object,
      default: null,
    },
    tab: {
      type: String,
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

      axios.post(`${this.$page.props.auth.company.id}/recruiting/job-openings/${this.jobOpening.id}/candidates/${this.candidate.id}/stages/${this.stage.id}`, this.form)
        .then(response => {
          localStorage.success = this.$t('dashboard.job_opening_stage_passed');
          this.$inertia.visit(response.data.url);
        })
        .catch(error => {
          this.loadingStateAccept = null;
          this.form.errors = error.response.data;
        });
    },

    reject() {
      this.loadingStateReject = 'loading';
      this.form.accepted = false;

      axios.post(`${this.$page.props.auth.company.id}/recruiting/job-openings/${this.jobOpening.id}/candidates/${this.candidate.id}/stages/${this.stage.id}`, this.form)
        .then(response => {
          localStorage.success = this.$t('dashboard.job_opening_stage_rejected');
          this.$inertia.visit(response.data.url);
        })
        .catch(error => {
          this.loadingStateReject = null;
          this.form.errors = error.response.data;
        });
    },

    load(url) {
      this.$inertia.visit(url);
    },
  }
};

</script>
