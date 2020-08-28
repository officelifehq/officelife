<template>
  <div class="di relative">
    <span v-if="employee.twitter_handle" data-cy="employee-twitter-handle">
      {{ employee.twitter_handle }}
    </span>
    <span v-else>
      {{ $t('employee.twitter_undefined') }}
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
