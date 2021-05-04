<template>
  <div class="mb5">
    <div class="mb2 fw5 relative">
      <span class="mr1">
        🏔
      </span> {{ $t('project.summary_description') }}

      <img src="/img/edit_button.svg" class="box-plus-button absolute br-100 pa2 bg-white pointer" data-cy="add-description-button"
           width="22"
           height="22" alt="add a description"
           loading="lazy"
           @click.prevent="displayEditBox()"
      />
    </div>

    <div class="bg-white box pa3">
      <div v-if="! showEdit">
        <div v-if="localProject.parsed_description" class="parsed-content" v-html="localProject.parsed_description"></div>
        <div v-else class="mb0 mt0 lh-copy f6 tc">
          {{ $t('project.summary_description_blank') }}
        </div>
      </div>

      <!-- edit description -->
      <div v-if="showEdit" class="">
        <form @submit.prevent="submit">
          <template v-if="form.errors.length > 0">
            <div class="cf pb1 w-100 mb2">
              <errors :errors="form.errors" />
            </div>
          </template>

          <text-area :ref="'editModal'"
                     v-model="form.description"
                     :label="$t('project.summary_description_title')"
                     :datacy="'description-textarea'"
                     :required="true"
                     :rows="10"
                     :help="$t('project.summary_description_title_help')"
                     @esc-key-pressed="showEdit = false"
          />

          <!-- Actions -->
          <div class="mt4">
            <div class="flex-ns justify-between">
              <div>
                <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" data-cy="clear-description" @click="clear()">
                  ❌ {{ $t('employee.description_clear') }}
                </a>
              </div>
              <div class="">
                <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3 mr2" data-cy="cancel-add-description" @click="showEdit = false">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.publish')" :cypress-selector="'submit-add-description'" />
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import TextArea from '@/Shared/TextArea';
import LoadingButton from '@/Shared/LoadingButton';
import Errors from '@/Shared/Errors';

export default {
  components: {
    TextArea,
    Errors,
    LoadingButton,
  },

  props: {
    project: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      showEdit: false,
      form: {
        description: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
      localProject: null,
    };
  },

  created() {
    this.localProject = this.project;
  },

  methods: {
    displayEditBox() {
      this.showEdit = true;
      this.form.description = this.localProject.raw_description;

      this.$nextTick(() => {
        this.$refs.editModal.focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.localProject.id}/description`, this.form)
        .then(response => {
          this.flash(this.$t('project.summary_description_success'), 'success');
          this.localProject.raw_description = response.data.data.raw_description;
          this.localProject.parsed_description = response.data.data.parsed_description;
          this.loadingState = null;
          this.showEdit = false;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    clear() {
      this.loadingState = 'loading';
      this.form.description = null;

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.localProject.id}/description`, this.form)
        .then(response => {
          this.localProject.raw_description = null;
          this.localProject.parsed_description = null;
          this.loadingState = null;
          this.showEdit = false;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
