<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 23px;
}

.team-member {
  padding-left: 34px;

  .avatar {
    top: -2px;
    left: 2px;
  }
}

.ball-pulse {
  right: 8px;
  top: 37px;
  position: absolute;
}

.plus-button {
  padding: 2px 7px 4px;
  margin-right: 4px;
  border-color: #60995c;
  color: #60995c;
}
</style>

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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/groups/'">{{ $t('app.breadcrumb_kb_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_kb_create') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('kb.create_title') }}

            <help :url="$page.props.help_links.team_recent_ship_create" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Title -->
            <text-input :id="'title'"
                        v-model="form.title"
                        :name="'name'"
                        :datacy="'kb-title-input'"
                        :errors="$page.props.errors.title"
                        :label="$t('kb.create_input_title')"
                        :help="$t('kb.create_input_title_help')"
                        :required="true"
                        :autofocus="true"
            />

            <!-- Actions -->
            <div class="mb4 mt5">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/company/kb/'" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.create')" :cypress-selector="'submit-create-project-button'" />
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
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    TextInput,
    Errors,
    LoadingButton,
    Help
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      form: {
        title: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/kb`, this.form)
        .then(response => {
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
