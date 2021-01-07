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
    <h2 class="normal mb3 mt3">
      {{ employee.name }}
    </h2>
    <ul class="list ma0 pl0 mb5">
      <li class="di mr4">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
        </svg>

        <employee-position
          :employee="employee"
          :positions="positions"
          :permissions="permissions"
        />
      </li>

      <li class="di">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
        </svg>

        <employee-team
          :employee="employee"
          :teams="teams"
          :permissions="permissions"
        />
      </li>
    </ul>
  </div>
</template>

<script>
import EmployeePosition from '@/Pages/Employee/Partials/EmployeePosition';
import EmployeeTeam from '@/Pages/Employee/Partials/EmployeeTeam';

export default {
  components: {
    EmployeePosition,
    EmployeeTeam,
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
    positions: {
      type: Array,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
  },
};

</script>
