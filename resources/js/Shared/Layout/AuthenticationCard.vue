<template>
  <div class="ph2 ph0-ns">
    <div class="cf mt6 mw6 center br3 mb2 bg-white box pa3">
      <div class="w-100 relative">
        <slot name="logo"></slot>
      </div>

      <div class="">
        <slot></slot>
      </div>
    </div>

    <div class="tc">
      <slot name="footer"></slot>
    </div>

    <toaster />
  </div>
</template>

<script>
import Toaster from '@/Shared/Toaster';

export default {
  components: {
    Toaster,
  },
};
</script>
