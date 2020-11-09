<style scoped>
.pronouns-list {
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

.existing-pronouns li:not(:last-child) {
  margin-right: 5px;
}
</style>

<template>
  <div class="di relative">
    <!-- Case when there is a prenoun -->
    <!-- Assigning an employee gender pronoun is restricted to HR or admin -->
    <ul v-if="permissions.can_manage_pronouns && updatedEmployee.pronoun" class="ma0 pa0 di existing-pronouns">
      <li class="di" data-cy="pronoun-label">
        {{ updatedEmployee.pronoun.label }}
      </li>
      <li data-cy="open-pronoun-modal" class="bb b--dotted bt-0 bl-0 br-0 pointer di f6" @click.prevent="modal = true">
        {{ $t('app.edit') }}
      </li>
    </ul>

    <ul v-if="!permissions.can_manage_pronouns && updatedEmployee.pronoun" class="ma0 pa0 existing-pronouns di">
      <li class="di" data-cy="pronoun-name-wrong-permission">
        {{ updatedEmployee.pronoun.label }}
      </li>
    </ul>

    <!-- Action when there is no pronoun defined -->
    <a v-show="!updatedEmployee.pronoun" v-if="permissions.can_manage_pronouns" class="bb b--dotted bt-0 bl-0 br-0 pointer f6" data-cy="open-pronoun-modal-blank" @click.prevent="modal = true">{{ $t('employee.pronoun_modal_cta') }}</a>

    <!-- Modal -->
    <div v-if="modal" v-click-outside="toggleModal" class="popupmenu absolute br2 bg-white z-max tl bounceIn faster">
      <p class="pa2 ma0 bb bb-gray">
        {{ $t('employee.pronoun_modal_title') }}
      </p>

      <form @submit.prevent="search">
        <div class="relative pv2 ph2 bb bb-gray">
          <input id="search" v-model="search" type="text" name="search"
                 :placeholder="$t('employee.pronoun_modal_filter')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                 @keydown.esc="toggleModal"
          />
        </div>
      </form>

      <!-- List of pronouns in modal -->
      <ul class="pl0 list ma0 overflow-auto relative pronouns-list">
        <li v-for="pronoun in filteredList" :key="pronoun.id" :data-cy="'list-pronoun-' + pronoun.id">
          <!-- case if the pronoun is selected -->
          <div v-if="isAssigned(pronoun.id)" class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative" @click="reset(pronoun)">
            {{ pronoun.label }}

            <img loading="lazy" src="/img/check.svg" class="pr1 absolute right-1" alt="check symbol" />
          </div>

          <!-- case if the pronoun is not yet selected -->
          <div v-else class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative f6" @click="assign(pronoun)">
            {{ pronoun.label }}
          </div>
        </li>
        <li>
          <a v-if="updatedEmployee.pronoun" class="pointer pv2 ph3 db no-underline c-delete bb-0 f6" data-cy="pronoun-reset-button" @click="reset(updatedEmployee.pronoun)">
            {{ $t('employee.pronoun_modal_reset') }}
          </a>
        </li>
      </ul>
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
    pronouns: {
      type: Array,
      default: null,
    },
    permissions: {
      type: Object,
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
      // also, sort the list by label
      var list = this.pronouns.filter(pronoun => {
        return pronoun.label.toLowerCase().includes(this.search.toLowerCase());
      });

      return _.sortBy(list, ['label']);
    }
  },

  created() {
    this.updatedEmployee = this.employee;
  },

  methods: {
    toggleModal() {
      this.modal = false;
    },

    assign(pronoun) {
      axios.post('/' + this.$page.props.auth.company.id + '/employees/' + this.employee.id + '/pronoun', pronoun)
        .then(response => {
          flash(this.$t('employee.pronoun_modal_assign_success'), 'success');

          this.updatedEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    reset(pronoun) {
      axios.delete('/' + this.$page.props.auth.company.id + '/employees/' + this.employee.id + '/pronoun/' + pronoun.id)
        .then(response => {
          flash(this.$t('employee.pronoun_modal_unassign_success'), 'success');

          this.updatedEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    isAssigned : function(id) {
      if (!this.updatedEmployee.pronoun) {
        return false;
      }
      if (this.updatedEmployee.pronoun.id === id) {
        return true;
      }
      return false;
    },
  }
};
</script>
