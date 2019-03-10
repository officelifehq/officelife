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
  <layout title="Home">
    <div class="ph2 ph0-ns">

      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di"><a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a></li>
          <li class="di">{{ $t('app.breadcrumb_account_home') }}</li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb3 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">{{ $t('account.home_title') }}</h2>
          <p v-html="$t('account.home_role_administrator')"></p>

          <ul class="options list pl0 mb5">
            <li class="pa2 pl0 relative">
              <img src="/img/company/account/employees.svg" class="pr1 absolute">
              <a :href="'/' + company.id + '/account/employees'" class="relative" data-cy="employee-admin-link">{{ $t('account.home_manage_employees') }}</a>
            </li>
            <li class="pa2 pl0 relative">
              <img src="/img/company/account/teams.svg" class="pr1 absolute">
              <a :href="'/' + company.id + '/account/teams'" class="relative" data-cy="team-admin-link">{{ $t('account.home_manage_teams') }}</a>
            </li>
          </ul>

          <p v-html="$t('account.home_role_owner')"></p>
          <ul class="options list pl0">
            <li class="pa2 pl0"><a :href="'/' + company.id + '/account/audit'" class="bb">{{ $t('account.home_audit_log') }}</a></li>
            <li class="pa2 pl0" v-show="!company.has_dummy_data"><a :href="'/' + company.id + '/account/dummy'">{{ $t('account.home_generate_fake_data') }}</a></li>
            <li class="pa2 pl0" v-show="company.has_dummy_data"><a :href="'/' + company.id + '/account/dummy'">{{ $t('account.home_remove_fake_data') }}</a></li>
          </ul>

        </div>
      </div>
    </div>
  </layout>
</template>

<script>
export default {
  props: [
    'company',
    'nbEmployees'
  ],
}
</script>
