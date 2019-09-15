<style style="scss" scoped>
.employee-item:last-child {
  border-bottom: 0;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $page.auth.company.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_employee_list') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 mt2">
          <!-- HEADER: number of employees and button -->
          <p class="relative">
            <span class="dib mb3 di-l">
              {{ $tc('account.employees_number_employees', employees.length, { company: $page.auth.company.name, count: employees.length}) }}
            </span>
          </p>

          <ul class="list pl0 mt0 mb0 center">
            <li
              v-for="employee in employees" :key="employee.id"
              class="flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10 employee-item"
            >
              <img class="w2 h2 w3-ns h3-ns br-100" :src="employee.avatar" width="64" height="64" />
              <div class="pl3 flex-auto">
                <span class="db black-70">
                  {{ employee.name }}
                </span>
                <ul class="f6 list pl0">
                  <template v-if="employee.position">
                    <li class="di pr2">
                      <span class="f7">
                        {{ employee.position.title }}
                      </span>
                    </li>
                  </template>
                  <template v-if="employee.teams">
                    <li class="di pr2">
                      <ul class="di list pl0">
                        <li v-for="team in employee.teams" :key="team.id" class="di team-item">
                          <span class="badge f7">
                            {{ team.name }}
                          </span>
                        </li>
                      </ul>
                    </li>
                  </template>
                  <li class="di pr2">
                    <inertia-link :href="'/' + $page.auth.company.id + '/employees/' + employee.id" data-cy="employee-view">
                      {{ $t('app.view') }}
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
    employees: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
    };
  },

  methods: {
  }
};

</script>
