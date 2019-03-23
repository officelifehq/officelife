<style scoped>
.list-employees ul {
  padding-left: 43px;
}

.list-employees li:last-child {
  margin-bottom: 0;
}

.avatar {
  top: 1px;
  left: -44px;
  width: 35px;
}

.popupmenu {
  border: 1px solid rgba(27,31,35,.15);
  box-shadow: 0 3px 12px rgba(27,31,35,.15);
  right: 22px;
  top: 46px;
}

.popupmenu:after,
.popupmenu:before {
  content: "";
  display: inline-block;
  position: absolute;
}

.popupmenu:after {
  border: 7px solid transparent;
  border-bottom-color: #fff;
  left: auto;
  right: 10px;
  top: -14px;
}

.popupmenu:before {
  border: 8px solid transparent;
  border-bottom-color: rgba(27,31,35,.15);
  left: auto;
  right: 9px;
  top: -16px;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="tc db fw5 mb2">{{ $t('employee.hierarchy_title') }}</span>
    <img v-show="user.permission_level <= 200" src="/img/plus_button.svg" class="box-plus-button absolute br-100 pa2 bg-white pointer" data-cy="add-hierarchy-button" @click.prevent="toggleModals()" />

    <!-- MENU TO CHOOSE FROM -->
    <div v-if="modal == 'menu'" v-click-outside="toggleModals" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
      <ul class="list ma0 pa0">
        <li class="pv2">
          <a class="pointer" data-cy="add-manager-button" @click.prevent="displayManagerModal()">{{ $t('employee.hierarchy_modal_add_manager') }}</a>
        </li>
        <li class="pv2">
          <a class="pointer" data-cy="add-direct-report-button" @click.prevent="displayDirectReportModal()">{{ $t('employee.hierarchy_modal_add_direct_report') }}</a>
        </li>
      </ul>
    </div>

    <!-- ADD MANAGER -->
    <div v-if="modal == 'manager'" v-click-outside="toggleModals" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
      <form @submit.prevent="search">
        <div class="mb3 relative">
          <p>{{ $t('employee.hierarchy_modal_add_manager_search', { name: employee.first_name}) }}</p>
          <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                 :placeholder="$t('employee.hierarchy_search_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required data-cy="search-manager" @keyup="search"
                 @keydown.esc="toggleModals()"
          />
        </div>
      </form>
      <ul class="pl0 list ma0">
        <li class="fw5 mb3">
          <span class="f6 mb2 dib">{{ $t('employee.hierarchy_search_results') }}</span>
          <ul v-if="searchManagers.length > 0" class="list ma0 pl0">
            <li v-for="manager in searchManagers" :key="manager.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
              {{ manager.name }}
              <a class="absolute right-1 pointer" data-cy="potential-manager-button" @click.prevent="assignManager(manager)">{{ $t('app.choose') }}</a>
            </li>
          </ul>
          <div v-else class="silver">
            {{ $t('app.no_results') }}
          </div>
        </li>
      </ul>
    </div>

    <!-- ADD DIRECT REPORT -->
    <div v-if="modal == 'directReport'" v-click-outside="toggleModals" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
      <form @submit.prevent="search">
        <div class="mb3 relative">
          <p>{{ $t('employee.hierarchy_modal_add_direct_report_search', { name: employee.first_name}) }}</p>
          <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                 :placeholder="$t('employee.hierarchy_search_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required data-cy="search-direct-report"
                 @keyup="search" @keydown.esc="toggleModals()"
          />
        </div>
      </form>
      <ul class="pl0 list ma0">
        <li class="fw5 mb3">
          <span class="f6 mb2 dib">{{ $t('employee.hierarchy_search_results') }}</span>
          <ul v-if="searchDirectReports.length > 0" class="list ma0 pl0">
            <li v-for="directReport in searchDirectReports" :key="directReport.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
              {{ directReport.name }}
              <a class="absolute right-1 pointer" data-cy="potential-direct-report-button" @click.prevent="assignDirectReport(directReport)">{{ $t('app.choose') }}</a>
            </li>
          </ul>
          <div v-else class="silver">
            {{ $t('app.no_results') }}
          </div>
        </li>
      </ul>
    </div>

    <!-- LIST OF EMPLOYEES -->
    <div class="br3 bg-white box z-1 pa3 list-employees">
      <!-- Blank state -->
      <p v-if="managers.length == 0 && directReports.length == 0" class="lh-copy mb0 f6">
        {{ $t('employee.hierarchy_blank') }}
      </p>

      <!-- Managers -->
      <div v-show="managers.length != 0" data-cy="list-managers">
        <p class="mt0 mb2 f6">
          {{ $tc('employee.hierarchy_list_manager_title', managers.length) }}
        </p>
        <ul class="list mv0">
          <li v-for="manager in managers" :key="manager.id" class="mb3 relative">
            <img :src="manager.avatar" class="br-100 absolute avatar" />
            <a :href="'/' + company.id + '/employees/' + manager.id" class="mb2">{{ manager.name }}</a>
            <span class="title db f7 mt1">Director of Management</span>
          </li>
        </ul>
      </div>

      <!-- Direct reports -->
      <div v-show="directReports.length != 0" :class="managers.length != 0 ? 'mt3' : ''" data-cy="list-direct-reports">
        <p class="mt0 mb2 f6">
          {{ $tc('employee.hierarchy_list_direct_report_title', directReports.length) }}
        </p>
        <ul class="list mv0">
          <li v-for="directReport in directReports" :key="directReport.id" class="mb3 relative">
            <img :src="directReport.avatar" class="br-100 absolute avatar" />
            <a :href="'/' + company.id + '/employees/' + directReport.id" class="mb2">{{ directReport.name }}</a>
            <span class="title db f7 mt1">Director of Management</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'

export default {

  directives: {
    ClickOutside
  },

  props: {
    company: {
      type: Object,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    managers: {
      type: Array,
      default: null,
    },
    directReports: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: 'hide',
      searchManagers: [],
      searchDirectReports: [],
      form: {
        searchTerm: null,
        errors: [],
      },
    }
  },

  mounted() {
    // prevent click outside event with popupItem.
    this.popupItem = this.$el
  },

  methods: {
    toggleModals() {
      if (this.modal == 'hide') {
        this.modal = 'menu'
      } else {
        this.modal = 'hide'
      }
      this.searchManagers = []
      this.searchDirectReports = []
      this.form.searchTerm = null
    },

    displayManagerModal() {
      this.modal = 'manager'

      this.$nextTick(() => {
        this.$refs.search.focus()
      })
    },

    displayDirectReportModal() {
      this.modal = 'directReport'

      this.$nextTick(() => {
        this.$refs.search.focus()
      })
    },

    search: _.debounce(
      function() {
        axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/search/hierarchy', this.form)
          .then(response => {
            if (this.modal == 'manager') {
              this.searchManagers = response.data.data
            }
            if (this.modal == 'directReport') {
              this.searchDirectReports = response.data.data
            }
          })
          .catch(error => {
            this.form.errors = _.flatten(_.toArray(error.response.data))
          })
      }, 500),

    assignManager(manager) {
      axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/assignManager', manager)
        .then(response => {
          this.$snotify.success(this.$t('employee.hierarchy_modal_add_manager_success'), {
            timeout: 5000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          })
          this.managers.push(response.data.data)
          this.modal = 'hide'
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data))
        })
    },

    assignDirectReport(directReport) {
      axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/assignDirectReport', directReport)
        .then(response => {
          this.$snotify.success(this.$t('employee.hierarchy_modal_add_direct_report_success'), {
            timeout: 5000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          })
          this.directReports.push(response.data.data)
          this.modal = 'hide'
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data))
        })
    }
  }
}
</script>
