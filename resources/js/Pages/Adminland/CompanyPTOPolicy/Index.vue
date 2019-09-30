<style scoped>
.list li:last-child {
  border-bottom: 0;
}

.weekend {
  background-color: #FFE2AF;
  border: 1px #ffbd49 solid;
  border-radius: 11px;
  color: #B00;
}

td, th {
  padding-bottom: 5px;
  padding-top: 5px;
  border-bottom: 1px #ddd dotted;
}

.edit-link {
  top: 8px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $page.auth.company.name }}</inertia-link>
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
                {{ $t('account.pto_policies_edit_year', { year: ptoPolicy.year}) }} <a :data-cy="'list-edit-button-' + ptoPolicy.id" class="pointer absolute right-0 f6 fw4 edit-link" @click.prevent="displayUpdateModal(ptoPolicy) ; form.total_worked_days = ptoPolicy.total_worked_days">
                  {{ $t('app.edit') }}
                </a>
              </h3>

              <!-- statistics -->
              <div v-show="idToUpdate != ptoPolicy.id" class="flex items-start-ns flex-wrap flex-nowrap-ns">
                <div class="mb1 w-25-ns w-50 mr4-ns">
                  <p class="db mb0 mt0 f4 fw3">
                    {{ $t('account.pto_policies_stat_days', { number: ptoPolicy.total_worked_days }) }}
                  </p>
                  <p class="f7 mt1 mb0 fw3 grey">
                    {{ $t('account.pto_policies_stat_worked_days') }}
                  </p>
                </div>
                <div class="mb1 w-25-ns w-50 mr4-ns">
                  <p class="db mb0 mt0 f4 fw3">
                    {{ $t('account.pto_policies_stat_days', { number: ptoPolicy.default_amount_of_allowed_holidays }) }}
                  </p>
                  <p class="f7 mt1 mb0 fw3 grey">
                    {{ $t('account.pto_policies_stat_default_holidays') }}
                  </p>
                </div>
                <div class="mb1 w-25-ns w-50 mr4-ns">
                  <p class="db mb0 mt0 f4 fw3">
                    {{ $t('account.pto_policies_stat_days', { number: ptoPolicy.default_amount_of_sick_days }) }}
                  </p>
                  <p class="f7 mt1 mb0 fw3 grey">
                    {{ $t('account.pto_policies_stat_default_sick_days') }}
                  </p>
                </div>
                <div class="mb1 w-25-ns w-50">
                  <p class="db mb0 mt0 f4 fw3">
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
                  <div class="dt-ns dt--fixed di">
                    <div class="dtc-ns pr2-ns pb0-ns w-100 pb3">
                      <text-input :id="'city'"
                                  v-model="form.default_amount_of_allowed_holidays"
                                  :name="'city'"
                                  :errors="$page.errors.city"
                                  :label="$t('employee.edit_information_city')"
                                  :required="true"
                                  :type="'number'"
                                  :min="1"
                                  :max="300"
                      />
                    </div>
                    <div class="dtc-ns pr2-ns pb0-ns w-100 pb3">
                      <text-input :id="'state'"
                                  v-model="form.default_amount_of_sick_days"
                                  :name="'state'"
                                  :errors="$page.errors.default_amount_of_sick_days"
                                  :label="$t('employee.edit_information_state')"
                                  :required="true"
                                  :type="'number'"
                                  :min="1"
                                  :max="300"
                      />
                    </div>
                    <div class="dtc-ns pr2-ns pb0-ns w-100 pb3">
                      <text-input :id="'postal_code'"
                                  v-model="form.default_amount_of_pto_days"
                                  :name="'postal_code'"
                                  :errors="$page.errors.default_amount_of_pto_days"
                                  :label="$t('employee.edit_information_postal_code')"
                                  :required="true"
                                  :type="'number'"
                                  :min="1"
                                  :max="300"
                      />
                    </div>
                  </div>

                  <p>Edit the calendar below to add/remove holidays.</p>
                  <div class="tc db mt3">
                    <table class="center">
                      <thead>
                        <tr class="f6 tc">
                          <th>Month</th>
                          <th>1</th>
                          <th>2</th>
                          <th>3</th>
                          <th>4</th>
                          <th>5</th>
                          <th>6</th>
                          <th>7</th>
                          <th>8</th>
                          <th>9</th>
                          <th>10</th>
                          <th>11</th>
                          <th>12</th>
                          <th>13</th>
                          <th>14</th>
                          <th>15</th>
                          <th>16</th>
                          <th>17</th>
                          <th>18</th>
                          <th>19</th>
                          <th>20</th>
                          <th>21</th>
                          <th>22</th>
                          <th>23</th>
                          <th>24</th>
                          <th>25</th>
                          <th>26</th>
                          <th>27</th>
                          <th>28</th>
                          <th>29</th>
                          <th>30</th>
                          <th>31</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="holidayRow in localHolidays" :key="holidayRow.id" class="">
                          <td v-for="holiday in holidayRow" :key="holiday.id" class="f6 tc">
                            <span :class="isOff(holiday)" class="ph1">
                              {{ holiday.abbreviation }}
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <p>Note: we'll recalculate the balance of holidays for all your employees based on these new numbers if you happen to change.</p>

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


          <ul class="list pl0 mv0 center ba br2 bb-gray" data-cy="pto-policies-list">
            <li v-for="ptoPolicy in ptoPolicies" :key="ptoPolicy.id" class="pv3 ph2 bb bb-gray bb-gray-hover">
              <span class="db mb3">
                {{ $t('account.pto_policies_edit_year', { year: ptoPolicy.year}) }}
              </span>
              {{ $t('account.pto_policies_number_days', { number: ptoPolicy.total_worked_days }) }}
              In 2021, employees have to work for 258 days. They are allowed to take 123 holidays

              <div class="tc db mt3">
                <table class="center">
                  <thead>
                    <tr class="f6 tc">
                      <th>Month</th>
                      <th>1</th>
                      <th>2</th>
                      <th>3</th>
                      <th>4</th>
                      <th>5</th>
                      <th>6</th>
                      <th>7</th>
                      <th>8</th>
                      <th>9</th>
                      <th>10</th>
                      <th>11</th>
                      <th>12</th>
                      <th>13</th>
                      <th>14</th>
                      <th>15</th>
                      <th>16</th>
                      <th>17</th>
                      <th>18</th>
                      <th>19</th>
                      <th>20</th>
                      <th>21</th>
                      <th>22</th>
                      <th>23</th>
                      <th>24</th>
                      <th>25</th>
                      <th>26</th>
                      <th>27</th>
                      <th>28</th>
                      <th>29</th>
                      <th>30</th>
                      <th>31</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="holidayRow in holidays" :key="holidayRow.id" class="">
                      <td v-for="holiday in holidayRow" :key="holiday.id" class="f6 tc">
                        <span :class="isOff(holiday)" class="ph1">
                          {{ holiday.abbreviation }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>


              <!-- RENAME PTO POLICY FORM -->
              <div v-show="idToUpdate == ptoPolicy.id" class="cf mt3">
                <form @submit.prevent="update(ptoPolicy.id)">
                  <div class="fl w-100 w-70-ns mb3 mb0-ns">
                    <text-input :id="'year-' + ptoPolicy.id"
                                :ref="'year' + ptoPolicy.id"
                                v-model="form.total_worked_days"
                                :type="'number'"
                                :min="1"
                                :max="300"
                                :placeholder="'Marketing coordinator'"
                                :custom-ref="'year' + ptoPolicy.id"
                                :datacy="'list-edit-input-name-' + ptoPolicy.id"
                                :errors="$page.errors.total_worked_days"
                                :required="true"
                                :extra-class-upper-div="'mb0'"
                                @esc-key-pressed="idToUpdate = 0"
                    />
                  </div>
                  <div class="fl w-30-ns w-100 tr">
                    <a class="btn dib-l db mb2 mb0-ns" :data-cy="'list-edit-cancel-button-' + ptoPolicy.id" @click.prevent="idToUpdate = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-edit-cta-button-' + ptoPolicy.id" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </div>

              <!-- LIST OF ACTIONS FOR EACH PTO POLICY -->
              <ul v-show="idToUpdate != ptoPolicy.id" class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns">
                <!-- EDIT A PTO POLICY -->
                <li class="di mr2">
                  <a class="pointer" :data-cy="'list-edit-button-' + ptoPolicy.id" @click.prevent="displayUpdateModal(ptoPolicy) ; form.total_worked_days = ptoPolicy.total_worked_days">
                    {{ $t('app.edit') }}
                  </a>
                </li>
              </ul>
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
    holidays: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      deleteModal: false,
      updateModal: false,
      loadingState: '',
      updateModalId: 0,
      idToUpdate: 0,
      idToDelete: 0,
      localHolidays: null,
      form: {
        default_amount_of_allowed_holidays: null,
        default_amount_of_sick_days: null,
        default_amount_of_pto_days: null,
        errors: [],
      },
    };
  },

  created() {
    this.localHolidays = this.holidays;
  },

  methods: {
    isOff: function (holiday) {
      return {
        'weekend': holiday.day_of_week == 0 || holiday.day_of_week == 6
      };
    },

    displayUpdateModal(ptoPolicy) {
      this.idToUpdate = ptoPolicy.id;
      this.form.default_amount_of_allowed_holidays = ptoPolicy.default_amount_of_allowed_holidays;
      this.form.default_amount_of_sick_days = ptoPolicy.default_amount_of_sick_days;
      this.form.default_amount_of_pto_days = ptoPolicy.default_amount_of_pto_days;
    },

    toggleOff: function (holiday) {
      //holiday.
    },

    update(id) {
      axios.put('/' + this.$page.auth.company.id + '/account/ptopolicies/' + id, this.form)
        .then(response => {
          this.$snotify.success(this.$t('account.pto_policies_update'), {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          });

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
