<style lang="scss" scoped>
.icon {
  color: #9AA7BF;
  position: relative;
  width: 17px;
  top: 3px;
}
</style>

<template>
  <div>
    <!-- case if an employee is locked -->
    <div v-if="employee.locked" class="bg-white box z-1 pa3 tc mb5">
      <img loading="lazy" src="/img/streamline-icon-store-close-blackboard@140x140.png" width="140" height="140" alt="meeting"
           class=""
      />

      <span class="db">
        {{ $t('employee.account_locked') }}
      </span>
    </div>

    <div class="bg-white box pa3 mb5">
      <h2 class="normal mb3 mt0 fw5">
        {{ employee.name }} <inertia-link v-if="permissions.can_edit_profile" :href="employee.url.edit" class="fw4 di f7 ml2">{{ $t('app.edit') }}</inertia-link>
      </h2>

      <ul class="list ma0 pl0 f6">
        <li class="mb2">
          <svg class="icon mr1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
          </svg>

          <employee-position
            :employee="employee"
            :permissions="permissions"
          />
        </li>

        <li class="mb2">
          <svg class="icon mr1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
          </svg>

          <employee-team
            :employee="employee"
            :permissions="permissions"
          />
        </li>
        <li>
          <svg class="icon mr1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
          </svg>

          <employee-location
            :employee="employee"
            :permissions="permissions"
          />
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import EmployeePosition from '@/Pages/Employee/Partials/EmployeePosition';
import EmployeeTeam from '@/Pages/Employee/Partials/EmployeeTeam';
import EmployeeLocation from '@/Pages/Employee/Partials/EmployeeLocation';

export default {
  components: {
    EmployeePosition,
    EmployeeTeam,
    EmployeeLocation
  },

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
