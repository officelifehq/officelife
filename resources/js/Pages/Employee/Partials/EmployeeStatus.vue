<style scoped>
.statuses-list {
  max-height: 150px;
}

.popupmenu {
  right: 2px;
  top: 26px;
  width: 280px;
}

.c-delete:hover {
  border-bottom-width: 0;
}

.existing-statuses li:not(:last-child) {
  margin-right: 5px;
}
</style>

<template>
  <div class="di relative">
    <!-- Case when there is a status -->
    <!-- Assigning an employee status is restricted to HR or admin -->
    <ul v-if="$page.auth.employee.permission_level <= 200 && updatedEmployee.status" class="ma0 pa0 di existing-statuses">
      <li class="bb b--dotted bt-0 bl-0 br-0 pointer di" data-cy="open-status-modal" @click.prevent="modal = true">
        {{ $t('employee.status_title') }}
      </li>
      <li class="di" data-cy="status-name-right-permission">
        {{ updatedEmployee.status.name }}
      </li>
    </ul>
    <ul v-if="$page.auth.employee.permission_level > 200 && updatedEmployee.status" class="ma0 pa0 existing-statuses di">
      <li class="di">
        {{ $t('employee.status_title') }}
      </li>
      <li class="di" data-cy="status-name-wrong-permission">
        {{ updatedEmployee.status.name }}
      </li>
    </ul>

    <!-- Action when there is no status defined -->
    <a v-show="!updatedEmployee.status" v-if="$page.auth.employee.permission_level <= 200" class="bb b--dotted bt-0 bl-0 br-0 pointer" data-cy="open-status-modal-blank" @click.prevent="modal = true">
      {{ $t('employee.status_modal_cta') }}
    </a>
    <span v-else v-show="!updatedEmployee.status">
      {{ $t('employee.status_modal_blank') }}
    </span>

    <!-- Modal -->
    <div v-if="modal" v-click-outside="toggleModal" class="popupmenu absolute br2 bg-white z-max tl bounceIn faster">
      <!-- Shown when there is at least one status in the account -->
      <div v-show="statuses.length != 0">
        <p class="pa2 ma0 bb bb-gray">
          {{ $t('employee.status_modal_title') }}
        </p>

        <form @submit.prevent="search">
          <div class="relative pv2 ph2 bb bb-gray">
            <input id="search" v-model="search" type="text" name="search"
                   :placeholder="$t('employee.status_modal_filter')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                   @keydown.esc="toggleModal"
            />
          </div>
        </form>

        <!-- List of statuses in modal -->
        <ul class="pl0 list ma0 overflow-auto relative statuses-list">
          <li v-for="status in filteredList" :key="status.id" :data-cy="'list-status-' + status.id">
            <!-- case if the status is selected -->
            <div v-if="isAssigned(status.id)" class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative" @click="reset(status)">
              {{ status.name }}

              <img src="/img/check.svg" class="pr1 absolute right-1" alt="check symbol" />
            </div>

            <!-- case if the status is not yet selected -->
            <div v-else class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative" @click="assign(status)">
              {{ status.name }}
            </div>
          </li>
          <li>
            <a v-if="updatedEmployee.status" class="pointer pv2 ph3 db no-underline c-delete bb-0" data-cy="status-reset-button" @click="reset(updatedEmployee.status)">
              {{ $t('employee.status_modal_reset') }}
            </a>
          </li>
        </ul>
      </div>

      <!-- Shown if there is no statuses setup in the account yet -->
      <div v-show="statuses.length == 0">
        <p class="pa2 tc lh-copy" data-cy="modal-blank-state-copy">
          {{ $t('employee.status_modal_blank_title') }} <inertia-link :href="'/' + $page.auth.company.id + '/account/employeestatuses'" data-cy="modal-blank-state-cta">
            {{ $t('employee.status_modal_blank_cta') }}
          </inertia-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import vClickOutside from 'v-click-outside';

export default {
  directives: {
    clickOutside: vClickOutside.directive
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    statuses: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      search: '',
      updatedEmployee: Object,
    };
  },

  computed: {
    filteredList() {
      // filter the list when searching
      // also, sort the list by name
      var list;
      list = this.statuses.filter(status => {
        return status.name.toLowerCase().includes(this.search.toLowerCase());
      });

      function compare(a, b) {
        if (a.name < b.name)
          return -1;
        if (a.name > b.name)
          return 1;
        return 0;
      }

      return list.sort(compare);
    }
  },

  created() {
    this.updatedEmployee = this.employee;
  },

  methods: {
    toggleModal() {
      this.modal = false;
    },

    assign(status) {
      axios.post('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/employeestatuses', status)
        .then(response => {
          flash(this.$t('employee.status_modal_assign_success'), 'success');

          this.updatedEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    reset(status) {
      axios.delete('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/employeestatuses/' + status.id)
        .then(response => {
          flash(this.$t('employee.status_modal_unassign_success'), 'success');

          this.updatedEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    isAssigned : function(id) {
      if (!this.updatedEmployee.status) {
        return false;
      }
      if (this.updatedEmployee.status.id == id) {
        return true;
      }
      return false;
    }
  }
};

</script>
