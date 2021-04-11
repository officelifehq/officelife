<style scoped>
.avatar {
  width: 80px;
  height: 80px;
  top: 32px;
  left: 50%;
  margin-top: -40px; /* Half the height */
  margin-left: -40px; /* Half the width */
}

.author-avatar {
  width: 35px;
  min-width: 35px;
  margin-right: 16px;
}

.log-item:last-child {
  border-bottom: 0;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account/teams'">{{ $t('app.breadcrumb_account_manage_teams') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_team_logs') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('audit.title') }}
          </h2>
          <ul class="list pl0 mt0 center">
            <li v-for="log in logs" :key="log.id" class="flex items-center lh-copy pa3 bb b--black-10 log-item">
              <!-- avatar -->
              <avatar :member="log.author" :size="35" :class="'author-avatar br-100'" />

              <div>
                <div class="db f7 mb2">
                  <!-- log author -->
                  <inertia-link v-if="log.author.url" :href="log.author.url">{{ log.author.name }}</inertia-link>
                  <span v-else>
                    {{ log.author.name }}
                  </span>
                </div>
                <!-- log content -->
                <div class="mb1">
                  {{ log.localized_content }}
                </div>

                <!-- log date -->
                <p class="ma0 f7 gray">
                  {{ log.localized_audited_at }}
                </p>
              </div>
            </li>
          </ul>

          <!-- Pagination -->
          <div class="center cf">
            <inertia-link v-show="paginator.previousPageUrl" class="fl dib" :href="paginator.previousPageUrl" title="Previous">
              &larr; {{ $t('app.previous') }}
            </inertia-link>
            <inertia-link v-show="paginator.nextPageUrl" class="fr dib" :href="paginator.nextPageUrl" title="Next">
              {{ $t('app.next') }} &rarr;
            </inertia-link>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Avatar,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    logs: {
      type: Array,
      default: null,
    },
    paginator: {
      type: Object,
      default: null,
    },
  },
};

</script>
