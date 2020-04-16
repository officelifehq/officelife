<style scoped>
.list li:last-child {
  border-bottom: 0;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_positions') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.positions_title', { company: $page.auth.company.name}) }}
          </h2>

          <p class="relative adminland-headline">
            <span class="dib mb3 di-l" :class="positions.length == 0 ? 'white' : ''">
              {{ $tc('account.positions_number_positions', positions.length, { company: $page.auth.company.name, count: positions.length}) }}
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
                            :errors="$page.errors.first_name"
                            :extra-class-upper-div="'mb0'"
                            @esc-key-pressed="modal = false"
                />
              </div>
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns" @click.prevent="modal = false">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :classes="'btn add w-auto-ns w-100 pv2 ph3'" data-cy="modal-add-cta" :state="loadingState" :text="$t('app.add')" />
              </div>
            </div>
          </form>

          <!-- LIST OF EXISTING POSITIONS -->
          <ul v-show="positions.length != 0" class="list pl0 mv0 center ba br2 bb-gray" data-cy="positions-list">
            <li v-for="(position) in positions" :key="position.id" class="pv3 ph2 bb bb-gray bb-gray-hover">
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
                                :errors="$page.errors.first_name"
                                required
                                :extra-class-upper-div="'mb0'"
                                @esc-key-pressed="idToUpdate = 0"
                    />
                  </div>
                  <div class="fl w-30-ns w-100 tr">
                    <a class="btn dib-l db mb2 mb0-ns" :data-cy="'list-rename-cancel-button-' + position.id" @click.prevent="idToUpdate = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-rename-cta-button-' + position.id" :state="loadingState" :text="$t('app.update')" />
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
          <div v-show="positions.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.positions_blank') }}
            </p>
            <img class="db center mb4" alt="add a position symbol" srcset="/img/company/account/blank-position-1x.png,
                                          /img/company/account/blank-position-2x.png 2x" loading="lazy"
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

export default {
  components: {
    Layout,
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

  methods: {
    displayAddModal() {
      this.modal = true;

      this.$nextTick(() => {
        this.$refs['newPositionModal'].$refs['input'].focus();
      });
    },

    displayUpdateModal(position) {
      this.idToUpdate = position.id;

      this.$nextTick(() => {
        // this is really barbaric, but I need to do this to
        // first: target the TextInput with the right ref attribute
        // second: target within the component, the refs of the input text
        // this is because we try here to access $refs from a child component
        this.$refs[`title${position.id}`][0].$refs[`title${position.id}`].focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/account/positions', this.form)
        .then(response => {
          flash(this.$t('account.position_success_new'), 'success');

          this.loadingState = null;
          this.form.title = null;
          this.modal = false;
          this.positions.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    update(id) {
      axios.put('/' + this.$page.auth.company.id + '/account/positions/' + id, this.form)
        .then(response => {
          flash(this.$t('account.position_success_update'), 'success');

          this.idToUpdate = 0;
          this.form.title = null;

          id = this.positions.findIndex(x => x.id === id);
          this.$set(this.positions, id, response.data.data);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    destroy(id) {
      axios.delete('/' + this.$page.auth.company.id + '/account/positions/' + id)
        .then(response => {
          flash(this.$t('account.position_success_destroy'), 'success');

          this.idToDelete = 0;
          id = this.positions.findIndex(x => x.id === id);
          this.positions.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
