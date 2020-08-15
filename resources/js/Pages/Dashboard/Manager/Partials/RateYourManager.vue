<style lang="scss" scoped>
.date {
  flex-grow: 3;
}

.survey-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
}

.survey-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 11px;
    border-bottom-right-radius: 11px;
  }
}

.rate-bad {
  background: linear-gradient(0deg, #ec89793d 0%, white 100%);
}

.rate-good {
  background: linear-gradient(0deg, #b1ecb23d 0%, white 100%);
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5">
      <span class="mr1">
        💵
      </span> How is your work as a manager

      <help :url="$page.help_links.manager_rate_manager" :datacy="'help-icon-rate'" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative">
      <!-- BLANK STATE -->
      <div data-cy="expense-list-blank-state" class="pa3">
        <img loading="lazy" class="db center mb4" alt="no expenses to validate" src="/img/streamline-icon-customer-service-rating-1-4@140x140.png" />

        <p class="fw5 mt3 tc">You don’t have any previous surveys about your performance as a manager yet.</p>
      </div>

      <!-- LIST OF surveyS -->
      <ul class="list pl0 mb0">
        <li class="flex items-center pa3 bb bb-gray bb-gray-hover survey-item">
          <div class="date">
            <span class="db f3 fw3 mb1">June 2020</span>
            <span class="gray f6">8 direct reports at the moment</span>
          </div>
          <div class="gray f6">
            Next survey in 3 days
          </div>
        </li>
        <li class="flex items-center pa3 bb bb-gray bb-gray-hover survey-item">
          <div class="date">
            <span class="db f3 fw3 mb1">June 2020</span>
            <span class="gray f6">8 direct reports at the moment</span>
          </div>
          <div class="gray f6">
            <p class="mb2">Survey in progress</p>
            <p class="f7 mt0 gray">Ends in 3 days</p>
          </div>
        </li>
        <li class="flex items-center pa3 bb bb-gray bb-gray-hover survey-item">
          <div class="date">
            <span class="db f3 fw3 mb1"><a href="">June 2020</a></span>
            <span class="gray f6">Survey made on 8 direct reports</span>
          </div>
          <div class="">
            <ul class="list pl0 mb2">
              <li class="mr3 di">😨 3</li>
              <li class="mr3 di">🙂 2</li>
              <li class="di">🤩 13</li>
            </ul>
            <p class="gray f6 tc ma0">13% response rate</p>
          </div>
        </li>
        <li class="flex items-center pa3 bb bb-gray bb-gray-hover survey-item">
          <div class="date">
            <span class="db f3 fw3 mb1"><a href="">June 2020</a></span>
            <span class="gray f6">Survey made on 8 direct reports</span>
          </div>
          <div class="">
            <ul class="list pl0 mb2">
              <li class="mr3 di">😨 3</li>
              <li class="mr3 di">🤔 2</li>
              <li class="di">🤩 13</li>
            </ul>
            <p class="gray f6 tc ma0">13% response rate</p>
          </div>
        </li>
      </ul>
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative pa3">
      <h2 class="mt0 tc fw4 mb4">
        Your May 2020 as a manager
      </h2>

      <!-- poll grades -->
      <div class="flex justify-center mb4">
        <div class="mr3 tc ba bb-gray br3 ph3 pv2 rate-bad">
          <span class="db mb2 fw5">
            😨 3
          </span>
          <span class="gray f6">
            Not ideal
          </span>
        </div>
        <div class="mr3 tc ba bb-gray br3 ph3 pv2">
          <span class="db mb2 fw5">
            🙂 13
          </span>
          <span class="gray f6">
            It’s going well
          </span>
        </div>
        <div class="tc ba bb-gray br3 ph3 pv2 rate-good">
          <span class="db mb2 fw5">
            🤩 1
          </span>
          <span class="gray f6">
            Simply great
          </span>
        </div>
      </div>

      <!-- lists of employees surveyed -->
      <p>Employees surveyed</p>

      <!-- comments, if any -->
      <ul class="pl0 list">
        <li class="mb3">
          <span class="db mb2 gray">Anonymous comment</span>
          <p class="mt0 lh-copy">orem ipsum dolor sit amet, consectetur adipiscing elit. Donec aam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.</p>
        </li>
        <li>
          <span class="db mb2 gray">Anonymous comment</span>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';
//import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Help,
    //SmallNameAndAvatar,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    expenses: {
      type: Array,
      default: null,
    },
    defaultCurrency: {
      type: Object,
      default: null,
    },
  },
};
</script>
