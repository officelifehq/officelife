<style lang="scss" scoped>
.rate-late {
  background: linear-gradient(0deg, #ec89793d 0%, white 100%);
}

.rate-on-track {
  background: linear-gradient(0deg, #abddac96 0%, white 100%);
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/projects/' + project.id"
                  :previous="project.name"
      >
        {{ $t('app.breadcrumb_project_status_edit') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('project.summary_project_status_edit_title') }}

            <help :url="$page.props.help_links.project" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Status -->
            <div class="flex-ns justify-around mt3 mb5">
              <span class="btn mr3-ns mb0-ns mb2 dib-l db rate-on-track" data-cy="project-status-on-track">
                <input id="status_on_track" v-model="form.status" type="radio" class="mr1 relative" name="status"
                       value="on_track"
                />
                <label for="status_on_track" class="pointer mb0">
                  <span class="mr1">
                    😇
                  </span> {{ $t('project.summary_project_status_edit_on_track') }}
                </label>
              </span>
              <span class="btn mr3-ns mb0-ns mb2 dib-l db rate-risk" data-cy="project-status-at-risk">
                <input id="status_at_risk" v-model="form.status" type="radio" class="mr1 relative" name="status"
                       value="at_risk"
                />
                <label for="status_at_risk" class="pointer mb0">
                  <span class="mr1">
                    🥴
                  </span> {{ $t('project.summary_project_status_edit_at_risk') }}
                </label>
              </span>
              <span class="btn mr3-ns mb0-ns mb2 dib-l db rate-late" data-cy="project-status-late">
                <input id="status_late" v-model="form.status" type="radio" class="mr1 relative" name="status"
                       value="late"
                />
                <label for="status_late" class="pointer mb0">
                  <span class="mr1">
                    🙀
                  </span> {{ $t('project.summary_project_status_edit_late') }}
                </label>
              </span>
            </div>

            <!-- Title -->
            <text-input :id="'title'"
                        v-model="form.title"
                        :name="'title'"
                        :datacy="'status-title-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('project.summary_project_status_edit_status')"
                        :required="true"
            />

            <!-- Description -->
            <text-area v-model="form.description"
                       :label="$t('project.summary_project_status_edit_description')"
                       :required="true"
                       :datacy="'textarea-description'"
                       :rows="10"
            />

            <!-- Actions -->
            <div class="mb4 mt5">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.update')" :cypress-selector="'submit-create-project-button'" />
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
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
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
        status: 'on_track',
        description: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.put(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/status`, this.form)
        .then(response => {
          this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
