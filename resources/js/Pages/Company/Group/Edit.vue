<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/groups/' + group.id"
                  :previous="group.name"
      >
        {{ $t('app.breadcrumb_group_edit') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('group.edit_title') }}
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Name -->
            <text-input :id="'name'"
                        v-model="form.name"
                        :name="'name'"
                        :errors="$page.props.errors.title"
                        :label="$t('group.create_input_name')"
                        :help="$t('group.create_input_name_help')"
                        :required="true"
            />

            <!-- Mission -->
            <text-area v-model="form.mission"
                       :label="$t('group.create_input_mission')"
                       :required="false"
                       :rows="10"
                       :help="$t('group.create_input_summary_help')"
            />

            <!-- Actions -->
            <div class="mb4 mt3">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/company/groups/' + group.id" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.update')" />
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    TextArea,
    Errors,
    LoadingButton,
  },

  props: {
    group: {
      type: Object,
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
        name: null,
        mission: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  created() {
    this.form.name = this.group.name;
    this.form.mission = this.group.mission;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.group.id}/update`, this.form)
        .then(response => {
          localStorage.success = this.$t('group.edit_success');
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
