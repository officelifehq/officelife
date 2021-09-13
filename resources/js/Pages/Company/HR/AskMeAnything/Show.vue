<style lang="scss" scoped>
.question-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.question-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}

.actions {
  background-color: #eef3f9;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/hr/ask-me-anything'"
                  :previous="$t('app.breadcrumb_ama_list')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_ama_show') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 relative z-1">
        <!-- title -->
        <div class="bg-white box pa3 center mb4">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('company.hr_ama_show_title', {date: data.happened_at}) }}

            <help :url="$page.props.help_links.wiki" :top="'1px'" />
          </h2>

          <!-- session theme -->
          <p v-if="data.theme" class="mb3 tc mv0">{{ data.theme }}</p>
        </div>

        <!-- actions -->
        <div v-if="data.permissions.can_edit" class="actions pa3 box flex justify-center mb4">
          <inertia-link :href="data.url.edit" class="btn dib mr3">{{ $t('app.edit') }}</inertia-link>
          <a class="btn" @click="''">{{ $t('company.hr_ama_actions_shuffle') }}</a>
        </div>

        <!-- tabs -->
        <div class="cf mw7 center br3 mb5 tc">
          <div class="cf dib btn-group">
            <inertia-link :href="data.url.unanswered_tab" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(tab == 'unanswered')}">
              {{ $t('company.hr_ama_show_questions_tab_unanswered') }}
            </inertia-link>
            <inertia-link :href="data.url.answered_tab" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(tab == 'answered')}">
              {{ $t('company.hr_ama_show_questions_tab_answered') }}
            </inertia-link>
          </div>
        </div>

        <!-- questions -->
        <div v-if="localQuestions.length > 0" class="ma0 bg-white box">
          <div v-for="question in localQuestions" :key="question.id" class="question-item bb bb-gray bb-gray-hover">
            <div v-if="!question.answered" class="flex items-center justify-between pa3">
              <div>
                <span class="db f4 mb2">
                  {{ question.question }}
                </span>

                <small-name-and-avatar
                  v-if="question.author"
                  :name="question.author.name"
                  :avatar="question.author.avatar"
                  :class="'gray'"
                  :size="'18px'"
                  :top="'0px'"
                  :margin-between-name-avatar="'25px'"
                />
              </div>

              <loading-button v-if="data.permissions.can_mark_answered" :class="'btn w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('company.hr_ama_show_questions_mark_answered')" @click="markAnswered(question)" />
            </div>
          </div>
        </div>

        <!-- blank state -->
        <div v-else class="list pl0 ma0 bg-white box tc">
          <p class="mb4 lh-copy">
            {{ $t('company.hr_ama_show_questions_blank') }}
          </p>
          <img loading="lazy" src="/img/streamline-icon-singer-record-14@400x400.png" alt="mic symbol" class="mr2" height="140"
               width="140"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    LoadingButton,
    Help,
    SmallNameAndAvatar,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    data: {
      type: Object,
      default: null,
    },
    tab: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      localQuestions: [],
      loadingState: '',
      errorTemplate: Error,
    };
  },

  mounted() {
    this.localQuestions = this.data.questions;

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    markAnswered(question) {
      this.loadingState = 'loading';

      axios.put(question.url_toggle, this.form)
        .then(response => {
          localStorage.success = this.$t('company.hr_ama_new_success');
          var id = this.localQuestions.findIndex(x => x.id === question.id);
          this.localQuestions.splice(id, 1);
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
