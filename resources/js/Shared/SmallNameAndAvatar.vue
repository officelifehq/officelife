<style lang="scss" scoped>
span {
  margin-left: 29px;
}
</style>

<template>
  <div v-if="member" class="relative di">
    <inertia-link v-if="member.url" :href="member.url"
                  :class="fontSize" :style="avatarMarginLeft" v-bind="$attrs"
    >
      <img v-if="member.avatar" loading="lazy"
           :src="member.avatar.normal" :srcset="srcset"
           :alt="altValue"
           :style="style" class="absolute br-100"
      />{{ member.name }}
    </inertia-link>
    <span v-else :class="fontSize" :style="avatarMarginLeft" v-bind="$attrs">
      <img v-if="member.avatar" loading="lazy"
           :src="member.avatar.normal" :srcset="srcset"
           :alt="altValue"
           :style="style" class="absolute br-100"
      />{{ member.name }}
    </span>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,

  props: {
    member: {
      type: Object,
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
      return this.member.avatar.normal + ' 1x,' + this.member.avatar.retina + ' 2x';
    },
    altValue() {
      return this.member.name ? this.member.name : this.alt;
    }
  }
};
</script>
