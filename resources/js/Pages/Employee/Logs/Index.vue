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
  width: 34px;
  min-width: 34px;
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id" data-cy="breadcrumb-employee">{{ employee.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_employee_logs') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="relative pt5">
          <!-- AVATAR -->
          <avatar :avatar="employee.avatar" :size="80" :classes="'avatar absolute br-100 db center'" />

          <h2 class="pa3 tc normal mb4">
            {{ $t('employee.audit_log_title') }}
          </h2>

          <ul class="list pl0 mt0 mb0 center" data-cy="logs-list">
            <li v-for="log in logs" :key="log.id" class="flex items-center lh-copy pa3 bb b--black-10 log-item">
              <!-- avatar -->
              <avatar :avatar="log.author.avatar" :size="34" :classes="'author-avatar br-100'" />

              <div>
                <div class="db f7 mb2">
                  <!-- log author -->
                  <inertia-link v-if="log.author.id" :href="log.author.url">{{ log.author.name }}</inertia-link>
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
          <div class="center cf pa3">
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
    employee: {
      type: Object,
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
