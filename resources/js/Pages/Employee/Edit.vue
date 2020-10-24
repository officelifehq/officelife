<style lang="scss" scoped>
.edit-information-menu {
  a {
    border-bottom: 0;
  }

  .selected {
    color: #4d4d4f;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id" data-cy="breadcrumb-employee">{{ employee.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_employee_edit') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="">
          <h2 class="pa3 mt2 center tc normal mb2">
            {{ $t('employee.edit_information_title') }}
          </h2>

          <div class="cf w-100">
            <ul class="list pl0 db tc bb bb-gray pa2 edit-information-menu">
              <li class="di mr2">
                <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/edit'" data-cy="menu-profile-link" class="no-underline ph3 pv2 bb-0 bt bl br bb-gray br--top br2 z-3 bg-white selected">
                  {{ $t('employee.edit_information_menu') }}
                </inertia-link>
              </li>
              <li class="di">
                <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/address/edit'" data-cy="menu-address-link" class="no-underline ph3 pv2 bb-0 bt bl br bb-gray br--top br2 z-3">
                  {{ $t('employee.edit_information_menu_address') }}
                </inertia-link>
              </li>
            </ul>
          </div>

          <form @submit.prevent="submit()">
            <template v-if="form.errors.length > 0">
              <div class="cf pa3 pb1 w-100">
                <errors :errors="form.errors" />
              </div>
            </template>

            <!-- Basic information -->
            <div class="cf pa3 pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('employee.edit_information_name') }}</strong>
                <p class="f7 silver lh-copy pr3-ns">
                  {{ $t('employee.edit_information_name_help') }}
                </p>
              </div>

              <div class="fl-ns w-two-thirds-ns w-100">
                <!-- name -->
                <div class="dt-ns dt--fixed di">
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- first name -->
                    <text-input :id="'firstname'"
                                v-model="form.first_name"
                                :name="'firstname'"
                                :errors="$page.props.errors.firstname"
                                :label="$t('employee.edit_information_firstname')"
                                :required="true"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- last name -->
                    <text-input :id="'lastname'"
                                v-model="form.last_name"
                                :name="'lastname'"
                                :errors="$page.props.errors.lastname"
                                :label="$t('employee.edit_information_lastname')"
                                :required="true"
                    />
                  </div>
                </div>

                <!-- email -->
                <text-input :id="'email'"
                            v-model="form.email"
                            :name="'email'"
                            :errors="$page.props.errors.email"
                            :label="$t('employee.edit_information_email')"
                            :required="true"
                            :type="'email'"
                            :help="$t('employee.edit_information_email_help')"
                />
              </div>
            </div>

            <!-- birthdate -->
            <div class="cf pa3 pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('employee.edit_information_birthdate') }}</strong>
                <p class="f7 silver lh-copy pr3-ns">
                  {{ $t('employee.edit_information_birthdate_help') }}
                </p>
              </div>

              <div class="fl-ns w-two-thirds-ns w-100">
                <!-- birthdate -->
                <div class="dt-ns dt--fixed di">
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- year -->
                    <text-input :id="'year'"
                                v-model="form.year"
                                :name="'year'"
                                :errors="$page.props.errors.year"
                                :label="$t('employee.edit_information_year')"
                                :required="true"
                                :type="'number'"
                                :min="1900"
                                :max="2020"
                                :help="$t('employee.edit_information_year_help')"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- month -->
                    <text-input :id="'month'"
                                v-model="form.month"
                                :name="'month'"
                                :errors="$page.props.errors.month"
                                :label="$t('employee.edit_information_month')"
                                :required="true"
                                :type="'number'"
                                :min="1"
                                :max="12"
                                :help="$t('employee.edit_information_month_help')"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- day -->
                    <text-input :id="'day'"
                                v-model="form.day"
                                :name="'day'"
                                :errors="$page.props.errors.day"
                                :label="$t('employee.edit_information_day')"
                                :required="true"
                                :type="'number'"
                                :min="1"
                                :max="31"
                                :help="$t('employee.edit_information_day_help')"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- hired at date -->
            <div v-if="canEditHiredAt" class="cf pa3 pb4" data-cy="hired-at-information">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('employee.edit_information_hired_at') }}</strong>
                <p class="f7 silver lh-copy pr3-ns">
                  {{ $t('employee.edit_information_hired_at_help') }}
                </p>
              </div>

              <div class="fl-ns w-two-thirds-ns w-100">
                <!-- hired_at -->
                <div class="dt-ns dt--fixed di">
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- year -->
                    <text-input :id="'hired_at_year'"
                                v-model="form.hired_year"
                                :name="'hired_at_year'"
                                :errors="$page.props.errors.hired_year"
                                :label="$t('employee.edit_information_year')"
                                :required="true"
                                :type="'number'"
                                :min="1900"
                                :max="2020"
                                :help="$t('employee.edit_information_year_help')"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- month -->
                    <text-input :id="'hired_at_month'"
                                v-model="form.hired_month"
                                :name="'hired_at_month'"
                                :errors="$page.props.errors.hired_month"
                                :label="$t('employee.edit_information_month')"
                                :required="true"
                                :type="'number'"
                                :min="1"
                                :max="12"
                                :help="$t('employee.edit_information_month_help')"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- day -->
                    <text-input :id="'hired_at_day'"
                                v-model="form.hired_day"
                                :name="'hired_at_day'"
                                :errors="$page.props.errors.hired_day"
                                :label="$t('employee.edit_information_day')"
                                :required="true"
                                :type="'number'"
                                :min="1"
                                :max="31"
                                :help="$t('employee.edit_information_day_help')"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- social links -->
            <div class="cf pa3 bb bb-gray pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('employee.edit_information_social') }}</strong>
                <p class="f7 silver lh-copy pr3-ns">
                  {{ $t('employee.edit_information_social_help') }}
                </p>
              </div>

              <div class="fl-ns w-two-thirds-ns w-100">
                <div class="dt-ns dt--fixed di">
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- twitter -->
                    <text-input :id="'twitter'"
                                v-model="form.twitter"
                                :name="'twitter'"
                                :errors="$page.props.errors.twitter"
                                :label="$t('employee.edit_information_twitter')"
                                :help="$t('employee.edit_information_twitter_help')"
                                :required="false"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- slack -->
                    <text-input :id="'slack'"
                                v-model="form.slack"
                                :name="'slack'"
                                :errors="$page.props.errors.slack"
                                :label="$t('employee.edit_information_slack')"
                                :help="$t('employee.edit_information_slack_help')"
                                :required="false"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="cf pa3">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :classes="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-edit-employee-button'" />
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
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    canEditHiredAt: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      form: {
        first_name: null,
        last_name: null,
        email: null,
        year: null,
        month: null,
        day: null,
        hired_year: null,
        hired_month: null,
        hired_day: null,
        twitter: null,
        slack: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created() {
    this.form.email = this.employee.email;
    this.form.first_name = this.employee.first_name;
    this.form.last_name = this.employee.last_name;
    this.form.email = this.employee.email;
    this.form.twitter = this.employee.twitter_handle;
    this.form.slack = this.employee.slack_handle;

    if (this.employee.birthdate != null) {
      this.form.year = this.employee.birthdate.year;
      this.form.month = this.employee.birthdate.month;
      this.form.day = this.employee.birthdate.day;
    }

    if (this.employee.hired_at != null) {
      this.form.hired_year = this.employee.hired_at.year;
      this.form.hired_month = this.employee.hired_at.month;
      this.form.hired_day = this.employee.hired_at.day;
    }
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/employees/' + this.employee.id + '/update', this.form)
        .then(response => {
          localStorage.success = this.$t('employee.edit_information_success');
          this.$inertia.visit('/' + this.$page.props.auth.company.id + '/employees/' + this.employee.id);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    }
  },
};

</script>
