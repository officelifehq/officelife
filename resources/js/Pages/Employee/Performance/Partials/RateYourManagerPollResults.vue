<style lang="scss" scoped>
.date {
  flex-grow: 3;
}

.survey-item:first-child {
  border-top-left-radius: 11px;
  border-top-right-radius: 11px;
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
    <div class="cf center mb2 fw5">
      <span class="mr1">
        💵
      </span> How is your work as a manager

      <help :url="$page.props.help_links.manager_rate_manager" :datacy="'help-icon-rate'" />
    </div>

    <div class="cf center br3 mb3 bg-white box relative">
      <!-- BLANK STATE -->
      <div v-if="!surveys" data-cy="expense-list-blank-state" class="pa3">
        <img loading="lazy" class="db center mb4" height="140" alt="" src="/img/streamline-icon-customer-service-rating-1-4@140x140.png" />

        <p class="fw5 mt3 tc">{{ $t('dashboard.manager_rate_manager_blank_state') }}</p>
      </div>

      <!-- LIST OF SURVEYS -->
      <ul v-else class="list mt0 pl0 mb0">
        <li v-for="survey in surveys.surveys" :key="survey.id" class="flex items-center pa3 bb bb-gray bb-gray-hover survey-item">
          <!-- date -->
          <div class="date">
            <span v-if="survey.active" class="db mb2 f3 fw3">{{ survey.month }}</span>
            <span v-else class="db mb2"><inertia-link :href="survey.url" :data-cy="'survey-' + survey.id" class="f3 fw3">{{ survey.month }}</inertia-link></span>
            <span v-if="survey.employees" class="gray f6">{{ $t('dashboard.manager_rate_manager_respondants', {respondants: survey.employees}) }}</span>
          </div>

          <!-- deadline or results -->
          <div v-if="survey.active" class="gray f6">
            {{ $t('dashboard.manager_rate_manager_current_survey') }} | {{ survey.deadline }}
          </div>
          <div v-if="survey.id == null" class="gray f6">
            {{ survey.deadline }}
          </div>
          <div v-if="survey.results && !survey.active">
            <ul class="list pl0 mb2">
              <li class="mr3 di"><span class="mr1">😨</span> {{ survey.results.bad }}</li>
              <li class="mr3 di"><span class="mr1">🙂</span> {{ survey.results.average }}</li>
              <li class="di"><span class="mr1">🤩</span> {{ survey.results.good }}</li>
            </ul>
            <p class="gray f6 tc ma0">{{ $t('dashboard.manager_rate_manager_response_rate', {rate: survey.response_rate}) }}</p>
          </div>
        </li>

        <!-- view all survey -->
        <div class="ph3 pv2 tc f6">
          <inertia-link :href="surveys.url_view_all">{{ $t('employee.survey_index_view_all') }}</inertia-link>
        </div>
      </ul>
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
    employee: {
      type: Object,
      default: null,
    },
    surveys: {
      type: Object,
      default: null,
    },
  },
};
</script>
