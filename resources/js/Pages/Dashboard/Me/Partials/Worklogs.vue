<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5">
      <span class="mr1">
        🔨
      </span> {{ $t('dashboard.worklog_title') }}

      <help :url="$page.props.help_links.worklogs" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box pa3">
      <div v-if="!editMode && !displaySuccessMessage">
        <!-- employee hasn't logged yet -->
        <p v-if="!localWorklogs.has_already_logged_a_worklog_today" class="db mt0">
          <span class="dib-ns db mb0-ns mb2 lh-copy">
            {{ $t('dashboard.worklog_placeholder') }}
          </span>
        </p>

        <!-- button to log the worklog -->
        <p v-if="!localWorklogs.has_already_logged_a_worklog_today" class="ma0">
          <a class="btn dib" data-cy="log-worklog-cta" @click.prevent="showEditor">
            {{ $t('dashboard.worklog_cta') }}
          </a>
        </p>

        <!-- employee has already logged -->
        <p v-if="localWorklogs.has_already_logged_a_worklog_today" class="db mb0 mt0">
          <span class="dib-ns db mb0-ns mb2">
            {{ $t('dashboard.worklog_already_logged') }}
          </span>
        </p>
      </div>

      <!-- Shows the editor -->
      <div v-if="editMode">
        <form @submit.prevent="store()">
          <errors :errors="form.errors" :class="'mb2'" />

          <text-area
            ref="editor"
            v-model="form.content"
            :datacy="'worklog-content'"
            @esc-key-pressed="editMode = false"
          />
          <p class="db lh-copy f6">
            👋 {{ $t('dashboard.worklog_entry_description') }}
          </p>
          <p class="ma0">
            <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-log-worklog'" />
            <a class="pointer" @click.prevent="editMode = false">
              {{ $t('app.cancel') }}
            </a>
          </p>
        </form>
      </div>

      <!-- employee just logged the worklog, we display the success message -->
      <p v-if="displaySuccessMessage" class="db mb3 mt4 tc">
        {{ $t('dashboard.worklog_added') }}
      </p>
    </div>
  </div>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';
import Errors from '@/Shared/Errors';
import Help from '@/Shared/Help';

export default {
  components: {
    LoadingButton,
    Errors,
    TextArea,
    Help,
  },

  props: {
    worklogs: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      editMode: false,
      form: {
        content: null,
        errors: [],
      },
      localWorklogs: null,
      loadingState: '',
      displaySuccessMessage: false,
    };
  },

  created: function() {
    this.localWorklogs = this.worklogs;
  },

  methods: {
    updateText(text) {
      this.form.content = text;
    },

    showEditor() {
      this.editMode = true;

      this.$nextTick(() => {
        this.$refs.editor.focus();
      });
    },

    store() {
      this.loadingState = 'loading';
      this.editMode = false;

      axios.post(`${this.$page.props.auth.company.id}/dashboard/worklog`, this.form)
        .then(response => {
          this.flash(this.$t('dashboard.worklog_success_message'), 'success');

          this.loadingState = null;
          this.displaySuccessMessage = true;
          this.localWorklogs.has_already_logged_a_worklog_today = true;
          this.localWorklogs.has_worklog_history = true;
        })
        .catch(error => {
          this.loadingState = null;
          this.displaySuccessMessage = false;
          this.editMode = true;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
