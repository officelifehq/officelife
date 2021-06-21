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
  <div :class="question ? 'mb5' : ''">
    <template v-if="localQuestion">
      <div class="cf mw7 center mb2 fw5">
        <span class="mr1">
          🎓
        </span> {{ $t('dashboard.question_title') }}

        <help :url="$page.props.help_links.questions" />
      </div>

      <!-- employee hasnt already answered -->
      <template v-if="!hasAlreadyAnswered">
        <div class="cf mw7 center br3 mb3 bg-white box relative" data-cy="answer-employee-hasnt-answered">
          <img loading="lazy" src="/img/dashboard/question_dashboard.png" alt="a group taking a selfie" class="absolute top-1" />

          <div class="pa3">
            <h2 class="f4 fw4 mt0 mb3 ml6 lh-copy">
              {{ question.title }}
            </h2>

            <!-- CTA to add an answer -->
            <p v-if="!addMode">
              <a class="btn dib ml6 mr2" data-cy="log-answer-cta" @click.prevent="showEditor()">{{ $t('dashboard.question_cta') }}</a>
              <span class="f6 silver di-l db mt4 mt0-l">{{ $tc('dashboard.question_number_of_answers', localQuestion.number_of_answers, { number: localQuestion.number_of_answers }) }}</span>
            </p>

            <!-- Add the answer form -->
            <div v-show="addMode" class="ml6">
              <form @submit.prevent="submit()">
                <errors :errors="form.errors" />

                <text-area
                  ref="editor"
                  v-model="form.body"
                  :datacy="'answer-content'"
                  @esc-key-pressed="addMode = false"
                />
                <p class="db lh-copy f6">
                  👋 {{ $t('dashboard.question_answer_help') }}
                </p>
                <p class="ma0">
                  <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-answer'" />
                  <a class="pointer" @click.prevent="addMode = false">
                    {{ $t('app.cancel') }}
                  </a>
                </p>
              </form>
            </div>
          </div>
        </div>
      </template>

      <!-- employee has already answered -->
      <template v-else>
        <div class="cf mw7 center br3 mb3 bg-white box relative">
          <div class="pa3">
            <p class="f5 fw6 mt0 mb1 lh-copy">{{ question.title }}</p>
            <p class="f6 silver mb3 mt0">{{ $tc('dashboard.question_number_of_answers', localQuestion.number_of_answers, { number: localQuestion.number_of_answers }) }}</p>

            <div v-for="answer in answers" :key="answer.id" class="bb-gray relative answer-entry" :data-cy="'answer-content-' + answer.id">
              <!-- avatar -->
              <small-name-and-avatar
                :name="answer.employee.name"
                :avatar="answer.employee.avatar"
              />

              <!-- actions (only for the employee) -->
              <span v-if="employee.id == answer.employee.id && idToUpdate != answer.id && !editMode" class="absolute top-0 right-0">
                <ul class="f6 list pl0 di">
                  <!-- edit -->
                  <li class="di pr2">
                    <a href="#" class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'answer-edit-link-' + answer.id" @click.prevent="showEditModal(answer)">{{ $t('app.edit') }}</a>
                  </li>

                  <!-- delete -->
                  <li v-if="idToDelete != answer.id" class="di">
                    <a href="#" class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'answer-destroy-' + answer.id" @click.prevent="showDeletionModal(answer)">{{ $t('app.delete') }}</a>
                  </li>
                  <li v-if="idToDelete == answer.id" class="di pr2">
                    {{ $t('app.sure') }}
                    <a class="mr1 pointer c-delete" :data-cy="'answer-destroy-confirm-' + answer.id" @click.prevent="destroy(answer)">
                      {{ $t('app.yes') }}
                    </a>
                    <a class="pointer" :data-cy="'answer-destroy-cancel-' + answer.id" @click.prevent="hideDeletionModal()">
                      {{ $t('app.no') }}
                    </a>
                  </li>
                </ul>
              </span>

              <!-- content of the answer -->
              <div v-show="idToUpdate != answer.id" class="lh-copy content mt2 br3" v-html="answer.body">
              </div>

              <!-- edit form -->
              <div v-show="idToUpdate == answer.id && editMode" class="edit-content pa3">
                <form @submit.prevent="update(answer)">
                  <errors :errors="form.errors" />

                  <text-area
                    :ref="'name' + answer.id"
                    v-model="form.body"
                    :datacy="'answer-edit-content'"
                    @esc-key-pressed="hideEditModal()"
                  />
                  <p class="db lh-copy f6">
                    👋 {{ $t('dashboard.question_answer_help') }}
                  </p>
                  <p class="ma0">
                    <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.update')" :cypress-selector="'submit-edit-answer'" />
                    <a class="pointer" @click.prevent="hideEditModal()">
                      {{ $t('app.cancel') }}
                    </a>
                  </p>
                </form>
              </div>
            </div>
          </div>
          <div v-if="localQuestion.number_of_answers > 3" class="ph3 pv2 tc f6 bt bb-gray">
            <a data-cy="view-all-work-from-home" :href="question.url">{{ $t('dashboard.question_answer_link') }}</a>
          </div>
        </div>
      </template>
    </template>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import Help from '@/Shared/Help';

export default {
  components: {
    Errors,
    LoadingButton,
    TextArea,
    SmallNameAndAvatar,
    Help,
  },

  props: {
    question: {
      type: Object,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      localQuestion: null,
      hasAlreadyAnswered: false,
      addMode: false,
      editMode: false,
      deletionMode: false,
      idToUpdate: 0,
      idToDelete: 0,
      answers: Array,
      form: {
        id: 0,
        body: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  watch: {
    question: {
      handler(value) {
        this.answers = value.answers;
      },
      deep: true
    }
  },

  created: function() {
    if (this.question) {
      this.localQuestion = this.question;
      this.hasAlreadyAnswered = this.question.employee_has_answered;
      this.answers = this.question.answers;
      this.form.id = this.question.id;
    }
  },

  methods: {
    showEditor() {
      this.addMode = true;

      this.$nextTick(() => {
        this.$refs.editor.focus();
      });
    },

    showEditModal(answer) {
      this.editMode = true;
      this.form.body = answer.body;
      this.idToUpdate = answer.id;

      this.$nextTick(() => {
        this.$refs[`name${answer.id}`].focus();
      });
    },

    hideEditModal() {
      this.editMode = false;
      this.idToUpdate = 0;
    },

    showDeletionModal(answer) {
      this.deletionMode = true;
      this.idToDelete = answer.id;
    },

    hideDeletionModal() {
      this.deletionMode = false;
      this.idToDelete = 0;
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/dashboard/question`, this.form)
        .then(response => {
          this.loadingState = null;
          this.answers = response.data.data;
          this.localQuestion.number_of_answers++;
          this.hasAlreadyAnswered = true;
          this.editMode = false;
          this.flash(this.$t('dashboard.question_answer_submitted'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.editMode = true;
          this.form.errors = error.response.data;
        });
    },

    update(answer) {
      this.loadingState = 'loading';

      axios.put(`${this.$page.props.auth.company.id}/dashboard/question/${answer.id}`, this.form)
        .then(response => {
          this.loadingState = null;
          this.flash(this.$t('dashboard.question_answer_updated'), 'success');

          this.answers[this.answers.findIndex(x => x.id === answer.id)] = response.data.data;

          this.idToUpdate = 0;
          this.editMode = false;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroy(answer) {
      axios.delete(`${this.$page.props.auth.company.id}/dashboard/question/${answer.id}`)
        .then(response => {
          this.flash(this.$t('dashboard.question_answer_destroyed'), 'success');

          this.idToDelete = 0;
          var id = this.answers.findIndex(x => x.id == answer.id);
          this.answers.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
