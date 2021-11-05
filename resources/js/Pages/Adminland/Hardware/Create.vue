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
                  :previous-url="'/' + $page.props.auth.company.id + '/account/hardware'"
                  :previous="$t('app.breadcrumb_account_manage_hardware')"
      >
        {{ $t('app.breadcrumb_account_add_hardware') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5 measure center">
          <h2 class="tc normal mb4">
            {{ $t('account.hardware_create_title') }}

            <help :url="$page.props.help_links.hardware" :datacy="'help-icon-hardware'" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf pa3 bb-gray pb1">
              <!-- Name -->
              <text-input :id="'name'"
                          v-model="form.name"
                          :name="'name'"
                          :datacy="'hardware-name-input'"
                          :errors="$page.props.errors.title"
                          :label="$t('account.hardware_create_name_input')"
                          :required="true"
                          :autofocus="true"
              />

              <!-- Serial number -->
              <text-input :id="'serial'"
                          v-model="form.serial"
                          :name="'serial'"
                          :datacy="'hardware-serial-input'"
                          :errors="$page.props.errors.title"
                          :label="$t('account.hardware_create_serial_input')"
                          :help="$t('account.hardware_create_serial_input_help')"
                          :required="false"
              />
            </div>

            <div class="cf pa3 bb-gray">
              <!--  -->
              <checkbox
                :id="'home'"
                v-model="form.lend_hardware"
                :datacy="'lend-hardware-checkbox'"
                :label="$t('account.hardware_create_lend_hardware')"
                :extra-class-upper-div="'mb0 relative'"
                :required="false"
              />

              <a-select
                v-if="form.lend_hardware"
                v-model:value="form.employee_id"
                show-search
                :placeholder="$t('account.hardware_create_lend_name')"
                style="width: 100%"
                :options="employees"
                option-filter-prop="label"
              />
            </div>

            <!-- Actions -->
            <div class="mv4">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/account/hardware'" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'submit-add-hardware-button'" />
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
import Checkbox from '@/Shared/Checkbox';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';

export default {
  components: {
    Checkbox,
    Layout,
    Breadcrumb,
    TextInput,
    Errors,
    LoadingButton,
    Help
  },

  props: {
    employees: {
      type: Array,
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
        serial: null,
        employee_id: null,
        lend_hardware: false,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';
      if (!this.form.lend_hardware) {
        this.form.employee_id = null;
      }

      axios.post('/' + this.$page.props.auth.company.id + '/account/hardware', this.form)
        .then(response => {
          localStorage.success = this.$t('account.hardware_create_success');
          this.$inertia.visit('/' + response.data.data + '/account/hardware');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
