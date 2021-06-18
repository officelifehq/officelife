<style lang="scss" scoped>
.flow-list {
  li:last-child {
    border-bottom: 0;
  }
}
</style>

<template>
  <layout :notifications="notifications">
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account/flows'">{{ $t('app.breadcrumb_account_manage_flows') }}</inertia-link>
          </li>
          <li class="di">
            Create a flow
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5 center">
          <h2 class="tc normal mb4">
            Choose the type of flow you want to create
          </h2>

          <!-- choose a flow type -->
          <div>
            <p class="mt0 f6 gray">Actions to execute on a specific date</p>
            <ul class="ba bb-gray br3 list pl0 flow-list">
              <li class="pa3 bb-gray bb">
                <inertia-link :href="url.onboarding" class="f5 fw5 dib mb2">When an employee joins the company</inertia-link>
                <span class="db gray f6">This lets you define an onboarding flow.</span>
              </li>
            </ul>

            <p class="mt4 f6 gray">Actions to execute on an anniversary of a specific date</p>
            <ul class="ba bb-gray br3 list pl0">
              <li>Job anniversary</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    url: {
      type: Object,
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
