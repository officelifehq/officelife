<style lang="scss" scoped>
.content {
  background-color: #f3f9fc;
  padding: 9px 14px;
}

.answer-entry:not(:first-child) {
  margin-top: 25px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/questions'"
                  :previous="$t('app.breadcrumb_company_questions')"
      >
        {{ $t('app.breadcrumb_company_questions_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="cf mw7 center br3 mb3 bg-white box relative">
        <div class="pa3">
          <p class="f3 fw4 mt0 mb1 measure lh-copy tc" data-cy="company-question-title">{{ question.title }}</p>
          <p v-if="question.date" class="f6 silver mt0 tc lh-copy">{{ question.date }}</p>
          <p class="f6 silver mb4 mt0 tc lh-copy">{{ $tc('dashboard.question_number_of_answers', question.number_of_answers, { number: question.number_of_answers }) }}</p>

          <div v-if="teams.length > 0" class="tr">
            <span class="dib mr2 f7 gray">
              {{ $t('company.question_filter_team') }}
            </span>
            <select id="teams" v-model="form.id" @change="navigateTo()">
              <option value="0" selected="selected">
                {{ $t('company.question_filter_no_specific_team') }}
              </option>
              <option v-for="team in teams" :key="team.id" :value="team.id">
                {{ team.name }}
              </option>
            </select>
          </div>

          <div v-for="answer in answers" :key="answer.id" class="bb-gray relative answer-entry" :data-cy="'answer-content-' + answer.id">
            <!-- avatar -->
            <small-name-and-avatar
              :name="answer.employee.name"
              :avatar="answer.employee.avatar"
            />

            <!-- content of the answer -->
            <div class="lh-copy content mt2 br3" v-html="answer.body">
            </div>
          </div>

          <!-- Pagination -->
          <div class="center cf mt3">
            <inertia-link v-if="paginator.previousPageUrl" class="fl dib" :href="paginator.previousPageUrl" title="Previous">
              &larr; {{ $t('app.previous') }}
            </inertia-link>
            <inertia-link v-if="paginator.nextPageUrl" class="fr dib" :href="paginator.nextPageUrl" title="Next">
              {{ $t('app.next') }} &rarr;
            </inertia-link>
          </div>

          <!-- Blank state -->
          <p v-if="answers.length == 0" class="mt5 tc" data-cy="company-question-blank-state">{{ $t('company.question_blank') }}</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    SmallNameAndAvatar,
  },

  props: {
    question: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
    paginator: {
      type: Object,
      default: null,
    },
    currentTeam: {
      type: Number,
      default: 0,
    }
  },

  data() {
    return {
      answers: Array,
      form: {
        id: 0,
      },
    };
  },

  created: function() {
    this.answers = this.question.answers;
    this.form.id = this.currentTeam;
  },

  methods: {
    navigateTo() {
      if (this.form.id == 0) {
        this.$inertia.visit('/' + this.$page.props.auth.company.id + '/company/questions/' + this.question.id);
      } else {
        this.$inertia.visit('/' + this.$page.props.auth.company.id + '/company/questions/' + this.question.id + '/teams/' + this.form.id);
      }
    },
  },
};

</script>
