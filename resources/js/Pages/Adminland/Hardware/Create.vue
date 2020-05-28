<style scoped>
input[type=checkbox] {
  top: 5px;
}
input[type=radio] {
  top: -2px;
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
            <inertia-link :href="'/' + $page.auth.company.id + '/account/hardware'">{{ $t('app.breadcrumb_account_manage_hardware') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_add_hardware') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5 measure center">
          <h2 class="tc normal mb4">
            {{ $t('account.hardware_create_title') }}

            <help :url="$page.help_links.account_hardware_create" :datacy="'help-icon-hardware'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf pa3 bb-gray pb1">
              <!-- Name -->
              <text-input :id="'name'"
                          v-model="form.name"
                          :name="'name'"
                          :datacy="'hardware-name-input'"
                          :errors="$page.errors.title"
                          :label="$t('account.hardware_create_name_input')"
                          :required="true"
                          :autofocus="true"
              />

              <!-- Serial number -->
              <text-input :id="'serial'"
                          v-model="form.serial"
                          :name="'serial'"
                          :datacy="'hardware-serial-input'"
                          :errors="$page.errors.title"
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
                :required="true"
                @change="updateStatus($event)"
              />

              <select-box v-if="form.lend_hardware"
                          :id="'employee_id'"
                          v-model="form.employee_id"
                          :options="employees"
                          :name="'employee_id'"
                          :errors="$page.errors.employee_id"
                          :placeholder="$t('account.hardware_create_lend_name')"
                          :required="false"
                          :value="form.employee_id"
                          :datacy="'employee-selector'"
              />
            </div>

            <!-- Actions -->
            <div class="mv4">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.auth.company.id + '/account/hardware'" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'submit-add-hardware-button'" />
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
import SelectBox from '@/Shared/Select';
import Help from '@/Shared/Help';

export default {
  components: {
    Checkbox,
    Layout,
    TextInput,
    Errors,
    LoadingButton,
    SelectBox,
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
    updateStatus(payload) {
      this.form.lend_hardware = payload;
    },

    submit() {
      this.loadingState = 'loading';
      if (this.form.employee_id) {
        this.form.employee_id = this.form.employee_id.value;
      }

      axios.post('/' + this.$page.auth.company.id + '/account/hardware', this.form)
        .then(response => {
          localStorage.success = this.$t('account.hardware_create_success');
          this.$inertia.visit('/' + response.data.data + '/account/hardware');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
