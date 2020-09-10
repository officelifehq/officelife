<style lang="scss" scoped>
.entry-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.entry-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}

.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
  }
}
</style>

<template>
  <div>
    <div class="mb5" data-cy="rate-your-manager-survey">
      <div class="cf mw7 center mb2 fw5 relative">
        <span class="mr1">
          🏔
        </span> {{ $t('dashboard.one_on_ones_title') }}

        <help :url="$page.help_links.manager_rate_manager" />
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box relative">
        <img loading="lazy" src="/img/streamline-icon-work-desk-sofa-1-3@140x140.png" width="90" alt="meeting" class="judge absolute top-1 left-1" />

        <ul class="pl6 pb3 pt3 pr3 ma0">
          <li v-for="manager in oneOnOnes" :key="manager.id" class="flex justify-between items-center br bl bb bb-gray bb-gray-hover pa3 entry-item">
            <!-- identity -->
            <div>
              <span class="pl3 db relative team-member">
                <img loading="lazy" :src="manager.avatar" alt="avatar" class="br-100 absolute avatar" />
                <inertia-link :href="manager.url" class="mb2">{{ manager.name }}</inertia-link>
                <span class="title db f7 mt1">
                  {{ manager.position }}
                </span>
              </span>
            </div>

            <!-- call to action -->
            <div class="tr">
              <inertia-link :href="manager.entry_url" class="btn dib-l db">{{ $t('dashboard.one_on_ones_cta') }}</inertia-link>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';

export default {
  components: {
    Help,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    oneOnOnes: {
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
        content: null,
        rating: 0,
        reveal: false,
        comment: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created: function() {
  },

  methods: {
    submit(answer, rating) {
      this.loadingState = 'loading';
      this.form.rating = rating;

      axios.post('/' + this.$page.auth.company.id + '/dashboard/manager/rate/' + answer.id, this.form)
        .then(response => {
          this.loadingState = null;
          this.alreadyAnswered = true;
          this.answerMode = true;
          flash(this.$t('dashboard.rate_your_manager_submitted'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data.errors;
        });
    },

    submitComment(answer) {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/dashboard/manager/rate/' + answer.id + '/comment', this.form)
        .then(response => {
          this.loadingState = null;
          this.alreadyAnswered = true;
          this.answerMode = true;
          this.commentMode = false;
          this.showFinalSucessMessage = true;
          flash(this.$t('dashboard.rate_your_manager_submitted'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data.errors;
        });
    },

    showAnswerPanel(answer) {
      this.commentMode = true;

      this.$nextTick(() => {
        this.$refs[`editor-${answer.id}`][0].$refs['input'].focus();
      });
    },

    toggleReveal() {
      this.form.reveal = !this.form.reveal;
    }
  }
};
</script>
