<style scoped>
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw7 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
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
      <div class="mw8 center br3 mb4 bg-white box relative z-1">
        <div class="pa3 relative">
          <h2 class="normal ma0 mb2">
            {{ team.name }}
          </h2>
          <ul class="ma0 pa0 f6">
            <li class="di mr2">
              <img src="/img/leader.svg" class="pr1" />
              Lead by John Rambo
            </li>
            <li class="di">
              <img src="/img/location.svg" class="pr1" />
              Office #5, south east
            </li>
          </ul>
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
