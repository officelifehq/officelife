<style lang="scss" scoped>
.question-item:last-child {
    border-bottom: 0;
    padding-bottom: 0;
  }
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/company'"
                  :previous="$t('app.breadcrumb_company')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_company_questions') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <!-- WHEN THERE ARE QUESTIONS -->
        <div class="pa3 mt3">
          <h2 class="tc normal mb4">
            {{ $t('company.questions_title') }}

            <help :url="$page.props.help_links.questions" :top="'1px'" />
          </h2>

          <!-- LIST OF QUESTIONS -->
          <ul v-if="questions && questions.length != 0" class="list pl0 mt0 center">
            <li v-for="question in questions" :key="question.id"
                class="pa3-l pa1 ph0-l bb b--black-10 question-item"
            >
              <!-- normal case (ie not rename mode) -->
              <inertia-link :href="question.url" class="mb2-ns mb2 di-ns dib mt0-ns mt2" :data-cy="'list-question-' + question.id">{{ question.title }}</inertia-link>
              <span class="ml2-ns ml0 f6 grey di-ns db mb2 mb0-ns">{{ $tc('company.question_number_of_answers', question.number_of_answers, { number: question.number_of_answers }) }}</span>
            </li>
          </ul>

          <!-- NO questions -->
          <div v-else class="pa3">
            <p class="tc measure center mb4 lh-copy" data-cy="questions-blank-message">
              {{ $t('account.questions_blank') }}
            </p>
            <img loading="lazy" class="db center mb4" alt="team" src="/img/streamline-icon-work-desk-sofa-3-1@140x140.png" />
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
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
};

</script>
