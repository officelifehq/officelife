<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';

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

.plus-button {
  padding: 2px 7px 4px;
  margin-right: 4px;
  border-color: #60995c;
  color: #60995c;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/teams/' + team.id"
                  :previous="team.name"
      >
        {{ $t('app.breadcrumb_team_add_recent_ship') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('team.recent_ship_create', { name: team.name}) }}

            <help :url="$page.props.help_links.team_recent_ship_create" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Title -->
            <text-input :id="'title'"
                        v-model="form.title"
                        :name="'title'"
                        :datacy="'recent-ship-title-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('team.recent_ship_new_title')"
                        :help="$t('team.team_news_new_title_help')"
                        :required="true"
            />

            <!-- Description -->
            <p v-if="!showDescription" class="bt bb-gray pt3 pointer" data-cy="ship-add-description" @click.prevent="showDescription = true"><span class="ba br-100 plus-button">+</span> Add description</p>
            <div v-if="showDescription" class="">
              <text-area v-model="form.description"
                         :label="$t('team.recent_ship_new_description')"
                         :datacy="'ship-description'"
                         :required="false"
                         :rows="10"
                         :help="$t('team.team_news_new_content_help')"
              />
            </div>

            <!-- list of people who worked on this ship -->
            <p v-if="!showTeamMembers && form.employees.length == 0" class="bt bb-gray pt3 pointer" data-cy="ship-add-employees" @click.prevent="showTeamMembers = true"><span class="ba br-100 plus-button">+</span> Give credit to specific people</p>
            <p v-if="!showTeamMembers && form.employees.length > 0" class="bt bb-gray pt3 pointer" data-cy="ship-add-employees" @click.prevent="showTeamMembers = true"><span class="ba br-100 plus-button">+</span> Add additional credits</p>

            <div v-if="showTeamMembers == true" class="bb bb-gray bt pt3">
              <form class="relative" @submit.prevent="search">
                <text-input :id="'name'"
                            v-model="form.searchTerm"
                            :name="'name'"
                            :datacy="'ship-employees'"
                            :errors="$page.props.errors.name"
                            :label="$t('team.recent_ship_new_credit')"
                            :placeholder="$t('team.recent_ship_new_credit_help')"
                            :required="false"
                            @keyup="search"
                            @update:model-value="search"
                            @esc-key-pressed="showTeamMembers = false"
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

            <div v-show="form.employees.length > 0" class="ba bb-gray mb3 mt4">
              <div v-for="employee in form.employees" :key="employee.id" class="pa2 db bb-gray bb" data-cy="members-list">
                <span class="pl3 db relative team-member">
                  <avatar :avatar="employee.avatar" :size="23" :class="'br-100 absolute avatar'" />

                  {{ employee.name }}

                  <!-- remove -->
                  <a href="#" class="db f7 mt1 c-delete dib fr" :data-cy="'remove-employee-' + employee.id" @click.prevent="detach(employee)">
                    {{ $t('app.remove') }}
                  </a>
                </span>
              </div>
            </div>

            <!-- Actions -->
            <div class="mb4 mt5">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/teams/' + team.id" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'submit-add-ship-button'" />
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';
import Help from '@/Shared/Help';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    TextInput,
    TextArea,
    Errors,
    LoadingButton,
    'ball-pulse-loader': BallPulseLoader.component,
    Help
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

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/teams/' + this.team.id + '/ships', this.form)
        .then(response => {
          localStorage.success = this.$t('team.recent_ship_create_success');
          this.$inertia.visit('/' + this.$page.props.auth.company.id + '/teams/' + this.team.id);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post(`/${this.$page.props.auth.company.id}/teams/${this.team.id}/ships/search`, this.form)
            .then(response => {
              this.potentialMembers = _.filter(response.data.data, employee => _.every(this.form.employees, e => employee.id !== e.id));
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = error.response.data;
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
        this.form.searchTerm = null;
      }
    },

    detach(employee) {
      var id = this.form.employees.findIndex(member => member.id === employee.id);
      this.form.employees.splice(id, 1);
    }
  }
};

</script>
