<style scoped>
input[type=checkbox] {
  top: 5px;
}
input[type=radio] {
  top: -2px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/news'"
                  :previous="$t('app.breadcrumb_account_manage_company_news')"
      >
        {{ $t('app.breadcrumb_account_edit_company_news') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5 measure center">
          <h2 class="tc normal mb4">
            {{ $t('account.company_news_edit_headline', { name: $page.props.auth.company.name}) }}
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Title -->
            <text-input :id="'title'"
                        v-model="form.title"
                        :name="'title'"
                        :datacy="'news-title-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('account.company_news_new_title')"
                        :help="$t('account.company_news_new_title_help')"
                        :required="true"
            />

            <!-- Content -->
            <text-area v-model="form.content"
                       :label="$t('account.company_news_new_content')"
                       :datacy="'news-content-textarea'"
                       :required="true"
                       :rows="10"
                       :help="$t('account.company_news_new_content_help')"
            />

            <!-- Actions -->
            <div class="mv4">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/account/news'" data-cy="cancel-button" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.publish')" :cypress-selector="'submit-update-news-button'" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import TextArea from '@/Shared/TextArea';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    TextArea,
    Errors,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    news: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        title: null,
        content: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  created() {
    this.form.title = this.news.title;
    this.form.content = this.news.content;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.put('/' + this.$page.props.auth.company.id + '/account/news/' + this.news.id, this.form)
        .then(response => {
          localStorage.success = this.$t('account.company_news_update_success');
          this.$inertia.visit('/' + response.data.data.company.id + '/account/news');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
