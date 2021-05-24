<style lang="scss" scoped>
.link {
  border-bottom: 0;
  text-decoration: none;
}
</style>


<template>
  <div v-if="avatar" v-bind="$attrs" class="di">
    <inertia-link v-if="url" :href="url" class="link">
      <img :loading="loading" :width="size" :height="size"
           :src="avatar.normal" :srcset="srcset"
           :alt="altValue"
           v-bind="$attrs"
      />
    </inertia-link>
    <img v-else :loading="loading" :width="size" :height="size"
         :src="avatar.normal" :srcset="srcset"
         :alt="altValue"
         v-bind="$attrs"
    />
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
    srcset() {
      return this.avatar.normal + ' 1x,' + this.avatar.retina + ' 2x';
    },
    altValue() {
      return this.name ? this.name : this.alt;
    }
  }
};
</script>
