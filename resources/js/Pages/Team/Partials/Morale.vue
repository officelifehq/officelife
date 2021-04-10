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
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5">
      🍻 Morale
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <div class="fl w-third pa2">
        <p>{{ morale.yesterday.percent }}</p>
        <div class="progress-bar mb2">
          <span class="bar">
            <span class="progress" :style="'width: 34%;'"></span>
          </span>
        </div>
      </div>
      <div class="fl w-third pa2">
        <p>{{ morale.last_week.percent }}</p>
        <div class="progress-bar mb2">
          <span class="bar">
            <span class="progress" :style="'width: 100%;'"></span>
          </span>
        </div>
      </div>
      <div class="fl w-third pa2">
        <p>{{ morale.last_month.percent }}</p>
        <div class="progress-bar mb2">
          <span class="bar">
            <span class="progress" :style="'width: 100%;'"></span>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  components: {
  },

  props: {
    morale: {
      type: Array,
      default: () => [],
    },
  },
};
</script>
