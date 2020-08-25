<style lang="scss" scoped>
.rate-bad {
  background: linear-gradient(0deg, #ec89793d 0%, white 100%);
}

.rate-good {
  background: linear-gradient(0deg, #b1ecb23d 0%, white 100%);
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/employees/' + employee.id + '/performance'">{{ employee.name }}</inertia-link>
          </li>
          <li class="di">
            Survey for May 2020
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1 pa3">
        <h2 class="pa3 mt2 mb4 center tc normal">
          Your May 2020 as a manager
        </h2>

        <!-- poll grades -->
        <div class="flex justify-center mb4">
          <div class="mr3 tc ba bb-gray br3 ph3 pv2 rate-bad">
            <span class="db mb2 fw5">
              😨 {{ survey.results.bad }}
            </span>
            <span class="gray f6">
              Not ideal
            </span>
          </div>
          <div class="mr3 tc ba bb-gray br3 ph3 pv2">
            <span class="db mb2 fw5">
              🙂 {{ survey.results.average }}
            </span>
            <span class="gray f6">
              It’s going well
            </span>
          </div>
          <div class="tc ba bb-gray br3 ph3 pv2 rate-good">
            <span class="db mb2 fw5">
              🤩 {{ survey.results.good }}
            </span>
            <span class="gray f6">
              Simply great
            </span>
          </div>
        </div>

        <!-- lists of employees surveyed -->
        <p class="fw5">Employees surveyed</p>
        <div class="flex flex-wrap mb4">
          <div v-for="employee in survey.direct_reports" :key="employee.id" class="mr3 mb3">
            <small-name-and-avatar
              v-if="employee.id"
              :name="employee.name"
              :avatar="employee.avatar"
              :classes="''"
              :size="'22px'"
              :top="'0px'"
              :margin-between-name-avatar="'25px'"
            />
          </div>
        </div>

        <!-- comments, if any -->
        <p class="fw5">Comments</p>
        <ul class="pl0 list">
          <li v-for="answer in survey.answers" :key="answer.id" class="mb4">
            <span v-if="answer.reveal_identity_to_manager" class="db mb2 gray">
              <small-name-and-avatar
                v-if="answer.employee.id"
                :name="answer.employee.name"
                :avatar="answer.employee.avatar"
                :classes="'gray'"
                :size="'18px'"
                :top="'0px'"
                :margin-between-name-avatar="'25px'"
              />
            </span>
            <span v-else class="db mb2 gray">Anonymous comment</span>
            <p class="mt0 lh-copy">{{ answer.comment }}</p>
          </li>
        </ul>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Layout,
    SmallNameAndAvatar,
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

  data() {
    return {
    };
  },

  created() {
  },

  methods: {
  },
};

</script>
