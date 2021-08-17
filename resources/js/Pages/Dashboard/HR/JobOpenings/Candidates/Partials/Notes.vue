<style lang="scss" scoped>
.note-item {
  &:last-child {
    margin-bottom: 0;
  }
}
</style>

<template>
  <div class="ph3 pv4">
    <h3 class="mt0 fw5 f5 flex justify-between">
      <span>
        <span class="mr1">
          📝
        </span> Notes
      </span>

      <a class="btn dib-l db" @click="showModal">Add a new note</a>
    </h3>

    <div v-if="modal">
      <form @submit.prevent="submit">
        <errors :errors="form.errors" />

        <text-area v-model="form.note"
                   :label="$t('account.')"
                   :datacy="'news-content-textarea'"
                   :required="true"
                   :rows="10"
                   :custom-ref="note"
                   :help="$t('account.')"
        />

        <!-- Actions -->
        <div class="mt2 mb3">
          <div class="flex-ns justify-between">
            <div>
              <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click="modal = false">
                {{ $t('app.cancel') }}
              </a>
            </div>
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.publish')" :cypress-selector="'submit-update-message-button'" />
          </div>
        </div>
      </form>
    </div>

    <div v-for="note in localNotes" :key="note.id" class="mb4 note-item bb-gray-hover">
      <div class="parsed-content" v-html="note.note"></div>

      <!-- poster information -->
      <p class="flex justify-between f7 gray mb1">
        <span>Submitted by {{ note.author.name }}</span>
        <span>{{ note.created_at }}</span>
      </p>
    </div>

    <!-- blank state -->
    <div v-if="localNotes.length == 0" class="bb bb-gray">
      <p class="tc measure center mb4 lh-copy">
        There are currently no job openings available.
      </p>
      <img loading="lazy" class="db center mb4" alt="add a position symbol" srcset="/img/company/account/blank-position-1x.png,
                                    /img/company/account/blank-position-2x.png 2x"
      />
    </div>
  </div>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';

export default {
  components: {
    LoadingButton,
    TextArea,
  },

  props: {
    jobOpeningId: {
      type: Number,
      default: null,
    },
    candidateId: {
      type: Number,
      default: null,
    },
    stageId: {
      type: Number,
      default: null,
    },
    notes: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      idToDelete: 0,
      form: {
        note: '',
        errors: [],
      },
      localNotes: [],
      loadingStateReject: '',
      processingSearch: false,
    };
  },

  mounted() {
    this.localNotes = this.notes;

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    showModal() {
      this.modal = true;

      this.$nextTick(() => {
        this.$refs.note.focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/dashboard/hr/job-openings/${this.jobOpeningId}/candidates/${this.candidateId}/stages/${this.stageId}/notes`, this.form)
        .then(response => {
          this.modal = false;
          this.flash(this.$t('dashboard.job_opening_stage_notes_success'), 'success');
          this.loadingState = null;
          this.localNotes.unshift(response.data.data);
        })
        .catch(error => {
          this.form.errors = error.response.data;
          this.loadingState = null;
        });
    },
  }
};

</script>
