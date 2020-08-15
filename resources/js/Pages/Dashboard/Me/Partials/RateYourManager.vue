<style lang="scss" scoped>
.judge {
  width: 100px;
}

.content {
  background-color: #f3f9fc;
  padding: 4px 10px;
}

.edit-content {
  background-color: #f3f9fc;
}

.rate-bad {
  background: linear-gradient(0deg, #ec89793d 0%, white 100%);
}

.rate-good {
  background: linear-gradient(0deg, #b1ecb23d 0%, white 100%);
}
</style>

<template>
  <div class="mb5">
    <div v-for="survey in rateYourManagerSurveys" :key="survey.id">
      <div class="cf mw7 center mb2 fw5 relative">
        <span class="mr1">
          👨‍⚖️
        </span> Rate your manager
        <span class="absolute right-0 fw3 f6">
          <span class="mr1">
            ⏳
          </span> 2 days left
        </span>
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box relative">
        <img loading="lazy" src="/img/streamline-icon-judge-1-4@140x140.png" alt="judge" class="judge absolute top-1 left-1" />

        <div class="pa3">
          <h2 class="f4 fw4 mt0 mb2 ml6 lh-copy">
            It’s the end of the month. It’s time to tell us how it goes with your manager.
          </h2>

          <p class="mt0 mb3 ml6 lh-copy gray f6">Your opinion will be completely anonymous and will help Roger be a better manager.</p>

          <div v-if="!alreadyAnswered" class="ml6">
            <div class="flex-ns justify-around mt3 mb3">
              <span class="btn mr3-ns mb0-ns mb2 dib-l db rate-bad" data-cy="log-rate-bad" @click.prevent="store(1)">
                <span class="mr1">
                  😨
                </span> Not ideal
              </span>
              <span class="btn mr3-ns mb0-ns mb2 dib-l db" data-cy="log-rate-normal" @click.prevent="store(2)">
                <span class="mr1">
                  🙂
                </span> It’s going well
              </span>
              <span class="btn dib-l db mb0-ns rate-good" data-cy="log-rate-good" @click.prevent="store(3)">
                <span class="mr1">
                  🤩
                </span> Simply great
              </span>
            </div>
          </div>

          <form v-if="answerMode" @submit.prevent="submit()">
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
              <loading-button :classes="'btn add w-auto-ns w-100 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-answer'" />
              <a class="pointer" @click.prevent="answerMode = false">
                {{ $t('app.cancel') }}
              </a>
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
//import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Errors,
    LoadingButton,
    TextArea,
    //SmallNameAndAvatar,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    rateYourManagerSurveys: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      answerMode: false,
      alreadyAnswered: false,
      form: {
        id: 0,
        body: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created: function() {
  },

  methods: {
    showEditor() {
      this.addMode = true;

      this.$nextTick(() => {
        this.$refs['editor'].$refs['input'].focus();
      });
    },

    showEditModal(answer) {
      this.editMode = true;
      this.form.body = answer.body;
      this.idToUpdate = answer.id;

      this.$nextTick(() => {
        this.$refs[`name${answer.id}`][0].$refs['input'].focus();
      });
    },

    showAnswerPanel() {
      this.answerMode = true;
    }
  }
};
</script>
