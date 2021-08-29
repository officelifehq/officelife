<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/flows'"
                  :previous="$t('app.breadcrumb_account_manage_flows')"
      >
        View a flow
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ flow.name }}
          </h2>
          <p class="relative">
            This flow is about {{ $t('account.flow_new_type_' + flow.type) }} and has {{ flow.steps.count }} steps.
          </p>

          <!-- List of steps happening before -->
          <ul>
            <li v-for="step in flow.steps.data" v-show="step.modifier == 'before'" :key="step.id">
              {{ step.number }} {{ step.unit_of_time }} before {{ flow.type }}
              <ul>
                <li v-for="action in step.actions.data" :key="action.id">
                  {{ action.type }} for {{ action.recipient }}
                </li>
              </ul>
            </li>
          </ul>

          <!-- List of steps happening on the day of the event -->
          <ul>
            <li v-for="step in flow.steps.data" v-show="step.modifier == 'same_day'" :key="step.id">
              On {{ flow.type }}
              <ul>
                <li v-for="action in step.actions.data" :key="action.id">
                  {{ action.type }} for {{ action.recipient }}
                </li>
              </ul>
            </li>
          </ul>

          <!-- List of steps happening after -->
          <ul>
            <li v-for="step in flow.steps.data" v-show="step.modifier == 'after'" :key="step.id">
              {{ step.number }} {{ step.unit_of_time }} after {{ flow.type }}
              <ul>
                <li v-for="action in step.actions.data" :key="action.id">
                  {{ action.type }} for {{ action.recipient }}
                </li>
              </ul>
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

export default {
  components: {
    Layout,
    Breadcrumb,
  },

  props: {
    flow: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },
};

</script>
