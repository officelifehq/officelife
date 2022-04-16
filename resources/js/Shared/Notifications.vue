<style lang="scss" scoped>
.notifications {
  background-color: #f4f3fb;

  &.more {
    background-color: #fde19d;
  }
}

.content-menu {
  left: auto;
  width: 240px;
  box-shadow: 0 1px 15px rgba(27,31,35,.15);
  right: 12px;
  top: 17px;
  max-height: 380px;
}
</style>

<template>
  <div class="relative">
    <div class="di" data-cy="notification-counter" @click="markRead()">
      <span v-if="notifications" class="mr2 f6 notifications pv1 ph2 br3 pointer" :class="{'more':(numberOfNotifications > 0)}">
        <span class="mr1">
          🔥
        </span> <span data-cy="number-notifications">
          {{ numberOfNotifications }}
        </span>
      </span>
      <span v-else class="mr2 f6 notifications pv1 ph2 br3 pointer">
        <span class="mr1">
          🔥
        </span> <span data-cy="number-notifications">
          0
        </span>
      </span>
    </div>

    <!-- content modal -->
    <ul v-if="showMenu" v-click-outside="toggleModal" class="popupmenu absolute overflow-auto right-0 content-menu list z-999 bg-white ba bb-gray br3 pa0">
      <li v-for="notification in notifications" :key="notification.id" class="pv2 ph3 bb bb-gray lh-copy" data-cy="notification-modal-content">
        {{ notification.localized_content }}
      </li>
      <li v-show="notifications == 0" class="pv2 ph3 bb bb-gray lh-copy">{{ $t('app.notification_blank_state') }} 🎉</li>
      <li class="pv2 ph3 f6 tc">
        <inertia-link :href="'/' + $page.props.auth.company.id + '/notifications'">{{ $t('app.notification_view_all') }}</inertia-link>
      </li>
    </ul>
  </div>
</template>

<script>
import vClickOutside from 'click-outside-vue3';

export default {
  directives: {
    clickOutside: vClickOutside.directive
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      showMenu: false,
      numberOfNotifications: 0,
    };
  },

  created() {
    if (this.notifications) {
      this.numberOfNotifications = this.notifications.length;
    }
  },

  methods: {
    toggleModal() {
      this.showMenu = false;
    },

    markRead() {
      this.showMenu = true;

      axios.post('/' + this.$page.props.auth.company.id + '/notifications/read')
        .then(response => {
          this.numberOfNotifications = 0;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    }
  }
};
</script>
