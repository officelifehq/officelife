<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 23px;
}

.team-member {
  padding-left: 34px;

  .avatar {
    top: 6px;
    left: 7px;
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
            <inertia-link :href="'/' + $page.auth.company.id + '/projects/' + project.id">{{ project.name }}</inertia-link>
          </li>
          <li class="di">
            Edit status
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            How is the project doing?

            <help :url="$page.help_links.team_recent_ship_create" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Status -->
            <div class="flex-ns justify-around mt3 mb3">
              <span class="btn mr3-ns mb0-ns mb2 dib-l db rate-bad" data-cy="log-rate-bad" @click.prevent="submit(answer, 'bad')">
                <span class="mr1">
                  😇
                </span> On track
              </span>
              <span class="btn mr3-ns mb0-ns mb2 dib-l db" data-cy="log-rate-normal" @click.prevent="submit(answer, 'average')">
                <span class="mr1">
                  🥴
                </span> At risk
              </span>
              <span class="btn dib-l db mb0-ns rate-good" data-cy="log-rate-good" @click.prevent="submit(answer, 'good')">
                <span class="mr1">
                  🙀
                </span> Late
              </span>
            </div>

            <!-- Title -->
            <text-input :id="'title'"
                        v-model="form.title"
                        :name="'title'"
                        :datacy="'status-title-input'"
                        :errors="$page.errors.title"
                        :label="'Project status'"
                        :placeholder="'It’s going well'"
                        :help="$t('project.status_input_title_help')"
                        :required="true"
            />

            <!-- Code -->
            <text-input :id="'code'" v-model="form.code"
                        :name="'code'"
                        :datacy="'project-code-input'"
                        :errors="$page.errors.title"
                        :label="$t('project.create_input_code')"
                        @esc-key-pressed="showCode = false"
            />

            <!-- Summary -->
            <text-area v-if="showSummary"
                       v-model="form.summary"
                       :label="$t('project.create_input_summary')"
                       :datacy="'project-summary-input'"
                       :required="false"
                       :rows="10"
                       :help="$t('project.create_input_summary_help')"
                       @esc-key-pressed="showSummary = false"
            />

            <!-- Actions -->
            <div class="mb4 mt5">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.auth.company.id + '/projects/'" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.create')" :cypress-selector="'submit-create-project-button'" />
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
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    TextInput,
    TextArea,
    Errors,
    LoadingButton,
    Help
  },

  props: {
    project: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      form: {
        title: null,
        status: null,
        description: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/projects', this.form)
        .then(response => {
          this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    add(employee) {
      this.form.projectLead = employee;
      this.potentialMembers = [];
      this.showAssignProjectLead = false;
      this.form.searchTerm = null;
    },

    unassignProjectLead() {
      this.form.projectLead = null;
      this.potentialMembers = [];
      this.showAssignProjectLead = false;
      this.form.searchTerm = null;
    },
  }
};

</script>
