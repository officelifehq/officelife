<template>
  <div class="di relative">
    <span v-if="employee.phone" data-cy="employee-phone">
      {{ employee.phone }}
    </span>
    <span v-else>
      {{ $t('employee.phone_undefined') }}
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
