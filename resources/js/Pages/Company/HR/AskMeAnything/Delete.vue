<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :previous-url="data.url.back"
                  :previous="$t('app.breadcrumb_ama_show')"
      >
        {{ $t('app.breadcrumb_ama_delete') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <h2 class="pa3 tc normal mb0">
          {{ $t('company.hr_ama_delete_title') }}
        </h2>

        <form @submit.prevent="submit">
          <errors :errors="form.errors" />

          <div class="cf pa3 bb-gray bb">
            <p class="lh-copy mv0">{{ $t('company.hr_ama_delete_description') }}</p>
          </div>

          <!-- Actions -->
          <div class="cf pa3">
            <div class="flex-ns justify-between">
              <div>
                <inertia-link :href="data.url.back" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2">
                  {{ $t('app.cancel') }}
                </inertia-link>
              </div>
              <loading-button :class="'btn destroy w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.delete')" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    Breadcrumb,
    Errors,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    data: {
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

      axios.delete(this.data.url.destroy)
        .then(response => {
          localStorage.success = this.$t('company.hr_ama_delete_success');
          this.$inertia.visit(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
