<style lang="scss" scoped>
.actions-dots {
  top: 24px;
  right: 13px;
}

.action-menu {
  right: 7px;
  top: 38px;
}

.icon-delete {
  top: 2px;
}
</style>

<template>
  <div>
    <!-- Image to trigger actions -->
    <img loading="lazy" src="/img/common/triple-dots.svg" alt="triple dot symbol" class="absolute right-0 pointer actions-dots" @click="showModal = true" />

    <!-- Actions available -->
    <div v-if="showModal" v-click-outside="toggleModal" class="popupmenu action-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal">
      <ul class="list ma0 pa0">
        <li v-show="!deleteActionConfirmation" class="pv2 relative">
          <icon-delete :class="'icon-delete relative'" :width="15" :height="15" />
          <a class="pointer ml1 c-delete" @click.prevent="deleteActionConfirmation = true">
            {{ $t('account.flow_new_action_remove') }}
          </a>
        </li>
        <li v-show="deleteActionConfirmation" class="pv2">
          {{ $t('app.sure') }}
          <a class="c-delete mr1 pointer" @click.prevent="destroyAction">
            {{ $t('app.yes') }}
          </a>
          <a class="pointer" @click.prevent="deleteActionConfirmation = false">
            {{ $t('app.no') }}
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import vClickOutside from 'v-click-outside';
import IconDelete from '@/Shared/IconDelete';

export default {
  components: {
    IconDelete,
  },

  directives: {
    clickOutside: vClickOutside.directive
  },

  emits: [
    'destroy'
  ],

  data() {
    return {
      showModal: false,
      deleteActionConfirmation: false,
    };
  },

  methods: {
    toggleModal() {
      this.showModal = false;
    },

    destroyAction() {
      this.$emit('destroy');
    },
  }
};

</script>
