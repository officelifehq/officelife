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

.then {
  left: 20px;
  padding-top: 10px;
  padding-bottom: 10px;
  padding-left: 12px;
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account/recruitment'">{{ $t('app.breadcrumb_account_manage_recruitment') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_recruitment_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5 relative">
          <h2 class="tc normal mb4">
            Job interview process
          </h2>

          <!-- list of stages -->
          <div v-for="(stage, key, index) in localStages" :key="stage.id">
            <div class="flex justify-between items-center ba bb-gray bb-gray-hover br3 ph3 pv2">
              <p>{{ stage.name }}</p>


              <!-- RENAME STAGE -->
              <div v-show="idToUpdate == stage.id" class="cf mt3">
                <form @submit.prevent="update(stage.id)">
                  <div class="fl w-100 w-70-ns mb3 mb0-ns">
                    <text-input :id="'name-' + stage.id"
                                :ref="'name' + stage.id"
                                v-model="form.name"
                                :placeholder="$t('account.recruitment_index_new_placeholder')"
                                :custom-ref="'name' + stage.id"
                                :errors="$page.props.errors.name"
                                required
                                :extra-class-upper-div="'mb0'"
                                @esc-key-pressed="idToUpdate = 0"
                    />
                  </div>
                  <div class="fl w-30-ns w-100 tr">
                    <a class="btn dib-l db mb2 mb0-ns" @click.prevent="idToUpdate = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </div>

              <!-- actions -->
              <ul class="list pa0 ma0 f6">
                <li @click.prevent="displayUpdateModal(position) ; form.name = stage.name"> class="di mr2">{{ $t('app.rename') }}</li>
                <li class="di">{{ $t('app.delete') }}</li>
              </ul>
            </div>

            <div v-if="index != localStages.length - 1" class="relative bl bb-gray then f6 gray">
              <span class="mr1">
                Then
              </span> 👇
            </div>
          </div>

          <!-- blank stage -->
          <div v-show="localStages.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              Stages let you define the order in which candidates are interviewed.
            </p>
            <img loading="lazy" class="db center mb4" alt="add a position symbol" srcset="/img/company/account/blank-position-1x.png,
                                          /img/company/account/blank-position-2x.png 2x"
            />
          </div>

          <!-- cta to add a stage -->
          <div class="tc center mb4">
            <a href="#" class="btn ba bb-gray pa3">Add another stage</a>
          </div>

          <!-- modal to add a stage -->
          <form v-show="modal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf">
              <div class="fl w-100 w-70-ns mb3 mb0-ns">
                <text-input :ref="'newStageModal'"
                            v-model="form.title"
                            :placeholder="'Marketing coordinator'"
                            :errors="$page.props.errors.first_name"
                            :extra-class-upper-div="'mb0'"
                            @esc-key-pressed="modal = false"
                />
              </div>
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns" @click.prevent="modal = false">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" data-cy="modal-add-cta" :state="loadingState" :text="$t('app.add')" />
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
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
    TextInput,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    template: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      loadingState: '',
      idToUpdate: 0,
      form: {
        name: null,
        errors: [],
      },
    };
  },

  watch: {
    templates: {
      handler(value) {
        this.localStages = value;
      },
      deep: true
    }
  },

  created() {
    this.localStages = this.template.stages;
  },

  methods: {
    displayAddModal() {
      this.modal = true;

      this.$nextTick(() => {
        this.$refs.newTemplateModal.focus();
      });
    },

    displayUpdateModal(stage) {
      this.idToUpdate = stage.id;

      this.$nextTick(() => {
        this.$refs[`name${stage.id}`].focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/account/recruitment/', this.form)
        .then(response => {
          this.flash(this.$t('account.recruitment_index_new_success'), 'success');

          this.loadingState = null;
          this.form.title = null;
          this.modal = false;
          this.localStages.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
