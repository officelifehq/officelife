<style scoped>
.positions-list {
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
</style>

<template>
  <div class="di relative">
    <!-- Assigning a title is restricted to HR or admin -->
    <span v-if="$page.auth.employee.permission_level <= 200" class="bb b--dotted bt-0 bl-0 br-0 pointer" data-cy="open-position-modal" @click.prevent="modal = true">
      {{ title }}
    </span>
    <span v-else data-cy="position-title">
      {{ title }}
    </span>

    <!-- Action when there is no title defined -->
    <a v-show="title == ''" v-if="$page.auth.employee.permission_level <= 200" class="bb b--dotted bt-0 bl-0 br-0 pointer" data-cy="open-position-modal-blank" @click.prevent="modal = true">{{ $t('employee.position_modal_title') }}</a>
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

      <ul class="pl0 list ma0 overflow-auto relative positions-list">
        <li v-for="position in filteredList" :key="position.id" class="pv2 ph3 bb bb-gray-hover bb-gray pointer" :data-cy="'list-position-' + position.id" @click="assign(position)">
          {{ position.title }}
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
      var list;
      list = this.positions.filter(position => {
        return position.title.toLowerCase().includes(this.search.toLowerCase());
      });

      function compare(a, b) {
        if (a.title < b.title)
          return -1;
        if (a.title > b.title)
          return 1;
        return 0;
      }

      return list.sort(compare);
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
      axios.post('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/position', position)
        .then(response => {
          flash(this.$t('employee.position_modal_assign_success'), 'success');

          this.title = response.data.data.position;
          this.updatedEmployee = response.data.data;
          this.modal = false;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    reset() {
      axios.delete('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/position/' + this.updatedEmployee.position.id)
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
