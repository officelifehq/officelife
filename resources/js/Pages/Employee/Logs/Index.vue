<style scoped>
.avatar {
  width: 80px;
  height: 80px;
  top: 32px;
  left: 50%;
  margin-top: -40px; /* Half the height */
  margin-left: -40px; /* Half the width */
}
</style>

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
            <inertia-link :href="'/' + $page.auth.company.id + '/employees/' + employee.id" data-cy="breadcrumb-employee">{{ employee.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_employee_logs') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 relative pt5">
          <!-- AVATAR -->
          <img :src="employee.avatar" class="avatar absolute br-100 db center" alt="avatar" loading="lazy" />

          <h2 class="tc normal mb4">
            Everything that ever happened to {{ employee.name }}
          </h2>
          <ul class="list pl0 mt0 center" data-cy="logs-list">
            <li v-for="log in logs" :key="log.id"
                class="flex items-center lh-copy pa2-l pa1 ph0-l bb b--black-10"
            >
              <div class="flex-auto">
                <!-- log author -->
                <inertia-link v-if="log.author.id" :href="'/' + $page.auth.company.id + '/employees/' + log.author.id" class="">
                  {{ log.author.name }}
                </inertia-link>
                <span v-else class="black-70">
                  {{ log.author.name }}
                </span>

                <!-- log content -->
                <span class="">
                  {{ log.localized_content }}
                </span>

                <!-- log date -->
                <span class="db f6 log_date">
                  {{ log.localized_audited_at }}
                </span>
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

export default {
  components: {
    Layout,
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
