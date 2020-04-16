<style scoped>
.list li:last-child {
  border-bottom: 0;
}

.weekend {
  background-color: #ffe2af;
  border: 1px #ffbd49 solid;
  border-radius: 11px;
  color: #B00;
}

.off {
  background-color: #AEE4FE;
  border: 1px #49c2fd solid;
  border-radius: 11px;
  color: #3341bd;
}

td, th {
  padding-bottom: 5px;
  padding-top: 5px;
  border-bottom: 1px #ddd dotted;
}

.edit-link {
  top: 8px;
}

.day-item {
  padding-left: 4px;
  padding-right: 4px;
}

.holiday-td {
  width: 25px;
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
            <inertia-link :href="'/' + $page.auth.company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_pto_policies') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.pto_policies_edit_title', { company: $page.auth.company.name}) }}
          </h2>

          <p class="lh-copy">
            {{ $t('account.pto_policies_edit_title_1') }}
          </p>
          <p class="lh-copy">
            {{ $t('account.pto_policies_edit_title_2') }}
          </p>
          <p class="lh-copy">
            {{ $t('account.pto_policies_edit_title_3') }}
          </p>
          <p class="lh-copy">
            {{ $t('account.pto_policies_edit_title_4') }}
          </p>

          <!-- LIST OF EXISTING PTO POLICIES -->
          <ul class="list pl0 mv0 center ba br2 bb-gray" data-cy="pto-policies-list">
            <li v-for="ptoPolicy in ptoPolicies" :key="ptoPolicy.id" class="pv3 ph3 bb bb-gray bb-gray-hover">
              <!-- title and edit button -->
              <h3 class="ma0 mb3 f3 fw5 relative">
                {{ $t('account.pto_policies_edit_year', { year: ptoPolicy.year}) }}
                <a :data-cy="'list-edit-button-' + ptoPolicy.id" class="pointer absolute right-0 f6 fw4 edit-link" @click.prevent="toggleUpdate(ptoPolicy)">
                  {{ $t('app.edit') }}
                </a>
              </h3>

              <!-- statistics -->
              <div v-show="idToUpdate != ptoPolicy.id" class="flex items-start-ns flex-wrap flex-nowrap-ns">
                <div class="mb1 w-25-ns w-50 mr4-ns">
                  <p class="db mb0 mt0 f4 fw3" :data-cy="'policy-worked-days-' + ptoPolicy.id">
                    {{ $t('account.pto_policies_stat_days', { number: ptoPolicy.total_worked_days }) }}
                  </p>
                  <p class="f7 mt1 mb0 fw3 grey">
                    {{ $t('account.pto_policies_stat_worked_days') }}
                  </p>
                </div>
                <div class="mb1 w-25-ns w-50 mr4-ns">
                  <p class="db mb0 mt0 f4 fw3" :data-cy="'policy-holidays-' + ptoPolicy.id">
                    {{ $t('account.pto_policies_stat_days', { number: ptoPolicy.default_amount_of_allowed_holidays }) }}
                  </p>
                  <p class="f7 mt1 mb0 fw3 grey" :data-cy="'policy-holidays-' + ptoPolicy.id">
                    {{ $t('account.pto_policies_stat_default_holidays') }}
                  </p>
                </div>
                <div class="mb1 w-25-ns w-50 mr4-ns">
                  <p class="db mb0 mt0 f4 fw3" :data-cy="'policy-sick-' + ptoPolicy.id">
                    {{ $t('account.pto_policies_stat_days', { number: ptoPolicy.default_amount_of_sick_days }) }}
                  </p>
                  <p class="f7 mt1 mb0 fw3 grey">
                    {{ $t('account.pto_policies_stat_default_sick_days') }}
                  </p>
                </div>
                <div class="mb1 w-25-ns w-50">
                  <p class="db mb0 mt0 f4 fw3" :data-cy="'policy-pto-' + ptoPolicy.id">
                    {{ $t('account.pto_policies_stat_days', { number: ptoPolicy.default_amount_of_pto_days }) }}
                  </p>
                  <p class="f7 mt1 mb0 fw3 grey">
                    {{ $t('account.pto_policies_stat_default_ptos') }}
                  </p>
                </div>
              </div>

              <!-- edit -->
              <div v-show="idToUpdate == ptoPolicy.id" class="cf mt3">
                <form @submit.prevent="update(ptoPolicy.id)">
                  <p>{{ $t('account.pto_policies_edit_default_employee_settings') }}</p>
                  <div class="dt-ns dt--fixed di">
                    <div class="dtc-ns pr2-ns pb0-ns w-100 pb3">
                      <text-input :id="'holidays'"
                                  v-model="form.default_amount_of_allowed_holidays"
                                  :name="'holidays'"
                                  :errors="$page.errors.holidays"
                                  :label="$t('account.pto_policies_edit_default_amount_of_allowed_holidays')"
                                  :required="true"
                                  :type="'number'"
                                  :min="1"
                                  :max="300"
                                  :datacy="'list-edit-input-holidays-' + ptoPolicy.id"
                      />
                    </div>
                    <div class="dtc-ns pr2-ns pb0-ns w-100 pb3">
                      <text-input :id="'sick'"
                                  v-model="form.default_amount_of_sick_days"
                                  :name="'sick'"
                                  :errors="$page.errors.default_amount_of_sick_days"
                                  :label="$t('account.pto_policies_edit_default_amount_of_sick_days')"
                                  :required="true"
                                  :type="'number'"
                                  :min="1"
                                  :max="300"
                                  :datacy="'list-edit-input-sick-' + ptoPolicy.id"
                      />
                    </div>
                    <div class="dtc-ns pr2-ns pb0-ns w-100 pb3">
                      <text-input :id="'pto'"
                                  v-model="form.default_amount_of_pto_days"
                                  :name="'pto'"
                                  :errors="$page.errors.default_amount_of_pto_days"
                                  :label="$t('account.pto_policies_edit_default_amount_of_pto_days')"
                                  :required="true"
                                  :type="'number'"
                                  :min="1"
                                  :max="300"
                                  :datacy="'list-edit-input-pto-' + ptoPolicy.id"
                      />
                    </div>
                  </div>

                  <p>{{ $t('account.pto_policies_edit_click_calendar') }}</p>
                  <p class="f6">
                    {{ $t('account.pto_policies_edit_calendar_help') }}
                  </p>
                  <div class="tc db mt3">
                    <table class="center" aria-describedby="company pto policies">
                      <thead>
                        <tr class="f6 tc">
                          <th scope="col">{{ $t('account.pto_policies_month') }}</th>
                          <th scope="col">1</th>
                          <th scope="col">2</th>
                          <th scope="col">3</th>
                          <th scope="col">4</th>
                          <th scope="col">5</th>
                          <th scope="col">6</th>
                          <th scope="col">7</th>
                          <th scope="col">8</th>
                          <th scope="col">9</th>
                          <th scope="col">10</th>
                          <th scope="col">11</th>
                          <th scope="col">12</th>
                          <th scope="col">13</th>
                          <th scope="col">14</th>
                          <th scope="col">15</th>
                          <th scope="col">16</th>
                          <th scope="col">17</th>
                          <th scope="col">18</th>
                          <th scope="col">19</th>
                          <th scope="col">20</th>
                          <th scope="col">21</th>
                          <th scope="col">22</th>
                          <th scope="col">23</th>
                          <th scope="col">24</th>
                          <th scope="col">25</th>
                          <th scope="col">26</th>
                          <th scope="col">27</th>
                          <th scope="col">28</th>
                          <th scope="col">29</th>
                          <th scope="col">30</th>
                          <th scope="col">31</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="holidayRow in localHolidays" :key="holidayRow.id" class="">
                          <td v-for="holiday in holidayRow" :key="holiday.id" class="f6 tc holiday-td">
                            <span :class="isOff(holiday)" class="pointer day-item" :data-cy="'calendar-item-' + holiday.id + '-' + ptoPolicy.id" @click.prevent="toggleDayOff(holiday)">
                              {{ holiday.abbreviation }}
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="dt-ns dt--fixed di">
                    <div class="dtc-ns pr2-ns pb0-ns w-100 pb3">
                      <span class="f6">
                        {{ $t('account.pto_policies_legend') }}
                      </span> <span class="weekend f7 pv1 ph2 mr1">
                        {{ $t('account.pto_policies_legend_weekend') }}
                      </span> <span class="off f7 pv1 ph2">
                        {{ $t('account.pto_policies_legend_holiday') }}
                      </span>
                    </div>
                    <div class="dtc-ns pr2-ns pb0-ns w-100 pb3">
                      <p class="tr" :data-cy="'total-worked-days-' + ptoPolicy.id">
                        {{ $t('account.pto_policies_edit_total', { totalWorkedDays: totalWorkedDays, year: ptoPolicy.year }) }}
                      </p>
                    </div>
                  </div>

                  <div class="w-100 tr">
                    <a class="btn dib-l db mb2 mb0-ns" :data-cy="'list-edit-cancel-button-' + ptoPolicy.id" @click.prevent="idToUpdate = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-edit-cta-button-' + ptoPolicy.id" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
    TextInput,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    ptoPolicies: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      totalWorkedDays: 0,
      editModal: false,
      loadingState: '',
      idToUpdate: 0,
      localHolidays: null,
      form: {
        default_amount_of_allowed_holidays: null,
        default_amount_of_sick_days: null,
        default_amount_of_pto_days: null,
        days_to_toggle: [],
        errors: [],
      },
    };
  },

  methods: {
    isOff: function (holiday) {
      var classes = '';
      if (holiday.day_of_week == 0 || holiday.day_of_week == 6) {
        classes = 'weekend';
      }

      if (holiday.is_worked == false && holiday.day_of_week != 0 && holiday.day_of_week != 6) {
        classes = classes + ' off';
      }

      return classes;
    },

    toggleUpdate(ptoPolicy) {
      if (!this.editModal) {
        this.load(ptoPolicy);
        this.idToUpdate = ptoPolicy.id;
        this.totalWorkedDays = ptoPolicy.total_worked_days;
        this.form.default_amount_of_allowed_holidays = ptoPolicy.default_amount_of_allowed_holidays;
        this.form.default_amount_of_sick_days = ptoPolicy.default_amount_of_sick_days;
        this.form.default_amount_of_pto_days = ptoPolicy.default_amount_of_pto_days;
        this.editModal = true;
      } else {
        this.totalWorkedDays = 0;
        this.localHolidays = null;
        this.idToUpdate = 0;
        this.editModal = false;
        this.form.days_to_toggle = [];
      }
    },

    toggleDayOff: function (day) {
      // we can't toggle a day in the weekend as it’s off by default
      if (day.day_of_week == 0 || day.day_of_week == 6) {
        return;
      }

      // was the day worked previoulsy?
      var wasWorked = day.is_worked;

      // toggle the day
      day.is_worked = !day.is_worked;

      // push the day in the array of days that will be send to the backend
      var id = this.form.days_to_toggle.findIndex(x => x.id === day.id);
      if (id != -1) {
        this.form.days_to_toggle.splice(id, 1);
      } else {
        this.form.days_to_toggle.push(day);
      }

      if (wasWorked) {
        this.totalWorkedDays = this.totalWorkedDays - 1;
      } else {
        this.totalWorkedDays = this.totalWorkedDays + 1;
      }
    },

    load(ptoPolicy) {
      axios.get('/' + this.$page.auth.company.id + '/account/ptopolicies/' + ptoPolicy.id + '/getHolidays')
        .then(response => {
          this.localHolidays = response.data;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(ersror.response.data));
        });
    },

    update(id) {
      axios.put('/' + this.$page.auth.company.id + '/account/ptopolicies/' + id, this.form)
        .then(response => {
          flash(this.$t('account.pto_policies_update'), 'success');

          this.idToUpdate = 0;
          this.form.year = null;

          id = this.ptoPolicies.findIndex(x => x.id === id);
          this.$set(this.ptoPolicies, id, response.data.data);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
