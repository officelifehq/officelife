<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        📍
      </span> {{ $t('employee.location_title') }}
    </span>

    <div v-if="employee.address" class="br3 bg-white box z-1 pa3">
      <!-- Mapbox image, if env variables are set -->
      <img v-if="employee.address.employee_cover_image_url" loading="lazy"
           :src="employee.address.employee_cover_image_url"
           :alt="$t('employee.location_alt_employee')"
           class="mb3 pointer"
           @click="navigateToUrl(employee.address.openstreetmap_url)"
      />

      <!-- partial address -->
      <p v-if="loggedEmployee.can_see_complete_address" class="mt0" data-cy="employee-location">
        {{ $t('employee.location_information', { address: employee.address.readable }) }}
      </p>

      <!-- complete address if it's the employee or an employee with HR role at least -->
      <p v-if="!loggedEmployee.can_see_complete_address" class="mt0" data-cy="employee-location">
        {{ $t('employee.location_information', { address: employee.address.partial }) }}
      </p>
    </div>

    <!-- case of no address set in profile -->
    <div v-else class="br3 bg-white box z-1 pa3">
      <p class="mb0 mt0 lh-copy f6">
        {{ $t('employee.location_no_info') }}
      </p>
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
    loggedEmployee: {
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
  }
};
</script>
