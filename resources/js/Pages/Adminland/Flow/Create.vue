<style scoped>
.flow {
  background-color: #f4f6fa;
  box-shadow: inset 1px 2px 2px rgba(0, 0, 0, 0.14);
  border-radius: 8px;
}

.box-plus-button {
  top: -19px;
}

.green-box {
  border: 2px solid #1cbb70;
}

.lh0 {
  line-height: 0;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/flows'"
                  :previous="$t('app.breadcrumb_account_manage_flows')"
      >
        {{ $t('app.breadcrumb_account_add_employee') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5 center">
          <h2 class="tc normal mb4">
            {{ $t('account.flows_cta') }}
          </h2>

          <!-- Form Errors -->
          <errors :errors="form.errors" />

          <form @submit.prevent="submit">
            <!-- Name -->
            <div class="mb3">
              <label class="db fw4 lh-copy f6" for="name">
                {{ $t('account.flow_new_flow') }}
              </label>
              <input id="name" v-model="form.name" type="text" name="name" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                     required
                     @update:model-value="checkComplete"
              />
              <p class="f7 mb4 lh-title">
                {{ $t('account.flow_new_help') }}
              </p>
            </div>

            <!-- Flow -->
            <div class="mb3 flow pv4">
              <div v-for="step in orderedSteps" :key="step.id">
                <!-- PLUS BUTTON -->
                <div v-show="oldestStep == step.id" class="tc lh0">
                  <img loading="lazy" src="/img/company/account/flow_plus_top.svg" class="center pointer" alt="add flow at the top" @click="addStepBefore()" />
                </div>

                <div class="step tc measure center bg-white br3 ma3 mt0 mb0 relative" :class="{'green-box':(numberOfSteps > 1 && step.type == 'same_day')}">
                  <!-- DELETE BUTTON -->
                  <img v-show="step.type != 'same_day'" loading="lazy" src="/img/trash_button.svg" class="box-plus-button absolute br-100 pa2 bg-white pointer" alt="remove flow"
                       @click.prevent="removeStep(step)"
                  />

                  <!-- CASE OF "BEFORE" STEP -->
                  <div v-show="step.type == 'before'" class="condition pa3 bb bb-gray">
                    <ul class="list ma0 pa0 mb2">
                      <li class="di mr2">
                        <input id="" v-model="step.number" type="number" min="1" max="100"
                               name="" class="tc br2 f5 ba b--black-40 pa2 outline-0"
                               required
                        />
                      </li>
                      <li class="di mr2">
                        <input :id="'frequency_days_' + step.id" v-model="step.frequency" type="radio" :name="'frequency_before_' + step.id" value="days"
                               class="mr1"
                        /> <label :for="'frequency_days_' + step.id">
                          {{ $t('account.flow_new_days') }}
                        </label>
                      </li>
                      <li class="di mr2">
                        <input :id="'frequency_weeks_' + step.id" v-model="step.frequency" type="radio" :name="'frequency_before_' + step.id" value="weeks"
                               class="mr1"
                        /> <label :for="'frequency_weeks_' + step.id">
                          {{ $t('account.flow_new_weeks') }}
                        </label>
                      </li>
                      <li class="di">
                        <input :id="'frequency_months_' + step.id" v-model="step.frequency" type="radio" :name="'frequency_before_' + step.id" value="months"
                               class="mr1"
                        /> <label :for="'frequency_months_' + step.id">
                          {{ $t('account.flow_new_months') }}
                        </label>
                      </li>
                    </ul>

                    <p class="ma0 pa0">
                      {{ $t('account.flow_new_before') }} <span class="brush-blue">
                        {{ $t('account.flow_new_type_' + form.type) }}
                      </span>
                    </p>
                  </div>

                  <!-- CASE OF "SAME DAY" STEP -->
                  <div v-show="step.type == 'same_day'" class="condition pa3 bb bb-gray">
                    <p class="ma0 pa0 mb2">
                      {{ $t('account.flow_new_the_day_event_happens') }}
                    </p>
                    <select v-model="form.type" @update:model-value="checkComplete">
                      <option value="employee_joins_company">
                        {{ $t('account.flow_new_type_employee_joins_company') }}
                      </option>
                      <option value="employee_leaves_company">
                        {{ $t('account.flow_new_type_employee_leaves_company') }}
                      </option>
                      <option value="employee_birthday">
                        {{ $t('account.flow_new_type_employee_birthday') }}
                      </option>
                      <option value="employee_joins_team">
                        {{ $t('account.flow_new_type_employee_joins_team') }}
                      </option>
                      <option value="employee_leaves_team">
                        {{ $t('account.flow_new_type_employee_leaves_team') }}
                      </option>
                      <option value="employee_becomes_manager">
                        {{ $t('account.flow_new_type_employee_becomes_manager') }}
                      </option>
                      <option value="employee_new_position">
                        {{ $t('account.flow_new_type_employee_new_position') }}
                      </option>
                      <option value="employee_leaves_holidays">
                        {{ $t('account.flow_new_type_employee_leaves_holidays') }}
                      </option>
                      <option value="employee_returns_holidays">
                        {{ $t('account.flow_new_type_employee_returns_holidays') }}
                      </option>
                      <option value="employee_returns_leave">
                        {{ $t('account.flow_new_type_employee_returns_leave') }}
                      </option>
                    </select>
                  </div>

                  <!-- CASE OF "AFTER" STEP -->
                  <div v-show="step.type == 'after'" class="condition pa3 bb bb-gray">
                    <ul class="list ma0 pa0 mb2">
                      <li class="di mr2">
                        <input id="" v-model="step.number" type="number" min="1" max="100"
                               name="" class="tc br2 f5 ba b--black-40 pa2 outline-0"
                               required
                        />
                      </li>
                      <li class="di mr2">
                        <input :id="'frequency_days_' + step.id" v-model="step.frequency" type="radio" :name="'frequency_after_' + step.id" value="days"
                               class="mr1"
                        /> <label :for="'frequency_days_' + step.id">
                          {{ $t('account.flow_new_days') }}
                        </label>
                      </li>
                      <li class="di mr2">
                        <input :id="'frequency_weeks_' + step.id" v-model="step.frequency" type="radio" :name="'frequency_after_' + step.id" value="weeks"
                               class="mr1"
                        /> <label :for="'frequency_weeks_' + step.id">
                          {{ $t('account.flow_new_weeks') }}
                        </label>
                      </li>
                      <li class="di">
                        <input :id="'frequency_months_' + step.id" v-model="step.frequency" type="radio" :name="'frequency_after_' + step.id" value="months"
                               class="mr1"
                        /> <label :for="'frequency_months_' + step.id">
                          {{ $t('account.flow_new_months') }}
                        </label>
                      </li>
                    </ul>

                    <p class="ma0 pa0">
                      {{ $t('account.flow_new_after') }} <span class="brush-blue">
                        {{ $t('account.flow_new_type_' + form.type) }}
                      </span>
                    </p>
                  </div>

                  <!-- list of actions -->
                  <actions v-model="step.actions" @completed="checkComplete" />
                </div>

                <!-- DIVIDER -->
                <div v-if="notFirstAndLastStep(step.id)" class="tc lh0">
                  <img loading="lazy" src="/img/company/account/flow_line.svg" class="center pointer" alt="divider between steps" />
                </div>

                <!-- PLUS BUTTON -->
                <div v-show="newestStep == step.id" class="tc">
                  <img loading="lazy" src="/img/company/account/flow_plus_bottom.svg" class="center pointer" alt="plus button to add a new step" @click="addStepAfter()" />
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="mv4">
              <div class="flex-ns justify-between">
                <div>
                  <a :href="'/' + $page.props.auth.company.id + '/account/employees'" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </a>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-add-employee-button'" />
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
import Actions from '@/Pages/Adminland/Flow/Actions';

export default {
  components: {
    Layout,
    Breadcrumb,
    Errors,
    LoadingButton,
    Actions,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      numberOfSteps: 1,
      isComplete: false,
      numberOfBeforeSteps: 0,
      numberOfAfterSteps: 0,
      oldestStep: 0,
      newestStep: 0,
      form: {
        name: null,
        type: null,
        steps: [],
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  computed: {
    orderedSteps: function () {
      return _.orderBy(this.form.steps, 'id');
    }
  },

  mounted() {
    this.form.steps.push({
      id: 0,
      type: 'same_day',
      frequency: 'days',
      number: 1,
      actions: [],
    });
  },

  methods: {
    // Check whether the given step is not the last and not the first
    // Useful to determine if we need to put a separator between steps
    notFirstAndLastStep(id) {
      if (this.oldestStep == id && this.numberOfSteps == 1) {
        return false;
      }
      if (this.newestStep == id) {
        return false;
      }

      return true;
    },

    addStepBefore() {
      this.oldestStep = this.oldestStep + 1 * -1;
      this.form.steps.push({
        id: this.oldestStep,
        type: 'before',
        frequency: 'days',
        number: 1,
        actions: [],
      });
      this.numberOfSteps = this.numberOfSteps + 1;
      this.numberOfBeforeSteps = this.numberOfBeforeSteps + 1;
    },

    addStepAfter() {
      this.newestStep = this.newestStep + 1;
      this.form.steps.push({
        id: this.newestStep,
        type: 'after',
        frequency: 'days',
        number: 1,
        actions: [],
      });
      this.numberOfSteps = this.numberOfSteps + 1;
      this.numberOfAfterSteps = this.numberOfAfterSteps + 1;
    },

    removeStep(step) {
      this.form.steps.splice(this.form.steps.findIndex(i => i.id === step.id), 1);

      if (step.type == 'before') {
        this.numberOfSteps = this.numberOfSteps - 1;
        this.numberOfBeforeSteps = this.numberOfBeforeSteps - 1;

        if (step.id == this.oldestStep) {
          // this basically calculates what is the mininum number that we should
          // assign to the step
          this.oldestStep = Math.min.apply(Math, this.form.steps.map(o => o.id));
        }
      }

      if (step.type == 'after') {
        this.numberOfSteps = this.numberOfSteps - 1;
        this.numberOfAfterSteps = this.numberOfAfterSteps - 1;

        if (step.id == this.newestStep) {
          this.newestStep = Math.max.apply(Math, this.form.steps.map(o => o.id));
        }
      }
    },

    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/account/flows', this.form)
        .then(response => {
          localStorage.success = 'The flow has been added';
          this.$inertia.visit('/' + response.data.company_id + '/account/flows');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    checkComplete(event) {
      var isCompleteYet = true;

      // check if the event is selected
      if (this.form.type === null) {
        isCompleteYet = false;
      }

      // check if a name has been set for the flow
      else if (!this.form.name) {
        isCompleteYet = false;
      }

      else {
        // check if all the steps have the all actions they need
        for (let step in this.form.steps) {
          for (let actions in step) {
            for (let action in actions) {
              if (action['complete'] === false || !action['complete']) {
                isCompleteYet = false;
                break;
              }
            }
            if (!isCompleteYet) {
              break;
            }
          }
          if (!isCompleteYet) {
            break;
          }
        }
      }

      this.isComplete = isCompleteYet;
    }
  }
};

</script>
