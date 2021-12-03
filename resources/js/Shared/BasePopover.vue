<style lang="scss" scoped>
.base-popover {
  position: relative;
  z-index: 50;
  top: 13px;

 &__overlay {
    position: absolute;
    top: 13px;
    left: 0;
    z-index: 40;
    width: calc(100vw - 1rem);
    height: 100vh;
  }
}
</style>

<template>
  <div>
    <div ref="basePopoverContent" class="base-popover">
      <slot></slot>
    </div>

    <div ref="basePopoverOverlay" class="base-popover__overlay" @click.stop="destroyPopover"></div>
  </div>
</template>

<script>
import Popper from 'popper.js';

export default {
  props: {
    popoverOptions: {
      type: Object,
      required: true
    }
  },

  emits: [
    'close-popover'
  ],

  data() {
    return {
      popperInstance: null
    };
  },

  mounted() {
    this.initPopper();
    this.updateOverlayPosition();
  },

  methods: {
    initPopper() {
      const modifiers = {};
      const { popoverReference, offset, placement } = this.popoverOptions;

      if (offset) {
        modifiers.offset = {
          offset
        };
      }

      if (placement) {
        modifiers.placement = placement;
      }

      this.popperInstance = new Popper(
        popoverReference,
        this.$refs.basePopoverContent,
        {
          placement,
          modifiers: {
            modifiers
          }
        }
      );
    },

    destroyPopover() {
      if (this.popperInstance) {
        this.popperInstance.destroy();
        this.popperInstance = null;
        this.$emit('close-popover');
      }
    },

    updateOverlayPosition() {
      const overlayElement = this.$refs.basePopoverOverlay;
      const overlayPosition = overlayElement.getBoundingClientRect();

      overlayElement.style.transform = `translate(-${overlayPosition.x}px, -${
        overlayPosition.y
      }px)`;
    }
  }
};
</script>
