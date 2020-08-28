<template>
  <div class="di relative">
    <span v-if="employee.slack_handle" data-cy="employee-slack-handle">
      {{ employee.slack_handle }}
    </span>
    <span v-else>
      {{ $t('employee.slack_undefined') }}
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
