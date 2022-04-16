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
    top: 4px;
    width: 18px;
  }
}

.popupmenu {
  &:after {
    left: auto;
    right: 10px;
  }

  &:before {
    left: auto;
    right: 9px;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb
        :root-url="'/' + $page.props.auth.company.id + '/teams'"
        :root="$t('app.breadcrumb_team_list')"
        :has-more="false"
      >
        {{ team.name }}
      </breadcrumb>

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

          <!-- Recent ships -->
          <recent-ships
            :recent-ships="recentShips"
            :team="team"
            :user-belongs-to-the-team="userBelongsToTheTeam"
          />

          <!-- News -->
          <h3 class="db fw5 mb3 flex justify-between items-center">
            <span><span class="mr1">
              🗞
            </span> {{ $tc('team.count_team_news', newsCount, { count: newsCount }) }}</span>
            <inertia-link v-if="userBelongsToTheTeam || $page.props.auth.employee.permission_level <= 200" :href="'/' + $page.props.auth.company.id + '/teams/' + team.id + '/news/create'" class="btn f5" data-cy="add-team-news">{{ $t('team.news_write') }}</inertia-link>
          </h3>

          <div class="mb4">
            <!-- if there are any news to display -->
            <div v-if="news.length > 0">
              <div class="bg-white box cf mb4 relative" data-cy="news-list" :data-cy-items="news.map(n => n.id)">
                <div v-for="newsItem in news" :key="newsItem.id" class="pa3 bb bb-gray">
                  <h3 class="mt0 mb1-ns mb2 normal pointer" @click.prevent="goToNews()">
                    {{ newsItem.title }}
                  </h3>
                  <div class="f6 relative news-information silver">
                    <avatar v-if="newsItem.author.avatar" :avatar="newsItem.author.avatar" :size="18" :class="'avatar br-100 relative'" />
                    {{ $t('team.team_news_written_by_at', { name: newsItem.author.name, created_at: newsItem.localized_created_at }) }}
                  </div>
                </div>

                <!-- link to go to the news page -->
                <div class="ph3 pv2 tc f6 bb-gray">
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/teams/' + team.id + '/news'" data-cy="view-all-news">{{ $t('team.news_view_all') }}</inertia-link>
                </div>
              </div>
            </div>

            <!-- blank state -->
            <div v-else class="bg-white box pa3 cf news mb4 tc">
              {{ $t('team.news_blank') }}
            </div>
          </div>

          <!-- birthdays -->
          <birthdays :birthdays="birthdays" />

          <!-- morale -->
          <morale :morale="morale" />

          <!-- hiring date anniversaries -->
          <new-hires-next-week :hires="newHiresNextWeek" />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Members from '@/Pages/Team/Partials/Members';
import TeamDescription from '@/Pages/Team/Partials/TeamDescription';
import NewHiresNextWeek from '@/Pages/Team/Partials/NewHiresNextWeek';
import TeamLead from '@/Pages/Team/Partials/TeamLead';
import TeamUsefulLink from '@/Pages/Team/Partials/TeamUsefulLink';
import RecentShips from '@/Pages/Team/Partials/RecentShips';
import Birthdays from '@/Pages/Team/Partials/Birthdays';
import Morale from '@/Pages/Team/Partials/Morale';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Avatar,
    Breadcrumb,
    Members,
    TeamDescription,
    NewHiresNextWeek,
    TeamLead,
    TeamUsefulLink,
    RecentShips,
    Birthdays,
    Morale,
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
    birthdays: {
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
    recentShips: {
      type: Array,
      default: null,
    },
    morale: {
      type: Array,
      default: null,
    },
    newHiresNextWeek: {
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
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  created() {
    this.updatedEmployees = this.employees;
  },

  methods: {
    goToNews() {
      this.$inertia.visit('/' + this.$page.props.auth.company.id + '/teams/' + this.team.id + '/news');
    },

    refreshTeamMembers(object) {
      this.updatedEmployees.push(object);
    }
  }
};

</script>
