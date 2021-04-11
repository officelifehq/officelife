<style lang="scss" scoped>
.link {
  border-bottom: 0;
  text-decoration: none;
}
</style>


<template>
  <div v-if="member && member.avatar" v-bind="$attrs" class="di">
    <inertia-link v-if="url" :href="url" class="link">
      <img :loading="loading" :width="size" :height="size"
           :src="member.avatar.normal" :srcset="srcset"
           :alt="altValue"
           v-bind="$attrs"
      />
    </inertia-link>
    <img v-else :loading="loading" :width="size" :height="size"
         :src="member.avatar.normal" :srcset="srcset"
         :alt="altValue"
         v-bind="$attrs"
    />
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
    size: {
      type: Number,
      default: 0,
    },
    loading: {
      type: String,
      default: 'lazy',
    },
    alt: {
      type: String,
      default: 'avatar',
    }
  },

  computed: {
    url() {
      return typeof this.member.url === 'string' ? this.member.url : '';
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
