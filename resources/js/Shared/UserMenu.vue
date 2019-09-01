<style scoped>
.menu {
  border: 1px solid rgba(27,31,35,.15);
  box-shadow: 0 3px 12px rgba(27,31,35,.15);
  top: 36px;
}

.menu:after,
.menu:before {
  content: "";
  display: inline-block;
  position: absolute;
}

.menu:after {
  border: 7px solid transparent;
  border-bottom-color: #fff;
  left: auto;
  right: 10px;
  top: -14px;
}

.menu:before {
  border: 8px solid transparent;
  border-bottom-color: rgba(27,31,35,.15);
  left: auto;
  right: 9px;
  top: -16px;
}

.notifications {
  background-color: #FAE19A;
}
</style>

<template>
  <div class="relative">
    <div class="relative">
      <span class="mr2 f6 notifications pv1 ph2 br3 pointer" @click.prevent="showNotifications = true">🔥 3</span>
      <a class="no-color no-underline relative pointer" data-cy="header-menu" @click.prevent="menu = !menu">
        {{ $page.auth.user.email }} <span class="dropdown-caret"></span>
      </a>
    </div>
    <div v-if="menu == true" class="absolute menu br2 bg-white z-max tl pv2 ph3 bounceIn faster">
      <ul class="list ma0 pa0">
        <li class="pv2">
          <inertia-link :href="'/home'" class="no-color no-underline" data-cy="switch-company-button">
            {{ $t('app.header_switch_company') }}
          </inertia-link>
        </li>
        <li class="pv2">
          <inertia-link href="/logout" class="no-color no-underline" data-cy="logout-button">
            {{ $t('app.header_logout') }}
          </inertia-link>
        </li>
      </ul>
    </div>

    <notifications-component :notifications="notifications" :show="showNotifications" />
  </div>
</template>

<script>
import NotificationsComponent from '@/Shared/Notifications';

export default {
  components: {
    NotificationsComponent,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      menu: false,
      showNotifications: false,
    };
  },

  created() {
    window.addEventListener('click', this.close);
  },

  beforeDestroy() {
    window.removeEventListener('click', this.close);
  },

  methods: {
    close(e) {
      if (!this.$el.contains(e.target)) {
        this.menu = false;
      }
    },
  }
};
</script>
