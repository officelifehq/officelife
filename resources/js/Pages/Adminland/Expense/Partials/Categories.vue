<style scoped>
.list li:last-child {
  border-bottom: 0;
}
</style>

<template>
  <!-- EXPENSES CATEGORIES -->
  <div class="mb5">
    <h3 class="relative adminland-headline fw4">
      <span class="dib mb3 di-l" :class="localCategories.length == 0 ? 'white' : ''">
        <span class="mr1">
          📦
        </span> {{ $tc('account.expense_category_headline') }}

        <help :url="$page.props.help_links.adminland_expense_categories" :datacy="'help-icon-expenses-categories'" :top="'1px'" />
      </span>
      <a class="btn absolute-l relative dib-l db right-0 f5" data-cy="add-category-button" @click.prevent="displayAddModal">
        {{ $t('account.expense_category_create_cta') }}
      </a>
    </h3>

    <!-- MODAL TO ADD AN EXPENSE CATEGORY -->
    <form v-show="modal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
      <errors :errors="form.errors" />

      <div class="cf">
        <div class="fl w-100 w-70-ns mb0-ns">
          <text-input
            :ref="'newCategory'"
            v-model="form.name"
            :errors="$page.props.errors.name"
            :datacy="'add-title-input'"
            required
            :placeholder="$t('account.expense_category_create_placeholder')"
            :extra-class-upper-div="'mb0'"
            @esc-key-pressed="modal = false"
          />
        </div>
        <div class="fl w-30-ns w-100 tr">
          <a class="btn dib-l db mb2 mb0-ns" @click.prevent="modal = false ; form.name = ''">
            {{ $t('app.cancel') }}
          </a>
          <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" data-cy="modal-add-cta" :state="loadingState" :text="$t('app.add')" />
        </div>
      </div>
    </form>

    <!-- LIST OF EXISTING EXPENSE CATEGORIES -->
    <ul v-show="localCategories.length != 0" class="list pl0 mv0 center ba br2 bb-gray" data-cy="categories-list">
      <li v-for="category in localCategories" :key="category.id" :data-cy="'category-' + category.id" class="pv3 ph2 bb bb-gray bb-gray-hover">
        {{ category.name }}

        <!-- RENAME POSITION FORM -->
        <div v-show="idToUpdate == category.id" class="cf mt3">
          <form @submit.prevent="update(category.id)">
            <div class="fl w-100 w-70-ns mb3 mb0-ns">
              <text-input :id="'title-' + category.id"
                          :ref="'title' + category.id"
                          v-model="form.name"
                          :placeholder="form.name"
                          :custom-ref="'title' + category.id"
                          :datacy="'list-rename-input-name-' + category.id"
                          :errors="$page.props.errors.first_name"
                          required
                          :extra-class-upper-div="'mb0'"
                          @esc-key-pressed="idToUpdate = 0"
              />
            </div>
            <div class="fl w-30-ns w-100 tr">
              <a class="btn dib-l db mb2 mb0-ns" :data-cy="'list-rename-cancel-button-' + category.id" @click.prevent="idToUpdate = 0">
                {{ $t('app.cancel') }}
              </a>
              <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-rename-cta-button-' + category.id" :state="loadingState" :text="$t('app.update')" />
            </div>
          </form>
        </div>

        <!-- LIST OF ACTIONS FOR EACH CATEGORY -->
        <ul v-show="idToUpdate != category.id" class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns f6">
          <!-- RENAME A CATEGORY -->
          <li class="di mr2">
            <a class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'list-rename-button-' + category.id" @click.prevent="displayUpdateModal(category) ; form.name = category.name">{{ $t('app.rename') }}</a>
          </li>

          <!-- DELETE A CATEGORY -->
          <li v-if="idToDelete == category.id" class="di">
            {{ $t('app.sure') }}
            <a class="c-delete mr1 pointer" :data-cy="'list-delete-confirm-button-' + category.id" @click.prevent="destroy(category.id)">
              {{ $t('app.yes') }}
            </a>
            <a class="pointer" :data-cy="'list-delete-cancel-button-' + category.id" @click.prevent="idToDelete = 0">
              {{ $t('app.no') }}
            </a>
          </li>
          <li v-else class="di">
            <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'list-delete-button-' + category.id" @click.prevent="idToDelete = category.id">
              {{ $t('app.delete') }}
            </a>
          </li>
        </ul>
      </li>
    </ul>

    <!-- BLANK STATE -->
    <div v-show="localCategories.length == 0" class="pa3 mt5">
      <p class="tc measure center mb4 lh-copy">
        {{ $t('account.expense_category_blank') }}
      </p>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Help,
    TextInput,
    Errors,
    LoadingButton
  },

  props: {
    categories: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localCategories: [],
      loadingState: '',
      modal: false,
      form: {
        name: null,
        errors: [],
      },
      idToUpdate: 0,
      idToDelete: 0,
    };
  },

  watch: {
    categories: {
      handler(value) {
        this.localCategories = value;
      },
      deep: true
    }
  },

  mounted() {
    this.localCategories = this.categories;
  },

  methods: {
    displayAddModal() {
      this.modal = true;
      this.form.name = '';

      this.$nextTick(() => {
        this.$refs.newCategory.focus();
      });
    },

    displayUpdateModal(category) {
      this.idToUpdate = category.id;

      this.$nextTick(() => {
        this.$refs[`title${category.id}`].focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('account.expenses.store', this.$page.props.auth.company.id), this.form)
        .then(response => {
          this.flash(this.$t('account.expense_category_success'), 'success');

          this.loadingState = null;
          this.form.name = null;
          this.modal = false;
          this.localCategories.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(id) {
      axios.put(this.route('account.expenses.update', [this.$page.props.auth.company.id, id]), this.form)
        .then(response => {
          this.flash(this.$t('account.expense_category_update_success'), 'success');

          this.idToUpdate = 0;
          this.form.name = null;

          this.localCategories[this.localCategories.findIndex(x => x.id === id)] = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(id) {
      axios.delete(this.route('account.expenses.destroy', [this.$page.props.auth.company.id, id]))
        .then(response => {
          this.flash(this.$t('account.expense_category_delete_success'), 'success');

          this.idToDelete = 0;
          id = this.localCategories.findIndex(x => x.id === id);
          this.localCategories.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
