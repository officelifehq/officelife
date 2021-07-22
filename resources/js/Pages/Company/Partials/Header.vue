<style lang="scss" scoped>
.company-logo {
  width: 70px;
}
</style>

<template>
  <div class="ph2 ph5-ns mb5 bb bb-gray pv3">
    <div class="flex-ns justify-between items-center">
      <div class="flex items-center">
        <!-- logo  -->
        <div v-if="statistics.logo" class="company-logo relative ba bb-gray br3 bg-white mr3-ns">
          <img :src="statistics.logo" alt="logo" />
        </div>

        <!-- company name  -->
        <h2 class="ma0 fw4 f3">
          {{ $page.props.auth.company.name }}
        </h2>
      </div>

      <div class="flex pa0-ns pa3">
        <div>
          <p class="ttu f7 gray mb1 mt0 mr5">{{ $t('company.stat_number_of_employees') }}</p>
          <p class="mt0 mb0">{{ statistics.number_of_employees }}</p>
        </div>
        <div>
          <p class="ttu f7 gray mb1 mt0 mr5">{{ $t('company.stat_number_of_teams') }}</p>
          <p class="mt0 mb0">{{ statistics.number_of_teams }}</p>
        </div>
        <div v-if="statistics.founded_at" class="">
          <p class="ttu f7 gray mb1 mt0 mr5">{{ $t('company.stat_founded_at') }}</p>
          <p class="mt0 mb0">{{ statistics.founded_at }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    statistics: {
      type: Object,
      default: null,
    },
  },
};
</script>
