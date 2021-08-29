<style lang="scss" scoped>
.date {
  flex-grow: 3;
}
.survey-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 11px;
    border-bottom-right-radius: 11px;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/performance'"
                  :previous="employee.name"
      >
        All the surveys as manager
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <h2 class="pa3 mt2 mb4 center tc normal">
          {{ $t('employee.survey_index') }}

          <help :url="$page.props.help_links.manager_rate_manager" />
        </h2>

        <div class="flex-ns justify-around dn">
          <div>
            <img loading="lazy" class="db center mb4" alt="total of expenses" src="/img/streamline-icon-gift-balloons-1@60x60.png" />
          </div>
          <div>
            <p class="mt0 f3 mb2">{{ surveys.number_of_completed_surveys }}</p>
            <p class="mt0 f6 gray">{{ $t('employee.survey_index_surveys') }}</p>
          </div>
          <div>
            <p class="mt0 f3 mb2">{{ surveys.number_of_unique_participants }}</p>
            <p class="mt0 f6 gray">{{ $t('employee.survey_index_direct_reports') }}</p>
          </div>
          <div>
            <p class="mt0 f3 mb2">{{ surveys.average_response_rate }}%</p>
            <p class="mt0 f6 gray">{{ $t('employee.survey_index_completion_rate') }}</p>
          </div>
        </div>

        <ul v-if="surveys.surveys.length > 0" class="list mt0 pl0 mb0">
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
        </ul>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    Help,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    expenses: {
      type: Array,
      default: null,
    },
    surveys: {
      type: Object,
      default: null,
    },
  },
};

</script>
