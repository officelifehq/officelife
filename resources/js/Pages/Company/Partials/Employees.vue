<style lang="scss" scoped>
.employees {
  left: 9px;
}

.small-avatar {
  margin-left: -8px;
  box-shadow: 0 0 0 2px #fff;
}

.more-members {
  height: 32px;
  top: 8px;
}
</style>

<template>
  <div class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        😎
      </span> {{ $t('company.employees_title') }}
    </span>

    <div class="br3 bg-white box z-1 relative">
      <!-- general stats -->
      <div class="pa3 bb bb-gray">
        <p>{{ $tc('company.employees_total', statistics.number_of_employees, { count: statistics.number_of_employees }) }}</p>

        <div class="flex items-center relative tr employees">
          <avatar v-for="employee in employees.ten_random_employees" :key="employee.id" :avatar="employee.avatar" :size="32" :url="employee.url"
                  :class="'br-100 small-avatar pointer'"
          />
          <div v-if="employees.number_of_employees_left > 0" class="pl2 f7 more-members relative gray">
            {{ $tc('project.menu_other_member', employees.number_of_employees_left, { count: employees.number_of_employees_left }) }}
          </div>
        </div>
      </div>

      <!-- general stats -->
      <div class="pa3">
        {{ $tc('company.new_hires_total', employees.employees_hired_in_the_current_year, { count: employees.employees_hired_in_the_current_year }) }}
      </div>

      <div class="ph3 pv2 tc f6 bt bb-gray">
        <inertia-link :href="employees.view_all_url">{{ $t('company.employees_view_all') }}</inertia-link>
      </div>
    </div>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Avatar,
  },

  props: {
    statistics: {
      type: Object,
      default: null,
    },
    employees: {
      type: Object,
      default: null,
    },
  },
};
</script>
