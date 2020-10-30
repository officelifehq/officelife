<style lang="scss" scoped>
.grey {
  color: #6e6e71;
}

.range {
  display: block;
  height: 5px;
  width: 100%;
  border-top: 1px solid #e8e8e8;
  border-left: 1px solid #e8e8e8;
  border-right: 1px solid #e8e8e8;
}

.days-left {
  float: right;
}

.progress {
  background-color: #edf2f7;
  border-radius: 3px;

  .inside {
    background-color: #CAD5E1;
    border-top-left-radius: 3px;
    border-bottom-left-radius: 3px;
    height: 16px;
  }

  .holiday {
    background-color: #68D391;
    height: 16px;
    top: 0;
  }
}

.date {
  color: #999999;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        🌴
      </span> Holidays
    </span>
    <img v-show="$page.props.auth.employee.permission_level <= 200" loading="lazy" src="/img/plus_button.svg" class="box-plus-button absolute br-100 pa2 bg-white pointer" data-cy="add-holiday-button"
         width="22"
         height="22" alt="add button"
         @click.prevent="toggleModals()"
    />

    <div class="br3 bg-white box z-1 pa3">
      <!-- Available balance -->
      <div class="flex justify-between mb4 mt3">
        <div class="w-50 f4 fw3">
          Available balance
        </div>
        <div class="w-50 tr f3">
          {{ employee.holidays.current_balance_round }} days
        </div>
      </div>

      <!-- Number of holidays total in year -->
      <p class="f7 grey tc mb1">
        {{ employee.holidays.amount_of_allowed_holidays }} days of holidays earned in a year
      </p>
      <div class="range mb1"></div>

      <!-- Days left to earn -->
      <div class="cf">
        <div class="fl" :style="'width: ' + employee.holidays.percent_year_completion_rate + '%'">
          &nbsp;
        </div>
        <div class="fl" :style="'width: ' + employee.holidays.reverse_percent_year_completion_rate + '%'">
          <p class="f7 grey tc mb1 mt1">
            {{ employee.holidays.number_holidays_left_to_earn_this_year }} days left to earn
          </p>
          <div class="range mb1"></div>
        </div>
      </div>

      <!-- Progress bar -->
      <div class="progress relative">
        <div class="inside" :style="'width: ' + employee.holidays.percent_year_completion_rate + '%'"></div>
        <div class="holiday absolute" style="width: 2%; left: 90%"></div>
        <div class="holiday absolute" style="width: 4%; left: 12%"></div>
      </div>
      <div class="flex justify-between mb4">
        <div class="w-50 f7 date mt2">
          Jan 1
        </div>
        <div class="w-50 f7 date mt2 tr">
          Dec 31
        </div>
      </div>

      <!-- Holidays statistics -->
      <div class="flex items-start-ns flex-wrap flex-nowrap-ns">
        <div class="mb1 w-25-ns w-50 mr4-ns">
          <p class="db mb2 mt0 f3 fw3">
            25 days
          </p>
          <p class="f7 mt0 fw3 grey lh-copy">
            Days taken so far this year
          </p>
        </div>
        <div class="mb1 w-25-ns w-50 mr4-ns">
          <p class="db mb2 mt0 f3">
            {{ employee.holidays.holidays_earned_each_month }} days
          </p>
          <p class="f7 mt0 fw3 grey lh-copy">
            New holidays earned each month
          </p>
        </div>
        <div class="mb1 w-25-ns w-50 mr4-ns">
          <p class="db mb2 mt0 f3 fw3">
            25 days
          </p>
          <p class="f7 mt0 fw3 grey lh-copy">
            Last taken holidays
          </p>
        </div>
        <div class="mb1 w-25-ns w-50">
          <p class="db mb2 mt0 f3 fw3">
            25 days
          </p>
          <p class="f7 mt0 fw3 grey lh-copy">
            Estimated balance at the end of the year
          </p>
        </div>
      </div>
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
};
</script>
