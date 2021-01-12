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
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        🎓
      </span> {{ $t('employee.question_title') }}
    </span>

    <div class="br3 bg-white box z-1">
      <ul v-if="questions.length > 0" class="list pl0 ma0">
        <li v-for="question in questions" :key="question.id" class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between question-item" :data-cy="'question-title-' + question.id">
          <div class="">
            <div class="mb1">
              <span class="mr1 fw5">Q:</span> {{ question.title }}
            </div>
            <div class="lh-copy gray" :data-cy="'answer-body-' + question.id">{{ question.answer.body }}</div>
          </div>

          <!-- view link -->
          <div>
            <inertia-link :href="question.url" class="ma0 pa0 f6" :data-cy="'entry-item-' + question.id">{{ $t('app.view') }}</inertia-link>
          </div>
        </li>
      </ul>

      <!-- blank state -->
      <p v-if="questions.length == 0" class="pa3 mb0 mt0 lh-copy f6" data-cy="question-blank-state">{{ $t('employee.question_blank') }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    questions: {
      type: Array,
      default: null,
    },
  },
};
</script>
