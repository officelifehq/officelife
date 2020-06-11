<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 23px;
}

.team-member {
  padding-left: 34px;

  .avatar {
    top: -2px;
  }
}

.ball-pulse {
  right: 8px;
  top: 37px;
  position: absolute;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/teams/' + team.id">{{ team.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_team_add_recent_ship') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4">
            {{ $t('team.team_news_create', { name: team.name}) }}
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Title -->
            <text-input :id="'title'"
                        v-model="form.title"
                        :name="'title'"
                        :datacy="'news-title-input'"
                        :errors="$page.errors.title"
                        :label="$t('team.recent_ship_new_title')"
                        :help="$t('team.team_news_new_title_help')"
                        :required="true"
            />

            <!-- Description -->
            <p v-if="!showDescription" @click.prevent="showDescription = true">+ Add description</p>
            <div v-if="showDescription" class="">
              <text-area v-model="form.content"
                         :label="$t('team.recent_ship_new_description')"
                         :datacy="'news-content-textarea'"
                         :required="false"
                         :rows="10"
                         :help="$t('team.team_news_new_content_help')"
              />
            </div>

            <!-- Attach team members -->
            <p @click.prevent="showTeamMembers = true">+ Give credit to specific team members (from this team or from another)</p>
            <template>
              <div class="bb bb-gray">
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
                    <a href="" class="fr f6" :data-cy="'employee-id-' + employee.id" @click.prevent="add(employee)">{{ $t('app.add') }}</a>
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
              <h3 v-show="form.employees.length > 0">
                Collaborators associated with this recent ship
              </h3>
              <div v-show="form.employees.length > 0" class="ba bb-gray">
                <div v-for="employee in form.employees" :key="employee.id" class="pa2 db bb-gray bb" data-cy="members-list">
                  <span class="pl3 db relative team-member">
                    <img :src="employee.avatar" class="br-100 absolute avatar" alt="avatar" loading="lazy" />

                    {{ employee.name }}

                    <!-- remove -->
                    <a href="#" class="db f7 mt1 c-delete dib fr" :data-cy="'remove-employee-' + employee.id" @click.prevent="detach(employee)">
                      {{ $t('team.members_remove') }}
                    </a>
                  </span>
                </div>
              </div>
            </template>

            <!-- Actions -->
            <div class="mv4">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.auth.company.id + '/teams/' + team.id" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.publish')" :cypress-selector="'submit-add-news-button'" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import TextArea from '@/Shared/TextArea';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import 'vue-loaders/dist/vue-loaders.css';
import BallPulseLoader from 'vue-loaders/src/loaders/ball-pulse';

export default {
  components: {
    Layout,
    TextInput,
    TextArea,
    Errors,
    LoadingButton,
    BallPulseLoader,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    team: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      showDescription: false,
      showTeamMembers: false,
      form: {
        title: null,
        description: null,
        searchTerm: null,
        employees: [],
        errors: [],
      },
      processingSearch: false,
      loadingState: '',
      potentialMembers: [],
      errorTemplate: Error,
    };
  },

  computed: {

  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/teams/' + this.team.id + '/news', this.form)
        .then(response => {
          localStorage.success = this.$t('team.team_news_create_success');
          this.$inertia.visit('/' + this.$page.auth.company.id + '/teams/' + this.team.id);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post('/' + this.$page.auth.company.id + '/teams/' + this.team.id + '/ships/search', this.form)
            .then(response => {

              const searchResults = response.data.data;

              // filter out the employees that are already in the list of employees
              for (let index = 0; index < this.form.employees.length; index++) {
                const employee = this.form.employees[index];
                var found = false;

                for (let otherIndex = 0; otherIndex < searchResults.length; otherIndex++) {
                  if (employee.id == searchResults[otherIndex].id) {
                    console.log('id: ' + searchResults[otherIndex].id);
                    found = true;
                    break;
                  }
                }

                if (found == true) {
                  //wsearchResults.splice(otherIndex, 1);
                  this.$delete(searchResults, otherIndex);
                }
              }
              this.potentialMembers = searchResults;
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = _.flatten(_.toArray(error.response.data));
              this.processingSearch = false;
            });
        } else {
          this.potentialMembers = [];
        }
      }, 500),

    add(employee) {
      var id = this.form.employees.findIndex(x => x.id === employee.id);

      if (id == -1) {
        this.form.employees.push(employee);
        this.potentialMembers = [];
        this.showTeamMembers = false;
      }
    },

    detach(employee) {
      var id = this.form.employees.findIndex(member => member.id === employee.id);
      this.form.employees.splice(id, 1);
    }
  }
};

</script>
