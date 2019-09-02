<style scoped>

</style>

<template>
  <div class="bg-white tl br2 absolute z-max">
    <div v-show="show">
      <div v-show="notifications.length == 0">
        <img class="db center mb2" srcset="/img/header/notification_blank.png,
                                    /img/header/notitication_blank@2x.png 2x"
        />
        <p class="tc">
          All is clear!
        </p>
      </div>

      <ul v-show="notifications.length > 0" class="pa0 ma0 list">
        <li v-for="notification in notifications" :key="notification.id" class="bb pa2 notification-item bb-gray">
          <span class="db mb1">{{ notification.localized_content }}</span>
          <span class="f7 dib notification-date">{{ notification.created_at | moment("from", "now") }}</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    notifications: {
      type: Array,
      default: null,
    },
    show: {
      type: Boolean,
      default: false,
    }
  },

  data() {
    return {
      showNotifications: false,
    };
  },

  created() {
    this.showNotifications = this.show;
  },

  methods: {
  }
};
</script>
