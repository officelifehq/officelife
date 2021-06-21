<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.add-item-section {
  margin-left: 24px;
  top: 5px;
  background-color: #f5f5f5;
}

.edit-item {
  left: -20px;
}

@media (max-width: 480px) {
  .edit-item {
    left: 0;
  }
}

.title-section {
  background-color: #E9EDF2;
}
</style>

<template>
  <div>
    <h3 class="f4 fw5 mb2">
      <span class="mr1">
        📝
      </span> {{ $t('dashboard.one_on_ones_note_title') }}
    </h3>
    <p class="f6 gray mt1 pl4">{{ $t('dashboard.one_on_ones_note_desc') }}</p>
    <ul class="pl4 mb2">
      <li v-for="note in localNotes" :key="note.id" :data-cy="'note-' + note.id" class="relative mb2 ml3" @mouseover="showHoverOnNote(note.id)"
          @mouseleave="hideHoverOnNote()"
      >
        <span v-if="noteToEdit != note.id" class="lh-copy">
          {{ note.note }}
        </span>

        <!-- actions that should appear on hover -->
        <div v-show="actionsForNote == note.id && noteToEdit != note.id" class="hide-actions di f6 bg-white ph1 pv1 br3">
          <!-- edit -->
          <span :data-cy="'edit-note-' + note.id" class="di mr1 bb b--dotted bt-0 bl-0 br-0 pointer" @click="showEditNote(note)">
            {{ $t('app.edit') }}
          </span>

          <!-- delete -->
          <a v-if="noteToDelete == 0" :data-cy="'delete-note-' + note.id" class="c-delete mr1 pointer" @click.prevent="noteToDelete = note.id">{{ $t('app.delete') }}</a>
          <span v-if="noteToDelete == note.id">
            {{ $t('app.sure') }}
            <a :data-cy="'delete-note-cta-' + note.id" class="c-delete mr1 pointer" @click.prevent="destroy(note.id)">
              {{ $t('app.yes') }}
            </a>
            <a class="pointer" @click.prevent="noteToDelete = 0">
              {{ $t('app.no') }}
            </a>
          </span>
        </div>

        <!-- edit note -->
        <div v-if="note.id == noteToEdit" class="bg-gray add-item-section edit-item ph2 mt1 mb3 pv1 br1 relative">
          <form @submit.prevent="update(note.id)">
            <text-area
              :ref="'note' + note.id"
              v-model="form.description"
              :label="$t('dashboard.one_on_ones_note_edit_desc')"
              :datacy="'edit-note-description-textarea-' + note.id"
              :required="true"
              :rows="2"
              @esc-key-pressed="noteToEdit = 0"
            />
            <!-- actions -->
            <div>
              <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" data-cy="edit-note-cta" :state="loadingState" :text="$t('app.update')" />
              <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="noteToEdit = 0">
                {{ $t('app.cancel') }}
              </a>
            </div>
          </form>
        </div>
      </li>
      <!-- cta to add a new note -->
      <li v-if="!addNoteMode && !entry.happened" class="list bg-gray add-item-section ph2 mt1 pv1 br1">
        <span class="bb b--dotted bt-0 bl-0 br-0 pointer f6" data-cy="add-new-note" @click="displayAddNote()">{{ $t('dashboard.one_on_ones_note_cta') }}</span>
      </li>
      <!-- add a new note -->
      <li v-if="addNoteMode" class="bg-gray add-item-section ph2 mt1 pv1 br1 list">
        <form @submit.prevent="store()">
          <text-area
            ref="newNoteItem"
            v-model="form.description"
            :label="$t('dashboard.one_on_ones_note_textarea_desc')"
            :datacy="'add-note-textarea'"
            :required="true"
            :rows="2"
            @esc-key-pressed="addNoteMode = false"
          />
          <!-- actions -->
          <div>
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" data-cy="add-new-note-cta" :state="loadingState" :text="$t('app.add')" />
            <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addNoteMode = false">
              {{ $t('app.cancel') }}
            </a>
          </div>
        </form>
      </li>
    </ul>
  </div>
</template>

<script>
import TextArea from '@/Shared/TextArea';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    TextArea,
    LoadingButton,
  },

  props: {
    entry: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      loadingState: '',
      addNoteMode: false,
      localNotes: null,
      actionsForNote: 0,
      noteToEdit: 0,
      noteToDelete: 0,
      editNoteMode: false,
      form: {
        description: null,
        content: null,
        errors: [],
      },
    };
  },

  watch: {
    entry: {
      handler(value) {
        this.localNotes = value.notes;
      },
      deep: true
    }
  },

  created() {
    this.localNotes = this.entry.notes;
  },

  methods: {
    showHoverOnNote(id) {
      if (!this.entry.happened) {
        this.actionsForNote = id;
      }
    },

    hideHoverOnNote(id) {
      this.actionsForNote = 0;
    },

    showEditNote(note) {
      this.noteToEdit = note.id;
      this.form.description = note.note;

      this.$nextTick(() => {
        this.$refs[`note${note.id}`].focus();
      });
    },

    displayAddNote() {
      this.addNoteMode = true;
      this.form.description = null;

      this.$nextTick(() => {
        this.$refs.newNoteItem.focus();
      });
    },

    store() {
      this.loadingState = 'loading';
      this.form.employee_id = this.entry.employee.id;
      this.form.manager_id = this.entry.manager.id;

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/notes', this.form)
        .then(response => {
          this.localNotes.push(response.data.data);
          this.addNoteMode = false;
          this.form.description = null;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(itemId) {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/notes/' + itemId, this.form)
        .then(response => {
          this.noteToEdit = 0;
          this.loadingState = null;
          this.form.description = null;

          this.localNotes[this.localNotes.findIndex(x => x.id === itemId)] = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroy(id) {
      axios.delete('/' + this.$page.props.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/notes/' + id)
        .then(response => {
          this.flash(this.$t('dashboard.one_on_ones_note_deletion_success'), 'success');
          id = this.localNotes.findIndex(x => x.id === id);
          this.localNotes.splice(id, 1);
          this.noteToDelete = 0;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  },
};

</script>
