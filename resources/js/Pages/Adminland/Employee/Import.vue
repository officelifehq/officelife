<style scoped>
input[type=checkbox] {
  top: 5px;
}

input[type=radio] {
  top: -2px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/employees'"
                  :previous="$t('app.breadcrumb_account_manage_employees')"
      >
        {{ $t('app.breadcrumb_account_import_employees') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="">
          <h2 class="pa3 mt5 center tc normal mb2">
            {{ $t('account.import_employees_import_title') }}
          </h2>

          <div class="pa3">
            <p>{{ $t('account.import_employees_import_description') }}</p>
            <div class="box-shadow-gray br3 pa3 f6">
              <p class="mt0 mb2">{{ $t('account.import_employees_import_note') }}</p>
              <a href="https://docs.officelife.io/documentation/manage/employee-management.html#importing-employees" target="_blank">{{ $t('account.import_employees_import_instructions') }}</a>
            </div>
          </div>

          <form @submit.prevent="importCSV">
            <div v-if="form.errors" class="pa3">
              <errors :errors="form.errors" />
            </div>

            <div class="cf pa3 bb bb-gray pb4 tc">
              <uploadcare :public-key="uploadcarePublicKey"
                          :tabs="'file'"
                          @success="onSuccess"
                          @error="onError"
              >
                <button>{{ $t('account.import_employees_import_cta') }}</button>
              </uploadcare>
            </div>

            <!-- disable actions if instance is in demo mode -->
            <div v-if="$page.props.demo_mode" class="cf pa3 tc">
              <span class="mr1">
                ⚠️
              </span> {{ $t('app.demo_mode_deactivated') }}
            </div>

            <!-- Actions -->
            <div v-if="!$page.props.demo_mode" class="cf pa3">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/account/employees'" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.import_review')" :cypress-selector="'submit-import-employee-button'" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Uploadcare from 'uploadcare-vue/src/Uploadcare.vue';

export default {
  components: {
    Layout,
    Breadcrumb,
    Errors,
    LoadingButton,
    Uploadcare,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    uploadcarePublicKey: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      document: null,
      form: {
        uuid: null,
        name: null,
        original_url: null,
        cdn_url: null,
        mime_type: null,
        size: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
    onSuccess(file) {
      this.value = file.cdnUrl;
      this.form.uuid = file.uuid;
      this.form.name = file.name;
      this.form.original_url = file.originalUrl;
      this.form.cdn_url = file.cdnUrl;
      this.form.mime_type = file.mimeType;
      this.form.size = file.size;

      this.importCSV();
    },

    onError() {
    },

    importCSV() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/account/employees/storeUpload`, this.form)
        .then(response => {
          this.$inertia.visit(response.data.url);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
