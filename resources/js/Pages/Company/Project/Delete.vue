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
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id">{{ $t('app.breadcrumb_project_detail') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_project_delete') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="mt5">
          <h2 class="pa3 tc normal mb0">
            {{ $t('project.delete_title') }}
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf pa3 bb-gray bb">
              <p class="lh-copy">{{ $t('project.delete_description') }}</p>
            </div>

            <!-- Actions -->
            <div class="cf pa3 bb-gray bb">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2" data-cy="cancel-button">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :classes="'btn destroy w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.delete')" :cypress-selector="'submit-delete-project-button'" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    Errors,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    project: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        errors: [],
      },
      loadingState: '',
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.delete(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}`)
        .then(response => {
          localStorage.success = this.$t('project.delete_success');
          this.$inertia.visit(`${this.$page.props.auth.company.id}/company/projects`);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
