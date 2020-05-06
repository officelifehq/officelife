<style lang="scss" scoped>
span {
  margin-left: 29px;
}
</style>

<template>
  <div class="relative di">
    <img :src="avatar" class="absolute br-100" alt="avatar" :style="style" />
    <span :class="classes" class="f6" :style="avatarMarginLeft">
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
    size: {
      type: String,
      default: '22px',
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
