<style scoped>
.teams-list {
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
    <!-- Assigning a team is restricted to HR or admin -->
    <span v-if="user.permission_level <= 200" class="bb b--dotted bt-0 bl-0 br-0 pointer" @click.prevent="modal = true">{{ name }}</span>
    <span v-else>{{ name }}</span>

    <!-- Action when there is no team defined -->
    <a v-show="name == ''" v-if="user.permission_level <= 200" class="pointer" @click.prevent="modal = true">{{ $t('employee.team_modal_title') }}</a>
    <span v-else v-show="name == ''">{{ $t('employee.team_blank') }}</span>

    <!-- Modal -->
    <div v-if="modal" v-click-outside="toggleModal" class="popupmenu absolute br2 bg-white z-max tl bounceIn faster">
      <p class="pa2 ma0 bb bb-gray">
        {{ $t('employee.team_modal_title') }}
      </p>
      <form @submit.prevent="search">
        <div class="relative pv2 ph2 bb bb-gray">
          <input id="search" v-model="search" type="text" name="search"
                 :placeholder="$t('employee.team_modal_filter')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                 @keydown.esc="toggleModal"
          />
        </div>
      </form>

      <ul class="pl0 list ma0 overflow-auto relative teams-list">
        <li v-for="team in filteredList" :key="team.id" class="pv2 ph3 bb bb-gray-hover bb-gray pointer" @click="assign(team)">
          {{ team.name }}
        </li>
      </ul>
      <a v-if="name != ''" class="pointer pv2 ph3 db no-underline c-delete bb-0" @click="reset">{{ $t('employee.team_modal_reset') }}</a>
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
    employee: {
      type: Object,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      search: '',
      name: '',
      updatedTeam: Object,
    }
  },

  computed: {
    filteredList() {
      // filter the list when searching
      // also, sort the list by name
      var list
      list = this.teams.filter(team => {
        return team.name.toLowerCase().includes(this.search.toLowerCase())
      })

      function compare(a, b) {
        if (a.name < b.name)
          return -1
        if (a.name > b.name)
          return 1
        return 0
      }

      return list.sort(compare)
    }
  },

  mounted() {
    if (this.employee.team != null) {
      this.name = this.employee.team.name
    }

    this.updatedTeam = this.team
  },

  methods: {
    toggleModal() {
      this.modal = false
    },

    assign(team) {
      axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/team', team)
        .then(response => {
          this.$snotify.success(this.$t('employee.team_modal_assign_success'), {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          })

          this.name = response.data.data.team.name
          this.updatedTeam = response.data.data
          this.modal = false
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data))
        })
    },

    reset() {
      axios.delete('/' + this.company.id + '/employees/' + this.employee.id + '/team/' + this.updatedTeam.team.id)
        .then(response => {
          this.$snotify.success(this.$t('employee.team_modal_unassign_success'), {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          })

          this.title = ''
          this.modal = false
          this.updatedTeam = response.data.data
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data))
        })
    },
  }
}

</script>
