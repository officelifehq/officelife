<style lang="scss" scoped>
.document-list {
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
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            …
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/hr/job-openings/' + jobOpening.id">{{ $t('app.breadcrumb_dashboard_job_opening_detail') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_dashboard_job_opening_candidate') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <!-- information about the candidate -->
        <information :candidate="candidate"
                     :tab="tab"
                     :job-opening="jobOpening"
                     :other-job-openings="otherJobOpenings"
        />

        <!-- central part  -->
        <div class="center">
          <div class="bg-white box">
            <ul class="list ma0 pl0 document-list">
              <li v-for="document in documents" :key="document.id" class="bb bb-gray bb-gray-hover pa3">
                <a :href="document.download_url" :download="document.download_url">{{ document.filename }}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Information from '@/Pages/Dashboard/HR/JobOpenings/Candidates/Partials/Information';

export default {
  components: {
    Layout,
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
    documents: {
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

      axios.post(`${this.$page.props.auth.company.id}/dashboard/hr/job-openings/${this.jobOpening.id}/candidates/${this.candidate.id}/stages/${this.stage.id}`, this.form)
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

      axios.post(`${this.$page.props.auth.company.id}/dashboard/hr/job-openings/${this.jobOpening.id}/candidates/${this.candidate.id}/stages/${this.stage.id}`, this.form)
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
