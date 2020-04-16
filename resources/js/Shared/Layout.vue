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

    &:hover {
      border-bottom-width: 0;
    }

    &.special {
      &:hover {
        border-radius: 11px;
        box-shadow: 1px 0px 1px rgba(43, 45, 80, 0.16), -1px 1px 1px rgba(43, 45, 80, 0.16), 0px 1px 4px rgba(43, 45, 80, 0.18);
      }
    }
  }
}
</style>

<template>
  <div>
    <div class="dn db-m db-l">
      <nav class="flex justify-between bb b--white-10">
        <div class="flex-grow pa2 flex items-center">
          <inertia-link href="/home" class="mr3 no-underline pa2 bb-0">
            <img src="/img/logo.svg" height="30" width="30" alt="logo" />
          </inertia-link>
          <div v-if="!noMenu">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'" class="mr2 no-underline pa2 bb-0 special">
              🏡 {{ $t('app.header_home') }}
            </inertia-link>
            <inertia-link :href="'/' + $page.auth.company.id + '/employees'" class="mr2 no-underline pa2 bb-0 special">
              🧑 {{ $t('app.header_employees') }}
            </inertia-link>
            <inertia-link :href="'/' + $page.auth.company.id + '/teams'" class="mr2 no-underline pa2 bb-0 special" data-cy="header-teams-link">
              👫 {{ $t('app.header_teams') }}
            </inertia-link>
            <inertia-link :href="'/' + $page.auth.company.id + '/company'" class="mr2 no-underline pa2 bb-0 special" data-cy="header-teams-link">
              ⛺️ {{ $t('app.header_company') }}
            </inertia-link>
            <a data-cy="header-find-link" class="mr2 no-underline pa2 bb-0 special pointer" @click="showFindModal">
              🔍 {{ $t('app.header_find') }}
            </a>
            <inertia-link v-if="$page.auth.company && $page.auth.employee.permission_level <= 200" :href="'/' + $page.auth.company.id + '/account'" data-cy="header-adminland-link" class="no-underline pa2 bb-0 special">
              👮‍♂️ Adminland
            </inertia-link>
          </div>
        </div>
        <div class="flex-grow pa2 flex items-center">
          <notifications-component :notifications="notifications" />
          <user-menu />
        </div>
      </nav>
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
            <span class="f6 mb2 dib">
              {{ $t('app.header_search_employees') }}
            </span>
            <ul v-if="employees.length > 0" class="list ma0 pl0">
              <li v-for="localEmployee in employees" :key="localEmployee.id">
                <inertia-link :href="'/' + localEmployee.company.id + '/employees/' + localEmployee.id">
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
              <li v-for="team in teams" :key="team.id">
                <inertia-link :href="'/' + team.company.id + '/teams/' + team.id">
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
            <img src="/img/logo.svg" width="30" height="27" alt="logo" />
          </a>
        </div>
      </div>
    </header>

    <div :class="[ modalFind ? 'bg-modal-find' : '' ]"></div>

    <slot></slot>

    <toaster />
  </div>
</template>

<script>
import vClickOutside from 'v-click-outside';
import UserMenu from '@/Shared/UserMenu';
import LoadingButton from '@/Shared/LoadingButton';
import NotificationsComponent from '@/Shared/Notifications';
import Toaster from '@/Shared/Toaster';

export default {
  components: {
    UserMenu,
    LoadingButton,
    NotificationsComponent,
    Toaster,
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
        errors: {
          type: Array,
          default: null,
        },
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
