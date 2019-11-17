<style lang="scss" scoped>
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      📍 {{ $t('employee.location_title') }}
    </span>

    <div v-if="employee.address" class="br3 bg-white box z-1 pa3">
      <!-- Mapbox image, if env variables are set -->
      <img v-if="employee.address.employee_cover_image_url"
           :src="employee.address.employee_cover_image_url"
           :alt="$t('employee.location_alt_employee')"
           class="mb3 pointer"
           @click="navigateToUrl(employee.address.openstreetmap_url)"
      />

      <p v-if="employeeOrAtLeastHR()" class="mt0" data-cy="employee-location">
        {{ $t('employee.location_information', { address: employee.address.readable }) }}
      </p>
      <p v-if="!employeeOrAtLeastHR()" class="mt0" data-cy="employee-location">
        {{ $t('employee.location_information', { address: employee.address.partial }) }}
      </p>
      <span class="f7 silver">
        {{ $t('employee.location_privacy_information') }}
      </span>
    </div>

    <div v-else class="br3 bg-white box z-1 pa3">
      <p class="mb0 mt0 lh-copy mb0 f6">
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
  },

  data() {
    return {
    };
  },

  methods: {
    navigateToUrl(params) {
      window.open(params, '_blank');
    },

    employeeOrAtLeastHR() {
      return this.$page.auth.employee.permission_level <= 200 || this.$page.auth.user.id == this.employee.user.id;
    }
  }
};
</script>
