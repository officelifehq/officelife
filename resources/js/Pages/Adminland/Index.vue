<style scoped>
.options {
  column-count: 2;
}

@media (max-width: 480px) {
  .options {
    column-count: 1;
  }
}

.options img {
  top: 7px;
}

.options a {
  left: 33px;
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
            {{ $t('app.breadcrumb_account_home') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb3 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.home_title') }}
          </h2>

          <!-- HR -->
          <p v-html="$t('account.home_role_administrator')"></p>
          <ul class="options list pl0 mb5">
            <li class="pa2 pl0 relative">
              🤖 <inertia-link :href="'/' + $page.auth.company.id + '/account/employees'" data-cy="employee-admin-link">
                {{ $t('account.home_manage_employees') }}
              </inertia-link>
            </li>
            <li class="pa2 pl0 relative">
              💂‍♀ <inertia-link :href="'/' + $page.auth.company.id + '/account/positions'" data-cy="position-admin-link">
                {{ $t('account.home_manage_positions') }}
              </inertia-link>
            </li>
            <li class="pa2 pl0 relative">
              👯‍♀️ <inertia-link :href="'/' + $page.auth.company.id + '/account/teams'" data-cy="team-admin-link">
                {{ $t('account.home_manage_teams') }}
              </inertia-link>
            </li>
            <li class="pa2 pl0 relative">
              🏄‍♂️ <inertia-link :href="'/' + $page.auth.company.id + '/account/flows'" data-cy="flow-admin-link">
                {{ $t('account.home_manage_flows') }}
              </inertia-link>
            </li>
            <li class="pa2 pl0 relative">
              ⛑ <inertia-link :href="'/' + $page.auth.company.id + '/account/employeestatuses'" data-cy="employee-statuses-admin-link">
                {{ $t('account.home_manage_employee_statuses') }}
              </inertia-link>
            </li>
            <li class="pa2 pl0 relative">
              📇 <inertia-link :href="'/' + $page.auth.company.id + '/account/news'" data-cy="news-admin-link">
                {{ $t('account.home_company_news') }}
              </inertia-link>
            </li>
            <li class="pa2 pl0 relative">
              🌴 <inertia-link :href="'/' + $page.auth.company.id + '/account/ptopolicies'" data-cy="pto-policies-admin-link">
                {{ $t('account.home_manage_pto') }}
              </inertia-link>
            </li>
            <li class="pa2 pl0 relative">
              🌈 <inertia-link :href="'/' + $page.auth.company.id + '/account/questions'" data-cy="questions-admin-link">
                {{ $t('account.home_manage_questions') }}
              </inertia-link>
            </li>
            <li class="pa2 pl0 relative">
              💻 <inertia-link :href="'/' + $page.auth.company.id + '/account/hardware'" data-cy="hardware-admin-link">
                {{ $t('account.home_manage_hardware') }}
              </inertia-link>
            </li>
          </ul>

          <!-- ACCOUNT OWNER -->
          <div v-show="$page.auth.employee.permission_level < 200">
            <p v-html="$t('account.home_role_owner')"></p>
            <ul class="options list pl0 mb0">
              <li class="pa2 pl0 relative">
                📡 <inertia-link :href="'/' + $page.auth.company.id + '/account/audit'">
                  {{ $t('account.home_audit_log') }}
                </inertia-link>
              </li>
              <li v-show="!$page.auth.company.has_dummy_data" class="pa2 pl0">
                <inertia-link :href="'/' + $page.auth.company.id + '/account/dummy'">
                  {{ $t('account.home_generate_fake_data') }}
                </inertia-link>
              </li>
              <li v-show="$page.auth.company.has_dummy_data" class="pa2 pl0">
                <inertia-link :href="'/' + $page.auth.company.id + '/account/dummy'">
                  {{ $t('account.home_remove_fake_data') }}
                </inertia-link>
              </li>
            </ul>
          </div>
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
  },
};
</script>
