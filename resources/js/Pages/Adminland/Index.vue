<style scoped>
.options img {
  top: 7px;
}

.options a {
  left: 33px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true" :has-more="false"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
      >
        {{ $t('app.breadcrumb_account_home') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb3 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.home_title') }}
          </h2>

          <!-- HR -->
          <p v-html="$t('account.home_role_administrator')"></p>
          <div class="flex options pl0 mb5">
            <div class="ph3 mr4">
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  🤖
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/employees'" data-cy="employee-admin-link">
                  {{ $t('account.home_manage_employees') }}
                </inertia-link>
              </div>
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  💂‍♀
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/positions'" data-cy="position-admin-link">
                  {{ $t('account.home_manage_positions') }}
                </inertia-link>
              </div>
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  👯‍♀️
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/teams'" data-cy="team-admin-link">
                  {{ $t('account.home_manage_teams') }}
                </inertia-link>
              </div>
              <!-- <div class="pa2 pl0 relative">
                <span class="mr1">
                  🏄‍♂️
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/flows'" data-cy="flow-admin-link">
                  {{ $t('account.home_manage_flows') }}
                </inertia-link>
              </div> -->
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  ⛑
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/employeestatuses'" data-cy="employee-statuses-admin-link">
                  {{ $t('account.home_manage_employee_statuses') }}
                </inertia-link>
              </div>
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  📇
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/news'" data-cy="news-admin-link">
                  {{ $t('account.home_company_news') }}
                </inertia-link>
              </div>
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  🥷
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/recruitment'" data-cy="news-admin-link">
                  {{ $t('account.home_manage_recruitment') }}
                </inertia-link>
              </div>
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  🎲
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/project'">
                  {{ $t('account.home_manage_project') }}
                </inertia-link>
              </div>
            </div>
            <div class="ph3">
              <!-- <div class="pa2 pl0 relative">
                <span class="mr1">🌴</span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/ptopolicies'" data-cy="pto-policies-admin-link">
                  {{ $t('account.home_manage_pto') }}
                </inertia-link>
              </div> -->
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  🌈
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/questions'" data-cy="questions-admin-link">
                  {{ $t('account.home_manage_questions') }}
                </inertia-link>
              </div>
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  💾
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/softwares'" data-cy="software-admin-link">
                  {{ $t('account.home_manage_software') }}
                </inertia-link>
              </div>
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  💻
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/hardware'" data-cy="hardware-admin-link">
                  {{ $t('account.home_manage_hardware') }}
                </inertia-link>
              </div>
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  💵
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/expenses'" data-cy="expenses-admin-link">
                  {{ $t('account.home_manage_expenses') }}
                </inertia-link>
              </div>
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  ☕️
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/ecoffee'">
                  {{ $t('account.home_manage_ecoffee') }}
                </inertia-link>
              </div>
              <div class="pa2 pl0 relative">
                <span class="mr1">
                  🏡
                </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/workFromHome'">
                  {{ $t('account.home_manage_work_from_home') }}
                </inertia-link>
              </div>
            </div>
          </div>

          <!-- ACCOUNT OWNER -->
          <div v-show="$page.props.auth.employee.permission_level < 200">
            <p v-html="$t('account.home_role_owner')"></p>
            <div class="options flex pl0 mb0">
              <div class="ph3">
                <div class="pa2 pl0 relative">
                  <span class="mr1">
                    ⚙️
                  </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/general'" data-cy="general-admin-link">
                    {{ $t('account.home_general_settings') }}
                  </inertia-link>
                </div>
                <div class="pa2 pl0 relative">
                  <span class="mr1">
                    📡
                  </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/audit'" data-cy="audit-admin-link">
                    {{ $t('account.home_audit_log') }}
                  </inertia-link>
                </div>
              </div>
              <div class="ph3 relative">
                <div v-if="paidPlanEnabled" class="pa2 pl0 relative">
                  <span class="mr1">
                    💸
                  </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/billing'">
                    {{ $t('account.home_account_usage') }}
                  </inertia-link>
                </div>
                <div class="pa2 pl0 relative">
                  <span class="mr1">
                    🗑
                  </span> <inertia-link :href="'/' + $page.props.auth.company.id + '/account/cancel'" data-cy="audit-cancel-link">
                    {{ $t('account.home_cancel_account') }}
                  </inertia-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';

export default {
  components: {
    Layout,
    Breadcrumb,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    paidPlanEnabled: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
