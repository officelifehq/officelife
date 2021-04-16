<template>
  <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
    <div class="ph4 pv3">
      <div class="f4 lh-copy">
        <slot name="title">
        </slot>
      </div>

      <div class="mt4">
        <slot name="content">
        </slot>
      </div>
    </div>

    <div class="ph4 pv3 bg-black-10 tr">
      <slot name="footer">
      </slot>
    </div>
  </modal>
</template>

<script>
import Modal from './Modal';

export default {

  components: {
    Modal,
  },

  props: {
    show: {
      type: Boolean,
      default: false
    },
    maxWidth: {
      type: String,
      default: 'xl'
    },
    closeable: {
      type: Boolean,
      default: true
    },
  },
  emits: ['close'],

  methods: {
    close() {
      this.$emit('close');
    },
  }
};
</script>
