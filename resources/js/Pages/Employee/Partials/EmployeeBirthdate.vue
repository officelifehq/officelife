<style scoped>
.popupmenu {
  right: 2px;
  top: 26px;
  width: 280px;
}
</style>

<template>
  <div class="di relative">
    <template v-if="employee.birthdate">
      <p v-if="permissions.can_see_full_birthdate" class="di" data-cy="employee-birthdate-information">{{ $t('employee.birthdate_information_full', { date: employee.birthdate.date, age: employee.birthdate.age }) }}</p>
      <p v-else class="di" data-cy="employee-birthdate-information">{{ $t('employee.birthdate_information_partial', { date: employee.birthdate.date }) }}</p>
    </template>
    <template v-else>
      <p class="di">{{ $t('employee.birthdate_information_blank') }}</p>
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
    permissions: {
      type: Object,
      default: null,
    },
  },
};
</script>
