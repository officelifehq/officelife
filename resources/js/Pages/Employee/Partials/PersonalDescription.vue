<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      💬 {{ $t('employee.description_title') }}
    </span>
    <img v-show="employeeOrAtLeastHR()" src="/img/edit_button.svg" class="box-plus-button absolute br-100 pa2 bg-white pointer" data-cy="add-description-button" width="22"
         height="22" alt="add a description"
         @click.prevent="displayEditBox()"
    />

    <div v-if="updatedEmployee.raw_description && !showEdit" class="br3 bg-white box z-1 pa3">
      <div class="parsed-content" v-html="updatedEmployee.parsed_description"></div>
    </div>

    <!-- No description set -->
    <div v-if="!updatedEmployee.raw_description && !showEdit" class="br3 bg-white box z-1 pa3">
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

        <text-area v-model="form.description"
                   :label="$t('employee.description_text_title')"
                   :datacy="'description-textarea'"
                   :required="true"
                   :rows="10"
                   :help="$t('employee.description_text_help')"
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
              <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" data-cy="cancel-add-description" @click="showEdit = false">
                {{ $t('app.cancel') }}
              </a>
              <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.publish')" :cypress-selector="'submit-add-description'" />
            </div>
          </div>
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
    employeeOrAtLeastHR() {
      if (this.$page.auth.employee.permission_level <= 200) {
        return true;
      }

      if (!this.employee.user) {
        return false;
      }

      if (this.$page.auth.user.id == this.employee.user.id) {
        return true;
      }
    },

    displayEditBox() {
      this.showEdit = true;
      this.form.description = this.updatedEmployee.raw_description;
    },

    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/description', this.form)
        .then(response => {
          flash(this.$t('employee.description_success'), 'success');

          this.updatedEmployee = response.data.data;
          this.showEdit = false;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    clear() {
      axios.delete('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/description/' + this.employee.id)
        .then(response => {
          flash(this.$t('employee.description_success'), 'success');

          this.updatedEmployee = response.data.data;
          this.showEdit = false;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};
</script>
