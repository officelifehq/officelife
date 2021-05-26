<style lang="scss" scoped>
.product-key {
  .code {
    font-family: Menlo,Consolas,monospace;
  }
}

.add-section {
  background-color: #EBF4FF;
}

.option {
  background-color: #06B6D4;
  width: 24px;
  color: #fff;
  height: 24px;
  padding: 3px 10px 5px 8px;
}

.seats-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.seats-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account/softwares'">{{ $t('app.breadcrumb_account_manage_softwares') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_show_software') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="mt5">
          <h2 class="pa3 normal ma0 bb bb-gray" data-cy="item-name">
            {{ software.name }}
          </h2>

          <div class="product-key pa3 bb bb-gray">
            <p class="mb1 mt0 f6 fw5"><span class="mr1">🗝</span> Product key</p>
            <div class="code br3 ba bb-gray pa3">
              {{ software.product_key }}
            </div>
          </div>

          <div class="pa3 bb bb-gray">
            <p class="mb1 f6 fw5"><span class="mr1">👨‍🎓</span> Purchase information</p>
            <div>
              Licensed to: {{ software.licensed_to_name }} <span v-if="software.licensed_to_email_address">
                ({{ software.licensed_to_email_address }})
              </span>
            </div>
          </div>

          <div v-if="software.purchase_amount" class="pa3 bb bb-gray">
            <p class="mb1 f6 fw5"><span class="mr1">💰</span> Price</p>
            <div class="">
              {{ software.currency }} {{ software.purchase_amount }} <span v-if="software.exchange_rate">
                ({{ software.converted_to_currency }} {{ software.converted_purchase_amount }} - Exchange rate: {{ software.exchange_rate }})
              </span>
            </div>
          </div>

          <div v-if="software.purchased_at" class="mb4">
            <p class="mb1 f6 fw5"><span class="mr1">📆</span> Purchase date</p>
            <div>{{ software.purchased_at }}</div>
          </div>

          <div v-if="software.website" class="mb4">
            <p class="mb1 f6 fw5"><span class="mr1">🔗</span> Website</p>
            <div>{{ software.website }}</div>
          </div>

          <div v-if="software.order_number" class="mb4">
            <p class="mb1 f6 fw5"><span class="mr1">🐝</span> Order number</p>
            <div>{{ software.order_number }}</div>
          </div>

          <!-- seats -->
          <div class="pa3">
            <div class="flex justify-between mb1 fw5">
              <div class="f6">
                <span class="mr1">
                  🪑
                </span> Seats
              </div>

              <a v-if="!assignSeatMode" href="#" class="btn" @click.prevent="setAssignMode()">
                Use a seat
              </a>
              <a v-if="assignSeatMode" href="#" class="btn" @click.prevent="hideAssignMode()">
                Cancel
              </a>
            </div>

            <!-- modal to use a seat -->
            <div v-if="assignSeatMode" class="add-section pa3 br3 mb3">
              <p v-if="!searchMode" class="fw5 mt0">You have two options to assign a software to an employee:</p>
              <div v-if="!searchMode" class="flex justify-between">
                <div class="w-50 mr4 flex items-start">
                  <p class="ma0 option br-100 mr2">a</p>
                  <div>
                    <div class="lh-copy mb3">
                      Give a seat to every active employee in the company who doesn't yet have this software ({{ employeesWithoutSofware }} total)
                    </div>
                    <a class="dib btn" href="#">Do this</a>
                  </div>
                </div>
                <div class="w-50 flex items-start">
                  <p class="ma0 option br-100 mr2">b</p>
                  <div>
                    <div class="mb3">
                      Give a seat to a specific employee
                    </div>
                    <a class="dib btn" href="#" @click.prevent="showSearch()">Do this</a>
                  </div>
                </div>
              </div>

              <!-- option b: give a seat to a specific employee -->
              <div v-if="searchMode">
                <form class="relative" @submit.prevent="search">
                  <text-input :id="'name'"
                              :ref="'search'"
                              v-model="form.searchTerm"
                              :name="'name'"
                              :errors="$page.props.errors.name"
                              :label="$t('group.meeting_show_participants_add_guest_input')"
                              :required="true"
                              @keyup="search"
                              @input="search"
                              @esc-key-pressed="hideSearch()"
                  />
                  <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
                </form>

                <!-- search results -->
                <div class="pl0 list ma0">
                  <ul v-if="potentialEmployees.length > 0" class="list ma0 pl0">
                    <li v-for="employee in potentialEmployees" :key="employee.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
                      {{ employee.name }}

                      <a class="absolute right-1 pointer" @click.prevent="attach(employee)">
                        {{ $t('app.choose') }}
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="mb2">
              <span class="gray">
                Current usage:
              </span> {{ localUsedSeats }}/{{ software.seats }} seats used
            </div>

            <ul class="mb3 list pl0 mv0 center ba br2 bb-gray">
              <li v-for="employee in localEmployees" :key="employee.id" class="pv3 ph2 bb bb-gray bb-gray-hover seats-item">
                <small-name-and-avatar
                  :name="employee.name"
                  :avatar="employee.avatar"
                  :class="'f4 fw4'"
                  :top="'0px'"
                  :margin-between-name-avatar="'29px'"
                />
              </li>
            </ul>
          </div>

          <p v-if="software.serial_number" class="relative mb3" data-cy="item-serial-number">
            <svg class="relative mr1" style="top: 3px;" width="15" height="15" viewBox="0 0 15 15"
                 fill="none" xmlns="http://www.w3.org/2000/svg"
            >
              <path d="M3.88889 11.1111H3.89611M3.88889 14C3.12271 14 2.38791 13.6956 1.84614 13.1539C1.30436 12.6121 1 11.8773 1 11.1111V2.44444C1 2.06135 1.15218 1.69395 1.42307 1.42307C1.69395 1.15218 2.06135 1 2.44444 1H5.33333C5.71642 1 6.08382 1.15218 6.35471 1.42307C6.6256 1.69395 6.77778 2.06135 6.77778 2.44444V11.1111C6.77778 11.8773 6.47341 12.6121 5.93164 13.1539C5.38987 13.6956 4.65507 14 3.88889 14V14ZM3.88889 14H12.5556C12.9386 14 13.306 13.8478 13.5769 13.5769C13.8478 13.306 14 12.9386 14 12.5556V9.66667C14 9.28358 13.8478 8.91618 13.5769 8.64529C13.306 8.3744 12.9386 8.22222 12.5556 8.22222H10.8634L3.88889 14ZM6.77778 4.13661L7.9745 2.93989C8.24537 2.6691 8.61271 2.51698 8.99572 2.51698C9.37874 2.51698 9.74607 2.6691 10.0169 2.93989L12.0601 4.98306C12.3309 5.25393 12.483 5.62126 12.483 6.00428C12.483 6.38729 12.3309 6.75463 12.0601 7.0255L5.93133 13.1536L6.77778 4.13661Z" stroke="#C9C4C4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ software.serial_number }}
          </p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import TextInput from '@/Shared/TextInput';
import 'vue-loaders/dist/vue-loaders.css';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';

export default {
  components: {
    Layout,
    SmallNameAndAvatar,
    TextInput,
    'ball-pulse-loader': BallPulseLoader.component,
  },

  props: {
    software: {
      type: Object,
      default: null,
    },
    employees: {
      type: Array,
      default: null,
    },
    paginator: {
      type: Object,
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
        searchTerm: null,
        employeeId: 0,
        errors: [],
      },
      potentialEmployees: [],
      processingSearch: false,
      loadingState: '',
      errorTemplate: Error,
      localEmployees: null,
      localUsedSeats: 0,
      employeesWithoutSofware: 0,
      assignSeatMode: false,
      searchMode: false,
    };
  },

  mounted() {
    this.localEmployees = this.employees;
    this.localUsedSeats = this.software.used_seats;
  },

  methods: {
    setAssignMode() {
      this.getNumberOfEmployeesWithoutSoftware();
      this.assignSeatMode = true;
      this.hideSearch();
    },

    showSearch() {
      this.searchMode = true;

      this.$nextTick(() => {
        this.$refs['search'].focus();
      });
    },

    hideAssignMode() {
      this.assignSeatMode = false;
      this.hideSearch();
    },

    hideSearch() {
      this.form.searchTerm = '';
      this.potentialEmployees = [];
      this.searchMode = false;
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}/search`, this.form)
            .then(response => {
              this.potentialEmployees = response.data.data;
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
            });
        }
      },
      500),

    attach(employee) {
      this.loadingState = 'loading';
      this.form.employeeId = employee.id;

      axios.post(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}/attach`, this.form)
        .then(response => {
          this.localEmployees.unshift(response.data.data);
          this.localUsedSeats = this.localUsedSeats + 1;
          this.hideAssignMode();
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    getNumberOfEmployeesWithoutSoftware() {
      axios.get(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}/numberOfEmployeesWhoDontHaveSoftware`)
        .then(response => {
          this.employeesWithoutSofware = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
