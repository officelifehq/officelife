<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';

.find-box {
  border: 1px solid rgba(27,31,35,.15);
  box-shadow: 0 3px 12px rgba(27,31,35,.15);
  top: 63px;
  width: 500px;
  left: 0;
  right: 0;
  margin: 0 auto;
}

.bg-modal-find {
  position: fixed;
  z-index: 100;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: rgba(0, 0, 0, 0.3);
  display: flex;
  justify-content: center;
  align-items: center;
}

nav {
  border-bottom: 1px solid #e0e0e0;
  background-color: #fff;

  a {
    color: #4d4d4f;

    &:hover,
    &:active {
      border-bottom-width: 0;
    }

    &.special {
      &:hover {
        border-radius: 11px;
        box-shadow: 1px 0px 1px rgba(43, 45, 80, 0.16), -1px 1px 1px rgba(43, 45, 80, 0.16), 0px 1px 4px rgba(43, 45, 80, 0.18);
      }
    }
  }

  &.demo-mode {
    background-color: #fff9cb;
  }

  &.beta-mode {
    background-color: #fff9cb;
  }
}

.ball-pulse {
  right: 8px;
  top: 10px;
  position: absolute;
}
</style>

<template>
  <div>
    <div class="dn db-m db-l">
      <!-- DEMO MODE -->
      <nav v-if="$page.props.demo_mode" class="bb b--white-10 tc pa3 demo-mode">
        <span class="mr1">
          ⚠️
        </span> {{ $t('app.demo_mode_desc') }} <a href="">{{ $t('app.demo_mode_read_more') }}</a>
      </nav>

      <nav class="flex justify-between bb b--white-10">
        <div class="flex-grow pa2 flex items-center">
          <inertia-link href="/home" class="mr3 no-underline pa2 bb-0">
            <img loading="lazy" src="/img/logo.png" height="30" width="30" alt="logo" />
          </inertia-link>

          <!-- MENU -->
          <div v-if="!noMenu">
            <inertia-link v-if="$page.props.auth.employee.display_welcome_message" :href="'/' + $page.props.auth.company.id + '/welcome'" data-cy="header-desktop-welcome-tab" class="mr2 no-underline pa2 bb-0 special">
              <span class="mr1">👋</span> {{ $t('app.header_welcome') }}
            </inertia-link>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'" class="mr2 no-underline pa2 bb-0 special">
              <span class="mr1">🏡</span> {{ $t('app.header_home') }}
            </inertia-link>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'" class="mr2 no-underline pa2 bb-0 special" data-cy="header-teams-link">
              <span class="mr1">⛺️</span> {{ $t('app.header_company') }}
            </inertia-link>
            <inertia-link v-if="$page.props.auth.employee.permission_level < 300" :href="'/' + $page.props.auth.company.id + '/recruiting/job-openings'" class="mr2 no-underline pa2 bb-0 special">
              <span class="mr1">🥇</span> {{ $t('app.header_recruiting') }}
            </inertia-link>
            <a data-cy="header-find-link" class="mr2 no-underline pa2 bb-0 special pointer" @click="showFindModal">
              <span class="mr1">🔍</span> {{ $t('app.header_find') }}
            </a>
            <inertia-link v-if="$page.props.auth.company && $page.props.auth.employee.permission_level <= 200" :href="'/' + $page.props.auth.company.id + '/account'" data-cy="header-adminland-link" class="no-underline pa2 bb-0 special">
              <span class="mr1">👮‍♂️</span> {{ $t('app.header_adminland') }}
            </inertia-link>
          </div>
        </div>
        <div class="pa2 flex items-center">
          <notifications-component :notifications="notifications" />

          <user-menu :show-help-on-page="showHelpOnPage" />
        </div>
      </nav>
    </div>

    <!-- FIND BOX -->
    <div v-show="modalFind" class="absolute z-max find-box">
      <div class="br2 bg-white tl pv3 ph3 bounceIn faster" @click.prevent="">
        <form @submit.prevent="search">
          <div class="relative">
            <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                   :placeholder="$t('app.header_search_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required @keydown.esc="modalFind = false" @keyup="search"
            />
            <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3 absolute top-0 right-0'" :state="loadingState" :text="$t('app.search')" :cypress-selector="'header-find-submit'" />
          </div>
        </form>

        <!-- Search results -->
        <ul v-show="dataReturnedFromSearch" class="pl0 list ma0 mt3" data-cy="results">
          <!-- Employees -->
          <li class="b mb3">
            <span class="f6 mb2 dib">
              {{ $t('app.header_search_employees') }}
            </span>
            <ul v-if="employees.length > 0" class="list ma0 pl0">
              <li v-for="localEmployee in employees" :key="localEmployee.id" class="mb2">
                <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + localEmployee.id">
                  {{ localEmployee.name }}
                </inertia-link>
              </li>
            </ul>
            <div v-else class="silver">
              {{ $t('app.header_search_no_employee_found') }}
            </div>
          </li>

          <!-- Teams -->
          <li class="fw5">
            <span class="f6 mb2 dib">
              {{ $t('app.header_search_teams') }}
            </span>
            <ul v-if="teams.length > 0" class="list ma0 pl0">
              <li v-for="team in teams" :key="team.id" class="mb2">
                <inertia-link :href="'/' + $page.props.auth.company.id + '/teams/' + team.id">
                  {{ team.name }}
                </inertia-link>
              </li>
            </ul>
            <div v-else class="silver">
              {{ $t('app.header_search_no_team_found') }}
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- MOBILE MENU -->
    <header class="bg-white mobile dn-ns mb3">
      <div class="ph2 pv2 w-100 relative">
        <div class="pv2 relative menu-toggle">
          <label for="menu-toggle" class="dib b relative">
            Menu
          </label>
          <input id="menu-toggle" type="checkbox" />
          <ul id="mobile-menu" class="list pa0 mt4 mb0">
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                Home
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_people
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_journal
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_find
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_changelog
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_settings
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                app.main_nav_signout
              </a>
            </li>
          </ul>
        </div>
        <div class="absolute pa2 header-logo">
          <a href="">
            <img loading="lazy" src="/img/logo.svg" width="30" height="27" alt="logo" />
          </a>
        </div>
      </div>
    </header>

    <div :class="[ modalFind ? 'bg-modal-find' : '' ]" @click.prevent="modalFind = false"></div>

    <main>
      <slot></slot>
    </main>

    <toaster />

    <div class="mt5 mb4 cf mw7 center tc f7">
      <ul class="list ma0">
        <li class="di mr2">
          <span class="mr1">🎡</span>
          Welcome to OfficeLife beta!
        </li>
        <li class="di mr2"><a href="https://docs.officelife.io" class="mr2">Read our documentation</a></li>
        <li class="di">Thanks for using our tool!</li>
      </ul>
    </div>
  </div>
</template>

<script>
import UserMenu from '@/Shared/UserMenu';
import LoadingButton from '@/Shared/LoadingButton';
import NotificationsComponent from '@/Shared/Notifications';
import Toaster from '@/Shared/Toaster';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';

export default {
  components: {
    UserMenu,
    LoadingButton,
    NotificationsComponent,
    Toaster,
    'ball-pulse-loader': BallPulseLoader.component,
  },

  props: {
    title: {
      type: String,
      default: '',
    },
    noMenu: {
      type: Boolean,
      default: false,
    },
    notifications: {
      type: Array,
      default: null,
    },
    showHelpOnPage: {
      type: Boolean,
      default: true,
    },
  },

  data() {
    return {
      submit: null,
      loadingState: '',
      modalFind: false,
      showModalNotifications: true,
      dataReturnedFromSearch: false,
      processingSearch: false,
      form: {
        searchTerm: null,
        errors: [],
      },
      employees: [],
      teams: [],
      cache: [],
    };
  },

  watch: {
    title(title) {
      this.updatePageTitle(title);
    }
  },

  mounted() {
    this.updatePageTitle(this.title);
    this.submit = _.debounce((text) => {
      const vm = this;
      vm.processingSearch = true;
      vm.loadingState = 'loading';

      Promise.all([
        axios.post('/search/employees', { searchTerm: text }),
        axios.post('/search/teams', { searchTerm: text })
      ])
        .then(results => {
          vm.cache[text] = {
            employees: results[0].data.data,
            teams: results[1].data.data,
          };
          vm.displayItems(text);
        })
        .catch(error => {
          vm.loadingState = null;
          vm.processingSearch = false;
          vm.form.errors = error.response.data;
        });
    }, 500);
  },


  methods: {
    updatePageTitle(title) {
      document.title = title ? `${title} | OfficeLife` : 'OfficeLife';
    },

    showFindModal() {
      this.dataReturnedFromSearch = false;
      this.form.searchTerm = null;
      this.employees = [];
      this.teams = [];
      this.modalFind = !this.modalFind;

      this.$nextTick(() => {
        this.$refs.search.focus();
      });
    },

    search: function () {
      let text = _.trim(this.form.searchTerm);
      if (text === null || text === undefined || text === '') {
        return;
      }

      if (this.cache[text] === undefined) {
        this.submit(text);
      } else {
        this.submit.cancel();
        this.displayItems(text);
      }
    },

    displayItems (text) {
      var data = this.cache[text];

      this.dataReturnedFromSearch = true;
      this.processingSearch = false;
      this.loadingState = null;

      this.employees = data.employees;
      this.teams = data.teams;
    },
  },
};
</script>
