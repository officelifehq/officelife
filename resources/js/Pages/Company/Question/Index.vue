<style style="scss" scoped>

</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $page.auth.company.name }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_company_questions') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <!-- WHEN THERE ARE QUESTIONS -->
        <div class="pa3 mt3">
          <h2 class="tc normal mb4">
            {{ $t('company.questions_title') }}
          </h2>

          <!-- LIST OF QUESTIONS -->
          <ul v-show="questions.length != 0" class="list pl0 mt0 center">
            <li
              v-for="question in questions" :key="question.id"
              class="pa3-l pa1 ph0-l bb b--black-10 question-item"
            >
              <!-- normal case (ie not rename mode) -->
              <inertia-link :href="question.url" class="mb2" :data-cy="'list-question-' + question.id">
                {{ question.title }}
              </inertia-link>
              <span class="ml2 f6 grey">{{ $tc('company.question_number_of_answers', question.number_of_answers, { number: question.number_of_answers }) }}</span>
            </li>
          </ul>
        </div>

        <!-- NO questions -->
        <div v-show="questions.length == 0" class="pa3">
          <p class="tc measure center mb4 lh-copy" data-cy="questions-blank-message">
            {{ $t('account.questions_blank') }}
          </p>
          <img class="db center mb4" alt="team" srcset="/img/company/account/blank-team-1x.png,
                                        /img/company/account/blank-team-2x.png 2x"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
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
