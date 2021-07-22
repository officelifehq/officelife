<style lang="scss" scoped>
input[type=checkbox] {
  top: 5px;
}

input[type=radio] {
  top: -2px;
}

.plus-button {
  padding: 2px 7px 4px;
  margin-right: 4px;
  border-color: #60995c;
  color: #60995c;
}

.sponsor {
  padding-left: 34px;

  .avatar {
    top: -2px;
    left: 2px;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account/softwares'">{{ $t('app.breadcrumb_account_manage_softwares') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_add_software') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <h2 class="pa3 mt5 center tc normal mb2">
          Create a new job opening

          <help :url="$page.props.help_links.softwares" :top="'1px'" />
        </h2>

        <div class="cf">
          <form @submit.prevent="submit">
            <div v-if="form.errors.length > 0" class="pa3">
              <errors :errors="form.errors" />
            </div>

            <!-- position -->
            <div class="cf pa3 bb bb-gray">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>Position</strong>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <!-- job position -->
                <select-box :id="'position'"
                            v-model="form.position"
                            :options="positions"
                            :name="'position'"
                            :errors="$page.props.errors.position"
                            :placeholder="'Select a position'"
                            :required="true"
                            :extra-class-upper-div="'mb0'"
                            :label="'What position is this job opening for?'"
                />
              </div>
            </div>

            <!-- sponsors -->
            <div class="cf pa3 bb bb-gray">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>Sponsors</strong>
                <p class="f7 silver lh-copy">
                  A sponsor is responsible for the new hire.
                </p>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <!-- cta to add a sponsor -->
                <p v-if="!showSponsors && form.sponsors.length == 0" class="pointer" @click.prevent="showSponsors = true"><span class="ba br-100 plus-button">+</span> Add sponsors</p>

                <!-- cta to add another sponsor -->
                <p v-if="!showSponsors && form.sponsors.length > 0" class="pointer ma0" @click.prevent="showSponsors = true"><span class="ba br-100 plus-button">+</span> Add additional sponsors</p>

                <!-- search sponsor form -->
                <div v-if="showSponsors == true">
                  <form class="relative" @submit.prevent="search">
                    <text-input :id="'name'"
                                v-model="form.searchTerm"
                                :name="'name'"
                                :errors="$page.props.errors.name"
                                :label="'Find a sponsor by typing a name'"
                                :placeholder="$t('group.create_members_help')"
                                :required="true"
                                @keyup="search"
                                @input="search"
                                @esc-key-pressed="showSponsors = false"
                    />
                    <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
                  </form>

                  <!-- search results -->
                  <ul v-show="potentialSponsors.length > 0" class="list pl0 ba bb-gray bb-gray-hover">
                    <li v-for="employee in potentialSponsors" :key="employee.id" class="relative pa2 bb bb-gray">
                      {{ employee.name }}
                      <a href="" class="fr f6" @click.prevent="add(employee)">{{ $t('app.add') }}</a>
                    </li>
                  </ul>

                  <!-- no results found -->
                  <ul v-show="potentialSponsors.length == 0 && form.searchTerm" class="list pl0 ba bb-gray bb-gray-hover">
                    <li class="relative pa2 bb bb-gray">
                      {{ $t('team.members_no_results') }}
                    </li>
                  </ul>
                </div>

                <!-- list of existing sponsors -->
                <div v-show="form.sponsors.length > 0" class="ba bb-gray mb3 mt3">
                  <div v-for="employee in form.sponsors" :key="employee.id" class="pa2 db bb-gray bb">
                    <span class="pl3 db relative sponsor">
                      <avatar :avatar="employee.avatar" :size="23" :class="'avatar absolute br-100'" />

                      {{ employee.name }}

                      <!-- remove -->
                      <a href="#" class="db f7 mt1 c-delete dib fr" @click.prevent="detach(employee)">
                        {{ $t('app.remove') }}
                      </a>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- team -->
            <div class="cf pa3 bb bb-gray">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>Position</strong>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <!-- job position -->
                <select-box :id="'position'"
                            v-model="form.position"
                            :options="positions"
                            :name="'position'"
                            :errors="$page.props.errors.position"
                            :placeholder="'Select a position'"
                            :required="true"
                            :extra-class-upper-div="'mb0'"
                            :label="'What position is this job opening for?'"
                />
              </div>
            </div>

            <!-- title & description -->
            <div class="cf pa3 bb bb-gray">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>Job opening details</strong>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <!-- Name -->
                <text-input :id="'name'"
                            v-model="form.name"
                            :name="'name'"
                            :errors="$page.props.errors.name"
                            :label="'Public name of the job opening'"
                            :help="'This is the job title that people will see.'"
                            :required="true"
                            :autofocus="true"
                />

                <!-- Reference number -->
                <text-input :id="'reference_number'"
                            v-model="form.reference_number"
                            :name="'reference_number'"
                            :errors="$page.props.errors.reference_number"
                            :label="'Reference number, if needed'"
                            :help="'This will be displayed on the public version as well.'"
                            :required="false"
                />

                <!-- Description -->
                <text-area v-model="form.product_key"
                           :label="'Complete job description'"
                           :required="true"
                           :rows="10"
                />
              </div>
            </div>

            <!-- Actions -->
            <div class="pa3">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/account/softwares'" class="btn dib tc w-auto-ns w-100">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'submit-add-hardware-button'" />
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
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import TextArea from '@/Shared/TextArea';
import Help from '@/Shared/Help';
import SelectBox from '@/Shared/Select';
import Avatar from '@/Shared/Avatar';
import 'vue-loaders/dist/vue-loaders.css';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';

export default {
  components: {
    Layout,
    TextInput,
    Errors,
    LoadingButton,
    TextArea,
    Avatar,
    Help,
    SelectBox,
    'ball-pulse-loader': BallPulseLoader.component,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    positions: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        name: null,
        reference_number: null,
        searchTerm: null,
        sponsors: [],
        errors: [],
      },
      loadingState: '',
      processingSearch: false,
      potentialSponsors: [],
      showSponsors: false,
      errorTemplate: Error,
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/account/softwares`, this.form)
        .then(response => {
          localStorage.success = this.$t('account.software_new_success');
          this.$inertia.visit(response.data.data);
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

          axios.post(`/${this.$page.props.auth.company.id}/dashboard/hr/job-openings/sponsors`, this.form)
            .then(response => {
              this.potentialSponsors = _.filter(response.data.data, employee => _.every(this.form.sponsors, e => employee.id !== e.id));
              this.processingSearch = false;
            })
            .catch(error => {
              console.log(error);
              this.form.errors = error.response.data;
              this.processingSearch = false;
            });
        } else {
          this.potentialSponsors = [];
        }
      }, 500),

    add(sponsor) {
      var id = this.form.sponsors.findIndex(x => x.id === sponsor.id);

      if (id == -1) {
        this.form.sponsors.push(sponsor);
        this.potentialSponsors = [];
        this.showSponsors = false;
        this.form.searchTerm = null;
      }
    },

    detach(sponsor) {
      var id = this.form.sponsors.findIndex(member => member.id === sponsor.id);
      this.form.sponsors.splice(id, 1);
    }
  }
};

</script>
