<style lang="scss" scoped>
.notification-item:last-child {
  border-bottom: 0;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb :with-box="true" :has-more="false"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
      >
        {{ $t('app.breadcrumb_notification_list') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 mt2">
          <!-- notifications list -->
          <ul v-show="allNotifications.length > 0" class="list pl0 mt0 mb0 center">
            <li
              v-for="notification in allNotifications" :key="notification.id"
              class="flex lh-copy pa3-l pa1 ph0-l pv0-ns pv2 bb b--black-10 notification-item"
            >
              <div class="pl3">
                {{ notification.localized_content }}
                <span class="db f6">{{ notification.created_at }}</span>
              </div>
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
    notifications: {
      type: Array,
      default: null,
    },
    allNotifications: {
      type: Array,
      default: null,
    },
  },
};

</script>
