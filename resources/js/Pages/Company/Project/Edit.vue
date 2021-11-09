<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/projects/' + project.id"
                  :previous="project.name"
      >
        {{ $t('app.breadcrumb_project_edit') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('project.edit_title') }}
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Name -->
            <text-input :id="'name'"
                        v-model="form.name"
                        :name="'name'"
                        :datacy="'project-name-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('project.create_input_name')"
                        :help="$t('project.create_input_name_help')"
                        :required="true"
            />

            <!-- Code -->
            <text-input :id="'code'"
                        v-model="form.code"
                        :name="'code'"
                        :datacy="'project-code-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('project.create_input_code')"
                        :help="$t('project.create_input_help')"
                        :maxlength="191"
            />

            <!-- short code -->
            <text-input :id="'short_code'"
                        v-model="form.short_code"
                        :name="'short_code'"
                        :required="true"
                        :errors="$page.props.errors.title"
                        :label="$t('project.create_input_short_code')"
                        :help="$t('project.create_input_short_code_help')"
                        :maxlength="3"
            />

            <!-- Summary -->
            <text-area v-model="form.summary"
                       :label="$t('project.create_input_summary')"
                       :datacy="'project-summary-input'"
                       :required="false"
                       :rows="10"
                       :help="$t('project.create_input_summary_help')"
            />

            <!-- Actions -->
            <div class="mb4 mt3">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.update')" :cypress-selector="'submit-edit-project-button'" />
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

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    TextArea,
    Errors,
    LoadingButton,
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
        name: null,
        code: null,
        short_code: null,
        summary: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  created() {
    this.form.name = this.project.name;
    this.form.code = this.project.code;
    this.form.short_code = this.project.short_code;
    this.form.summary = this.project.summary;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/update`, this.form)
        .then(response => {
          localStorage.success = this.$t('project.edit_success');
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
