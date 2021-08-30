<style lang="scss" scoped>
.avatar {
  top: 1px;
  left: 0;
  width: 35px;
}

.employee-name,
.position {
  padding-left: 42px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/employees/' + employee.id"
                  :previous="employee.name"
      >
        {{ $t('app.breadcrumb_employee_ecoffee') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <h2 class="pa3 mt2 mb4 center tc normal relative">
          {{ $t('employee.e_coffee_show_title') }}

          <help :url="$page.props.help_links.ecoffee" :top="'1px'" />
        </h2>

        <ul v-if="eCoffees.length > 0" class="list pl0 ma0">
          <li v-for="ecoffee in eCoffees" :key="ecoffee.id" class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between ecoffee-item" :data-cy="'ecoffee-title-' + ecoffee.id">
            <div class="mb1 relative">
              <avatar :avatar="ecoffee.with_employee.avatar" :size="35" :class="'br-100 absolute avatar'" />

              <span class="employee-name db">
                <inertia-link :href="ecoffee.with_employee.url" class="mb2">{{ ecoffee.with_employee.name }}</inertia-link>
              </span>
              <span v-if="ecoffee.with_employee.position" class="position f7 mt1">{{ ecoffee.with_employee.position }}</span>
            </div>
            <span class="ma0 mb2 f7 grey">
              <span class="db mb1">{{ $t('employee.e_coffee_week') }}</span>
              {{ ecoffee.ecoffee.started_at }} → {{ ecoffee.ecoffee.ended_at }}
            </span>
          </li>
        </ul>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';
import Help from '@/Shared/Help';

export default {
  components: {
    Help,
    Avatar,
    Layout,
    Breadcrumb,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    eCoffees: {
      type: Array,
      default: null,
    },
  },
};
</script>
