<style scoped>
.icon {
  color: #9AA7BF;
  position: relative;
  width: 17px;
  top: 3px;
}

.title {
  color: #757982;
}
</style>

<template>
  <div class="mb4 relative">
    <div class="db fw4 mb3 relative">
      <svg class="icon mr1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M6 3a1 1 0 011-1h.01a1 1 0 010 2H7a1 1 0 01-1-1zm2 3a1 1 0 00-2 0v1a2 2 0 00-2 2v1a2 2 0 00-2 2v.683a3.7 3.7 0 011.055.485 1.704 1.704 0 001.89 0 3.704 3.704 0 014.11 0 1.704 1.704 0 001.89 0 3.704 3.704 0 014.11 0 1.704 1.704 0 001.89 0A3.7 3.7 0 0118 12.683V12a2 2 0 00-2-2V9a2 2 0 00-2-2V6a1 1 0 10-2 0v1h-1V6a1 1 0 10-2 0v1H8V6zm10 8.868a3.704 3.704 0 01-4.055-.036 1.704 1.704 0 00-1.89 0 3.704 3.704 0 01-4.11 0 1.704 1.704 0 00-1.89 0A3.704 3.704 0 012 14.868V17a1 1 0 001 1h14a1 1 0 001-1v-2.132zM9 3a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm3 0a1 1 0 011-1h.01a1 1 0 110 2H13a1 1 0 01-1-1z" clip-rule="evenodd" />
      </svg>
      <span class="f6 title">
        {{ $t('employee.important_dates') }}
      </span>

      <inertia-link v-if="permissions.can_manage_description" :href="employee.url.edit" data-cy="edit-important-date-link" class="di f7 ml2">{{ $t('app.edit') }}</inertia-link>
    </div>

    <!-- birthdates -->
    <template v-if="employee.birthdate">
      <p v-if="permissions.can_see_full_birthdate" class="mt0 mb2" data-cy="employee-birthdate-information">{{ $t('employee.birthdate_information_full', { date: employee.birthdate.date }) }} <span class="f7 gray">{{ $t('employee.birthdate_information_age', { age: employee.birthdate.age }) }}</span></p>
      <p v-else class="mt0 mb2" data-cy="employee-birthdate-information">{{ $t('employee.birthdate_information_partial', { date: employee.birthdate.date }) }}</p>
    </template>

    <template v-else>
      <p class="mt0 mb2">{{ $t('employee.birthdate_information_blank') }}</p>
    </template>

    <!-- hired date -->
    <div v-if="employee.hired_at" data-cy="employee-contract-renewal-date">
      <p class="mt0 mb2">{{ $t('employee.hired_at_information', { date: employee.hired_at.full }) }} <span class="f7 gray">({{ $t('employee.stat_hiring', { 'percent': employee.hired_at.percent, 'name': employee.first_name}) }})</span></p>
    </div>
    <div v-else>
      <p class="mt0 mb2">{{ $t('employee.hired_at_information_blank') }}</p>
    </div>
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
