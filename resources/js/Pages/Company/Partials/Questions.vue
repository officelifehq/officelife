<style lang="scss" scoped>
.answer-item:last-child {
  margin-bottom: 0;
}

.past-questions {
  background-color: #eef3f9;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        🎓
      </span> {{ $t('employee.question_title') }}

      <help :url="$page.props.help_links.questions" />
    </span>

    <div class="br3 bg-white box z-1">
      <!-- active question, if defined -->
      <div v-if="questions.active_question" class="pa3 bb bb-gray">
        <p class="f7 gray ma0">{{ $t('company.questions_active') }}</p>
        <inertia-link :href="questions.active_question.url" class="dib ma0 mt2 fw6">{{ questions.active_question.title }}</inertia-link>
      </div>


      <!-- list of inactive questions -->
      <ul v-if="questions.total_number_of_questions > 0" class="list pa3 ma0 past-questions">
        <li v-for="question in questions.questions" :key="question.id" class="answer-item mb3" :data-cy="'question-title-' + question.id">
          <inertia-link :href="question.url" class="fw5 f5 lh-copy mb2">{{ question.title }}</inertia-link> <span class="gray f7">({{ question.number_of_answers }})</span>
        </li>
      </ul>

      <!-- Link to view all questions -->
      <div v-if="questions.total_number_of_questions > 0" class="ph3 pv2 tc f6 bt bb-gray">
        <inertia-link :href="questions.all_questions_url">{{ $t('company.questions_view_all', { count: questions.total_number_of_questions }) }}</inertia-link>
      </div>

      <!-- blank state -->
      <p v-if="questions.total_number_of_questions == 0" class="mb0 mt0 lh-copy f6 pa3 tc" data-cy="question-blank-state">{{ $t('company.questions_blank') }}</p>
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
    questions: {
      type: Object,
      default: null,
    },
  },
};
</script>
