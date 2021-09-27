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

.gender-item:last-child {
  margin-bottom: 0;
}
</style>

<template>
  <div class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        👫
      </span> {{ $t('company.hr_genders_title') }}
    </span>

    <div class="br3 bg-white box z-1 pa3 relative">
      <!-- genders -->
      <div v-for="gender in genders" :key="gender.id" class="gender-item mb3">
        <div class="flex justify-between mb2">
          <span class="fw5">
            {{ gender.label }}
          </span>
          <span class="gray f6">
            {{ gender.percent }}%
          </span>
        </div>
        <div class="progress-bar mb2">
          <span class="bar">
            <span class="progress" :style="'width: ' + gender.percent + '%;'"></span>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    genders: {
      type: Object,
      default: null,
    },
  },
};
</script>
