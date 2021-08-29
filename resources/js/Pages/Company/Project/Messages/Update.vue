<style lang="scss" scoped>
.ball-pulse {
  right: 8px;
  top: 37px;
  position: absolute;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/messages/' + message.id"
                  :previous="$t('app.breadcrumb_project_message')"
      >
        {{ $t('app.breadcrumb_project_message_edit') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('project.message_edit_title_message') }}

            <help :url="$page.props.help_links.project_messages" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Title -->
            <text-input :id="'title'"
                        v-model="form.title"
                        :name="'title'"
                        :datacy="'message-title-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('project.message_create_title_message')"
                        :help="$t('project.message_create_title_message_help')"
                        :required="true"
            />

            <!-- Content -->
            <text-area v-model="form.content"
                       :label="$t('project.message_create_title_content')"
                       :datacy="'message-content-textarea'"
                       :required="true"
                       :rows="10"
                       :help="$t('project.message_create_title_content_help')"
            />

            <!-- Actions -->
            <div class="mv4">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/messages/' + message.id" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.publish')" :cypress-selector="'submit-update-message-button'" />
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
    message: {
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
        content: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  created() {
    this.form.title = this.message.title;
    this.form.content = this.message.content;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.put(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/messages/${this.message.id}`, this.form)
        .then(response => {
          localStorage.success = this.$t('project.message_update_success');
          this.$inertia.visit(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/messages/${this.message.id}`);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
