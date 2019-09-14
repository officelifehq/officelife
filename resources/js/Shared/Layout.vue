<style lang="scss" scoped>
.find-box {
  border: 1px solid rgba(27,31,35,.15);
  box-shadow: 0 3px 12px rgba(27,31,35,.15);
  top: 63px;
  width: 500px;
  left: 0;
  right: 0;
  margin: 0 auto;
}

.notifications-box {
  border: 1px solid rgba(27,31,35,.15);
  box-shadow: 0 3px 12px rgba(27,31,35,.15);
  top: 63px;
  width: 500px;
  left: 0;
  right: 0;
  margin: 0 auto;
}

.notification-item {
  &:last-child {
    border-bottom: 0;
  }
}

.notification-date {
  color: #777A88;
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
</style>

<template>
  <div>
    <vue-snotify />

    <header class="bg-white dn db-m db-l mb3 relative">
      <div class="ph3 pt1 w-100">
        <div class="cf">
          <div class="fl w-20 pa2">
            <a class="relative header-logo" href="">
              <img src="/img/logo.svg" height="30" />
            </a>
          </div>
          <div class="fl w-50 tc">
            <div v-show="noMenu" class="dib w-100"></div>
            <ul v-show="!noMenu" class="mv2">
              <li class="di header-menu-item pa2 pointer mr2">
                <inertia-link href="/home">
                  <span class="fw5">
                    <img class="relative" src="/img/header/icon-home.svg" />
                    {{ $t('app.header_home') }}
                  </span>
                </inertia-link>
              </li>
              <li class="di header-menu-item pa2 pointer mr2" data-cy="header-find-link" @click="showFindModal">
                <span class="fw5">
                  <img class="relative" src="/img/header/icon-find.svg" />
                  {{ $t('app.header_find') }}
                </span>
              </li>
              <li class="di header-menu-item pa2 pointer" data-cy="header-notifications-link" @click="showNotifications">
                <span class="fw5">
                  <img class="relative" src="/img/header/icon-notification.svg" />
                  {{ $t('app.header_notifications') }}
                </span>
              </li>
              <li v-if="$page.auth.company && $page.auth.employee.permission_level <= 200" class="di header-menu-item pa2 pointer" data-cy="header-notifications-link">
                <inertia-link :href="'/' + $page.auth.company.id + '/account'">
                  <span class="fw5">
                    <img class="relative" src="/img/header/icon-notification.svg" />
                    Adminland
                  </span>
                </inertia-link>
              </li>
            </ul>
          </div>
          <div class="fl w-30 pa2 tr relative header-menu-settings">
            <notifications-component :notifications="notifications" />
            <user-menu />
          </div>
        </div>
      </div>

      <!-- FIND BOX -->
      <div v-show="modalFind" class="absolute z-max find-box">
        <div class="br2 bg-white tl pv3 ph3 bounceIn faster">
          <form @submit.prevent="submit">
            <div class="relative">
              <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                     :placeholder="$t('app.header_search_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required @keydown.esc="modalFind = false"
              />
              <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3 absolute top-0 right-0'" :state="loadingState" :text="$t('app.search')" :cypress-selector="'header-find-submit'" />
            </div>
          </form>

          <!-- Search results -->
          <ul v-show="dataReturnedFromSearch" class="pl0 list ma0 mt3" data-cy="results">
            <!-- Employees -->
            <li class="b mb3">
              <span class="f6 mb2 dib">{{ $t('app.header_search_employees') }}</span>
              <ul v-if="employees.length > 0" class="list ma0 pl0">
                <li v-for="localEmployee in employees" :key="localEmployee.id">
                  <a :href="'/' + localEmployee.company.id + '/employees/' + localEmployee.id">{{ localEmployee.name }}</a>
                </li>
              </ul>
              <div v-else class="silver">
                {{ $t('app.header_search_no_employee_found') }}
              </div>
            </li>

            <!-- Teams -->
            <li class="fw5">
              <span class="f6 mb2 dib">{{ $t('app.header_search_teams') }}</span>
              <ul v-if="teams.length > 0" class="list ma0 pl0">
                <li v-for="team in teams" :key="team.id">
                  <a :href="'/' + team.company.id + '/teams/' + team.id">{{ team.name }}</a>
                </li>
              </ul>
              <div v-else class="silver">
                {{ $t('app.header_search_no_team_found') }}
              </div>
            </li>
          </ul>
        </div>
      </div>
    </header>

    <!-- MOBILE MENU -->
    <header class="bg-white mobile dn-ns mb3">
      <div class="ph2 pv2 w-100 relative">
        <div class="pv2 relative menu-toggle">
          <label for="menu-toggle" class="dib b relative">Menu</label>
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
            <img src="/img/logo.svg" width="30" height="27" />
          </a>
        </div>
      </div>
    </header>

    <div :class="[ modalFind ? 'bg-modal-find' : '' ]"></div>

    <slot></slot>
  </div>
</template>

<script>
import vClickOutside from 'v-click-outside';
import UserMenu from '@/Shared/UserMenu';
import LoadingButton from '@/Shared/LoadingButton';
import NotificationsComponent from '@/Shared/Notifications';

export default {
  components: {
    UserMenu,
    LoadingButton,
    NotificationsComponent,
  },

  directives: {
    clickOutside: vClickOutside.directive
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
  },

  data() {
    return {
      loadingState: '',
      modalFind: false,
      showModalNotifications: true,
      dataReturnedFromSearch: false,
      form: {
        searchTerm: null,
        errors: [],
      },
      employees: [],
      teams: [],
    };
  },

  watch: {
    title(title) {
      this.updatePageTitle(title);
    }
  },

  mounted() {
    this.updatePageTitle(this.title);
  },


  methods: {
    updatePageTitle(title) {
      document.title = title ? `${title} | Example app` : 'Example app';
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

    showNotifications() {
      this.showModalNotifications = !this.showModalNotifications;

      axios.post('/notifications/read')
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    hideNotifications() {
      this.showModalNotifications = false;
    },

    submit() {
      axios.post('/search/employees', this.form)
        .then(response => {
          this.dataReturnedFromSearch = true;
          this.employees = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });

      axios.post('/search/teams', this.form)
        .then(response => {
          this.dataReturnedFromSearch = true;
          this.teams = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    }
  },
};
</script>
