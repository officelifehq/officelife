<style lang="scss" scoped>
.content {
  background-color: #f3f9fc;
  padding: 4px 10px;
}

.answer-entry {
  &:not(:first-child) {
    margin-top: 25px;
  }
}
</style>

<template>
  <div :class="question ? 'mb5' : ''">
    <template v-if="question">
      <div class="cf mw7 center mb2 fw5">
        🎓 {{ $t('dashboard.question_title') }}
      </div>

      <!-- employee hasnt already answered -->
      <template v-if="!hasAlreadyAnswered">
        <div class="cf mw7 center br3 mb3 bg-white box relative">
          <img src="/img/dashboard/question_dashboard.png" alt="a group taking a selfie" class="absolute top-1" />

          <div class="pa3">
            <h2 class="f4 fw4 mt0 mb3 ml6 lh-copy">
              {{ question.title }}
            </h2>

            <!-- CTA to add an answer -->
            <p v-if="!editMode">
              <a class="btn dib ml6 mr2" data-cy="log-answer-cta" @click.prevent="showEditor()">{{ $t('dashboard.question_cta') }}</a>
              <span class="f6 silver di-l db mt4 mt0-l">{{ $tc('dashboard.question_number_of_answers', question.number_of_answers, { number: question.number_of_answers }) }}</span>
            </p>

            <!-- Add the answer form -->
            <div v-show="editMode" class="ml6">
              <form @submit.prevent="submit()">
                <errors :errors="form.errors" />

                <text-area
                  ref="editor"
                  v-model="form.body"
                  :datacy="'answer-content'"
                  @esc-key-pressed="editMode = false"
                />
                <p class="db lh-copy f6">
                  👋 {{ $t('dashboard.question_answer_help') }}
                </p>
                <p class="ma0">
                  <loading-button :classes="'btn add w-auto-ns w-100 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-answer'" />
                  <a class="pointer" @click.prevent="editMode = false">
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
            <p class="f6 silver mb3 mt0">{{ $tc('dashboard.question_number_of_answers', question.number_of_answers, { number: question.number_of_answers }) }}</p>

            <div v-for="answer in answers" :key="answer.id" class="bb-gray relative answer-entry">
              <!-- avatar -->
              <small-name-and-avatar
                :name="answer.employee.name"
                :avatar="answer.employee.avatar"
              />

              <!-- actions (only for the employee) -->
              <span v-if="employee.id == answer.employee.id" class="absolute top-0 right-0">
                <ul class="f6 list pl0 di">
                  <!-- edit -->
                  <li class="di pr2">
                    <a href="#" class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'answer-edit-link-' + answer.id" @click.prevent="showRenameModal(answer)">{{ $t('app.edit') }}</a>
                  </li>

                  <!-- delete -->
                  <li class="di">
                    <a href="#" class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'answer-destroy-link-' + answer.id" @click.prevent="showDeletionModal(answer)">{{ $t('app.delete') }}</a>
                  </li>
                  <li class="di pr2">
                    {{ $t('app.sure') }}
                    <a class="mr1 pointer" :data-cy="'answer-activate-link-confirm-' + answer.id" @click.prevent="activate(answer)">
                      {{ $t('app.yes') }}
                    </a>
                    <a class="pointer" :data-cy="'answer-activate-link-cancel-' + answer.id" @click.prevent="answerToActivate = 0">
                      {{ $t('app.no') }}
                    </a>
                  </li>
                </ul>
              </span>

              <!-- content of the answer -->
              <div class="lh-copy content mt2 br3" v-html="answer.body">
              </div>
            </div>
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

export default {
  components: {
    Errors,
    LoadingButton,
    TextArea,
    SmallNameAndAvatar,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      hasAlreadyAnswered: false,
      editMode: false,
      deletionMode: false,
      question: null,
      answers: Array,
      form: {
        id: 0,
        body: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created: function() {
    this.hasAlreadyAnswered = this.employee.question.employee_has_answered;
    this.question = this.employee.question;
    this.answers = this.employee.question.answers;
    this.form.id = this.question.id;
  },

  methods: {
    showEditor() {
      this.editMode = true;

      this.$nextTick(() => {
        this.$refs['editor'].$refs['input'].focus();
      });
    },

    showDeletionModal(answer) {
      this.deletionMode = false;
    },

    submit() {
      axios.post('/' + this.$page.auth.company.id + '/dashboard/question', this.form)
        .then(response => {
          this.answers = response.data.data;
          this.question.number_of_answers++;
          this.hasAlreadyAnswered = true;
          this.editMode = false;
          flash(this.$t('dashboard.question_answer_submitted'), 'success');
        })
        .catch(error => {
          this.editMode = true;
          this.form.errors = error.response.data.errors;
        });
    },
  }
};
</script>
