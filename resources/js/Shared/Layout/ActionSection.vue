<template>
  <div class="cf pa3 bb bb-gray">
    <section-title>
      <template #title>
        <slot name="title"></slot>
      </template>
      <template #description>
        <slot name="description"></slot>
      </template>
    </section-title>

    <div class="fl-ns w-two-thirds-ns w-100">
      <div class="ml2">
        <slot name="content"></slot>
      </div>
    </div>
  </div>
</template>

<script>
import SectionTitle from './SectionTitle';

export default {
  components: {
    SectionTitle,
  }
};
</script>
