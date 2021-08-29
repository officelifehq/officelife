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
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account'"
                  :previous="$t('app.breadcrumb_account_home')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_account_audit_logs') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('audit.title') }}
          </h2>
          <ul class="list pl0 mt0 center">
            <li v-for="log in logs" :key="log.id" class="flex items-center lh-copy pa3 bb b--black-10 log-item">
              <!-- avatar -->
              <avatar v-if="log.author.avatar" :avatar="log.author.avatar" :size="35" :class="'author-avatar br-100'" />

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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
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
