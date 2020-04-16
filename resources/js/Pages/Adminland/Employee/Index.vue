<style lang="scss" scoped>
.employee-item {
  &:last-child {
    border-bottom: 0;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_employees') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.employees_title', { company: $page.auth.company.name}) }}
          </h2>

          <!-- HEADER: number of employees and button -->
          <p class="relative adminland-headline">
            <span class="dib mb3 di-l">
              {{ $tc('account.employees_number_employees', employees.length, { company: $page.auth.company.name, count: employees.length}) }}
            </span>
            <inertia-link :href="'/' + $page.auth.company.id + '/account/employees/create'" class="btn absolute-l relative dib-l db right-0" data-cy="add-employee-button">
              {{ $t('account.employees_cta') }}
            </inertia-link>
          </p>

          <!-- list of employees -->
          <ul class="list pl0 mt0 center">
            <li
              v-for="currentEmployee in employees" :key="currentEmployee.id"
              class="flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10 employee-item"
            >
              <img class="w2 h2 w3-ns h3-ns br-100" :src="currentEmployee.avatar" width="64" height="64" alt="avatar"
                   loading="lazy"
              />
              <div class="pl3 flex-auto">
                <span class="db black-70" :name="currentEmployee.name" :data-invitation-link="currentEmployee.invitation_link">
                  {{ currentEmployee.name }}
                </span>
                <ul class="f6 list pl0">
                  <li class="di pr2">
                    <span class="badge f7">
                      {{ $t('app.permission_' + currentEmployee.permission_level) }}
                    </span>
                  </li>
                  <li class="di pr2">
                    <inertia-link :href="'/' + $page.auth.company.id + '/employees/' + currentEmployee.id" data-cy="employee-view">
                      {{ $t('app.view') }}
                    </inertia-link>
                  </li>
                  <li class="di pr2">
                    <inertia-link :href="'/account/employees/' + currentEmployee.id + '/permissions'">
                      {{ $t('account.employees_change_permission') }}
                    </inertia-link>
                  </li>
                  <li class="di pr2">
                    <inertia-link :href="'/employees/' + currentEmployee.id + '/lock'">
                      {{ $t('account.employees_lock_account') }}
                    </inertia-link>
                  </li>
                  <li class="di">
                    <inertia-link :href="'/account/employees/' + currentEmployee.id + '/destroy'">
                      {{ $t('app.delete') }}
                    </inertia-link>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employees: {
      type: Array,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');

      localStorage.removeItem('success');
    }
  },
};

</script>
