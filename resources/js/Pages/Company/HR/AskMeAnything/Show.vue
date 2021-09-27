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

.dot {
  height: 1px;
  top: 1px;
}

.status-active {
  top: -4px;
  background-color: #dcf7ee;

  .dot {
    background-color: #00b760;
  }
}
.status-inactive {
  top: -4px;
  background-color: #ffe9e3;

  .dot {
    background-color: #ff3400;
  }
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
          <h2 class="tc normal mb4 lh-copy relative mt0">
            {{ $t('company.hr_ama_show_title', {date: data.happened_at}) }}

            <span v-if="activeStatus" class="status-active relative dib pa1 br3 f7 ml2 mr2">
              <span class="br3 f7 fw3 ph1 pv1 dib relative dot"></span>
              {{ $t('company.hr_ama_active') }}
            </span>
            <span v-else class="status-inactive relative dib pa1 br3 f7 ml2 mr2">
              <span class="br3 f7 fw3 ph1 pv1 dib relative dot"></span>
              {{ $t('company.hr_ama_inactive') }}
            </span>

            <help :url="$page.props.help_links.ask_me_anything" :top="'1px'" />
          </h2>

          <!-- session theme -->
          <p v-if="data.theme" class="mb3 tc mv0">{{ data.theme }}</p>
        </div>

        <!-- actions -->
        <div v-if="data.permissions.can_edit" class="actions pa3 box flex justify-center mb4">
          <a v-if="activeStatus" class="btn mr3" @click="toggle()">{{ $t('company.hr_ama_deactivate') }}</a>
          <a v-else class="btn mr3" @click="toggle()">{{ $t('company.hr_ama_activate') }}</a>
          <inertia-link :href="data.url.edit" class="btn dib mr3">{{ $t('app.edit') }}</inertia-link>
          <inertia-link :href="data.url.delete" class="btn destroy dib mr3">{{ $t('app.delete') }}</inertia-link>
        </div>

        <!-- tabs -->
        <div class="cf mw7 center br3 mb5 tc">
          <div class="cf dib btn-group">
            <inertia-link :href="data.url.unanswered_tab" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{ selected: tab === 'unanswered' }">
              {{ $t('company.hr_ama_show_questions_tab_unanswered') }}
            </inertia-link>
            <inertia-link :href="data.url.answered_tab" class="f6 fl ph3 pv2 dib pointer" :class="{ selected: tab === 'answered' }">
              {{ $t('company.hr_ama_show_questions_tab_answered') }}
            </inertia-link>
          </div>
        </div>

        <!-- questions -->
        <div v-if="localQuestions.length > 0" class="ma0 bg-white box">
          <div v-for="question in localQuestions" :key="question.id" class="question-item bb bb-gray bb-gray-hover">
            <div v-if="!question.answered" class="flex items-center justify-between pa3">
              <div>
                <span class="db f4 lh-copy measure">
                  {{ question.question }}
                </span>

                <div v-if="question.author" class="mt2">
                  <small-name-and-avatar
                    :name="question.author.name"
                    :avatar="question.author.avatar"
                    :class="'gray mt2'"
                    :size="'18px'"
                    :top="'0px'"
                    :margin-between-name-avatar="'25px'"
                  />
                </div>
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
      type: String,
      default: null,
    },
  },

  data() {
    return {
      activeStatus: false,
      localQuestions: [],
      loadingState: '',
      loadingToggleState: '',
      errorTemplate: Error,
    };
  },

  mounted() {
    this.localQuestions = this.data.questions;
    this.activeStatus = this.data.active;

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

    toggle() {
      this.loadingToggleState = 'loading';

      axios.put(this.data.url.toggle, this.form)
        .then(response => {
          this.loadingToggleState = null;
          this.activeStatus = !this.activeStatus;
        })
        .catch(error => {
          this.loadingToggleState = null;
        });
    },
  }
};

</script>
