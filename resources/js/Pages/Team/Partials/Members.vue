<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
  }
}

.ball-pulse {
  right: 8px;
  top: 37px;
  position: absolute;
}
</style>

<template>
  <div>
    <h3 class="db fw5 mb3 flex justify-between items-center">
      <span>🤼‍♀️ {{ $tc('team.count_team_members', listOfEmployees.length, { count: listOfEmployees.length }) }}</span>

      <!-- actions -->
      <a v-if="!editMode && $page.auth.employee.permission_level <= 200" href="" class="btn f5" data-cy="manage-team-on" @click.prevent="editMode = true">{{ $t('team.members_enable_manage_mode') }}</a>
      <a v-if="editMode && $page.auth.employee.permission_level <= 200" href="" class="btn f5" data-cy="manage-team-off" @click.prevent="editMode = false">{{ $t('team.members_disable_manage_mode') }}</a>
    </h3>

    <div class="mb4 bg-white box cf">
      <!-- search employees when on edit mode -->
      <div v-show="editMode" class="bb bb-gray pa3">
        <form class="relative" @submit.prevent="search">
          <text-input :id="'title'"
                      v-model="form.searchTerm"
                      :name="'title'"
                      :datacy="'member-input'"
                      :errors="$page.errors.title"
                      :label="$t('team.members_add_input')"
                      :placeholder="$t('team.members_add_input_help')"
                      :required="false"
                      @keyup="search"
                      @input="search"
          />
          <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
        </form>

        <!-- search results -->
        <ul v-show="potentialMembers.length > 0" class="list pl0 ba bb-gray bb-gray-hover">
          <li v-for="employee in potentialMembers" :key="employee.id" class="relative pa2 bb bb-gray">
            {{ employee.name }}
            <a href="" class="fr f6" :data-cy="'employee-id-' + employee.id" @click.prevent="add(employee)">{{ $t('team.members_add_cta') }}</a>
          </li>
        </ul>

        <!-- no results found -->
        <ul v-show="potentialMembers.length == 0 && form.searchTerm" class="list pl0 ba bb-gray bb-gray-hover">
          <li class="relative pa2 bb bb-gray">
            {{ $t('team.members_no_results') }}
          </li>
        </ul>
      </div>

      <!-- list of employees -->
      <div v-show="listOfEmployees.length > 0" class="pa3">
        <div v-for="employee in listOfEmployees" :key="employee.id" class="fl w-third-l w-100 mb4" data-cy="members-list">
          <span class="pl3 db relative team-member">
            <img :src="employee.avatar" class="br-100 absolute avatar" alt="avatar" loading="lazy" />

            <!-- normal mode -->
            <inertia-link v-show="!editMode" :href="'/' + $page.auth.company.id + '/employees/' + employee.id" class="mb2">
              {{ employee.name }}
            </inertia-link>

            <!-- position -->
            <span v-show="!editMode" v-if="employee.position" class="title db f7 mt1">
              {{ employee.position.title }}
            </span>
            <span v-show="!editMode" v-else class="title db f7 mt1">
              {{ $t('app.no_position_defined') }}
            </span>

            <!-- edit mode -->
            <span v-show="editMode" class="mb2">
              {{ employee.name }}
            </span>

            <!-- remove from team -->
            <a v-show="editMode" href="" class="title db f7 mt1 c-delete dib" :data-cy="'remove-employee-' + employee.id" @click.prevent="detach(employee)">
              {{ $t('team.members_remove') }}
            </a>
          </span>
        </div>
      </div>

      <!-- blank state -->
      <div v-show="listOfEmployees.length == 0" class="pa3 tc" data-cy="members-list-blank-state">
        <p class="mv0">{{ $t('team.members_blank') }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import 'vue-loaders/dist/vue-loaders.css';
import BallPulseLoader from 'vue-loaders/src/loaders/ball-pulse';

export default {
  components: {
    TextInput,
    BallPulseLoader,
  },

  props: {
    team: {
      type: Object,
      default: null,
    },
    employees: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      editMode: false,
      form: {
        searchTerm: null,
        errors: [],
      },
      processingSearch: false,
      loadingState: '',
      errorTemplate: Error,
      potentialMembers: [],
      listOfEmployees: {
        type: Array,
        default: [],
      }
    };
  },

  mounted() {
    this.listOfEmployees = this.employees;

    // when a team lead is set, we must react to the event emitted by the
    // TeamLead.vue component and add the team lead to the list of employees
    this.$root.$on('leadSet', employee => {
      var id = this.listOfEmployees.findIndex(member => member.id === employee.id);

      if (id == -1) {
        this.listOfEmployees.push(employee);
      }
    });
  },

  methods: {
    load(employee) {
      this.$inertia.visit('/' + this.$page.auth.company.id + '/employees/' + employee.id);
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post('/' + this.$page.auth.company.id + '/teams/' + this.team.id + '/members/search', this.form)
            .then(response => {
              this.potentialMembers = response.data.data;
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = _.flatten(_.toArray(error.response.data));
              this.processingSearch = false;
              this.addedEmployeeId = 0;
            });
        } else {
          this.potentialMembers = [];
          this.addedEmployeeId = 0;
        }
      }, 500),

    add(employee) {
      axios.post('/' + this.$page.auth.company.id + '/teams/' + this.team.id + '/members/attach/' + employee.id)
        .then(response => {
          this.listOfEmployees.push(response.data.data);
          this.potentialMembers = [];
          this.form.searchTerm = '';

          flash(this.$t('account.employee_statuses_success_destroy'), 'success');
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
          this.potentialMembers = [];
          this.form.searchTerm = '';
        });
    },

    detach(employee) {
      axios.post('/' + this.$page.auth.company.id + '/teams/' + this.team.id + '/members/detach/' + employee.id)
        .then(response => {
          var id = this.listOfEmployees.findIndex(member => member.id === employee.id);
          this.listOfEmployees.splice(id, 1);

          flash(this.$t('account.employee_statuses_success_destroy'), 'success');
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    }
  }
};

</script>
