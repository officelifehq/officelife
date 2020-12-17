<template>
  <div class="di relative">
    <span data-cy="employee-contract-renewal-date">
      {{ employee.contract_renewed_at.date }}
    </span>
  </div>
</template>

<script>
export default {
  props: {
    employee: {
      type: Object,
      default: null,
    },
  },
};

</script>
