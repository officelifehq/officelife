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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/teams/' + team.id">{{ team.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_team_add_team_news') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4">
            {{ $t('team.team_news_create', { name: team.name}) }}
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Title -->
            <text-input :id="'title'"
                        v-model="form.title"
                        :name="'title'"
                        :datacy="'news-title-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('team.team_news_new_title')"
                        :help="$t('team.team_news_new_title_help')"
                        :required="true"
            />

            <!-- Content -->
            <text-area v-model="form.content"
                       :label="$t('team.team_news_new_content')"
                       :datacy="'news-content-textarea'"
                       :required="true"
                       :rows="10"
                       :help="$t('team.team_news_new_content_help')"
            />

            <!-- Actions -->
            <div class="mv4">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/teams/' + team.id" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.publish')" :cypress-selector="'submit-add-news-button'" />
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

export default {
  components: {
    Layout,
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
    team: {
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

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/teams/' + this.team.id + '/news', this.form)
        .then(response => {
          localStorage.success = this.$t('team.team_news_create_success');
          this.$inertia.visit('/' + this.$page.props.auth.company.id + '/teams/' + this.team.id);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
