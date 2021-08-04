<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            …
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/kb/' + page.wiki.id + '/pages/' + page.id">{{ page.title }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_page_edit') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('kb.page_edit_title') }}

            <help :url="$page.props.help_links.wiki" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Title -->
            <text-input :id="'title'"
                        v-model="form.title"
                        :name="'name'"
                        :datacy="'kb-title-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('kb.page_create_input_title')"
                        :help="$t('kb.page_create_input_title_help')"
                        :required="true"
                        :autofocus="true"
                        :maxlength="191"
            />

            <!-- Content -->
            <text-area v-model="form.content"
                       :label="$t('kb.page_create_input_content')"
                       :required="true"
                       :rows="60"
            />

            <!-- Actions -->
            <div class="flex-ns justify-between">
              <div>
                <inertia-link :href="'/' + $page.props.auth.company.id + '/company/kb/' + page.wiki.id + '/pages/' + page.id" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                  {{ $t('app.cancel') }}
                </inertia-link>
              </div>
              <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.update')" />
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
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    TextInput,
    TextArea,
    Errors,
    LoadingButton,
    Help
  },

  props: {
    page: {
      type: Array,
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
        title: null,
        content: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  mounted() {
    this.form.title = this.page.title;
    this.form.content = this.page.content;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.put(`/${this.$page.props.auth.company.id}/company/kb/${this.page.wiki.id}/pages/${this.page.id}`, this.form)
        .then(response => {
          localStorage.success = this.$t('kb.page_edit_success');
          this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
