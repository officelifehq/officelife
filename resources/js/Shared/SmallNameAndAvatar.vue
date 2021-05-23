<style lang="scss" scoped>
span {
  margin-left: 29px;
}

a {
  border-bottom: 0;
}
</style>

<template>
  <div v-if="avatar || url" class="relative di">
    <inertia-link v-if="url" :href="url"
                  :class="fontSize" :style="avatarMarginLeft" v-bind="$attrs"
    >
      <img v-if="avatar" loading="lazy"
           :src="avatar.normal" :srcset="srcset"
           :alt="altValue"
           :style="style" class="absolute br-100"
      />{{ name }}
    </inertia-link>
    <span v-else :class="fontSize" :style="avatarMarginLeft" v-bind="$attrs">
      <img v-if="avatar" loading="lazy"
           :src="avatar.normal" :srcset="srcset"
           :alt="altValue"
           :style="style" class="absolute br-100"
      />{{ name }}
    </span>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,

  props: {
    avatar: {
      type: Object,
      default: null,
    },
    url: {
      type: String,
      default: null,
    },
    name: {
      type: String,
      default: null,
    },
    top: {
      type: String,
      default: '-2px',
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
    },
    alt: {
      type: String,
      default: 'avatar',
    },
  },

  computed: {
    style: function () {
      return 'top:' + this.top + '; height:' + this.size + '; width:' + this.size + ';';
    },
    avatarMarginLeft: function () {
      return 'margin-left:' + this.marginBetweenNameAvatar;
    },
    srcset() {
      return this.avatar.normal + ' 1x,' + this.avatar.retina + ' 2x';
    },
    altValue() {
      return this.name ? this.name : this.alt;
    }
  }
};
</script>
