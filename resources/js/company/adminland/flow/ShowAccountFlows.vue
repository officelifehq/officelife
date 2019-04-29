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
            {{ $t('account.teams_title', { company: company.name}) }}
          </h2>

          <!-- LIST OF TEAMS -->
          <ul v-show="teams.length != 0" class="list pl0 mt0 center">
            <li
              v-for="team in teams" :key="team.id"
              class="flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10"
            >
              <div class="flex-auto">
                <span class="db b">{{ team.name }}</span>
                <ul class="f6 list pl0">
                  <li class="di pr2">
                    <a :href="'/' + company.id + '/teams/' + team.id">{{ $t('app.view') }}</a>
                  </li>
                  <li class="di pr2">
                    <a :href="'/' + company.id + '/teams/' + team.id + '/lock'">{{ $t('app.rename') }}</a>
                  </li>
                  <li class="di">
                    <a :href="'/' + company.id + '/teams/' + team.id + '/destroy'">{{ $t('app.delete') }}</a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>

        <!-- NO TEAMS -->
        <div v-show="teams.length == 0" class="pa3">
          <p class="tc measure center mb4 lh-copy">
            {{ $t('account.teams_blank') }}
          </p>
          <img class="db center mb4" srcset="/img/company/account/blank-team-1x.png,
                                        /img/company/account/blank-team-2x.png 2x"
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
  }
}

</script>
