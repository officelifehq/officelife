<style lang="scss" scoped>
@import 'vue-loaders/dist/vue-loaders.css';

.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.project-lead {
  padding-left: 44px;
}

.ball-pulse {
  right: 8px;
  top: 10px;
  position: absolute;
}

.project-lead-action {
  top: 18px;
}

.popupmenu {
  right: -4px;

  &:after {
    left: auto;
    right: 10px;
  }

  &:before {
    left: auto;
    right: 9px;
  }
}
</style>

<template>
  <div class="pa3 bb bb-gray">
    <!-- case of project lead set -->
    <p class="silver f6 ma0 mb2">{{ $t('project.summary_project_lead_label') }}</p>

    <template v-if="localProject.project_lead">
      <div class="lh-copy ma0">
        <span class="db project-lead relative">
          <avatar :avatar="localProject.project_lead.avatar" :size="35" :class="'br-100 absolute avatar'" />
          <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + localProject.project_lead.id" class="mb2" data-cy="current-project-lead">
            {{ localProject.project_lead.name }}
          </inertia-link>

          <span v-if="!localProject.project_lead.position" class="db f7 mt1">
            {{ $t('app.no_position_defined') }}
          </span>

          <span v-if="localProject.project_lead.position" class="db f7 mt1">
            {{ localProject.project_lead.position.title }}
          </span>

          <img loading="lazy" src="/img/common/triple-dots.svg" class="absolute right-0 pointer project-lead-action" data-cy="display-remove-project-lead-modal"
               alt="display the menu"
               @click.prevent="removeMode = true"
          />

          <!-- REMOVE PROJECT LEADER MENU -->
          <template v-if="removeMode">
            <div v-click-outside="hideRemovalMode" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3">
              <ul class="list ma0 pa0">

                <!-- click to remove -->
                <li v-show="!removalConfirmation" class="pv2 relative">
                  <icon-delete :class="'icon-delete relative'" :width="15" :height="15" />
                  <a class="pointer ml1 c-delete" data-cy="remove-project-lead-button" @click.prevent="removalConfirmation = true">
                    {{ $t('project.summary_project_lead_remove_label') }}
                  </a>
                </li>

                <!-- confirmation to remove -->
                <li v-show="removalConfirmation" class="pv2">
                  {{ $t('app.sure') }}
                  <a data-cy="confirm-remove-project-lead" class="c-delete mr1 pointer" @click.prevent="removeLead()">
                    {{ $t('app.yes') }}
                  </a>
                  <a class="pointer" @click.prevent="removalConfirmation = false">
                    {{ $t('app.no') }}
                  </a>
                </li>
              </ul>
            </div>
          </template>
        </span>
      </div>
    </template>

    <!-- project lead blank state -->
    <div v-show="!localProject.project_lead && !editMode" class="lh-copy ma0">
      <a class="bb b--dotted bt-0 bl-0 br-0 pointer" data-cy="add-project-lead-blank-state" @click.prevent="displaySearch()">{{ $t('project.summary_project_lead_no_leader') }}</a>
    </div>

    <!-- form to add the leader -->
    <div v-show="editMode" class="bb bb-gray">
      <form @submit.prevent="search">
        <!-- errors -->
        <template v-if="form.errors.length > 0">
          <div class="cf pb1 w-100 mb2">
            <errors :errors="form.errors" />
          </div>
        </template>

        <!-- search form -->
        <div class="mb3 relative">
          <p class="mt0">Search employees <a href="#" @click.prevent="editMode = false">{{ $t('app.cancel') }}</a></p>
          <div class="relative">
            <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                   :placeholder="$t('employee.hierarchy_search_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required data-cy="search-project-lead"
                   @keyup="search()" @keydown.esc="editMode = false"
            />
            <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
          </div>
        </div>
      </form>

      <!-- RESULTS -->
      <div class="pl0 list ma0">
        <ul v-if="potentialLeads.length > 0" class="list ma0 pl0">
          <li v-for="lead in potentialLeads" :key="lead.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
            {{ lead.name }}
            <a class="absolute right-1 pointer" :data-cy="'potential-project-lead-' + lead.id" @click.prevent="assign(lead)">
              {{ $t('app.choose') }}
            </a>
          </li>
        </ul>
      </div>
      <div v-if="hasMadeASearch && potentialLeads.length == 0" class="silver">
        {{ $t('app.no_results') }}
      </div>
    </div>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';
import IconDelete from '@/Shared/IconDelete';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';
import vClickOutside from 'click-outside-vue3';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Errors,
    Avatar,
    IconDelete,
    'ball-pulse-loader': BallPulseLoader.component,
  },

  directives: {
    clickOutside: vClickOutside.directive
  },

  props: {
    project: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      processingSearch: false,
      hasMadeASearch: false,
      editMode: false,
      removeMode: false,
      removalConfirmation: false,
      potentialLeads: [],
      form: {
        employeeId: 0,
        searchTerm: null,
        errors: [],
      },
      localProject: null,
    };
  },

  created: function() {
    this.localProject = this.project;
  },

  methods: {
    displaySearch() {
      this.editMode = true;

      this.$nextTick(() => {
        this.$refs.search.focus();
      });
    },

    hideRemovalMode() {
      this.removeMode = false;
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.hasMadeASearch = false;
          this.processingSearch = true;

          axios.post(`/${this.$page.props.auth.company.id}/company/projects/search`, this.form)
            .then(response => {
              this.potentialLeads = response.data.data;
              this.processingSearch = false;
              this.hasMadeASearch = true;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
              this.hasMadeASearch = false;
            });
        }
      },
      500),

    assign(lead) {
      this.form.employeeId = lead.id;

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.localProject.id}/lead/assign`, this.form)
        .then(response => {
          this.flash(this.$t('project.summary_project_lead_added_success'), 'success');

          this.localProject.project_lead = response.data.data;
          this.editMode = false;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    removeLead() {
      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.localProject.id}/lead/clear`)
        .then(response => {
          this.flash(this.$t('project.summary_project_lead_cleared_success'), 'success');

          this.localProject.project_lead = null;
          this.removeMode = false;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
