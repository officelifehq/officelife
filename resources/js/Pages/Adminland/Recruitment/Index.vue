<style lang="scss" scoped>
.list li:last-child {
  border-bottom: 0;
}

.stage-list {
  li + li:before {
    content: '>';
    padding-left: 5px;
    padding-right: 5px;
  }
}

.template-item {
  &:first-child:hover {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  &:last-child {
    border-bottom: 0;

    &:hover {
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account'"
                  :previous="$t('app.breadcrumb_account_home')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_account_manage_recruitment') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.recruitment_index') }}

            <help :url="$page.props.help_links.recruitment_template" :top="'2px'" />
          </h2>

          <p class="relative adminland-headline">
            <span class="dib mb3 di-l">&nbsp;</span>
            <a class="btn absolute-l relative dib-l db right-0" @click.prevent="displayAddModal">
              {{ $t('account.recruitment_index_cta') }}
            </a>
          </p>

          <!-- MODAL TO ADD A POSITION -->
          <form v-show="modal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf">
              <div class="fl w-100 w-70-ns mb3 mb0-ns">
                <text-input :ref="'newTemplateModal'"
                            v-model="form.name"
                            :placeholder="$t('account.recruitment_index_new_placeholder')"
                            :errors="$page.props.errors.name"
                            :extra-class-upper-div="'mb0'"
                            @esc-key-pressed="modal = false"
                />
              </div>
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns mr2" @click.prevent="modal = false">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" data-cy="modal-add-cta" :state="loadingState" :text="$t('app.add')" />
              </div>
            </div>
          </form>

          <!-- LIST OF TEMPLATES -->
          <ul v-if="localTemplates.length > 0" class="list pl0 mv0 center ba br2 bb-gray">
            <li v-for="template in localTemplates" :key="template.id" class="pv3 ph2 bb bb-gray bb-gray-hover template-item">
              <span class="mb2 db"><inertia-link :href="template.url">{{ template.name }}</inertia-link></span>

              <div v-if="template.stages">
                <ul v-if="template.stages.length > 0" class="pl0 ma0 list f7 gray stage-list">
                  <li v-for="stage in template.stages" :key="stage.id" class="di">{{ stage.name }}</li>
                </ul>
              </div>

              <span v-else class="f7 gray">{{ $t('account.recruitment_index_no_stages') }}</span>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-else class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.recruitment_index_blank') }}
            </p>
            <img loading="lazy" class="db center mb4" alt="add a position symbol" srcset="/img/company/account/blank-position-1x.png,
                                          /img/company/account/blank-position-2x.png 2x"
            />
          </div>
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    Errors,
    LoadingButton,
    Help,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    data: {
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

  watch: {
    templates: {
      handler(value) {
        this.localTemplates = value;
      },
      deep: true
    }
  },

  created() {
    this.localTemplates = this.data.templates;
  },

  methods: {
    displayAddModal() {
      this.modal = true;

      this.$nextTick(() => {
        this.$refs.newTemplateModal.focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.data.url.store, this.form)
        .then(response => {
          this.flash(this.$t('account.recruitment_index_new_success'), 'success');

          this.loadingState = null;
          this.form.title = null;
          this.modal = false;
          this.localTemplates.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
