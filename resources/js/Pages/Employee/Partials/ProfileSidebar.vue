<style lang="scss" scoped>
.avatar {
  border: 1px solid #e1e4e8 !important;
  padding: 10px;
  background-color: #fff;
  border-radius: 7px;

  img {
    width: 100%;
    height: auto;
  }
}
</style>

<template>
  <div>
    <div class="db center mb4 avatar">
      <img :class="{'black-white':(employee.locked)}" loading="lazy" :src="employee.avatar" alt="avatar" />
    </div>

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
import PersonalDescription from '@/Pages/Employee/Partials/PersonalDescription';
import EmployeeImportantDates from '@/Pages/Employee/Partials/EmployeeImportantDates';
import EmployeeStatus from '@/Pages/Employee/Partials/EmployeeStatus';
import EmployeeContact from '@/Pages/Employee/Partials/EmployeeContact';
import EmployeeGenderPronoun from '@/Pages/Employee/Partials/EmployeeGenderPronoun';
import ProfileActions from '@/Pages/Employee/Partials/ProfileActions';

export default {
  components: {
    PersonalDescription,
    EmployeeImportantDates,
    EmployeeStatus,
    EmployeeContact,
    EmployeeGenderPronoun,
    ProfileActions,
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
