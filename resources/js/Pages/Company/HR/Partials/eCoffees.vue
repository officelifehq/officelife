<style lang="scss" scoped>
.progress-bar {
	border-radius: 3px;
	overflow: hidden;
  width: 100%;

	span {
		display: block;
	}

}

.bar {
  background: rgba(0,0,0,0.075);
  width: 100%
}

.progress {
  animation: loader 8s cubic-bezier(.45,.05,.55,.95) forwards;
  background: #75b800;
  color: #fff;
  padding: 5px;
}
</style>

<template>
  <div v-if="eCoffees.active_session" class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        ☕️
      </span> {{ $t('company.hr_e_coffees_title') }}
    </span>

    <div class="br3 bg-white box z-1 pa3 relative">
      <!-- current session -->
      <div class="mb4">
        <div class="mb2">
          {{ $t('company.hr_e_coffees_current_session') }}
        </div>
        <div class="progress-bar mb2">
          <span class="bar">
            <span class="progress" :style="'width: ' + eCoffees.active_session.percent + '%;'"></span>
          </span>
        </div>
        <div class="mb2 f7 gray lh-copy">
          {{ $t('company.hr_e_coffees_current_session_number', { happened: eCoffees.active_session.happened, total: eCoffees.active_session.total }) }}
        </div>
      </div>

      <div class="cf">
        <!-- past session -->
        <div class="fl w-50 pr3">
          <div class="mb2 gray fw3">
            {{ $t('company.hr_e_coffees_previous_session') }}
          </div>
          <div class="f3 mb1">
            {{ eCoffees.last_active_session.percent }}%
          </div>
          <div class="f7 gray">
            {{ $t('company.hr_e_coffees_previous_session_stat') }}
          </div>
        </div>

        <!-- all sessions -->
        <div class="fl w-50">
          <div class="mb2 gray fw3">
            {{ $t('company.hr_e_coffees_overall') }}
          </div>
          <div class="f3 mb1">
            {{ eCoffees.average_total_sessions }}%
          </div>
          <div class="f7 gray lh-copy">
            {{ $t('company.hr_e_coffees_overall_stat') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    eCoffees: {
      type: Object,
      default: null,
    },
  },
};
</script>
