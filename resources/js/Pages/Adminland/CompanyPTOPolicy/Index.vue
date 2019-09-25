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
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $page.auth.company.name }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_pto_policies') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.pto_policies_edit_title', { company: $page.auth.company.name}) }}
          </h2>

          <p class="lh-copy">
            {{ $t('account.pto_policies_edit_title_1') }}
          </p>
          <p class="lh-copy">
            {{ $t('account.pto_policies_edit_title_2') }}
          </p>
          <p class="lh-copy">
            {{ $t('account.pto_policies_edit_title_3') }}
          </p>
          <p class="lh-copy">
            {{ $t('account.pto_policies_edit_title_4') }}
          </p>

          <!-- LIST OF EXISTING PTO POLICIES -->
          <ul class="list pl0 mv0 center ba br2 bb-gray" data-cy="pto-policies-list">
            <li v-for="ptoPolicy in ptoPolicies" :key="ptoPolicy.id" class="pv3 ph2 bb bb-gray bb-gray-hover">
              <span class="db mb3">
                {{ $t('account.pto_policies_edit_year', { year: ptoPolicy.year}) }}
              </span>
              {{ $t('account.pto_policies_number_days', { number: ptoPolicy.total_worked_days }) }}

              <!-- RENAME PTO POLICY FORM -->
              <div v-show="idToUpdate == ptoPolicy.id" class="cf mt3">
                <form @submit.prevent="update(ptoPolicy.id)">
                  <div class="fl w-100 w-70-ns mb3 mb0-ns">
                    <text-input :id="'year-' + ptoPolicy.id"
                                :ref="'year' + ptoPolicy.id"
                                v-model="form.total_worked_days"
                                :type="'number'"
                                :min="1"
                                :max="300"
                                :placeholder="'Marketing coordinator'"
                                :custom-ref="'year' + ptoPolicy.id"
                                :datacy="'list-edit-input-name-' + ptoPolicy.id"
                                :errors="$page.errors.total_worked_days"
                                :required="true"
                                :extra-class-upper-div="'mb0'"
                                @esc-key-pressed="idToUpdate = 0"
                    />
                  </div>
                  <div class="fl w-30-ns w-100 tr">
                    <a class="btn dib-l db mb2 mb0-ns" :data-cy="'list-edit-cancel-button-' + ptoPolicy.id" @click.prevent="idToUpdate = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-edit-cta-button-' + ptoPolicy.id" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </div>

              <!-- LIST OF ACTIONS FOR EACH PTO POLICY -->
              <ul v-show="idToUpdate != ptoPolicy.id" class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns">
                <!-- EDIT A PTO POLICY -->
                <li class="di mr2">
                  <a class="pointer" :data-cy="'list-edit-button-' + ptoPolicy.id" @click.prevent="displayUpdateModal(ptoPolicy) ; form.total_worked_days = ptoPolicy.total_worked_days">
                    {{ $t('app.edit') }}
                  </a>
                </li>
              </ul>
            </li>
          </ul>
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
    ptoPolicies: {
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
        total_worked_days: null,
        errors: [],
      },
    };
  },

  methods: {
    displayUpdateModal(ptoPolicy) {
      this.idToUpdate = ptoPolicy.id;

      this.$nextTick(() => {
        // this is really barbaric, but I need to do this to
        // first: target the TextInput with the right ref attribute
        // second: target within the component, the refs of the input text
        // this is because we try here to access $refs from a child component
        this.$refs[`year${ptoPolicy.id}`][0].$refs[`year${ptoPolicy.id}`].focus();
      });
    },

    update(id) {
      axios.put('/' + this.$page.auth.company.id + '/account/ptopolicies/' + id, this.form)
        .then(response => {
          this.$snotify.success(this.$t('account.pto_policies_update'), {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          });

          this.idToUpdate = 0;
          this.form.year = null;

          id = this.ptoPolicies.findIndex(x => x.id === id);
          this.$set(this.ptoPolicies, id, response.data.data);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
