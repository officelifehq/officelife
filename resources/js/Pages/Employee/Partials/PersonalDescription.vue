<style lang="scss" scoped>
.icon {
  color: #9AA7BF;
  position: relative;
  width: 17px;
  top: 3px;
}

.title {
  color: #757982;
}
</style>

<template>
  <div class="mb4 relative">
    <div class="db fw4 mb3 relative">
      <svg class="icon mr1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
      </svg>
      <span class="f6 title">
        {{ $t('employee.description_title') }}
      </span>

      <a v-show="permissions.can_manage_description && !showEdit" data-cy="add-description-button" class="bb b--dotted bt-0 bl-0 br-0 pointer di f7 ml2" @click.prevent="displayEditBox()">Edit</a>
    </div>

    <div v-if="updatedEmployee.raw_description && !showEdit" class="br3 z-1">
      <div class="parsed-content" v-html="updatedEmployee.parsed_description"></div>
    </div>

    <!-- No description set -->
    <div v-if="!updatedEmployee.raw_description && !showEdit">
      <p class="mb0 mt0 lh-copy f6">
        {{ $t('employee.description_no_description') }}
      </p>
    </div>

    <!-- edit description -->
    <div v-if="showEdit" class="br3 bg-white box z-1 pa3">
      <form @submit.prevent="submit">
        <template v-if="form.errors.length > 0">
          <div class="cf pb1 w-100 mb2">
            <errors :errors="form.errors" />
          </div>
        </template>

        <text-area :ref="'editModal'"
                   v-model="form.description"
                   :label="$t('employee.description_text_title')"
                   :datacy="'description-textarea'"
                   :required="true"
                   :rows="10"
                   :help="$t('employee.description_text_help')"
                   @esc-key-pressed="showEdit = false"
        />

        <!-- Actions -->
        <div class="mt4">
          <div class="flex-ns justify-between mb3">
            <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" data-cy="cancel-add-description" @click="showEdit = false">
              {{ $t('app.cancel') }}
            </a>
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.publish')" :cypress-selector="'submit-add-description'" />
          </div>
          <p class="ma0 f7">
            <a href="#" class="dib tc w-auto-ns w-100 b--dotted bb bt-0 br-0 bl-0" data-cy="clear-description" @click.prevent="clear()">
              {{ $t('employee.description_clear') }}
            </a>
          </p>
        </div>
      </form>
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
    employee: {
      type: Object,
      default: null,
    },
    permissions: {
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
      updatedEmployee: null,
    };
  },

  created: function() {
    this.updatedEmployee = this.employee;
  },

  methods: {
    displayEditBox() {
      this.showEdit = true;
      this.form.description = this.updatedEmployee.raw_description;
      this.$nextTick(() => {
        this.$refs.editModal.focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/description`, this.form)
        .then(response => {
          this.flash(this.$t('employee.description_success'), 'success');

          this.updatedEmployee = response.data.data;
          this.showEdit = false;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    clear() {
      axios.delete(`${this.$page.props.auth.company.id}/employees/${this.employee.id}/description/${this.employee.id}`)
        .then(response => {
          this.flash(this.$t('employee.description_success'), 'success');

          this.updatedEmployee = response.data.data;
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
