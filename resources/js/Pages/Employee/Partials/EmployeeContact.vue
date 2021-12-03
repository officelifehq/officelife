<style lang="scss" scoped>
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
        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
      </svg>
      <span class="f6 title">
        {{ $t('employee.contacts_title') }}
      </span>

      <inertia-link v-show="permissions.can_edit_profile" :href="employee.url.edit" data-cy="edit-contact-information-link" class="di f7 ml2">{{ $t('app.edit') }}</inertia-link>
    </div>

    <ul class="list ma0 pl0">
      <li class="mb2">{{ employee.email }}</li>
      <li v-if="employee.phone" class="mb2" data-cy="employee-phone">
        <a :href="'tel:' + employee.phone">{{ employee.phone }}</a>
      </li>
      <li v-if="employee.slack_handle" data-cy="employee-slack-handle" class="mb2">
        {{ employee.slack_handle }} <span class="gray f7">(Slack)</span>
      </li>
      <li v-if="employee.twitter_handle" data-cy="employee-twitter-handle" class="mb2">
        {{ employee.twitter_handle }} <span class="gray f7">(Twitter)</span>
      </li>
    </ul>
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
