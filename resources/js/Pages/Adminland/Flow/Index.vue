<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account'"
                  :previous="$t('app.breadcrumb_account_home')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_account_manage_flows') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <!-- WHEN THERE ARE TEAMS -->
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.flows_title', { company: $page.props.auth.company.name}) }}
          </h2>
          <p class="relative adminland-headline">
            <span class="dib mb3 di-l">
              {{ $tc('account.flows_number_flows', flows.length, { company: $page.props.auth.company.name, count: flows.length}) }}
            </span>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account/flows/create'" class="btn absolute-l relative dib-l db right-0" data-cy="add-employee-button">
              {{ $t('account.flows_cta') }}
            </inertia-link>
          </p>

          <!-- LIST OF FLOWS -->
          <ul v-show="flows.length != 0" class="list pl0 mt0 center" :datacy="'employees'" :cy-items="flowsMap">
            <li
              v-for="flow in flows" :key="flow.id"
              class="flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10"
            >
              <div class="flex-auto">
                <span class="db b">
                  {{ flow.name }} <span v-if="flow.steps" class="normal f6">
                    ({{ flow.steps.count }} steps)
                  </span>
                </span>
                <ul class="f6 list pl0">
                  <li class="di pr2">
                    <inertia-link :href="'/' + $page.props.auth.company.id + '/account/flows/' + flow.id" :datacy="'lock-account-'+flow.id">
                      {{ $t('app.view') }}
                    </inertia-link>
                  </li>
                  <li class="di pr2">
                    <inertia-link :href="'/' + $page.props.auth.company.id + '/account/flows/' + flow.id + '/lock'">
                      {{ $t('app.rename') }}
                    </inertia-link>
                  </li>
                  <li class="di">
                    <inertia-link :href="'/' + $page.props.auth.company.id + '/account/flows/' + flow.id + '/destroy'">
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
          <img loading="lazy" class="db center mb4" alt="blank flow" srcset="/img/company/account/blank-flow-1x.png,
                                        /img/company/account/blank-flow-2x.png 2x"
          />
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
    flows: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    flowsMap() {
      return _.isArray(flows) ? flows.map(f => f.id): '';
    },
  }
};

</script>
