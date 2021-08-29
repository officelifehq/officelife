<style lang="scss" scoped>
  .question-item:last-child {
    border-bottom: 0;
    padding-bottom: 0;
  }

  .question-badge-inactive {
    background-color: #E2E4E8;
  }

  .question-badge-active {
    background-color: #52CF6E;
    color: #fff;
  }
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account'"
                  :previous="$t('app.breadcrumb_account_home')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_account_manage_questions') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <!-- WHEN THERE ARE QUESTIONS -->
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.questions_title', { company: $page.props.auth.company.name}) }}

            <help :url="$page.props.help_links.questions" :top="'1px'" />
          </h2>

          <!-- add a question -->
          <p class="relative adminland-headline mb0">
            <span class="db mb3" :class="localQuestions.length == 0 ? 'white' : ''">
              {{ $tc('account.questions_number_questions', localQuestions.length, { company: $page.props.auth.company.name, count: localQuestions.length}) }}
            </span>
            <span v-if="localQuestions.length > 0" class="dib mb3 f6 gray lh-copy">{{ $t('account.questions_description') }}</span>
            <a v-if="!modal" data-cy="add-question-button" class="btn tc absolute-l relative dib-l db right-0" @click.prevent="showAddModal">
              {{ $t('account.questions_cta') }}
            </a>
          </p>

          <!-- MODAL TO ADD A QUESTION -->
          <form v-show="modal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf">
              <div class="fl w-100 w-70-ns mb3 mb0-ns">
                <text-input :ref="'newQuestionModal'"
                            v-model="form.title"
                            :placeholder="$t('account.questions_form_title_placeholder')"
                            :datacy="'add-title-input'"
                            :errors="$page.props.errors.first_name"
                            :extra-class-upper-div="'mb0'"
                            @esc-key-pressed="modal = false"
                />
              </div>
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns" @click.prevent="modal = false">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" data-cy="modal-add-cta" :state="loadingState" :text="$t('app.add')" />
              </div>
            </div>
          </form>

          <!-- LIST OF QUESTIONS -->
          <ul v-show="localQuestions.length != 0" class="list pl0 mt0 center">
            <li
              v-for="question in localQuestions" :key="question.id"
              class="pa3-l pa1 ph0-l bb b--black-10 question-item"
            >
              <!-- normal case (ie not rename mode) -->
              <template v-if="questionToRename.id != question.id && questionToDelete.id != question.id">
                <span class="db b mb2" :data-cy="'list-question-' + question.id">
                  {{ question.title }}
                </span>

                <!-- list of actions -->
                <ul class="f6 list pl0 mb2 mb0-ns">
                  <!-- status -->
                  <li v-if="question.active" class="dib mr2 pb2 pb0-ns" :data-cy="'question-status-active-' + question.id">
                    <div class="br3 question-badge-active f7 ph2 di">{{ $t('account.question_status_active') }}</div>
                  </li>
                  <li v-else class="dib mr2 pb2 pb0-ns" :data-cy="'question-status-inactive-' + question.id">
                    <div class="br3 question-badge-inactive f7 ph2 di">{{ $t('account.question_status_inactive') }}</div>
                  </li>

                  <!-- confirm activation -->
                  <li v-if="questionToActivate.id == question.id" class="di pr2 pb2 pb0-ns">
                    {{ $t('app.sure') }}
                    <a class="mr1 pointer" :data-cy="'question-activate-link-confirm-' + question.id" @click.prevent="activate(question)">
                      {{ $t('app.yes') }}
                    </a>
                    <a class="pointer" :data-cy="'question-activate-link-cancel-' + question.id" @click.prevent="questionToActivate = 0">
                      {{ $t('app.no') }}
                    </a>
                  </li>
                  <!-- confirm deactivation -->
                  <li v-if="questionToDeactivate.id == question.id" class="di pr2 pb2 pb0-ns">
                    {{ $t('app.sure') }}
                    <a class="mr1 pointer" :data-cy="'question-deactivate-link-confirm-' + question.id" @click.prevent="deactivate(question)">
                      {{ $t('app.yes') }}
                    </a>
                    <a class="pointer" :data-cy="'question-deactivate-link-cancel-' + question.id" @click.prevent="questionToDeactivate = 0">
                      {{ $t('app.no') }}
                    </a>
                  </li>
                  <li v-if="questionToActivate.id != question.id && !question.active" class="di pr2 pb2 pb0-ns">
                    <a class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'question-activate-link-' + question.id" @click.prevent="questionToActivate = question">{{ $t('account.question_activate') }}</a>
                  </li>
                  <li v-if="questionToDeactivate.id != question.id && question.active" class="di pr2 pb2 pb0-ns">
                    <a class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'question-deactivate-link-' + question.id" @click.prevent="questionToDeactivate = question">{{ $t('account.question_deactivate') }}</a>
                  </li>

                  <!-- number of answers -->
                  <li :data-cy="'question-number-of-answers-' + question.id" class="di pr2 pb2 pb0-ns">
                    <inertia-link :href="question.url">{{ $tc('account.question_number_of_answers', question.number_of_answers, { count: question.number_of_answers}) }}</inertia-link>
                  </li>

                  <!-- rename -->
                  <li class="di pr2 pb2 pb0-ns">
                    <a href="#" class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'question-rename-link-' + question.id" @click.prevent="showRenameModal(question)">{{ $t('app.rename') }}</a>
                  </li>

                  <!-- delete -->
                  <li class="di pb2 pb0-ns">
                    <a href="#" class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'question-destroy-link-' + question.id" @click.prevent="showDeletionModal(question)">{{ $t('app.delete') }}</a>
                  </li>
                </ul>
              </template>

              <!-- rename modal -->
              <template v-if="questionToRename.id == question.id && renameMode">
                <template v-if="form.errors.length > 0">
                  <div class="cf pb1 w-100 mb2">
                    <errors :errors="form.errors" />
                  </div>
                </template>

                <!-- form -->
                <form class="flex" @submit.prevent="update(question)">
                  <div class="w-100 w-70-ns mb3 mb0-ns">
                    <text-input :id="'name-' + question.id"
                                :ref="'name' + question.id"
                                v-model="form.title"
                                :custom-ref="'name' + question.id"
                                :datacy="'list-rename-input-name-' + question.id"
                                :errors="$page.props.errors.name"
                                required
                                :extra-class-upper-div="'mb0'"
                                @esc-key-pressed="questionToRename = 0"
                    />
                  </div>

                  <!-- actions -->
                  <div class="w-30-ns w-100 tr">
                    <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" :data-cy="'list-rename-cancel-button-' + question.id" @click.prevent="questionToRename = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-rename-cta-button-' + question.id" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </template>

              <!-- deletion modal -->
              <template v-if="questionToDelete.id == question.id && deletionMode">
                <template v-if="form.errors.length > 0">
                  <div class="cf pb1 w-100 mb2">
                    <errors :errors="form.errors" />
                  </div>
                </template>

                <!-- form -->
                <form @submit.prevent="destroy(question)">
                  <p class="lh-copy">{{ $t('account.question_confirm_deletion') }}</p>
                  <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3 mr3" :data-cy="'list-destroy-cancel-button-' + question.id" @click.prevent="questionToDelete = 0">
                    {{ $t('app.cancel') }}
                  </a>
                  <loading-button :class="'btn destroy w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-destroy-cta-button-' + question.id" :state="loadingState" :text="$t('app.delete')" />
                </form>
              </template>
            </li>
          </ul>
        </div>

        <!-- NO questions -->
        <div v-show="localQuestions.length == 0" class="pa3">
          <p class="tc measure center mb4 lh-copy" data-cy="questions-blank-message">
            {{ $t('account.questions_blank') }}
          </p>
          <img loading="lazy" class="db center mb4" alt="team" src="/img/streamline-icon-customer-doubt-4@140x140.png" />
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
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    Errors,
    LoadingButton,
    Help,
  },

  props: {
    questions: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localQuestions: [],
      modal: false,
      renameMode: false,
      deletionMode: false,
      questionToRename: Object,
      questionToDelete: Object,
      questionToActivate: Object,
      questionToDeactivate: Object,
      form: {
        title: null,
        active: false,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  watch: {
    questions: {
      handler(value) {
        this.localQuestions = value;
      },
      deep: true
    }
  },

  mounted() {
    this.localQuestions = this.questions;
  },

  methods: {
    showRenameModal(question) {
      this.form.errors = [];
      this.renameMode = true;
      this.questionToRename = question;
      this.form.title = question.title;
      this.form.active = question.active;

      this.$nextTick(() => {
        this.$refs[`name${question.id}`].focus();
      });
    },

    showDeletionModal(question) {
      this.form.errors = [];
      this.deletionMode = true;
      this.questionToDelete = question;
    },

    showAddModal() {
      this.modal = !this.modal;
      this.form.errors = [];

      this.$nextTick(() => {
        this.$refs.newQuestionModal.focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('questions.store', this.$page.props.auth.company.id), this.form)
        .then(response => {
          this.flash(this.$t('account.question_creation_success'), 'success');

          this.loadingState = null;
          this.form.title = null;
          this.modal = false;
          this.localQuestions.unshift(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(question) {
      this.loadingState = 'loading';

      axios.put(this.route('questions.update', [this.$page.props.auth.company.id, question.id]), this.form)
        .then(response => {
          this.flash(this.$t('account.question_update_success'), 'success');

          this.questionToRename = 0;
          this.form.title = null;
          this.form.active = false;
          this.loadingState = null;

          this.localQuestions[this.localQuestions.findIndex(x => x.id === question.id)] = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroy(question) {
      axios.delete(this.route('questions.destroy', [this.$page.props.auth.company.id, question.id]))
        .then(response => {
          this.flash(this.$t('account.question_destroy_success'), 'success');

          this.questionToDelete = 0;
          var id = this.localQuestions.findIndex(x => x.id === question.id);
          this.localQuestions.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    activate(question) {
      axios.put(this.route('questions.activate', [this.$page.props.auth.company.id, question.id]))
        .then(response => {
          this.flash(this.$t('account.question_activate_success'), 'success');

          this.localQuestions = response.data.data;
          this.questionToActivate = 0;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    deactivate(question) {
      axios.put(this.route('questions.deactivate', [this.$page.props.auth.company.id, question.id]))
        .then(response => {
          this.flash(this.$t('account.question_deactivate_success'), 'success');

          this.localQuestions = response.data.data;
          this.questionToDeactivate = 0;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    }
  }
};

</script>
