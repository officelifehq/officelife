<style scoped>
.popupmenu {
  right: 2px;
  top: 26px;
  width: 280px;
}
</style>

<template>
  <div class="di relative" data-cy="hired-at-date">
    <template v-if="employee.hired_at">
      <p class="di">{{ $t('employee.hired_at_information', { date: employee.hired_at.full }) }}</p>
    </template>
    <template v-else>
      <p class="di">{{ $t('employee.hired_at_information_blank') }}</p>
    </template>
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
    employeeOrAtLeastHR() {
      if (this.$page.props.auth.employee.permission_level <= 200) {
        return true;
      }

      if (!this.employee.user) {
        return false;
      }

      if (this.$page.props.auth.user.id == this.employee.user.id) {
        return true;
      }
    }
  }
};

</script>
