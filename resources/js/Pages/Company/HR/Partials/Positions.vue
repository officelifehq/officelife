<style lang="scss" scoped>
.progress-bar {
	border-radius: 3px;
	overflow: hidden;
  width: 100%;

	span {
		display: block;
	}
}

.position-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.position-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
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
  <div class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        💂‍♀
      </span> {{ $t('company.hr_positions_title') }}
    </span>

    <div class="br3 bg-white box z-1 relative">
      <div v-for="position in positions" :key="position.id" class="relative position-item pa3 bb bb-gray bb-gray-hover">
        <div class="flex justify-between">
          <span class="fw5">
            {{ position.title }}
          </span>
          <inertia-link :href="position.url" class="gray f6">
            {{ $tc('company.hr_positions_stat', position.number_of_employees, {number: position.number_of_employees}) }} ({{ position.percent }}%)
          </inertia-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    positions: {
      type: Object,
      default: null,
    },
  },
};
</script>
