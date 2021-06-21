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
      <form @submit.prevent="$emit('submitted')">
        <div class="mb2">
          <slot name="form"></slot>
        </div>

        <div v-if="hasActions" class="mt3 flex-ns justify-between">
          <slot name="actions"></slot>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import SectionTitle from './SectionTitle';

export default {

  components: {
    SectionTitle,
  },
  emits: ['submitted'],

  computed: {
    hasActions() {
      return !! this.$slots.actions;
    }
  }
};
</script>
