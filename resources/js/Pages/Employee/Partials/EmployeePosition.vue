<style scoped>
.positions-list {
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

.special-color {
  color: #566384;
}
</style>

<template>
  <div class="di relative">
    <span v-if="updatedEmployee.position" data-cy="position-label">
      <span class="special-color">
        {{ title }}
      </span>
      <span v-if="permissions.can_manage_position" data-cy="open-position-modal" class="bb b--dotted bt-0 bl-0 br-0 pointer di f7 ml2" @click.prevent="showPopover">
        {{ $t('app.edit') }}
      </span>
    </span>

    <!-- Action when there is no title defined + has the right to set one -->
    <a v-show="title == ''" v-if="permissions.can_manage_position" data-cy="open-position-modal-blank" class="bb b--dotted bt-0 bl-0 br-0 pointer di f7" @click="showPopover">{{ $t('employee.position_modal_title') }}</a>

    <!-- Action when there is no title defined + doesn't have the right to set one -->
    <span v-else v-show="title == ''">
      {{ $t('employee.position_no_position_set') }}
    </span>

    <!-- Modal -->
    <div v-if="showPopup" v-click-outside="hidePopover" class="absolute popupmenu popover z-max">
      <p class="pa2 ma0 bb bb-gray">
        {{ $t('employee.position_modal_title') }}
      </p>
      <form @submit.prevent="search">
        <div class="relative pv2 ph2 bb bb-gray">
          <input id="search" v-model="search" type="text" name="search"
                 :placeholder="$t('employee.position_modal_filter')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                 @keydown.esc="hidePopover"
          />
        </div>
      </form>

      <!-- list of available positions -->
      <ul class="pl0 list ma0 overflow-auto relative positions-list">
        <li v-for="position in filteredList" :key="position.id" class="pv2 ph3 bb bb-gray-hover bb-gray pointer" :data-cy="'list-position-' + position.id" @click="assign(position)">
          {{ position.title }}

          <!-- current position indicator -->
          <template v-if="updatedEmployee.position">
            <img v-if="position.id == updatedEmployee.position.id" loading="lazy" src="/img/check.svg" class="pr1 absolute right-1" alt="check symbol" />
          </template>
        </li>
      </ul>
      <a v-if="title != ''" class="pointer pv2 ph3 db no-underline c-delete bb-0" data-cy="position-reset-button" @click="reset">
        {{ $t('employee.position_modal_reset') }}
      </a>
    </div>
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
      positions: null,
      search: '',
      title: '',
      updatedEmployee: Object,
      showPopup: false,
    };
  },

  computed: {
    filteredList() {
      // filter the list when searching
      // also, sort the list by title
      if (this.positions) {
        var list = this.positions.filter(position => {
          return position.title.toLowerCase().includes(this.search.toLowerCase());
        });
      }

      return _.sortBy(list, ['title']);
    }
  },

  mounted() {
    if (this.employee.position != null) {
      this.title = this.employee.position.title;
    }

    this.updatedEmployee = this.employee;
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
      if (! this.positions) {
        axios.get(`${this.$page.props.auth.company.id}/positions`)
          .then(response => {
            this.positions = response.data.data;
          })
          .catch(error => {
            this.form.errors = error.response.data;
          });
      }
    },

    assign(position) {
      axios.post('/' + this.$page.props.auth.company.id + '/employees/' + this.employee.id + '/position', position)
        .then(response => {
          this.flash(this.$t('employee.position_modal_assign_success'), 'success');

          this.title = response.data.data.position.title;
          this.updatedEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    reset() {
      axios.delete('/' + this.$page.props.auth.company.id + '/employees/' + this.employee.id + '/position/' + this.updatedEmployee.position.id)
        .then(response => {
          this.flash(this.$t('employee.position_modal_unassign_success'), 'success');

          this.title = '';
          this.updatedEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
