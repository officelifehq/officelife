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
            <inertia-link :href="'/' + $page.auth.company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_flows') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <!-- WHEN THERE ARE TEAMS -->
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.flows_title', { company: $page.auth.company.name}) }}
          </h2>
          <p class="relative adminland-headline">
            <span class="dib mb3 di-l">
              {{ $tc('account.flows_number_flows', flows.length, { company: $page.auth.company.name, count: flows.length}) }}
            </span>
            <inertia-link :href="'/' + $page.auth.company.id + '/account/flows/create'" class="btn absolute-l relative dib-l db right-0" data-cy="add-employee-button">
              {{ $t('account.flows_cta') }}
            </inertia-link>
          </p>

          <!-- LIST OF FLOWS -->
          <ul v-show="flows.length != 0" class="list pl0 mt0 center">
            <li
              v-for="flow in flows" :key="flow.id"
              class="flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10"
            >
              <div class="flex-auto">
                <span class="db b">
                  {{ flow.name }} <span class="normal f6">
                    ({{ flow.steps.count }} steps)
                  </span>
                </span>
                <ul class="f6 list pl0">
                  <li class="di pr2">
                    <inertia-link :href="'/' + $page.auth.company.id + '/account/flows/' + flow.id">
                      {{ $t('app.view') }}
                    </inertia-link>
                  </li>
                  <li class="di pr2">
                    <inertia-link :href="'/' + $page.auth.company.id + '/account/flows/' + flow.id + '/lock'">
                      {{ $t('app.rename') }}
                    </inertia-link>
                  </li>
                  <li class="di">
                    <inertia-link :href="'/' + $page.auth.company.id + '/account/flows/' + flow.id + '/destroy'">
                      {{ $t('app.delete') }}
                    </inertia-link>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>

        <!-- NO flows -->
        <div v-show="flows.length == 0" class="pa3">
          <p class="tc measure center mb4 lh-copy">
            {{ $t('account.flows_blank') }}
          </p>
          <img class="db center mb4" alt="blank flow" srcset="/img/company/account/blank-flow-1x.png,
                                        /img/company/account/blank-flow-2x.png 2x"
          />
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
    flows: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
