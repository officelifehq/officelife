<style lang="scss" scoped>
.judge {
  width: 100px;
}

.rate-bad {
  background: linear-gradient(0deg, #ec89793d 0%, white 100%);
}

.rate-good {
  background: linear-gradient(0deg, #b1ecb23d 0%, white 100%);
}
</style>

<template>
  <div>
    <div v-for="answer in rateYourManagerAnswers" :key="answer.id" class="mb5" data-cy="rate-your-manager-survey">
      <div class="cf mw7 center mb2 fw5 relative">
        <span class="mr1">
          👨‍⚖️
        </span> {{ $t('dashboard.rate_your_manager_title') }}

        <help :url="$page.props.help_links.manager_rate_manager" />

        <span class="absolute right-0 fw3 f6">
          <span class="mr1">
            ⏳
          </span> {{ answer.deadline }}
        </span>
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box relative">
        <img loading="lazy" src="/img/streamline-icon-judge-1-4@140x140.png" alt="judge" class="judge absolute-ns di-ns dn top-1 left-1" />

        <div class="pa3">
          <!-- Emotion panel -->
          <div v-if="!alreadyAnswered" class="ml6-ns ml0">
            <h2 class="f4 fw4 mt0 mb2 lh-copy">
              {{ $t('dashboard.rate_your_manager_subtitle', { name: answer.manager_name}) }}
            </h2>

            <p class="mt0 mb3 lh-copy gray f6">{{ $t('dashboard.rate_your_manager_anonymous') }}</p>

            <div class="flex-ns justify-around mt3 mb3">
              <span class="btn mr3-ns mb0-ns mb2 dib-l db rate-bad" data-cy="log-rate-bad" @click.prevent="submit(answer, 'bad')">
                <span class="mr1">
                  😨
                </span> {{ $t('app.rate_manager_bad') }}
              </span>
              <span class="btn mr3-ns mb0-ns mb2 dib-l db" data-cy="log-rate-normal" @click.prevent="submit(answer, 'average')">
                <span class="mr1">
                  🙂
                </span> {{ $t('app.rate_manager_average') }}
              </span>
              <span class="btn dib-l db mb0-ns rate-good" data-cy="log-rate-good" @click.prevent="submit(answer, 'good')">
                <span class="mr1">
                  🤩
                </span> {{ $t('app.rate_manager_good') }}
              </span>
            </div>
          </div>

          <!-- care to add a comment modal -->
          <div v-if="answerMode && !showFinalSucessMessage" class="ml6">
            <h2 class="f4 fw4 mt0 mb2 lh-copy">
              {{ $t('dashboard.rate_your_manager_thanks_add_comment') }}
            </h2>

            <p class="mt0 mb3 lh-copy gray f6">{{ $t('dashboard.rate_your_manager_thanks_add_comment_desc') }}</p>

            <a v-if="!commentMode" href="#" class="btn dib" data-cy="add-comment" @click.prevent="showAnswerPanel(answer)">{{ $t('dashboard.rate_your_manager_thanks_add_comment_cta') }}</a>
          </div>

          <!-- actually add a comment -->
          <form v-if="commentMode" class="ml6" @submit.prevent="submitComment(answer)">
            <errors :errors="form.errors" />

            <text-area
              :ref="'editor-' + answer.id"
              v-model="form.comment"
              :required="true"
              :datacy="'answer-content'"
              @esc-key-pressed="commentMode = false"
            />

            <checkbox
              :id="'home'"
              v-model="form.reveal"
              :datacy="'answer-not-anonymous'"
              :label="$t('dashboard.rate_your_manager_thanks_add_comment_reveal_identity')"
              :extra-class-upper-div="'mb0 relative'"
              :required="false"
            />

            <!-- actions -->
            <p class="ma0">
              <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.submit')" :cypress-selector="'submit-answer'" />
              <a class="pointer" @click.prevent="commentMode = false">
                {{ $t('app.cancel') }}
              </a>
            </p>
          </form>

          <!-- final success message -->
          <div v-if="showFinalSucessMessage" class="ml6 tc">
            <h2 class="f3 fw4 mt3 mb2 lh-copy">
              🤝
            </h2>

            <p class="mt0 mb3 lh-copy f5">{{ $t('dashboard.rate_your_manager_final_sucess_message') }}.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';
import Checkbox from '@/Shared/Checkbox';
import Help from '@/Shared/Help';

export default {
  components: {
    Errors,
    LoadingButton,
    TextArea,
    Checkbox,
    Help,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    rateYourManagerAnswers: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      answerMode: false,
      commentMode: false,
      alreadyAnswered: false,
      showFinalSucessMessage: false,
      form: {
        rating: 0,
        reveal: false,
        comment: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  methods: {
    submit(answer, rating) {
      this.loadingState = 'loading';
      this.form.rating = rating;

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/rate/' + answer.id, this.form)
        .then(response => {
          this.loadingState = null;
          this.alreadyAnswered = true;
          this.answerMode = true;
          this.flash(this.$t('dashboard.rate_your_manager_submitted'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    submitComment(answer) {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/rate/' + answer.id + '/comment', this.form)
        .then(response => {
          this.loadingState = null;
          this.alreadyAnswered = true;
          this.answerMode = true;
          this.commentMode = false;
          this.showFinalSucessMessage = true;
          this.flash(this.$t('dashboard.rate_your_manager_submitted'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    showAnswerPanel(answer) {
      this.commentMode = true;

      this.$nextTick(() => {
        this.$refs[`editor-${answer.id}`].focus();
      });
    },
  }
};
</script>
