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
  <layout title="Home" :user="user">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <a :href="'/' + company.id + '/account/flows'">{{ $t('app.breadcrumb_account_manage_flows') }}</a>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_add_employee') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5 center">
          <h2 class="tc normal mb4">
            Add a new flow
          </h2>

          <!-- Form Errors -->
          <errors :errors="form.errors" />

          <form @submit.prevent="submit">
            <!-- Name -->
            <div class="mb3">
              <label class="db fw4 lh-copy f6" for="first_name">What is the name of the flow?</label>
              <input id="first_name" v-model="form.first_name" type="text" name="first_name" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                     required
              />
              <p class="f7 mb4 lh-title">
                This is an internal name, only used to identify the flow.
              </p>
            </div>

            <!-- Flow -->
            <div class="mb3 flow pv4">

              <div v-for="step in orderedSteps" :key="step.id">

                <!-- PLUS BUTTON -->
                <div class="tc lh0" v-show="oldestStep == step.id">
                  <img src="/img/company/account/flow_plus_top.svg" class="center pointer" @click="addStepBefore()" />
                </div>

                <div class="step tc measure center bg-white br3 ma3 mt0 mb0 relative" v-bind:class="{'green-box':(numberOfSteps > 1 && step.type == 'same_day')}">

                  <!-- DELETE BUTTON -->
                  <img v-show="step.type != 'same_day'" src="/img/trash_button.svg" class="box-plus-button absolute br-100 pa2 bg-white pointer" @click.prevent="removeStep(step)" />

                  <!-- CASE OF "BEFORE" STEP -->
                  <div class="condition pa3 bb bb-gray" v-show="step.type == 'before'">
                    <p class="ma0 pa0 mb2">BEFORE {{ step.id }}</p>
                  </div>

                  <!-- CASE OF "SAME DAY" STEP -->
                  <div class="condition pa3 bb bb-gray" v-show="step.type == 'same_day'">
                    <p class="ma0 pa0 mb2">The day this event happens</p>
                    <select>
                      <option>Employee's hiring date</option>
                    </select>
                  </div>

                  <!-- CASE OF "AFTER" STEP -->
                  <div class="condition pa3 bb bb-gray" v-show="step.type == 'after'">
                    <p class="ma0 pa0 mb2">AFTER {{ step.id }}</p>
                  </div>

                  <!-- list of actions -->
                  <actions v-model="step.actions" />
                </div>

                <!-- DIVIDER -->
                <div class="tc lh0" v-if="notFirstAndLastStep(step.id)">
                  <img src="/img/company/account/flow_line.svg" class="center pointer" />
                </div>

                <!-- PLUS BUTTON -->
                <div class="tc" v-show="newestStep == step.id">
                  <img src="/img/company/account/flow_plus_bottom.svg" class="center pointer" @click="addStepAfter()" />
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="mv4">
              <div class="flex-ns justify-between">
                <div>
                  <a :href="'/' + company.id + '/account/employees'" class="btn btn-secondary dib tc w-auto-ns w-100 mb2 pv2 ph3">{{ $t('app.cancel') }}</a>
                </div>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-add-employee-button'" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>

export default {
  props: {
    company: {
      type: Object,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    }
  },

  data() {
    return {
      steps: [],
      numberOfSteps: 1,
      numberOfBeforeSteps: 0,
      numberOfAfterSteps: 0,
      oldestStep: 0,
      newestStep: 0,
      form: {
        first_name: null,
        last_name: null,
        email: null,
        permission_level: null,
        send_invitation: false,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    }
  },

  mounted() {
    this.steps.push({
      id: 0,
      type: 'same_day',
      actions: [],
    })
  },

  computed: {
    orderedSteps: function () {
      return _.orderBy(this.steps, 'id')
    }
  },

  methods: {
    // Check whether the given step is not the last and not the first
    // Useful to determine if we need to put a separator between steps
    notFirstAndLastStep(id) {
      if (this.oldestStep == id && this.numberOfSteps == 1) {
        return false
      }
      if (this.newestStep == id) {
        return false
      }

      return true
    },

    addStepBefore() {
      this.oldestStep = this.oldestStep + 1 * -1
      this.steps.push({
        id: this.oldestStep,
        type: 'before',
        actions: [],
      })
      this.numberOfSteps = this.numberOfSteps + 1
      this.numberOfBeforeSteps = this.numberOfBeforeSteps + 1
    },

    addStepAfter() {
      this.newestStep = this.newestStep + 1
      this.steps.push({
        id: this.newestStep,
        type: 'after',
        actions: [],
      })
      this.numberOfSteps = this.numberOfSteps + 1
      this.numberOfAfterSteps = this.numberOfAfterSteps + 1
    },

    removeStep(step) {
      var idToRemove = step.id
      this.steps.splice(this.steps.findIndex(i => i.id === step.id), 1)

      if (step.type == 'before') {
        this.numberOfSteps = this.numberOfSteps - 1
        this.numberOfBeforeSteps = this.numberOfBeforeSteps - 1

        if (step.id == this.oldestStep) {
          this.oldestStep = Math.min.apply(Math, this.steps.map(function(o) { return o.id }))
        }
      }

      if (step.type == 'after') {
        this.numberOfSteps = this.numberOfSteps - 1
        this.numberOfAfterSteps = this.numberOfAfterSteps - 1

        if (step.id == this.newestStep) {
          this.newestStep = Math.max.apply(Math, this.steps.map(function(o) { return o.id }))
        }
      }
    },
  }
}

</script>
