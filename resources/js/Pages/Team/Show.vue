<style lang="scss" scoped>

.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-lead {
  padding-left: 44px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
  }
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
            <p class="lh-copy ma0 pa3 bb bb-gray">
              This team has {{ employeeCount }} members, the most recent being <a href="">
                sdfsd
              </a>.
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
                <inertia-link class="mb2">
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
          <span class="db fw5 mb2">
            🤼‍♀️ {{ employees.length }} team members
          </span>
          <div class="mb4 bg-white box pa3 cf">
            <div v-for="employee in employees" :key="employee.id" class="fl w-third-l w-100 mb4">
              <span class="pl3 db relative team-member">
                <img :src="employee.avatar" class="br-100 absolute avatar" />
                <inertia-link :href="'/' + $page.auth.company.id + '/employees/' + employee.id" class="mb2">
                  {{ employee.name }}
                </inertia-link>

                <span class="db f7 mt1">
                  {{ $t('app.no_position_defined') }}
                </span>
              </span>
            </div>
          </div>

          <!-- News -->
          <span class="db fw5 mb2">
            🗞 {{ employees.length }} news
          </span>
          <div class="mb4 bg-white box pa3 cf">
            <p class="lh-copy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue.</p>
            <p class="lh-copy">Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.</p>
          </div>
        </div>
      </div>

      <div class="cf mw6 center mb4">
        <div class="bg-white box pa3 mb4">
          <p class="lh-copy ma0 mb2">
            This team has {{ employeeCount }} members, the most recent being <a href="">
              sdfsd
            </a>.
          </p>
          <p class="ma0">
            <a href="">
              View team members
            </a>
          </p>
        </div>

        <div class="bg-white box pa3 mb4">
          <p class="ma0 mb2">
            Want to find out how someone in this team can help you?
          </p>
          <p class="ma0">
            <a href="">
              Read about the different ways they can help you
            </a>
          </p>
        </div>

        <div class="bg-white box pa3 mb4">
          <p class="f6 ma0 mb1">
            2 days ago
          </p>
          <p class="lh-copy ma0 mb2">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.
          </p>
          <p class="ma0">
            <a href="">
              Read all the news
            </a>
          </p>
        </div>

        <div class="bg-white box pa3">
          <p class="ma0 mb2">
            New to the team?
          </p>
          <p class="ma0">
            <a href="">
              Start here
            </a>
          </p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import vClickOutside from 'v-click-outside';

export default {
  components: {
    Layout,
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
  }
};

</script>
