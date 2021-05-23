<style lang="scss" scoped>
.progress-bar {
	overflow: hidden;
  width: 100%;

	span {
		display: block;
	}

  &.left {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-left-radius: 11px;
    border-bottom-right-radius: 0;
  }

  &.center {
    border-radius: 0;
  }

  &.right {
    border-top-right-radius: 0;
    border-top-left-radius: 0;
    border-bottom-right-radius: 11px;
    border-bottom-left-radius: 0;
  }
}

.bar {
  background: rgba(0,0,0,0.075);
  width: 100%
}

.progress {
  animation: loader 8s cubic-bezier(.45,.05,.55,.95) forwards;
  color: #fff;
  padding: 5px;

  &.happy {
    background: #75b800;
  }
  &.sad {
    background: #df5721;
  }
  &.average {
    background: #c4d3c0;
  }
  &.none {
    background: #5c5c5c;
  }
}

.number {
  padding: 12px 6px;

  &.no-data {
    padding: 12px;
  }

  &.happy {
    border-color: #75b800;
  }
  &.sad {
    border-color: #df5721;
  }
  &.average {
    border-color: #c4d3c0;
  }
}
</style>

<template>
  <div class="mb4">
    <div class="cf mw7 center mb2 fw5">
      <span class="mr1">
        🍻
      </span> {{ $t('team.morale_title') }}

      <help :url="$page.props.help_links.team_morale" :top="'2px'" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <!-- yesterday -->
      <div class="fl w-third br bb-gray">
        <div class="flex justify-between pa3">
          <p class="ma0 fw5">Yesterday <span class="db fw4 f7 gray mt1">{{ $t('team.morale_on_average') }}</span></p>
          <p v-if="morale.yesterday.percent" :class="morale.yesterday.emotion" class="ma0 number ba br-100">{{ morale.yesterday.percent }}%</p>
          <p v-else class="ma0 number no-data ba br-100 bb-gray">🚫</p>
        </div>
        <div class="progress-bar left">
          <span class="bar">
            <span :class="morale.yesterday.emotion" :style="'width: ' + morale.yesterday.percent + '%;'" class="progress"></span>
          </span>
        </div>
      </div>

      <!-- last week -->
      <div class="fl w-third br bb-gray">
        <div class="flex justify-between pa3">
          <p class="ma0 fw5">Last week <span class="db fw4 f7 gray mt1">{{ $t('team.morale_on_average') }}</span></p>
          <p v-if="morale.last_week.percent" :class="morale.last_week.emotion" class="ma0 number ba br-100">{{ morale.last_week.percent }}%</p>
          <p v-else class="ma0 number no-data ba br-100 bb-gray">🚫</p>
        </div>
        <div class="progress-bar center">
          <span class="bar">
            <span :class="morale.last_week.emotion" :style="'width: ' + morale.last_week.percent + '%;'" class="progress"></span>
          </span>
        </div>
      </div>

      <!-- last month -->
      <div class="fl w-third">
        <div class="flex justify-between pa3">
          <p class="ma0 fw5">Last month <span class="db fw4 f7 gray mt1">{{ $t('team.morale_on_average') }}</span></p>
          <p v-if="morale.last_month.percent" :class="morale.last_month.emotion" class="ma0 number ba br-100">{{ morale.last_month.percent }}%</p>
          <p v-else class="ma0 number no-data ba br-100 bb-gray">🚫</p>
        </div>
        <div class="progress-bar right">
          <span class="bar">
            <span :class="morale.last_month.emotion" :style="'width: ' + morale.last_month.percent + '%;'" class="progress"></span>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';

export default {
  components: {
    Help,
  },

  props: {
    morale: {
      type: Array,
      default: () => [],
    },
  },
};
</script>
