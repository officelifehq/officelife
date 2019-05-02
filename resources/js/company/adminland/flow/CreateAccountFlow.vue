<style scoped>
.flow {
  background-color: #f4f6fa;
  box-shadow: inset 1px 2px 2px rgba(0, 0, 0, 0.14);
  border-radius: 8px;
}

.actions-dots {
  top: 15px;
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
              <div v-for="step in orderedSteps">
                <!-- PLUS BUTTON -->
                <div class="tc" v-show="firstStep == step.id">
                  <img src="/img/company/account/flow_plus_top.svg" class="center pointer" @click="addStepBefore()" />
                </div>

                <div class="step tc measure center bg-white br3 ma3 mt0 mb0">

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
                  <div class="actions pa3 bb bb-gray">
                    <p class="ma0 pa0 mb3">Do the following</p>
                    <ul class="list ma0 pa0 tl">
                      <li class="relative db bb-gray-hover pv2 ph1">
                        <span class="number">1</span>
                        Notify <span class="bb b--dotted bt-0 bl-0 br-0 pointer">an employee</span> with <span class="bb b--dotted bt-0 bl-0 br-0 pointer">a message</span>
                        <img src="/img/common/triple-dots.svg" class="absolute right-0 pointer actions-dots" />
                      </li>
                      <li class="relative db bb-gray-hover pv2 ph1">
                        <span class="number">2</span>
                        Notify an employee with a message
                        <img src="/img/common/triple-dots.svg" class="absolute right-0 pointer actions-dots" />
                      </li>
                    </ul>
                  </div>

                  <!-- add actions -->
                  <div class="pa3">
                    <a href="" class="btn dib">Add action</a>
                  </div>
                </div>

                <!-- DIVIDER -->
                <div class="tc" v-if="notFirstAndLastStep(step.id)">
                  <img src="/img/company/account/flow_line.svg" class="center pointer" />
                </div>

                <!-- PLUS BUTTON -->
                <div class="tc" v-show="lastStep == step.id">
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
      numberOfAfterSteps: 1,
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
      id: 1,
      type: 'same_day',
    })
  },

  computed: {
    firstStep() {
      return this.steps[0].id
    },

    lastStep() {
      return this.steps[this.steps.length - 1].id
    },

    orderedSteps: function () {
      return _.orderBy(this.steps, 'id')
    }
  },

  methods: {
    // Check whether the given step is not the last and not the first
    // Useful to determine if we need to put a separator between steps
    notFirstAndLastStep(id) {
      if (this.firstStep == id && this.numberOfSteps == 2) {
        return false
      }
      if (this.lastStep == id) {
        return false
      }

      return true
    },

    addStepBefore() {
      this.numberOfBeforeSteps = this.numberOfBeforeSteps + 1
      this.steps.push({
        id: this.numberOfBeforeSteps * -1,
        type: 'before',
      })
      this.numberOfSteps = this.numberOfSteps + 1
    },

    addStepAfter() {
      this.numberOfAfterSteps = this.numberOfAfterSteps + 1
      this.steps.push({
        id: this.numberOfAfterSteps,
        type: 'after',
      })
      this.numberOfSteps = this.numberOfSteps + 1
    },

    submit() {
      this.loadingState = 'loading'

      axios.post('/' + this.company.id + '/account/employees', this.form)
        .then(response => {
          localStorage.success = 'The employee has been added'
          Turbolinks.visit('/' + response.data.company_id + '/account/employees')
        })
        .catch(error => {
          this.loadingState = null
          this.form.errors = _.flatten(_.toArray(error.response.data))
        })
    },
  }
}

</script>
