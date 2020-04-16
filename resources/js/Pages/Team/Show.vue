<style lang="scss" scoped>
.useful-link {
  top: 6px;
}

.team-title {
  background-color: #2E3748;
  color: #fff;
  border-top-right-radius: 6px;
  border-top-left-radius: 6px;
}

.news-information {
  img {
    top: 7px;
    width: 23px;
  }
}

</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb3 mw7 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/teams'">{{ $t('app.breadcrumb_team_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ team.name }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="cf mw8 center">
        <!-- LEFT COLUMN -->
        <div class="fl w-30-l w-100">
          <!-- Team profile -->
          <div class="bg-white box mb4">
            <h2 class="bb bb-gray pa3 ma0 fw5 team-title">
              {{ team.name }}
            </h2>

            <!-- team description -->
            <team-description
              :team="team"
              :user-belongs-to-the-team="userBelongsToTheTeam"
            />

            <!-- team info -->
            <p v-if="employeeCount != 0" class="lh-copy ma0 pa3 bb bb-gray" data-cy="latest-added-employee-name">
              {{ mostRecentEmployee }}
            </p>

            <!-- Team lead -->
            <team-lead
              :team="team"
              :user-belongs-to-the-team="userBelongsToTheTeam"
              @lead-set="refreshTeamMembers($event)"
            />

            <!-- Links -->
            <team-useful-link
              :team="team"
              :user-belongs-to-the-team="userBelongsToTheTeam"
              :links="links"
            />
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-70-l w-100 pl4-l">
          <!-- Employees -->
          <members
            :employees="updatedEmployees"
            :team="team"
          />

          <!-- News -->
          <h3 class="db fw5 mb3 flex justify-between items-center">
            <span>🗞 {{ $tc('team.count_team_news', newsCount, { count: newsCount }) }}</span>
            <inertia-link v-if="userBelongsToTheTeam || $page.auth.employee.permission_level <= 200" :href="'/' + $page.auth.company.id + '/teams/' + team.id + '/news/create'" class="btn f5" data-cy="add-team-news">{{ $t('team.news_write') }}</inertia-link>
          </h3>

          <div class="mb4">
            <!-- if there are any news to display -->
            <div v-if="news.length > 0">
              <div class="bg-white box cf mb4 relative" data-cy="news-list">
                <div v-for="newsItem in news" :key="newsItem.id" class="pa3 bb bb-gray">
                  <h3 class="mt0 mb0-ns mb2 normal pointer" @click.prevent="goToNews()">
                    {{ newsItem.title }}
                  </h3>
                  <div class="f6 relative news-information silver">
                    <img :src="newsItem.author.avatar" class="br-100 relative mr1 dib-ns dn" alt="avatar" />
                    {{ $t('team.team_news_written_by_at', { name: newsItem.author.name, created_at: newsItem.localized_created_at }) }}
                  </div>
                </div>

                <!-- link to go to the news page -->
                <div class="pa3 tc">
                  <inertia-link :href="'/' + $page.auth.company.id + '/teams/' + team.id + '/news'" data-cy="view-all-news">{{ $t('team.news_view_all') }}</inertia-link>
                </div>
              </div>
            </div>

            <!-- blank state -->
            <div v-else class="bg-white box pa3 cf news mb4 tc">
              {{ $t('team.news_blank') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import vClickOutside from 'v-click-outside';
import Members from '@/Pages/Team/Partials/Members';
import TeamDescription from '@/Pages/Team/Partials/TeamDescription';
import TeamLead from '@/Pages/Team/Partials/TeamLead';
import TeamUsefulLink from '@/Pages/Team/Partials/TeamUsefulLink';

export default {
  components: {
    Layout,
    Members,
    TeamDescription,
    TeamLead,
    TeamUsefulLink,
  },

  directives: {
    clickOutside: vClickOutside.directive
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employees: {
      type: Array,
      default: null,
    },
    mostRecentEmployee: {
      type: String,
      default: null,
    },
    employeeCount: {
      type: Number,
      default: null,
    },
    team: {
      type: Object,
      default: null,
    },
    news: {
      type: Array,
      default: null,
    },
    newsCount: {
      type: Number,
      default: null,
    },
    userBelongsToTheTeam: {
      type: Boolean,
      default: false,
    },
    links: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      updatedEmployees: {
        type: Array,
        default: [],
      }
    };
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  created() {
    this.updatedEmployees = this.employees;
  },

  methods: {
    goToNews() {
      this.$inertia.visit('/' + this.$page.auth.company.id + '/teams/' + this.team.id + '/news');
    },

    refreshTeamMembers(object) {
      this.updatedEmployees.push(object);
    }
  }
};

</script>
