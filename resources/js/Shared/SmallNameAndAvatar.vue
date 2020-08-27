<style lang="scss" scoped>
span {
  margin-left: 29px;
}
</style>

<template>
  <div class="relative di">
    <img loading="lazy" :src="avatar" class="absolute br-100" alt="avatar" :style="style" />
    <inertia-link v-if="url" :href="url" :class="classes + ' ' + fontSize" :style="avatarMarginLeft">{{ name }}</inertia-link>
    <span v-else :class="classes + ' ' + fontSize" :style="avatarMarginLeft">
      {{ name }}
    </span>
  </div>
</template>

<script>
export default {
  props: {
    name: {
      type: String,
      default: null,
    },
    avatar: {
      type: String,
      default: null,
    },
    classes: {
      type: String,
      default: null,
    },
    top: {
      type: String,
      default: '-2px',
    },
    url: {
      type: String,
      default: null,
    },
    size: {
      type: String,
      default: '22px',
    },
    fontSize: {
      type: String,
      default: 'f6',
    },
    marginBetweenNameAvatar: {
      type: String,
      default: '29px',
    }
  },

  computed: {
    style: function () {
      return 'top:' + this.top + '; height:' + this.size + '; width:' + this.size + ';';
    },

    avatarMarginLeft: function () {
      return 'margin-left:' + this.marginBetweenNameAvatar;
    }
  }
};
</script>
