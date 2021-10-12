<style scoped>
.list li:last-child {
  border-bottom: 0;
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
        {{ $t('app.breadcrumb_account_manage_positions') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.positions_title', { company: $page.props.auth.company.name}) }}
          </h2>

          <p class="relative adminland-headline">
            <span class="dib mb3 di-l" :class="localPositions.length == 0 ? 'white' : ''">
              {{ $tc('account.positions_number_positions', localPositions.length, { company: $page.props.auth.company.name, count: localPositions.length}) }}
            </span>
            <a class="btn absolute-l relative dib-l db right-0" data-cy="add-position-button" @click.prevent="displayAddModal">
              {{ $t('account.positions_cta') }}
            </a>
          </p>

          <!-- MODAL TO ADD A POSITION -->
          <form v-show="modal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf">
              <div class="fl w-100 w-70-ns mb3 mb0-ns">
                <text-input :ref="'newPositionModal'"
                            v-model="form.title"
                            :placeholder="'Marketing coordinator'"
                            :datacy="'add-title-input'"
                            :errors="$page.props.errors.first_name"
                            :extra-class-upper-div="'mb0'"
                            @esc-key-pressed="modal = false"
                />
              </div>
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns mr2-ns" @click.prevent="modal = false">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" data-cy="modal-add-cta" :state="loadingState" :text="$t('app.add')" />
              </div>
            </div>
          </form>

          <!-- LIST OF EXISTING POSITIONS -->
          <ul v-show="localPositions.length != 0" class="list pl0 mv0 center ba br2 bb-gray" data-cy="positions-list">
            <li v-for="(position) in localPositions" :key="position.id" class="pv3 ph2 bb bb-gray bb-gray-hover">
              {{ position.title }}

              <!-- RENAME POSITION FORM -->
              <div v-show="idToUpdate == position.id" class="cf mt3">
                <form @submit.prevent="update(position.id)">
                  <div class="fl w-100 w-70-ns mb3 mb0-ns">
                    <text-input :id="'title-' + position.id"
                                :ref="'title' + position.id"
                                v-model="form.title"
                                :placeholder="'Marketing coordinator'"
                                :custom-ref="'title' + position.id"
                                :datacy="'list-rename-input-name-' + position.id"
                                :errors="$page.props.errors.first_name"
                                required
                                :extra-class-upper-div="'mb0'"
                                @esc-key-pressed="idToUpdate = 0"
                    />
                  </div>
                  <div class="fl w-30-ns w-100 tr">
                    <a class="btn dib-l db mb2 mb0-ns mr2-ns" :data-cy="'list-rename-cancel-button-' + position.id" @click.prevent="idToUpdate = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-rename-cta-button-' + position.id" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </div>

              <!-- LIST OF ACTIONS FOR EACH POSITION -->
              <ul v-show="idToUpdate != position.id" class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns f6">
                <!-- RENAME A POSITION -->
                <li class="di mr2">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'list-rename-button-' + position.id" @click.prevent="displayUpdateModal(position) ; form.title = position.title">{{ $t('app.rename') }}</a>
                </li>

                <!-- DELETE A POSITION -->
                <li v-if="idToDelete == position.id" class="di">
                  {{ $t('app.sure') }}
                  <a class="c-delete mr1 pointer" :data-cy="'list-delete-confirm-button-' + position.id" @click.prevent="destroy(position.id)">
                    {{ $t('app.yes') }}
                  </a>
                  <a class="pointer" :data-cy="'list-delete-cancel-button-' + position.id" @click.prevent="idToDelete = 0">
                    {{ $t('app.no') }}
                  </a>
                </li>
                <li v-else class="di">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'list-delete-button-' + position.id" @click.prevent="idToDelete = position.id">
                    {{ $t('app.delete') }}
                  </a>
                </li>
              </ul>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-show="localPositions.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.positions_blank') }}
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

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    positions: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localPositions: [],
      modal: false,
      deleteModal: false,
      updateModal: false,
      loadingState: '',
      updateModalId: 0,
      idToUpdate: 0,
      idToDelete: 0,
      form: {
        title: null,
        errors: [],
      },
    };
  },

  watch: {
    positions: {
      handler(value) {
        this.localPositions = value;
      },
      deep: true
    }
  },

  mounted() {
    this.localPositions = this.positions;
  },

  methods: {
    displayAddModal() {
      this.modal = true;

      this.$nextTick(() => {
        this.$refs.newPositionModal.focus();
      });
    },

    displayUpdateModal(position) {
      this.idToUpdate = position.id;

      this.$nextTick(() => {
        this.$refs[`title${position.id}`].focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('positions.store', this.$page.props.auth.company.id), this.form)
        .then(response => {
          this.flash(this.$t('account.position_success_new'), 'success');

          this.loadingState = null;
          this.form.title = null;
          this.modal = false;
          this.localPositions.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(id) {
      axios.put(this.route('positions.update', [this.$page.props.auth.company.id, id]), this.form)
        .then(response => {
          this.flash(this.$t('account.position_success_update'), 'success');

          this.idToUpdate = 0;
          this.form.title = null;

          this.localPositions[this.localPositions.findIndex(x => x.id === id)] = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(id) {
      axios.delete(this.route('positions.destroy', [this.$page.props.auth.company.id, id]))
        .then(response => {
          this.flash(this.$t('account.position_success_destroy'), 'success');

          this.idToDelete = 0;
          id = this.localPositions.findIndex(x => x.id === id);
          this.localPositions.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
