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
        </span> {{ $t('dashboard.job_opening_stage_notes_title') }}
      </span>

      <a class="btn dib-l db" @click="showModal">{{ $t('dashboard.job_opening_stage_notes_cta') }}</a>
    </h3>

    <div v-if="modal">
      <form @submit.prevent="submit">
        <errors :errors="form.errors" />

        <text-area ref="note"
                   v-model="form.note"
                   :label="$t('dashboard.job_opening_stage_notes_note_textarea')"
                   :datacy="'news-content-textarea'"
                   :required="true"
                   :rows="10"
        />

        <!-- Actions -->
        <div class="mt2 mb3">
          <div class="flex-ns justify-between">
            <div>
              <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click="modal = false">
                {{ $t('app.cancel') }}
              </a>
            </div>
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.publish')" />
          </div>
        </div>
      </form>
    </div>

    <div v-for="note in localNotes" :key="note.id" class="mb4 note-item bb-gray-hover">
      <div v-if="idToEdit != note.id">
        <div class="parsed-content" v-html="note.parsed_note"></div>

        <!-- poster information -->
        <p class="flex justify-between f7 gray mb1">
          <ul class="pl0 ma0 list">
            <li class="di mr2">{{ $t('dashboard.job_opening_stage_notes_written_by', { name: note.author.name }) }}</li>

            <!-- edit link -->
            <li v-if="note.permissions.can_edit" class="di mr2"><a class="bb b--dotted bt-0 bl-0 br-0 pointer" @click.prevent="showEditModal(note)">{{ $t('app.edit') }}</a></li>

            <!-- delete link -->
            <li v-if="idToDelete == note.id" class="di">
              {{ $t('app.sure') }}
              <a class="c-delete mr1 pointer" @click.prevent="destroy(note)">
                {{ $t('app.yes') }}
              </a>
              <a class="pointer" @click.prevent="idToDelete = 0">
                {{ $t('app.no') }}
              </a>
            </li>
            <li v-if="note.permissions.can_destroy && idToDelete != note.id" class="di">
              <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click.prevent="idToDelete = note.id">
                {{ $t('app.delete') }}
              </a>
            </li>
          </ul>
          <span>{{ note.created_at }}</span>
        </p>
      </div>

      <!-- edit note -->
      <div v-else>
        <form @submit.prevent="update(note)">
          <errors :errors="form.errors" />

          <text-area ref="note"
                     v-model="form.note"
                     :label="$t('dashboard.job_opening_stage_notes_note_textarea')"
                     :datacy="'news-content-textarea'"
                     :required="true"
                     :rows="10"
          />

          <!-- Actions -->
          <div class="mt2 mb3">
            <div class="flex-ns justify-between">
              <div>
                <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click="idToEdit = 0">
                  {{ $t('app.cancel') }}
                </a>
              </div>
              <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.publish')" />
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- blank state -->
    <div v-if="localNotes.length == 0">
      <p class="tc measure center mb4 lh-copy">
        {{ $t('dashboard.job_opening_stage_notes_blank') }}
      </p>

      <img loading="lazy" src="/img/streamline-icon-color-notes@100x100.png" alt="symbol" class="db center mb4" height="80"
           width="80"
      />
    </div>
  </div>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';
import Errors from '@/Shared/Errors';

export default {
  components: {
    LoadingButton,
    TextArea,
    Errors,
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
      idToEdit: 0,
      idToDelete: 0,
      form: {
        note: '',
        errors: [],
      },
      localNotes: [],
      loadingState: '',
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
      this.form.note = '';

      this.$nextTick(() => {
        this.$refs.note.focus();
      });
    },

    showEditModal(note) {
      this.form.note = note.note;
      this.idToEdit = note.id;

      this.$nextTick(() => {
        this.$refs.note.focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/recruiting/job-openings/${this.jobOpeningId}/candidates/${this.candidateId}/stages/${this.stageId}/notes`, this.form)
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

    update(note) {
      this.loadingState = 'loading';

      axios.put(`${this.$page.props.auth.company.id}/recruiting/job-openings/${this.jobOpeningId}/candidates/${this.candidateId}/stages/${this.stageId}/notes/${note.id}`, this.form)
        .then(response => {
          this.localNotes[this.localNotes.findIndex(x => x.id === note.id)] = response.data.data;
          this.flash(this.$t('dashboard.job_opening_stage_notes_update_success'), 'success');

          this.idToEdit = 0;
          this.form.note = '';
          this.loadingState = null;
        })
        .catch(error => {
          this.form.errors = error.response.data;
          this.loadingState = null;
        });
    },

    destroy(note) {
      axios.delete(`${this.$page.props.auth.company.id}/recruiting/job-openings/${this.jobOpeningId}/candidates/${this.candidateId}/stages/${this.stageId}/notes/${note.id}`)
        .then(response => {
          this.idToDelete = 0;
          var id = this.localNotes.findIndex(x => x.id == note.id);
          this.localNotes.splice(id, 1);

          this.flash(this.$t('dashboard.job_opening_stage_notes_destroy_success'), 'success');
        })
        .catch(error => {
          this.idToDelete = 0;
        });
    },
  }
};

</script>
