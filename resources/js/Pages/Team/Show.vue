<style lang="scss" scoped>

.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-lead {
  padding-left: 44px;
}

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
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $page.auth.company.name }}</inertia-link>
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
            <p class="lh-copy ma0 pa3 bb bb-gray">
              This team has {{ employeeCount }} members, the most recent being sdfsd.
            </p>

            <!-- Team lead - blank -->
            <p class="lh-copy ma0 pa3 bb bb-gray">
              <a class="bb b--dotted bt-0 bl-0 br-0 pointer">Assign a team lead</a>
            </p>

            <!-- Team lead -->
            <div class="lh-copy ma0 pa3 bb bb-gray">
              <p class="silver f6 ma0 mb1">Team lead</p>
              <span class="pl3 db team-lead relative">
                <img :src="'https://api.adorable.io/avatars/200/f25dbf67-01c1-4d12-91a2-927053fbf671.png'" class="br-100 absolute avatar" />
                <inertia-link :href="'/' + $page.auth.company.id + '/employees/'" class="mb2">
                  JOhn Doe
                </inertia-link>

                <span class="db f7 mt1">
                  {{ $t('app.no_position_defined') }}
                </span>
              </span>
            </div>

            <!-- Links -->
            <div class="ma0 pa3">
              <p class="silver f6 ma0 mb1">Links</p>
              <ul class="list pl0 mb0">
                <li class="mb2 relative">
                  <img src="/img/slack.svg" class="relative useful-link" />
                  <a href="" class="relative ml1">sadfsadfs</a>
                </li>
                <li class="mb2 relative">
                  <img src="/img/mail.svg" class="relative useful-link" />
                  <a href="" class="relative ml1">sadfsadfs</a>
                </li>
                <li class="mt3"><a href="" class="bb b--dotted bt-0 bl-0 br-0 pointer f6"><span>+</span> Add a new link</a></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-70-l w-100 pl4-l">
          <!-- Employees -->
          <members
            :employees="employees"
            :team="team"
          />

          <!-- News -->
          <h3 class="db fw5 mb3 flex justify-between items-center">
            <span>🗞 {{ $tc('team.count_team_news', newsCount, { count: newsCount }) }}</span>
            <inertia-link :href="'/' + $page.auth.company.id + '/teams/' + team.id + '/news/create'" class="btn btn-secondary f5" data-cy="add-team-news">{{ $t('team.news_write') }}</inertia-link>
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
                    <img :src="newsItem.author.avatar" class="br-100 relative mr1 dib-ns dn" />
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

export default {
  components: {
    Layout,
    Members,
    TeamDescription,
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
    }
  },

  data() {
    return {
    };
  },

  mounted() {
    if (localStorage.success) {
      this.$snotify.success(localStorage.success, {
        timeout: 2000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true,
      });
      localStorage.clear();
    }
  },

  methods: {
    goToNews() {
      this.$inertia.visit('/' + this.$page.auth.company.id + '/teams/' + this.team.id + '/news');
    }
  }
};

</script>
