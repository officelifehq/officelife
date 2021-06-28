<style scoped>
.home-box {
  color: #4d4d4f;
  height: 300px;
  width: 300px;
}

@media (max-width: 480px) {
  .home-box {
    width: 100%;
  }
}

.home-company {
  left: -20px;
  bottom: -20px;
}

@media (max-width: 480px) {
  .home-company img {
    bottom: 0;
  }
}

.home-join {
  left: 14px;
  bottom: 11px;
}

@media (max-width: 480px) {
  .home-join img {
    bottom: 0;
  }
}
</style>

<template>
  <layout :no-menu="true" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- Blank state -->
      <div v-show="employees.length == 0" class="cf mt4 mt5-l mw7 center">
        <div class="fl w-100 w-25-m w-50-l pr2-l">
          <inertia-link href="/company/create">
            <div class="pa3-l">
              <div class="br3 mb3 bg-white box pa3 tc relative home-box" data-cy="create-company-blank-state">
                <h3>{{ $t('home.create_company') }}</h3>
                <p>{{ $t('home.create_company_desc') }}</p>
                <img loading="lazy" src="/img/home/create-company.png" class="home-company absolute" alt="create company button" />
              </div>
            </div>
          </inertia-link>
        </div>
        <div class="fl w-100 w-25-m w-50-l">
          <inertia-link href="/company/join">
            <div class="pa3-l">
              <div class="br3 mb3 bg-white box pa3 tc relative home-box">
                <h3>{{ $t('home.join_company') }}</h3>
                <p>{{ $t('home.join_company_desc') }}</p>
                <img loading="lazy" src="/img/home/join-company.png" class="home-join absolute" alt="join company button" />
              </div>
            </div>
          </inertia-link>
        </div>
      </div>

      <!-- List of companies -->
      <div v-show="employees.length != 0">
        <div class="mt4 mt5-l mw7 center section-btn relative">
          <p>
            <span class="pr2">
              {{ $t('home.companies_part_of') }}
            </span>
            <inertia-link href="/company/create" class="btn absolute db-l dn">
              {{ $t('home.create_company_cta') }}
            </inertia-link>
          </p>
        </div>
        <div class="cf mt4 mw7 center">
          <div v-for="employee in employees" :key="employee.id" class="fl w-100 w-25-m w-third-l pr2">
            <inertia-link :href="'/' + employee.company_id + '/welcome'">
              <div class="br3 mb3 bg-white box pa3 home-index-company fw5 relative" :data-cy="'company-' + employee.company_id">
                {{ employee.company_name }}

                <span class="absolute normal f6">
                  {{ $tc('home.number_of_employees', employee.number_of_employees, { count: employee.number_of_employees }) }}
                </span>
              </div>
            </inertia-link>
          </div>
        </div>
        <div class="w-100 dn-ns db mt2">
          <inertia-link href="/company/create" class="btn br3 pa3 white no-underline bb-0 db tc">
            {{ $t('home.create_company_cta') }}
          </inertia-link>
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
    employees: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },
};
</script>
