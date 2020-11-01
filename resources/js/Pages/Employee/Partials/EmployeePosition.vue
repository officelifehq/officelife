<style scoped>
.positions-list {
  max-height: 150px;
}

.popupmenu {
  right: 2px;
  top: 26px;
  width: 300px;
}

.c-delete:hover {
  border-bottom-width: 0;
}
</style>

<template>
  <div class="di relative">
    <!-- Assigning a title is restricted to HR or admin -->
    <span v-if="permissions.can_manage_position && updatedEmployee.position" data-cy="position-label">
      {{ title }}
      <span data-cy="open-position-modal" class="bb b--dotted bt-0 bl-0 br-0 pointer di" @click.prevent="modal = true">
        {{ $t('app.edit') }}
      </span>
    </span>
    <span v-else data-cy="position-title">
      {{ title }}
    </span>

    <!-- Action when there is no title defined -->
    <a v-show="title == ''" v-if="permissions.can_manage_position" data-cy="open-position-modal-blank" class="bb b--dotted bt-0 bl-0 br-0 pointer" @click.prevent="modal = true">{{ $t('employee.position_modal_title') }}</a>
    <span v-else v-show="title == ''">
      {{ $t('employee.position_blank') }}
    </span>

    <!-- Modal -->
    <div v-if="modal" v-click-outside="toggleModal" class="popupmenu absolute br2 bg-white z-max tl bounceIn faster">
      <p class="pa2 ma0 bb bb-gray">
        {{ $t('employee.position_modal_title') }}
      </p>
      <form @submit.prevent="search">
        <div class="relative pv2 ph2 bb bb-gray">
          <input id="search" v-model="search" type="text" name="search"
                 :placeholder="$t('employee.position_modal_filter')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                 @keydown.esc="toggleModal"
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
    permissions: {
      type: Object,
      default: null,
    },
    positions: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      search: '',
      title: '',
      updatedEmployee: Object,
    };
  },

  computed: {
    filteredList() {
      // filter the list when searching
      // also, sort the list by title
      var list = this.positions.filter(position => {
        return position.title.toLowerCase().includes(this.search.toLowerCase());
      });

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
    toggleModal() {
      this.modal = false;
    },

    assign(position) {
      axios.post('/' + this.$page.props.auth.company.id + '/employees/' + this.employee.id + '/position', position)
        .then(response => {
          flash(this.$t('employee.position_modal_assign_success'), 'success');

          this.title = response.data.data.position.title;
          this.updatedEmployee = response.data.data;
          this.modal = false;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    reset() {
      axios.delete('/' + this.$page.props.auth.company.id + '/employees/' + this.employee.id + '/position/' + this.updatedEmployee.position.id)
        .then(response => {
          flash(this.$t('employee.position_modal_unassign_success'), 'success');

          this.title = '';
          this.modal = false;
          this.updatedEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
