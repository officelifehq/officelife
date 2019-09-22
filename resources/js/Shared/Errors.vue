<template>
  <div v-if="errors.length > 0">
    <p>app.error_title</p>
    <br />
    {{ errors[0] }}
    <ul>
      <li v-for="error in errors[1]" :key="error.id">
        {{ error[0] }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    errors: {
      type: Array,
      default: () => [],
    },
  },
};
</script>
