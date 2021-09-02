<style scoped>
input[type=checkbox] {
  top: 5px;
}

input[type=radio] {
  top: -2px;
}

.step {
  background-color: #06B6D4;
  width: 24px;
  color: #fff;
  height: 24px;
  padding: 3px 10px 5px 8px;
}

.check {
  color: #fb8444;
  top: 4px;
  width: 20px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/recruiting/job-openings'"
                  :root="$t('app.breadcrumb_hr_job_openings_active')"
                  :previous-url="'/' + $page.props.auth.company.id + '/recruiting/job-openings/' + jobOpening.id + '/candidates/' + candidate.id"
                  :previous="$t('app.breadcrumb_dashboard_job_opening_candidate')"
      >
        {{ $t('app.breadcrumb_dashboard_job_opening_candidate_hire') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb3 bg-white box relative z-1">
        <div class="">
          <h2 class="pa3 mt3 center tc normal mb2">
            {{ $t('dashboard.job_opening_hire_title', { name: candidate.name }) }}
          </h2>

          <form @submit.prevent="submit">
            <div v-if="form.errors" class="pa3">
              <errors :errors="form.errors" />
            </div>

            <!-- Basic information -->
            <p class="pa3 relative mv0"><span class="step br-100 relative mr2">1</span> {{ $t('dashboard.job_opening_hire_step_1') }}</p>
            <div class="cf pa3 bb bb-gray pb4">
              <!-- First name -->
              <text-input :id="'first_name'"
                          v-model="form.first_name"
                          :name="'first_name'"
                          :errors="$page.props.errors.first_name"
                          :label="$t('account.employee_new_firstname')"
                          :required="true"
              />

              <!-- Last name -->
              <text-input :id="'last_name'"
                          v-model="form.last_name"
                          :name="'last_name'"
                          :errors="$page.props.errors.last_name"
                          :label="$t('account.employee_new_lastname')"
                          :required="true"
              />

              <!-- Email -->
              <text-input :id="'email'"
                          v-model="form.email"
                          :name="'email'"
                          :type="'email'"
                          :errors="$page.props.errors.email"
                          :label="$t('account.employee_new_email')"
                          :required="true"
              />
            </div>

            <!-- Hiring date -->
            <p class="pa3 pb0 relative mb0"><span class="step br-100 relative mr2">2</span> {{ $t('dashboard.job_opening_hire_step_2') }}</p>
            <div class="cf pa3 bb bb-gray pb4">
              <div class="dt-ns dt--fixed di">
                <div class="dtc-ns pr2-ns pb0-ns w-100">
                  <!-- year -->
                  <text-input :id="'year'"
                              v-model="form.year"
                              :name="'year'"
                              :errors="$page.props.errors.year"
                              :label="$t('employee.edit_information_year')"
                              :required="true"
                              :type="'number'"
                              :min="1900"
                              :max="2050"
                              :help="$t('employee.edit_information_year_help')"
                  />
                </div>
                <div class="dtc-ns pr2-ns pb0-ns w-100">
                  <!-- month -->
                  <text-input :id="'month'"
                              v-model="form.month"
                              :name="'month'"
                              :errors="$page.props.errors.month"
                              :label="$t('employee.edit_information_month')"
                              :required="true"
                              :type="'number'"
                              :min="1"
                              :max="12"
                              :help="$t('employee.edit_information_month_help')"
                  />
                </div>
                <div class="dtc-ns pr2-ns pb0-ns w-100">
                  <!-- day -->
                  <text-input :id="'day'"
                              v-model="form.day"
                              :name="'day'"
                              :errors="$page.props.errors.day"
                              :label="$t('employee.edit_information_day')"
                              :required="true"
                              :type="'number'"
                              :min="1"
                              :max="31"
                              :help="$t('employee.edit_information_day_help')"
                  />
                </div>
              </div>
            </div>

            <!-- Hiring date -->
            <p class="pa3 relative mb0"><span class="step br-100 relative mr2">3</span> {{ $t('dashboard.job_opening_hire_step_3') }}</p>
            <div v-if="jobOpening.team" class="cf pa3 bb bb-gray pb4">
              {{ $t('dashboard.job_opening_hire_step_3_text_team', { position: jobOpening.position.title, team: jobOpening.team.name }) }}
            </div>
            <div v-else class="cf pa3 bb bb-gray pb4">
              {{ $t('dashboard.job_opening_hire_step_3_text', { position: jobOpening.position.title }) }}
            </div>

            <!-- Hiring date -->
            <div class="pa3 bb bb-gray">
              <p class="relative mb3"><span class="step br-100 relative mr2">4</span> {{ $t('dashboard.job_opening_hire_step_4_title') }}</p>
              <ul class="list pl4 mt3">
                <li class="lh-copy mb2 relative">
                  <svg class="check relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>

                  {{ $t('dashboard.job_opening_hire_step_4') }}
                </li>
                <li class="lh-copy relative">
                  <svg class="check relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>

                  {{ $t('dashboard.job_opening_hire_step_4_bis') }}
                </li>
              </ul>
            </div>

            <!-- Actions -->
            <div class="cf pa3">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/recruiting/job-openings/' + jobOpening.id + '/candidates/' + candidate.id" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.confirm')" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    candidate: {
      type: Object,
      default: null,
    },
    jobOpening: {
      type: Object,
      default: null,
    },
    year: {
      type: Number,
      default: null,
    },
    month: {
      type: Number,
      default: null,
    },
    day: {
      type: Number,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      form: {
        first_name: null,
        last_name: null,
        email: null,
        year: null,
        month: null,
        day: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  mounted() {
    this.form.first_name = this.candidate.name;
    this.form.last_name = this.candidate.name;
    this.form.email = this.candidate.email;
    this.form.year = this.year;
    this.form.month = this.month;
    this.form.day = this.day;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/recruiting/job-openings/${this.jobOpening.id}/candidates/${this.candidate.id}/hire`, this.form)
        .then(response => {
          localStorage.success = this.$t('dashboard.job_opening_hire_success');
          this.$inertia.visit(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
