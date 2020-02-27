<style scoped>
.popupmenu {
  right: 2px;
  top: 26px;
  width: 280px;
}
</style>

<template>
  <div class="di relative">
    <!-- Case when the birthdate is set -->
    <template v-if="employee.birthdate">
      <p v-if="employeeOrAtLeastHR()" class="di" data-cy="employee-birthdate-information">{{ $t('employee.birthdate_information_full', { date: employee.birthdate.full, age: employee.birthdate.age }) }}</p>
      <p v-else class="di" data-cy="employee-birthdate-information">{{ $t('employee.birthdate_information_partial', { date: employee.birthdate.partial }) }}</p>
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
