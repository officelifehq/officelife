<template>
  <div v-if="Object.keys(errors).length > 0">
    <p>app.error_title</p>
    <br />
    <ul v-for="errorsList in errors" :key="errorsList.id">
      <li v-for="error in errorsList" :key="error.id">
        {{ error }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    errors: {
      type: Object,
      default: null,
    },
  },
};
</script>
