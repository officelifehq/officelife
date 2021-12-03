<style lang="scss" scoped>
.statuses-list {
  max-height: 150px;
}

.popover {
  width: 300px;
}

.popupmenu {
  left: 0;
  top: 33px;
}

.c-delete:hover {
  border-bottom-width: 0;
}

.existing-statuses li:not(:last-child) {
  margin-right: 5px;
}

.icon {
  color: #9AA7BF;
  position: relative;
  width: 17px;
  top: 3px;
}

.title {
  color: #757982;
}
</style>

<template>
  <div class="mb4 relative">
    <div class="db fw4 mb3 relative">
      <svg class="icon mr1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
      </svg>
      <span class="f6 title">
        {{ $t('employee.status_title') }}
      </span>
    </div>

    <!-- Modal -->
    <div v-if="showPopup" v-click-outside="hidePopover" class="absolute popupmenu popover bg-white z-max">
      <!-- Shown when there is at least one status in the account -->
      <div v-if="statuses">
        <p class="pa2 ma0 bb bb-gray">
          {{ $t('employee.status_modal_title') }}
        </p>

        <form @submit.prevent="search">
          <div class="relative pv2 ph2 bb bb-gray">
            <input id="search" v-model="search" type="text" name="search"
                   :placeholder="$t('employee.status_modal_filter')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                   @keydown.esc="hidePopover"
            />
          </div>
        </form>

        <!-- List of statuses in modal -->
        <ul class="pl0 list ma0 overflow-auto relative statuses-list">
          <li v-for="status in filteredList" :key="status.id" :data-cy="'list-status-' + status.id">
            <!-- case if the status is selected -->
            <div v-if="isAssigned(status.id)" class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative" @click="reset(status)">
              {{ status.name }}

              <img loading="lazy" src="/img/check.svg" class="pr1 absolute right-1" alt="check symbol" />
            </div>

            <!-- case if the status is not yet selected -->
            <div v-else class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative" @click="assign(status)">
              {{ status.name }}
            </div>
          </li>
          <li>
            <a v-if="localEmployee.status" class="pointer pv2 ph3 db no-underline c-delete bb-0" data-cy="status-reset-button" @click="reset(localEmployee.status)">
              {{ $t('employee.status_modal_reset') }}
            </a>
          </li>
        </ul>
      </div>

      <!-- Shown if there is no statuses setup in the account yet -->
      <div v-if="!statuses">
        <p class="pa2 tc lh-copy" data-cy="modal-blank-state-copy">
          {{ $t('employee.status_modal_blank_title') }} <inertia-link :href="'/' + $page.props.auth.company.id + '/account/employeestatuses'" data-cy="modal-blank-state-cta">
            {{ $t('employee.status_modal_blank_cta') }}
          </inertia-link>
        </p>
      </div>
    </div>

    <!-- Case when there is a status -->
    <ul v-if="localEmployee.status" class="ma0 pa0 di existing-statuses list">
      <li class="mb2" data-cy="status-name-right-permission">
        {{ localEmployee.status.name }}

        <a v-show="permissions.can_manage_status" data-cy="edit-status-button" class="bb b--dotted bt-0 bl-0 br-0 pointer di f7 ml2" @click.prevent="showPopover()">{{ $t('app.edit') }}</a>
      </li>

      <!-- contract renewed at date -->
      <li v-if="localEmployee.contract_renewed_at && permissions.can_manage_status" class="lh-copy mb1" data-cy="employee-contract-renewal-date">
        {{ $t('employee.contract_renewal_date', { date: localEmployee.contract_renewed_at.date }) }}

        <inertia-link :href="employee.url.edit_contract" class="bb b--dotted bt-0 bl-0 br-0 pointer di f7 ml2">{{ $t('app.edit') }}</inertia-link>
      </li>

      <!-- contract rate -->
      <li v-if="localEmployee.contract_rate && permissions.can_manage_status" class="lh-copy mb1" data-cy="employee-contract-rate">
        {{ $t('employee.contract_renewal_rate', { rate: localEmployee.contract_rate.rate, currency: localEmployee.contract_rate.currency }) }}
      </li>
    </ul>

    <!-- Action when there is no status defined -->
    <span v-if="!localEmployee.status" class="f6">
      {{ $t('employee.status_modal_blank') }}

      <a v-show="permissions.can_manage_status" data-cy="edit-status-button" class="bb b--dotted bt-0 bl-0 br-0 pointer di f7 ml2" @click.prevent="showPopover()">{{ $t('app.edit') }}</a>
    </span>
  </div>
</template>

<script>
import vClickOutside from 'click-outside-vue3';

export default {
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
  },

  data() {
    return {
      statuses: null,
      modal: false,
      search: '',
      localEmployee: Object,
      showPopup: false,
    };
  },

  computed: {
    filteredList() {
      // filter the list when searching
      // also, sort the list by name
      if (!this.statuses) {
        return;
      }

      var list = this.statuses.filter(status => {
        return status.name.toLowerCase().includes(this.search.toLowerCase());
      });

      return _.sortBy(list, ['name']);
    }
  },

  created() {
    this.localEmployee = this.employee;
  },

  methods: {
    hidePopover: function() {
      this.showPopup = false;
    },

    showPopover: function() {
      this.load();
      this.showPopup = true;
    },

    load() {
      if (! this.statuses) {
        axios.get(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/employeestatuses`)
          .then(response => {
            this.statuses = response.data.data;
          })
          .catch(error => {
            this.form.errors = error.response.data;
          });
      }
    },

    assign(status) {
      axios.post(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/employeestatuses`, status)
        .then(response => {
          this.flash(this.$t('employee.status_modal_assign_success'), 'success');

          this.localEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    reset(status) {
      axios.delete(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/employeestatuses/${status.id}`)
        .then(response => {
          this.flash(this.$t('employee.status_modal_unassign_success'), 'success');

          this.localEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    isAssigned : function(id) {
      if (!this.localEmployee.status) {
        return false;
      }
      if (this.localEmployee.status.id == id) {
        return true;
      }
      return false;
    }
  }
};

</script>
