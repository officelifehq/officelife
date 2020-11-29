<style scoped>
.dummy {
  right: 40px;
  bottom: 20px;
}

.grid {
  display: grid;
  grid-template-columns: 3fr repeat(7, 1fr);
  align-items: center;
}

.project {
  width: 280px;
  max-width: 400px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ $page.props.auth.company.name }}
        </h2>
      </div>

      <dashboard-menu :employee="employee" />

      <div class="cf mw8 center br3 mb3 bg-white box pa3 relative">
        <p class="mt0 mb2 lh-copy f6">{{ $t('dashboard.manager_expense_description') }}</p>

        <div class="dt bt br bb-gray">
          <!-- header -->
          <div class="dt-row">
            <div class="dtc bl bb bb-gray project">
            </div>
            <!-- monday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                Mon
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- tuesday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                T
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- wednesday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                W
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- thursday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                T
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- friday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                T
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- saturday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                S
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- sunday -->
            <div class="tc pv2 bl bb bb-gray">
              <span class="db">
                S
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
          </div>

          <!-- log new time -->
          <div class="dt-row">
            <div class="ph2 dtc bl bb bb-gray project relative v-mid">
              <span class="db">
                Dunder Mifflin Infinity
              </span>
              <span>Management of the timeline</span>
              <span class="absolute f7 fw5 right-1">
                37h30
              </span>
            </div>
            <!-- monday -->
            <div class="ph2 pv1 dtc bl bb bb-gray">
              <text-duration />
            </div>
            <!-- tuesday -->
            <div class="ph2 pv1 dtc bl bb bb-gray">
              <text-duration />
            </div>
            <!-- wednesday -->
            <div class="ph2 pv1 dtc bl bb bb-gray">
              <text-duration />
            </div>
            <!-- thursday -->
            <div class="ph2 pv1 dtc bl bb bb-gray">
              <text-duration />
            </div>
            <!-- friday -->
            <div class="ph2 pv1 dtc bl bb bb-gray">
              <text-duration />
            </div>
            <!-- saturday -->
            <div class="ph2 pv1 dtc bl bb bb-gray">
              <text-duration />
            </div>
            <!-- sunday -->
            <div class="ph2 pv1 dtc bl bb bb-gray">
              <text-duration />
            </div>
          </div>
        </div>

        <!-- add a new entry -->
        <div class="">
          add a new row
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';
import TextDuration from '@/Shared/TextDuration';

export default {
  components: {
    Layout,
    DashboardMenu,
    TextDuration,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    timesheet: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      form: {
        title: null,
        content: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');

      localStorage.removeItem('success');
    }
  },
};
</script>
