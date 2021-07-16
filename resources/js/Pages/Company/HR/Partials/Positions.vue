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
  <div class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        💂‍♀
      </span> Positions in the company
    </span>

    <div class="br3 bg-white box z-1 pa3 relative">
      <div v-for="position in positions" :key="position.id" class="mb3">
        <div class="flex justify-between mb2">
          <span class="fw5">
            {{ position.title }}
          </span>
          <span class="gray f6">
            {{ position.percent }}%
          </span>
        </div>
        <div class="progress-bar mb2">
          <span class="bar">
            <span class="progress" :style="'width: ' + position.percent + '%;'"></span>
          </span>
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
