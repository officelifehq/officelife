<style lang="scss" scoped>
.answer-item:last-child {
  margin-bottom: 0;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      💵 {{ $t('employee.expense_title') }}
    </span>

    <div class="br3 bg-white box z-1 pa3">
      <ul v-if="expenses.length > 0" class="list pl0 ma0">
        <li v-for="expense in expenses" :key="expense.id" class="answer-item mb3" :data-cy="'expense-title-' + expense.id">
          <inertia-link :href="expense.url" class="fw5 f5 dib mb2">{{ expense.title }}</inertia-link>
          {{ expense.amount }}
          {{ expense.status }}
        </li>
      </ul>

      <!-- blank state -->
      <p v-if="expenses.length == 0" class="mb0 mt0 lh-copy f6" data-cy="expense-blank-state">{{ $t('employee.expense_blank') }}</p>
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
    expenses: {
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
