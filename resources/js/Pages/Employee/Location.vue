<style lang="scss" scoped>
.map {
  background-image: url('https://locationiq.com/docs-html/images/examplestaticmap.png');
  background-repeat: no-repeat;
  background-position: right center;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      📍 Location
    </span>

    <div v-if="employee.address" class="br3 bg-white box z-1 pa3">
      <p class="mt0">
        Lives in {{ employee.address.partial }}.
      </p>
      <p class="">
        Local time: 9:23am. Clear night.
      </p>
      <span class="f7 silver">
        Only you and HR can view your complete address.
      </span>
    </div>

    <div v-else class="br3 bg-white box z-1 pa3">
      <p class="mb0 mt0 lh-copy mb0 f6">
        There is no address defined in this profile.
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    employee: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
    };
  },

  methods: {
  }
};
</script>
