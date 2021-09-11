<style lang="scss" scoped>
.content {
  background-color: #f3f9fc;
  padding: 4px 10px;
}

.edit-content {
  background-color: #f3f9fc;
}

.answer-entry {
  &:not(:first-child) {
    margin-top: 25px;
  }
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5">
      <span class="mr1">
        🎤
      </span> Upcoming Ask Me Anything session

      <help :url="$page.props.help_links.questions" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative">
      <img loading="lazy" src="/img/dashboard/question_dashboard.png" alt="a group taking a selfie" class="absolute top-1" />

      <div class="pa3">
        <h2 class="f4 fw4 mt0 mb3 ml6 lh-copy">
          The next Ask Me Anything session is scheduled for {{ session.happened_at }}.
        </h2>

        <!-- CTA to add an answer -->
        <p v-if="!addMode">
          <a class="btn dib ml6 mr2" data-cy="log-answer-cta" @click.prevent="showEditor()">{{ $t('dashboard.ask_me_anything_cta') }}</a>
          <span class="f6 silver di-l db mt4 mt0-l">{{ $tc('dashboard.ask_me_anything_total_questions', session.questions_asked_by_employee_count, { number: session.questions_asked_by_employee_count }) }}</span>
        </p>

        <!-- Add the answer form -->
        <div v-show="addMode" class="ml6">
          <form @submit.prevent="submit()">
            <errors :errors="form.errors" />

            <text-area
              ref="editor"
              v-model="form.question"
              @esc-key-pressed="addMode = false"
            />

            <checkbox
              v-model="form.anonymous"
              :label="$t('dashboard.ask_me_anything_anonymous')"
              :extra-class-upper-div="'mb2 relative'"
              :required="false"
              @update:model-value="updateAnonymous($event)"
            />

            <p class="ma0">
              <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-answer'" />
              <a class="pointer" @click.prevent="addMode = false">
                {{ $t('app.cancel') }}
              </a>
            </p>

            <p class="db lh-copy f6">
              <span class="mr1">👋</span> {{ $t('dashboard.ask_me_anything_new_help') }}
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';
import Help from '@/Shared/Help';
import Checkbox from '@/Shared/Checkbox';

export default {
  components: {
    Errors,
    LoadingButton,
    TextArea,
    Help,
    Checkbox,
  },

  props: {
    session: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      localQuestion: null,
      numberOfQuestions: 0,
      addMode: false,
      form: {
        anonymous: false,
        question: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created() {
    this.numberOfQuestions = this.session.questions_asked_by_employee_count;
  },

  methods: {
    showEditor() {
      this.addMode = true;

      this.$nextTick(() => {
        this.$refs.editor.focus();
      });
    },

    updateAnonymous(event) {
      if (event) {
        this.form.anonymous = true;
      } else {
        this.form.anonymous = false;
      }
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.session.url_new, this.form)
        .then(response => {
          this.flash(this.$t('dashboard.ask_me_anything_success'), 'success');
          this.addMode = false;
          this.loadingState = null;
          this.numberOfQuestions++;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
