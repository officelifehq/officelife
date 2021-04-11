<style lang="scss" scoped>
.employee-item {
  &:last-child {
    border-bottom: 0;
  }
}

.badge {
  color: #fff;
  background-color: #6e84a3;
  padding: 2px 6px;
  border-radius: 10rem;
  top: -3px;
}
</style>

<template>
  <div>
    <!-- list of employees -->
    <ul class="list pl0 mt0 center" data-cy="employee-list" :data-cy-items="sortedEmployees().map(e => e.id)">
      <li
        v-for="currentEmployee in employees" :key="currentEmployee.id"
        class="flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10 employee-item"
      >
        <avatar :avatar="currentEmployee.avatar" :size="64" :class="'w2 h2 w3-ns h3-ns br-100'" />

        <div class="pl3 flex-auto">
          <!-- name -->
          <span class="db black-70 f4 mb1 relative" :name="currentEmployee.name" :data-cy="currentEmployee.id" :data-invitation-link="currentEmployee.invitation_link">
            {{ currentEmployee.name }}

            <!-- lock status -->
            <span v-if="currentEmployee.lock_status" data-cy="lock-status">🔐</span>

            <!-- permission level -->
            <span v-if="currentEmployee.permission_level < 300" class="badge f7 relative">
              {{ $t('app.permission_' + currentEmployee.permission_level) }}
            </span>
          </span>

          <!-- invited by email -->
          <span v-if="currentEmployee.invited" class="db f6 ">{{ 'Invited by email' }}</span>

          <!-- actions -->
          <ul class="f6 list pl0">
            <li class="di pr2">
              <inertia-link :href="currentEmployee.url_view" data-cy="employee-view">{{ $t('app.view') }}</inertia-link>
            </li>
            <li v-if="currentEmployee.id != $page.props.auth.employee.id && !currentEmployee.invited" class="di pr2">
              <inertia-link :href="currentEmployee.url_permission">{{ $t('account.employees_change_permission') }}</inertia-link>
            </li>
            <li v-if="currentEmployee.id != $page.props.auth.employee.id && !currentEmployee.lock_status && !currentEmployee.invited" class="di pr2">
              <inertia-link :href="currentEmployee.url_lock" :data-cy="'lock-account-' + currentEmployee.id">{{ $t('account.employees_lock_account') }}</inertia-link>
            </li>
            <li v-if="currentEmployee.id != $page.props.auth.employee.id && currentEmployee.lock_status" class="di pr2">
              <inertia-link :href="currentEmployee.url_unlock" :data-cy="'unlock-account-' + currentEmployee.id">{{ $t('account.employees_unlock_account') }}</inertia-link>
            </li>
            <li v-if="currentEmployee.id != $page.props.auth.employee.id" class="di">
              <inertia-link :href="currentEmployee.url_delete" class="c-delete" data-cy="delete-account">{{ $t('app.delete') }}</inertia-link>
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Avatar,
  },

  props: {
    employees: {
      type: Array,
      default: null,
    },
  },

  methods: {
    sortedEmployees() {
      return _.sortBy(this.employees, 'id');
    }
  }
};
</script>
