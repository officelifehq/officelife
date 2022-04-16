<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';

.list-employees > ul {
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

.list-employees-action {
  top: 15px;
}

.list-employees-modal.popupmenu {
  right: -5px;
  top: 33px;
}

.icon-delete {
  top: 2px;
}

.ball-pulse {
  right: 8px;
  top: 10px;
  position: absolute;
}

.popupmenu {
  right: 22px;
  top: 63px;

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
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        👨‍✈️
      </span> {{ $t('employee.hierarchy_title') }}
    </span>
    <img v-show="permissions.can_manage_hierarchy" loading="lazy" src="/img/plus_button.svg" class="box-plus-button absolute br-100 pa2 bg-white pointer" data-cy="add-hierarchy-button"
         width="22"
         height="22" alt="add button"
         @click.prevent="showPopover()"
    />

    <!-- MENU TO CHOOSE FROM -->
    <div v-if="modal == 'menu'" v-click-outside="hidePopover" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3">
      <ul class="list ma0 pa0">
        <li class="pv2">
          <a class="pointer" data-cy="add-manager-button" @click.prevent="displayManagerModal()">
            {{ $t('employee.hierarchy_modal_add_manager') }}
          </a>
        </li>
        <li class="pv2">
          <a class="pointer" data-cy="add-direct-report-button" @click.prevent="displayDirectReportModal()">
            {{ $t('employee.hierarchy_modal_add_direct_report') }}
          </a>
        </li>
      </ul>
    </div>

    <!-- ADD MANAGER -->
    <div v-if="modal == 'manager'" v-click-outside="hidePopover" class="absolute popupmenu popover bg-white z-max tl pv2 ph3">
      <!-- FORM to search -->
      <form @submit.prevent="search">
        <div class="mb3 relative">
          <p>{{ $t('employee.hierarchy_modal_add_manager_search', { name: employee.first_name}) }}</p>
          <div class="relative">
            <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                   :placeholder="$t('employee.hierarchy_search_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required data-cy="search-manager"
                   @keyup="search" @keydown.esc="hidePopover()"
            />
            <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
          </div>
        </div>
      </form>

      <!-- RESULTS -->
      <ul class="pl0 list ma0">
        <li class="fw5 mb3">
          <span class="f6 mb2 dib">
            {{ $t('employee.hierarchy_search_results') }}
          </span>
          <ul v-if="searchManagers.length > 0" class="list ma0 pl0">
            <li v-for="manager in searchManagers" :key="manager.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
              {{ manager.name }}
              <a class="absolute right-1 pointer" data-cy="potential-manager-button" @click.prevent="assignManager(manager)">
                {{ $t('app.choose') }}
              </a>
            </li>
          </ul>
          <div v-else class="silver">
            {{ $t('app.no_results') }}
          </div>
        </li>
      </ul>
    </div>

    <!-- ADD DIRECT REPORT -->
    <div v-if="modal == 'directReport'" v-click-outside="hidePopover" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3">
      <!-- FORM to search -->
      <form @submit.prevent="search">
        <div class="mb3 relative">
          <p>{{ $t('employee.hierarchy_modal_add_direct_report_search', { name: employee.first_name}) }}</p>
          <div class="relative">
            <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                   :placeholder="$t('employee.hierarchy_search_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required data-cy="search-direct-report"
                   @keyup="search" @keydown.esc="hidePopover()"
            />
            <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
          </div>
        </div>
      </form>

      <!-- RESULTS -->
      <ul class="pl0 list ma0">
        <li class="fw5 mb3">
          <span class="f6 mb2 dib">
            {{ $t('employee.hierarchy_search_results') }}
          </span>
          <ul v-if="searchDirectReports.length > 0" class="list ma0 pl0">
            <li v-for="directReport in searchDirectReports" :key="directReport.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
              {{ directReport.name }}
              <a class="absolute right-1 pointer" data-cy="potential-direct-report-button" @click.prevent="assignDirectReport(directReport)">
                {{ $t('app.choose') }}
              </a>
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
      <p v-if="localManagersOfEmployee.length == 0 && localDirectReports.length == 0" class="lh-copy mb0 mt0 f6">
        {{ $t('employee.hierarchy_blank') }}
      </p>

      <!-- Managers -->
      <template v-if="localManagersOfEmployee.length != 0">
        <div data-cy="list-managers">
          <p class="mt0 mb2 f6">
            {{ $tc('employee.hierarchy_list_manager_title', localManagersOfEmployee.length) }}
          </p>
          <ul class="list mv0">
            <li v-for="manager in localManagersOfEmployee" :key="manager.id" class="mb3 relative bb-gray-hover">
              <avatar :avatar="manager.avatar" :size="35" :class="'br-100 absolute avatar'" />
              <inertia-link :href="manager.url" class="mb2">
                {{ manager.name }}
              </inertia-link>

              <!-- position -->
              <span v-if="manager.position !== null" class="title db f7 mt1">
                {{ manager.position.title }}
              </span>
              <span v-else class="title db f7 mt1">
                {{ $t('app.no_position_defined') }}
              </span>

              <img v-if="permissions.can_manage_hierarchy" loading="lazy" src="/img/common/triple-dots.svg" alt="triple dot symbol" class="absolute right-0 pointer list-employees-action"
                   data-cy="display-remove-manager-modal"
                   @click="managerModalId = manager.id"
              />

              <!-- DELETE MANAGER MENU -->
              <template v-if="managerModalId == manager.id">
                <div v-show="permissions.can_manage_hierarchy" v-click-outside="hideManagerModal" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal">
                  <ul class="list ma0 pa0">
                    <li v-show="!deleteEmployeeConfirmation" class="pv2 relative">
                      <icon-delete :class="'icon-delete relative'" :width="15" :height="15" />
                      <a class="pointer ml1 c-delete" data-cy="remove-manager-button" @click.prevent="deleteEmployeeConfirmation = true">
                        {{ $t('employee.hierarchy_modal_remove_manager') }}
                      </a>
                    </li>
                    <li v-show="deleteEmployeeConfirmation" class="pv2">
                      {{ $t('app.sure') }}
                      <a data-cy="confirm-remove-manager" class="c-delete mr1 pointer" @click.prevent="unassignManager(manager)">
                        {{ $t('app.yes') }}
                      </a>
                      <a class="pointer" @click.prevent="deleteEmployeeConfirmation = false">
                        {{ $t('app.no') }}
                      </a>
                    </li>
                  </ul>
                </div>
              </template>
            </li>
          </ul>
        </div>
      </template>

      <!-- Direct reports -->
      <template v-if="localDirectReports.length != 0">
        <div :class="localManagersOfEmployee.length != 0 ? 'mt3' : ''" data-cy="list-direct-reports">
          <p class="mt0 mb2 f6">
            {{ $tc('employee.hierarchy_list_direct_report_title', localDirectReports.length) }}
          </p>
          <ul class="list mv0">
            <li v-for="directReport in localDirectReports" :key="directReport.id" class="mb3 relative bb-gray-hover">
              <!-- avatar -->
              <avatar :avatar="directReport.avatar" :size="35" :class="'br-100 absolute avatar'" />

              <!-- name -->
              <inertia-link :href="directReport.url" class="mb2">
                {{ directReport.name }}
              </inertia-link>

              <!-- position -->
              <span v-if="directReport.position !== null" class="title db f7 mt1">
                {{ directReport.position.title }}
              </span>
              <span v-else class="title db f7 mt1">
                {{ $t('app.no_position_defined') }}
              </span>

              <img v-if="permissions.can_manage_hierarchy" loading="lazy" src="/img/common/triple-dots.svg" alt="triple dot symbol" class="absolute right-0 pointer list-employees-action"
                   data-cy="display-remove-directreport-modal"
                   @click="directReportModalId = directReport.id"
              />

              <!-- DELETE DIRECT REPORT MENU -->
              <template v-if="directReportModalId == directReport.id">
                <div v-show="permissions.can_manage_hierarchy" v-click-outside="hideDirectReportModal" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal">
                  <ul class="list ma0 pa0">
                    <li v-show="!deleteEmployeeConfirmation" class="pv2 relative">
                      <icon-delete :class="'icon-delete relative'" :width="15" :height="15" />
                      <a class="pointer ml1 c-delete" data-cy="remove-directreport-button" @click.prevent="deleteEmployeeConfirmation = true">
                        {{ $t('employee.hierarchy_modal_remove_direct_report') }}
                      </a>
                    </li>
                    <li v-show="deleteEmployeeConfirmation" class="pv2">
                      {{ $t('app.sure') }}
                      <a data-cy="confirm-remove-directreport" class="c-delete mr1 pointer" @click.prevent="unassignDirectReport(directReport)">
                        {{ $t('app.yes') }}
                      </a>
                      <a class="pointer" @click.prevent="deleteEmployeeConfirmation = false">
                        {{ $t('app.no') }}
                      </a>
                    </li>
                  </ul>
                </div>
              </template>
            </li>
          </ul>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import IconDelete from '@/Shared/IconDelete';
import Avatar from '@/Shared/Avatar';
import vClickOutside from 'click-outside-vue3';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';

export default {
  components: {
    'ball-pulse-loader': BallPulseLoader.component,
    IconDelete,
    Avatar,
  },

  directives: {
    clickOutside: vClickOutside.directive
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    permissions: {
      type: Object,
      default: null,
    },
    managersOfEmployee: {
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
      localManagersOfEmployee: [],
      localDirectReports: [],
      modal: 'hide',
      processingSearch: false,
      searchManagers: [],
      searchDirectReports: [],
      form: {
        searchTerm: null,
        errors: [],
      },
      managerModalId: 0,
      directReportModalId: 0,
      deleteEmployeeConfirmation: false,
    };
  },

  watch: {
    managersOfEmployee: {
      handler(value) {
        this.localManagersOfEmployee = value;
      },
      deep: true
    },
    directReports: {
      handler(value) {
        this.localDirectReports = value;
      },
      deep: true
    },
  },

  mounted() {
    this.localManagersOfEmployee = this.managersOfEmployee;
    this.localDirectReports = this.directReports;
  },

  methods: {
    hidePopover: function() {
      this.modal = 'hide';
    },

    showPopover: function() {
      this.modal = 'menu';
      this.searchManagers = [];
      this.searchDirectReports = [];
      this.form.searchTerm = null;
    },

    displayManagerModal() {
      this.modal = 'manager';

      this.$nextTick(() => {
        this.$refs.search.focus();
      });
    },

    displayDirectReportModal() {
      this.modal = 'directReport';

      this.$nextTick(() => {
        this.$refs.search.focus();
      });
    },

    hideManagerModal() {
      this.managerModalId = 0;
    },

    hideDirectReportModal() {
      this.directReportModalId = 0;
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post('/' + this.$page.props.auth.company.id + '/employees/' + this.employee.id + '/search/hierarchy', this.form)
            .then(response => {
              if (this.modal == 'manager') {
                this.searchManagers = response.data.data;
              }
              if (this.modal == 'directReport') {
                this.searchDirectReports = response.data.data;
              }
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
            });
        }
      }, 500),

    assignManager(manager) {
      axios.put(this.route('employee.manager.assign', [this.$page.props.auth.company.id, this.employee.id]), manager)
        .then(response => {
          this.flash(this.$t('employee.hierarchy_modal_add_manager_success'), 'success');
          this.localManagersOfEmployee.push(response.data.data);
          this.modal = 'hide';
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    assignDirectReport(directReport) {
      axios.put(this.route('employee.directReport.assign', [this.$page.props.auth.company.id, this.employee.id]), directReport)
        .then(response => {
          this.flash(this.$t('employee.hierarchy_modal_add_direct_report_success'), 'success');
          this.localDirectReports.push(response.data.data);
          this.modal = 'hide';
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    unassignManager(manager) {
      axios.put(this.route('employee.manager.unassign', [this.$page.props.auth.company.id, this.employee.id]), manager)
        .then(response => {
          this.flash(this.$t('employee.hierarchy_modal_remove_manager_success'), 'success');

          this.localManagersOfEmployee.splice(this.localManagersOfEmployee.findIndex(i => i.id === response.data.data.id), 1);
          this.deleteEmployeeConfirmation = false;
          this.managerModalId = 0;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    unassignDirectReport(directReport) {
      axios.put(this.route('employee.directReport.unassign', [this.$page.props.auth.company.id, this.employee.id]), directReport)
        .then(response => {
          this.flash(this.$t('employee.hierarchy_modal_remove_direct_report_success'), 'success');

          this.localDirectReports.splice(this.localDirectReports.findIndex(i => i.id === response.data.data.id), 1);
          this.deleteEmployeeConfirmation = false;
          this.directReportModalId = 0;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    }
  }
};
</script>
