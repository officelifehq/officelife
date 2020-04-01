<style lang="scss" scoped>
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      🏡 Work from home
    </span>

    <p>Doesn't work from home today.</p>

    <p>1.2 day per week, In the last 3 months</p>
    <p>1.2 day per week, In the last 3 months</p>
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

  data() {
    return {
    };
  },

  methods: {
    navigateToUrl(params) {
      window.open(params, '_blank');
    },

    employeeOrAtLeastHR() {
      if (this.$page.auth.employee.permission_level <= 200) {
        return true;
      }

      if (!this.employee.user) {
        return false;
      }

      if (this.$page.auth.user.id == this.employee.user.id) {
        return true;
      }
    }
  }
};
</script>
