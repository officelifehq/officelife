<style lang="scss" scoped>
.entry-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.entry-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}

.illustration {
  top: 43px;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        💺
      </span> {{ $t('dashboard.participant_dashboard_job_opening_title') }}

      <help :url="$page.props.help_links.one_on_ones" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative pa3">
      <p class="mt0 mb2 f6 gray">{{ $t('dashboard.participant_dashboard_job_opening_desc') }}</p>

      <img loading="lazy" src="/img/streamline-icon-user-rating-star-4@100x100.png" width="90" alt="meeting" class="illustration absolute-ns di-ns dn top-1 left-1" />

      <ul class="pl6-ns pl3 pb3 pt3 pr3 ma0 list">
        <li v-for="jobOpening in localJobOpenings" :key="jobOpening.id" class="flex justify-between items-center br bl bb bb-gray bb-gray-hover pa3 entry-item">
          <div v-if="idToEdit != jobOpening.id">
            <span class="db mb0">{{ jobOpening.candidate.name }}</span>
            <span class="f7 gray">{{ jobOpening.title }}</span>
          </div>

          <!-- call to action -->
          <div v-if="idToEdit != jobOpening.id && !jobOpening.participated" class="tr">
            <a class="btn dib-l db" @click="showEditor(jobOpening.id)">{{ $t('dashboard.one_on_ones_cta') }}</a>
          </div>

          <div v-if="jobOpening.participated">
            <span class="mr1">🙏</span> {{ $t('dashboard.participant_dashboard_job_opening_thanks') }}
          </div>

          <!-- add comment about participant -->
          <div v-if="idToEdit == jobOpening.id">
            <form @submit.prevent="store(jobOpening)">
              <errors :errors="form.errors" :class="'mb2'" />

              <text-area
                :ref="'note' + jobOpening.id"
                v-model="form.note"
                :rows="10"
                @esc-key-pressed="idToEdit = 0"
              />
              <p class="db lh-copy f6">
                <span class="mr1">👋</span> {{ $t('dashboard.participant_dashboard_job_opening_note') }}
              </p>
              <p class="ma0">
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.submit')" />
                <a class="pointer" @click.prevent="idToEdit = 0">
                  {{ $t('app.cancel') }}
                </a>
              </p>
            </form>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';
import Errors from '@/Shared/Errors';

export default {
  components: {
    Help,
    LoadingButton,
    TextArea,
    Errors,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    jobOpenings: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      idToEdit: 0,
      localJobOpenings: [],
      form: {
        note: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created: function() {
    this.localJobOpenings = this.jobOpenings;
  },

  methods: {
    showEditor(id) {
      this.idToEdit = id;

      this.$nextTick(() => {
        this.$refs[`note${id}`].focus();
      });
    },

    store(jobOpening) {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/dashboard/job-openings/${jobOpening.id}/candidates/${jobOpening.candidate.id}/stages/${jobOpening.candidate_stage_id}/notes`, this.form)
        .then(response => {
          this.flash(this.$t('dashboard.participant_dashboard_job_opening_success'), 'success');
          this.idToEdit = 0;
          this.loadingState = null;
          this.localJobOpenings[this.localJobOpenings.findIndex(x => x.id === jobOpening.id)].participated = true;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
