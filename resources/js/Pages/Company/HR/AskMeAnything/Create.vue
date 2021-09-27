<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/hr/ask-me-anything'"
                  :previous="$t('app.breadcrumb_ama_list')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_ama_new') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('company.hr_ama_new_title') }}

            <help :url="$page.props.help_links.ask_me_anything" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- date -->
            <p>{{ $t('company.hr_ama_new_dates') }}</p>
            <div class="dt-ns dt--fixed di mb3">
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
                            :max="2050"
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

            <!-- Title -->
            <text-input :id="'theme'"
                        v-model="form.theme"
                        :name="'name'"
                        :errors="$page.props.errors.theme"
                        :label="$t('company.hr_ama_new_theme_label')"
                        :help="$t('company.hr_ama_new_theme_help')"
                        :required="false"
                        :autofocus="true"
                        :maxlength="191"
            />

            <!-- Actions -->
            <div class="mb4 mt5">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="data.url_back" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.create')" />
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    Errors,
    LoadingButton,
    Help
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    data: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        year: null,
        month: null,
        day: null,
        theme: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  mounted() {
    this.form.year = this.data.year;
    this.form.month = this.data.month;
    this.form.day = this.data.day;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(this.data.url_submit, this.form)
        .then(response => {
          localStorage.success = this.$t('company.hr_ama_new_success');
          this.$inertia.visit(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
