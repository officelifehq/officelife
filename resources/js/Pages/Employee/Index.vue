<style lang="scss" scoped>
.employee-item:last-child {
  border-bottom: 0;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb :with-box="true" :has-more="false">
        {{ $t('app.breadcrumb_employee_list') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 mt2">
          <!-- HEADER: number of employees and button -->
          <p class="relative">
            <span class="dib mb3 di-l">
              {{ $tc('account.employees_number_employees', employees.length, { company: $page.props.auth.company.name, count: employees.length}) }}
            </span>
          </p>

          <ul class="list pl0 mt0 mb0 center">
            <li
              v-for="employee in employees" :key="employee.id"
              class="flex lh-copy pa3-l pa1 ph0-l pv0-ns pv2 bb b--black-10 employee-item"
            >
              <avatar :avatar="employee.avatar" :size="64" :class="'w2 h2 w3-ns h3-ns br-100'" />

              <div class="pl3">
                <!-- name -->
                <inertia-link class="dib pointer mb1" :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id">
                  {{ employee.name }}
                </inertia-link>

                <!-- employee position -->
                <ul class="f7 pl0">
                  <template v-if="employee.position">
                    <li class="di pr2">
                      <span class="f7">
                        {{ employee.position.title }}
                      </span>
                    </li>
                  </template>
                </ul>

                <!-- teams -->
                <ul class="f7 pl0">
                  <template v-if="employee.teams.length > 0">
                    <li class="di pr2">
                      <ul class="di list pl0">
                        <li class="di">Part of </li>
                        <li v-for="team in employee.teams" :key="team.id" class="di mr2">
                          <inertia-link :href="'/' + $page.props.auth.company.id + '/teams/' + team.id">
                            {{ team.name }}
                          </inertia-link>
                        </li>
                      </ul>
                    </li>
                  </template>
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
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
