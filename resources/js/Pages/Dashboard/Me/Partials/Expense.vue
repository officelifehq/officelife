k<style lang="scss" scoped>
.expense-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.expense-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}

.expense-action {
  width: 80px;
}

.expense-status {
  width: 180px;
}

.expense-amount {
  width: 150px;
}

.expense-badge-waiting {
  background-color: #FFF6E4;
  color: #083255;
}
</style>

<template>
  <div :class="'mb5'">
    <div class="cf mw7 center mb2 fw5">
      <span class="mr1">
        💵
      </span> {{ $t('dashboard.expense_title') }}

      <help :url="$page.props.help_links.employee_expenses" :datacy="'help-icon-expense'" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box pa3 relative">
      <img loading="lazy" src="/img/dashboard/question_expense.png" alt="a group taking a selfie" class="absolute-ns right-1" :class="addMode ? 'dn' : 'di-ns'" />

      <p v-if="!addMode" class="lh-copy measure">{{ $t('dashboard.expense_show_description') }}</p>

      <!-- CTA to add an expense -->
      <p v-if="!addMode">
        <a class="btn dib mr2" data-cy="create-expense-cta" @click.prevent="displayAddMode()">{{ $t('dashboard.expense_cta') }}</a>
      </p>

      <!-- FORM TO ADD AN EXPENSE -->
      <div v-show="addMode">
        <form @submit.prevent="submit()">
          <errors :errors="form.errors" />

          <div class="cf">
            <div class="fl-ns w-two-thirds-ns w-100">
              <!-- title -->
              <text-input :id="'title'"
                          :ref="'expenseTitle'"
                          v-model="form.title"
                          :datacy="'expense-title'"
                          :name="'title'"
                          :errors="$page.props.errors.title"
                          :label="$t('dashboard.expense_create_title')"
                          :required="true"
                          @esc-key-pressed="hideAddMode()"
              />

              <div class="dt-ns dt--fixed di">
                <div class="dtc-ns pr2-ns pb0-ns w-100">
                  <!-- amount -->
                  <text-input :id="'amount'"
                              v-model="form.amount"
                              :name="'amount'"
                              :datacy="'expense-amount'"
                              :errors="$page.props.errors.amount"
                              :label="$t('dashboard.expense_create_amount')"
                              :required="true"
                              :type="'number'"
                              :step="'0.01'"
                              :min="0"
                              :max="10000000000"
                              @esc-key-pressed="hideAddMode()"
                  />
                </div>
                <div class="dtc-ns pr2-ns pb0-ns w-100">
                  <!-- currency -->
                  <label class="db mb-2">
                    {{ $t('dashboard.expense_create_currency') }}
                  </label>
                  <a-select
                    v-model:value="form.currency"
                    :placeholder="$t('dashboard.expense_create_currency')"
                    style="width: 200px; margin-bottom: 10px;"
                    :options="currencies"
                    show-search
                    option-filter-prop="label"
                  />
                </div>
              </div>

              <!-- expense category -->
              <select-box :id="'category'"
                          v-model="form.category"
                          :options="categories"
                          :name="'category'"
                          :label="$t('dashboard.expense_create_category')"
                          :custom-label-key="'name'"
                          :custom-value-key="'id'"
                          :errors="$page.props.errors.category"
                          :placeholder="$t('dashboard.expense_create_category')"
                          :required="false"
                          :datacy="'expense-category'"
              />

              <!-- receipt -->
              <!-- <file-input :id="'title'"
                          :ref="'expenseReceipt'"
                          :datacy="'expense-receipt'"
                          :name="'title'"
                          :type="'file'"
                          :errors="$page.props.errors.title"
                          :label="$t('dashboard.expense_create_title')"
                          :required="true"
                          @change="selectFile()"
              /> -->
            </div>
            <div class="fl-ns w-third-ns w-100 mb3 mb0-ns pl3-ns">
              <strong>{{ $t('dashboard.expense_create_help_title') }}</strong>
              <p class="f7 silver lh-copy">
                {{ $t('dashboard.expense_create_help_description') }}
              </p>
            </div>
          </div>

          <!-- Actions -->
          <p class="db lh-copy f6">
            <span class="mr1">👋</span> {{ $t('dashboard.expense_create_help') }}
          </p>
          <p class="ma0">
            <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3 mr2 mb0-ns mb2-ns'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-expense'" />
            <a data-cy="expense-create-cancel" class="pointer mb0-ns mt2 mt0-ns mb3 dib" @click.prevent="hideAddMode()">
              {{ $t('app.cancel') }}
            </a>
          </p>
        </form>
      </div>

      <!-- LIST OF IN PROGRESS EXPENSES -->
      <div v-if="localExpenses.length > 0">
        <ul class="list pl0 mb0" data-cy="expenses-list">
          <li v-for="expense in localExpenses" :key="expense.id" :data-cy="'expense-item-' + expense.id" class="expense-item dt-ns br bl bb bb-gray bb-gray-hover pa3 w-100">
            <div class="dt-row-ns">
              <div class="dtc-ns db mb3 mb0-ns">
                <inertia-link :href="expense.url" :data-cy="'expense-cta-' + expense.id" class="dib mb2">{{ expense.title }}</inertia-link>
                <ul class="f7 fw3 grey list pl0">
                  <li class="mr2 di">{{ expense.expensed_at }}</li>
                  <li v-if="expense.category" class="di">{{ expense.category }}</li>
                </ul>
              </div>
              <div class="expense-amount tc-ns dtc-ns v-mid fw5 db mb3 mb0-ns">
                {{ expense.amount }}

                <!-- converted amount -->
                <div v-if="expense.converted_amount" class="db f6 fw4 mt2 gray">{{ expense.converted_amount }}</div>
              </div>
              <div class="expense-status tc-ns dtc-ns v-mid db mb3 mb0-ns">
                <span class="br3 expense-badge-waiting f7 fw5 ph2 pv2 di" :data-cy="'expense-' + expense.id + '-status-' + expense.status">{{ $t('dashboard.expense_show_status_' + expense.status) }}</span>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import Help from '@/Shared/Help';

export default {
  components: {
    Errors,
    LoadingButton,
    TextInput,
    Help,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    expenses: {
      type: Array,
      default: null,
    },
    categories: {
      type: Array,
      default: null,
    },
    currencies: {
      type: Array,
      default: null,
    },
    defaultCurrency: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      localExpenses: [],
      addMode: false,
      form: {
        title: null,
        amount: null,
        currency: null,
        description: null,
        category: null,
        receipt: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  watch: {
    expenses: {
      handler(value) {
        this.localExpenses = value;
      },
      deep: true
    }
  },

  mounted() {
    this.localExpenses = this.expenses;
    this.form.currency = this.defaultCurrency.id;
  },

  methods: {
    hideAddMode() {
      this.addMode = false;
      this.form.title = null;
      this.form.amount = null;
      this.form.currency = null;
      this.form.description = null;
      this.form.category = null;
    },

    displayAddMode() {
      this.addMode = true;

      this.$nextTick(() => {
        this.$refs.expenseTitle.focus();
      });
    },

    selectFile(event) {
      // `files` is always an array because the file input may be in multiple mode
      this.form.receipt = event;
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('dashboard.expense.store', this.$page.props.auth.company.id), this.form)
        .then(response => {
          this.loadingState = null;
          this.localExpenses.unshift(response.data.data);
          this.hideAddMode();
          this.flash(this.$t('dashboard.expense_submitted'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
