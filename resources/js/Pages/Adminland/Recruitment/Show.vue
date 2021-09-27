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
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/recruitment'"
                  :previous="$t('app.breadcrumb_account_manage_recruitment')"
      >
        {{ $t('app.breadcrumb_account_manage_recruitment_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5 relative">
          <h2 class="tc normal mb4">
            {{ template.name }}
          </h2>

          <!-- list of stages -->
          <div v-if="localStages.length > 0" class="mb4">
            <div v-for="stage in localStages" :key="stage.id">
              <div v-show="idToUpdate != stage.id" class="flex justify-between items-center ba bb-gray bb-gray-hover br3 ph3 pv2">
                <!-- name of the stage -->
                <p>{{ stage.name }}</p>

                <!-- actions -->
                <ul class="list pa0 ma0 f6">
                  <!-- rename -->
                  <li class="bb b--dotted bt-0 bl-0 br-0 pointer di mr2" @click.prevent="displayUpdateModal(stage) ; form.name = stage.name">{{ $t('app.rename') }}</li>

                  <!-- delete a stage -->
                  <li v-if="idToDelete == stage.id" class="di">
                    {{ $t('app.sure') }}
                    <a class="c-delete mr1 pointer" @click.prevent="destroy(stage.id)">
                      {{ $t('app.yes') }}
                    </a>
                    <a class="pointer" @click.prevent="idToDelete = 0">
                      {{ $t('app.no') }}
                    </a>
                  </li>
                  <li v-else class="di">
                    <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click.prevent="idToDelete = stage.id">
                      {{ $t('app.delete') }}
                    </a>
                  </li>
                </ul>
              </div>

              <!-- rename stage modal -->
              <div v-show="idToUpdate == stage.id" class="ba bb-gray bb-gray-hover br3 pa3">
                <form class="cf w-100" @submit.prevent="update(stage)">
                  <div class="mb3 mb0-ns fl w-70">
                    <text-input :id="'name-' + stage.id"
                                :ref="'name' + stage.id"
                                v-model="form.name"
                                :placeholder="$t('account.recruitment_index_new_placeholder')"
                                :custom-ref="'name' + stage.id"
                                :errors="$page.props.errors.name"
                                required
                                :extra-class-upper-div="'mb0'"
                                :maxlength="191"
                                @esc-key-pressed="idToUpdate = 0"
                    />
                  </div>
                  <div class="tr fl w-30">
                    <a class="btn dib-l db mb2 mb0-ns mr2" @click.prevent="idToUpdate = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </div>

              <div v-if="stage.position != localStages.length" class="relative bl bb-gray then f6 gray">
                <span class="mr1">
                  {{ $t('account.recruitment_show_then') }}
                </span> 👇
              </div>
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
          <div v-if="!showAddModal" class="tc center mb4">
            <a class="btn ba bb-gray pa3" @click="displayAddModal()">{{ $t('account.recruitment_show_add_stage_cta') }}</a>
          </div>

          <!-- modal to add a stage -->
          <form v-if="showAddModal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf">
              <div class="fl w-100 w-70-ns mb3 mb0-ns">
                <text-input :ref="'newStageModal'"
                            v-model="form.name"
                            :placeholder="$t('account.recruitment_index_new_placeholder')"
                            :errors="$page.props.errors.name"
                            :extra-class-upper-div="'mb0'"
                            :maxlength="191"
                            @esc-key-pressed="showAddModal = false"
                />
              </div>
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns mr2" @click.prevent="showAddModal = false">
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    template: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      showAddModal: false,
      loadingState: '',
      idToUpdate: 0,
      idToDelete: 0,
      form: {
        name: null,
        position: null,
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

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  created() {
    this.localStages = this.template.stages;
  },

  methods: {
    displayAddModal() {
      this.showAddModal = true;

      this.$nextTick(() => {
        this.$refs.newStageModal.focus();
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

      axios.post('/' + this.$page.props.auth.company.id + '/account/recruitment/' + this.template.id, this.form)
        .then(response => {
          this.flash(this.$t('account.recruitment_show_new_success'), 'success');

          this.loadingState = null;
          this.form.name = null;
          this.showAddModal = false;
          this.localStages.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(stage) {
      this.loadingState = 'loading';
      this.form.position = stage.position;

      axios.put('/' + this.$page.props.auth.company.id + '/account/recruitment/' + this.template.id + '/stage/' + stage.id, this.form)
        .then(response => {
          this.flash(this.$t('account.recruitment_show_update_success'), 'success');

          this.localStages[this.localStages.findIndex(x => x.id === stage.id)] = response.data.data;

          this.loadingState = null;
          this.form.name = null;
          this.form.position = null;
          this.idToUpdate = 0;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroy(stageId) {
      axios.delete('/' + this.$page.props.auth.company.id + '/account/recruitment/' + this.template.id + '/stage/' + stageId)
        .then(response => {
          this.idToDelete = 0;
          this.localStages.splice(this.localStages.findIndex(i => i.id === stageId), 1);

          this.flash(this.$t('account.recruitment_show_delete_success'), 'success');
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
