<style scoped>
</style>

<template>
  <layout title="Home" :user="user">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a>
          </li>
          <li class="di">
            <a :href="'/' + company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</a>
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
            {{ $t('account.flows_title', { company: company.name}) }}
          </h2>
          <p class="relative">
            <span class="dib mb3 di-l">{{ $tc('account.flows_number_flows', flows.length, { company: company.name, count: flows.length}) }}</span>
            <a :href="'/' + company.id + '/account/flows/create'" class="btn primary absolute-l relative dib-l db right-0" data-cy="add-employee-button">{{ $t('account.flows_cta') }}</a>
          </p>

          <!-- LIST OF TEAMS -->
          <ul v-show="flows.length != 0" class="list pl0 mt0 center">
            <li
              v-for="flow in flows" :key="flow.id"
              class="flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10"
            >
              <div class="flex-auto">
                <span class="db b">{{ flow.name }} <span class="normal f6">({{ flow.steps.count }} steps)</span></span>
                <ul class="f6 list pl0">
                  <li class="di pr2">
                    <a :href="'/' + company.id + '/flows/' + flow.id">{{ $t('app.view') }}</a>
                  </li>
                  <li class="di pr2">
                    <a :href="'/' + company.id + '/flows/' + flow.id + '/lock'">{{ $t('app.rename') }}</a>
                  </li>
                  <li class="di">
                    <a :href="'/' + company.id + '/flows/' + flow.id + '/destroy'">{{ $t('app.delete') }}</a>
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
          <img class="db center mb4" srcset="/img/company/account/blank-flow-1x.png,
                                        /img/company/account/blank-flow-2x.png 2x"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>

export default {
  props: {
    company: {
      type: Object,
      default: null,
    },
    flows: {
      type: Array,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      this.$snotify.success(localStorage.success, {
        timeout: 2000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true,
      })
      localStorage.clear()
    }
  },
}

</script>
