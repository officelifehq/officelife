<template>
  <div class="di relative">
    <span v-if="employee.address" class="mt0 mb0" data-cy="employee-location">
      {{ $t('employee.location_information', { address: employee.address.sentence }) }}

      <span v-if="employee.address">
        <a :href="employee.address.openstreetmap_url" target="_blank" class="bb b--dotted bt-0 bl-0 br-0 pointer di f7 ml2">{{ $t('employee.location_view_link') }}</a>
      </span>

      <inertia-link v-if="permissions.can_see_complete_address" :href="employee.url.edit_address" class="di f7 ml2">{{ $t('app.edit') }}</inertia-link>
    </span>

    <!-- case of no address set in profile -->
    <inertia-link v-if="!employee.address && permissions.can_edit_profile" :href="employee.url.edit_address" class="di f7">
      {{ $t('employee.location_no_info_with_right') }}
    </inertia-link>
    <span v-if="!employee.address && !permissions.can_edit_profile" class="mb0 mt0 lh-copy f6">
      {{ $t('employee.location_no_info') }}
    </span>
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

  methods: {
    navigateToUrl(params) {
      window.open(params, '_blank');
    },
  }
};
</script>
