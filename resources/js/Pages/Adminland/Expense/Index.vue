<style scoped>
.list li:last-child {
  border-bottom: 0;
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
            {{ $t('app.breadcrumb_account_manage_expense_categories') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.expense_categories_title', { company: $page.auth.company.name}) }}

            <help :url="$page.help_links.expenses" :datacy="'help-icon-expenses'" :top="'1px'" />
          </h2>

          <p class="relative adminland-headline">
            <span class="dib mb3 di-l" :class="categories.length == 0 ? 'white' : ''">
              {{ $tc('account.expense_category_total', categories.length, { company: $page.auth.company.name, count: categories.length}) }}
            </span>
            <a class="btn absolute-l relative dib-l db right-0" data-cy="add-category-button" @click.prevent="displayAddModal">
              {{ $t('account.expense_category_create_cta') }}
            </a>
          </p>

          <!-- MODAL TO ADD AN EXPENSE CATEGORY -->
          <form v-show="modal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf">
              <div class="fl w-100 w-70-ns mb0-ns">
                <text-input
                  :ref="'newCategory'"
                  v-model="form.name"
                  :errors="$page.errors.name"
                  :datacy="'add-title-input'"
                  required
                  :placeholder="$t('account.expense_category_create_placeholder')"
                  :extra-class-upper-div="'mb0'"
                />
              </div>
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns" @click.prevent="modal = false ; form.name = ''">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" data-cy="modal-add-cta" :state="loadingState" :text="$t('app.add')" />
              </div>
            </div>
          </form>

          <!-- LIST OF EXISTING EXPENSE CATEGORIES -->
          <ul v-show="categories.length != 0" class="list pl0 mv0 center ba br2 bb-gray" data-cy="categories-list">
            <li v-for="category in categories" :key="category.id" class="pv3 ph2 bb bb-gray bb-gray-hover">
              <inertia-link :href="category.url">{{ category.name }}</inertia-link>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-show="categories.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.expense_category_blank') }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import Help from '@/Shared/Help';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
    Help,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    categories: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      loadingState: '',
      form: {
        name: null,
        errors: [],
      },
    };
  },

  methods: {
    displayAddModal() {
      this.modal = true;
      this.form.name = '';

      this.$nextTick(() => {
        this.$refs['newCategory'].$refs['input'].focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/account/expenses', this.form)
        .then(response => {
          flash(this.$t('account.employee_statuses_success_new'), 'success');

          this.loadingState = null;
          this.form.name = null;
          this.modal = false;
          this.categories.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
