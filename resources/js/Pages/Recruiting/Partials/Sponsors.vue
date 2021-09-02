<style lang="scss" scoped>
.icon {
  color: #6a73a4;
  width: 15px;
  top: 2px;
}

.avatar {
  width: 35px;
}

.name {
  padding-left: 44px;
}
</style>

<template>
  <div v-if="sponsors" class="mr5">
    <div class="db relative mb2">
      <svg class="icon mr1 relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
      </svg>
      <span class="f7 gray">
        {{ $t('dashboard.job_opening_show_sponsor') }}
      </span>
    </div>

    <div class="flex items-start">
      <div v-for="sponsor in sponsors" :key="sponsor.id" class="mr3 relative">
        <avatar :avatar="sponsor.avatar" :size="35" :class="'br-100 absolute avatar'" />

        <div class="name">
          <span class="db ma0">
            <inertia-link :href="sponsor.url">{{ sponsor.name }}</inertia-link>
          </span>
          <span v-if="sponsor.position" class="fw3 gray f7">
            {{ sponsor.position.title }}
          </span>
          <span v-else class="fw3 gray f7">
            {{ $t('app.no_position_defined') }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Avatar,
  },

  props: {
    sponsors: {
      type: Object,
      default: null,
    },
  },
};

</script>
