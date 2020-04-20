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
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/account/flows'">{{ $t('app.breadcrumb_account_manage_flows') }}</inertia-link>
          </li>
          <li class="di">
            View a flow
          </li>
        </ul>
      </div>

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

export default {
  components: {
    Layout,
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
