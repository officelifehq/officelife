<style scoped>
.off-days {
  color: #4b7682;
  background-color: #e6f5f9;
}
</style>

<template>
  <div class="dt-row">
    <div class="dtc bl bb bb-gray project">
    </div>

    <!-- monday -->
    <div class="tc pv2 dtc bl bb bb-gray">
      <span class="db">
        {{ days.monday.short }}
      </span>
      <span class="f7 gray">
        {{ days.monday.full }}
      </span>
    </div>
    <!-- tuesday -->
    <div class="tc pv2 dtc bl bb bb-gray">
      <span class="db">
        {{ days.tuesday.short }}
      </span>
      <span class="f7 gray">
        {{ days.tuesday.full }}
      </span>
    </div>
    <!-- wednesday -->
    <div class="tc pv2 dtc bl bb bb-gray">
      <span class="db">
        {{ days.wednesday.short }}
      </span>
      <span class="f7 gray">
        {{ days.wednesday.full }}
      </span>
    </div>
    <!-- thursday -->
    <div class="tc pv2 dtc bl bb bb-gray">
      <span class="db">
        {{ days.thursday.short }}
      </span>
      <span class="f7 gray">
        {{ days.thursday.full }}
      </span>
    </div>
    <!-- friday -->
    <div class="tc pv2 dtc bl bb bb-gray">
      <span class="db">
        {{ days.friday.short }}
      </span>
      <span class="f7 gray">
        {{ days.friday.full }}
      </span>
    </div>
    <!-- saturday -->
    <div class="tc pv2 dtc bl bb bb-gray off-days">
      <span class="db">
        {{ days.saturday.short }}
      </span>
      <span class="f7 gray">
        {{ days.saturday.full }}
      </span>
    </div>
    <!-- sunday -->
    <div class="tc pv2 bl bb bb-gray off-days">
      <span class="db">
        {{ days.sunday.short }}
      </span>
      <span class="f7 gray">
        {{ days.sunday.full }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    days: {
      type: Object,
      default: null,
    },
  },
};
</script>
