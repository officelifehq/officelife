<style lang="scss" scoped>
.answer-item:last-child {
  margin-bottom: 0;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      🎓 {{ $t('employee.question_title') }}
    </span>

    <div class="br3 bg-white box z-1 pa3">
      <ul v-if="questions.length > 0" class="list pl0 ma0">
        <li v-for="question in questions" :key="question.id" class="answer-item mb3" :data-cy="'question-title-' + question.id">
          <inertia-link :href="question.url" class="fw5 f5 dib mb2">{{ question.title }}</inertia-link>
          <div class="lh-copy gray" :data-cy="'answer-body-' + question.id">{{ question.answer.body }}</div>
        </li>
      </ul>

      <!-- blank state -->
      <p v-if="questions.length == 0" class="mb0 mt0 lh-copy f6" data-cy="question-blank-state">{{ $t('employee.question_blank') }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    employee: {
      type: Object,
      default: null,
    },
    questions: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
    };
  },

  methods: {
  }
};
</script>
