<style lang="scss" scoped>
</style>

<template>
  <div>
    <avatar
      :employee="employee"
      :permissions="permissions"
      :uploadcare-public-key="uploadcarePublicKey"
    />

    <personal-description
      :employee="employee"
      :permissions="permissions"
    />

    <employee-important-dates
      :employee="employee"
      :permissions="permissions"
    />

    <employee-gender-pronoun
      :employee="employee"
      :permissions="permissions"
    />

    <employee-status
      :employee="employee"
      :permissions="permissions"
    />

    <employee-contact
      :employee="employee"
      :permissions="permissions"
    />

    <profile-actions
      :employee="employee"
      :permissions="permissions"
    />
  </div>
</template>

<script>
import PersonalDescription from '@/Pages/Employee/Partials/PersonalDescription.vue';
import EmployeeImportantDates from '@/Pages/Employee/Partials/EmployeeImportantDates.vue';
import EmployeeStatus from '@/Pages/Employee/Partials/EmployeeStatus.vue';
import EmployeeContact from '@/Pages/Employee/Partials/EmployeeContact.vue';
import EmployeeGenderPronoun from '@/Pages/Employee/Partials/EmployeeGenderPronoun.vue';
import ProfileActions from '@/Pages/Employee/Partials/ProfileActions.vue';
import Avatar from '@/Pages/Employee/Partials/Avatar.vue';

export default {
  components: {
    PersonalDescription,
    EmployeeImportantDates,
    EmployeeStatus,
    EmployeeContact,
    EmployeeGenderPronoun,
    ProfileActions,
    Avatar,
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
    uploadcarePublicKey: {
      type: String,
      default: null,
    },
  },
};

</script>
