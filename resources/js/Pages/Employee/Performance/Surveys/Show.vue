<style lang="scss" scoped>
.rate-bad {
  background: linear-gradient(0deg, #ec89793d 0%, white 100%);
}

.rate-good {
  background: linear-gradient(0deg, #b1ecb23d 0%, white 100%);
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
        {{ $t('app.breadcrumb_employee_surveys_detail', { month: survey.survey.month }) }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1 pa3">
        <h2 class="pa3 mt2 mb4 center tc normal">
          {{ $t('employee.survey_detail_title', { month: survey.survey.month }) }}

          <help :url="$page.props.help_links.manager_rate_manager" />
        </h2>

        <!-- poll grades -->
        <div class="flex justify-center mb4">
          <div class="mr3 tc ba bb-gray br3 ph3 pv2 rate-bad">
            <span class="db mb2 fw5" data-cy="result-bad">
              <span class="mr1">
                😨
              </span> {{ survey.results.bad }}
            </span>
            <span class="gray f6">
              {{ $t('app.rate_manager_bad') }}
            </span>
          </div>
          <div class="mr3 tc ba bb-gray br3 ph3 pv2">
            <span class="db mb2 fw5">
              <span class="mr1">
                🙂
              </span> {{ survey.results.average }}
            </span>
            <span class="gray f6">
              {{ $t('app.rate_manager_average') }}
            </span>
          </div>
          <div class="tc ba bb-gray br3 ph3 pv2 rate-good">
            <span class="db mb2 fw5">
              <span class="mr1">
                🤩
              </span> {{ survey.results.good }}
            </span>
            <span class="gray f6">
              {{ $t('app.rate_manager_good') }}
            </span>
          </div>
        </div>

        <!-- lists of employees surveyed -->
        <p class="fw5">{{ $t('employee.survey_detail_participants') }}</p>
        <div class="flex flex-wrap mb4">
          <div v-for="currentEmployee in survey.direct_reports" :key="currentEmployee.id" class="mr3 mb3">
            <small-name-and-avatar
              v-if="currentEmployee.id"
              :name="currentEmployee.name"
              :avatar="currentEmployee.avatar"
              :url="currentEmployee.url"
              :size="'22px'"
              :top="'0px'"
              :margin-between-name-avatar="'28px'"
            />
          </div>
        </div>

        <!-- comments, if any -->
        <p v-if="survey.answers.length > 0" class="fw5">{{ $t('employee.survey_detail_comment') }}</p>
        <ul v-if="survey.answers.length > 0" class="pl0 list" data-cy="survey-comment">
          <li v-for="answer in survey.answers" :key="answer.id" class="mb4">
            <span v-if="answer.reveal_identity_to_manager" class="db mb2 gray">
              <small-name-and-avatar
                v-if="answer.employee.id"
                :name="answer.employee.name"
                :avatar="answer.employee.avatar"
                :class="'gray'"
                :size="'18px'"
                :top="'0px'"
                :margin-between-name-avatar="'28px'"
              />
            </span>
            <span v-else class="db mb2 gray">{{ $t('employee.survey_detail_comment_anonymous') }}</span>
            <p class="mt0 lh-copy">{{ answer.comment }}</p>
          </li>
        </ul>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    SmallNameAndAvatar,
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
    survey: {
      type: Object,
      default: null,
    },
  },
};

</script>
