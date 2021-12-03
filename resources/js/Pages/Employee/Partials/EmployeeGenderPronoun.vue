<style lang="scss" scoped>
.pronouns-list {
  max-height: 150px;
}

.popover {
  width: 200px;
  z-index:999;
  left: 10px;
}

.c-delete:hover {
  border-bottom-width: 0;
}

.existing-pronouns li:not(:last-child) {
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
        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
      </svg>
      <span class="f6 title">
        {{ $t('employee.pronoun_title') }}
      </span>

      <a v-show="permissions.can_manage_pronouns" class="bb b--dotted bt-0 bl-0 br-0 pointer di f7 ml2" @click="showPopover">{{ $t('app.edit') }}</a>

      <!-- Modal to update pronouns -->
      <div v-if="showPopup" v-click-outside="hidePopover" class="absolute popupmenu popover z-max">
        <p class="pa2 ma0 bb bb-gray">
          {{ $t('employee.pronoun_modal_title') }}
        </p>

        <form class="bg-white" @submit.prevent="search">
          <div class="relative pv2 ph2 bb bb-gray">
            <input id="search" v-model="search" type="text" name="search"
                   :placeholder="$t('employee.pronoun_modal_filter')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                   @keydown.esc="hidePopover"
            />
          </div>
        </form>

        <!-- List of pronouns in modal -->
        <ul class="pl0 list ma0 overflow-auto relative pronouns-list bg-white">
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
            <a v-if="localEmployee.pronoun" class="pointer pv2 ph3 db no-underline c-delete bb-0 f6" data-cy="pronoun-reset-button" @click="reset(localEmployee.pronoun)">
              {{ $t('employee.pronoun_modal_reset') }}
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Case when there is a prenoun -->
    <ul v-if="permissions.can_manage_pronouns && localEmployee.pronoun" class="ma0 pa0 di existing-pronouns">
      <li class="di" data-cy="pronoun-label">
        {{ localEmployee.pronoun.label }}
      </li>
    </ul>

    <ul v-if="!permissions.can_manage_pronouns && localEmployee.pronoun" class="ma0 pa0 existing-pronouns di">
      <li class="di" data-cy="pronoun-name-wrong-permission">
        {{ localEmployee.pronoun.label }}
      </li>
    </ul>

    <!-- Action when there is no pronoun defined -->
    <span v-if="!localEmployee.pronoun" class="f6">
      {{ $t('employee.pronoun_modal_blank') }}
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
      pronouns: null,
      search: '',
      localEmployee: Object,
      showPopup: false,
    };
  },

  computed: {
    filteredList() {
      // filter the list when searching
      // also, sort the list by label
      if (this.pronouns) {
        var list = this.pronouns.filter(pronoun => {
          return pronoun.label.toLowerCase().includes(this.search.toLowerCase());
        });
      }

      return _.sortBy(list, ['label']);
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
      if (! this.pronouns) {
        axios.get(`${this.$page.props.auth.company.id}/pronouns`)
          .then(response => {
            this.pronouns = response.data.data;
          })
          .catch(error => {
            this.form.errors = error.response.data;
          });
      }
    },

    assign(pronoun) {
      axios.post(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/pronoun`, pronoun)
        .then(response => {
          this.flash(this.$t('employee.pronoun_modal_assign_success'), 'success');

          this.localEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    reset(pronoun) {
      axios.delete(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/pronoun/${pronoun.id}`)
        .then(response => {
          this.flash(this.$t('employee.pronoun_modal_unassign_success'), 'success');

          this.localEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    isAssigned : function(id) {
      if (!this.localEmployee.pronoun) {
        return false;
      }
      if (this.localEmployee.pronoun.id === id) {
        return true;
      }
      return false;
    },
  }
};
</script>
