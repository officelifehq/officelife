<template>
  <div class="di relative">
    {{ employee.email }}
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
