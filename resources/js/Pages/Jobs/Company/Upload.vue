<style lang="scss" scoped>
img {
  max-height: 100px;
  max-width: 100px;
}
</style>

<template>
  <div class="ph2 ph5-ns">
    <div class="mw7 center br3 mb5 bg-white box relative z-1">
      <div class="flex">
        <img :src="data.company.logo" :alt="data.company.name" />

        <div>
          <h3>{{ data.company.name }}</h3>
          <ul class="list pl0 ma0">
            <li class="di mr2">{{ data.company.location }}</li>
          </ul>
        </div>
      </div>

      <h3>{{ data.job_opening.title }} <span>{{ data.job_opening.reference_number }}</span></h3>

      <p>Step 1 - Candidate information</p>
      <p>Step 2 - Upload your CV</p>

      <form @submit.prevent="submit">
        <errors :errors="form.errors" />

        <p>To complete your application, please upload your CV and/or any relevant document.</p>

        <uploadcare :public-key="uploadcarePublicKey"
                    :tabs="'file'"
                    :preview-step="false"
                    @success="onSuccess"
                    @error="onError"
        >
          <button class="btn">
            {{ $t('app.upload') }}
          </button>
        </uploadcare>

        <!-- Actions -->
        <div class="mv4">
          <div class="flex-ns justify-between">
            <div>
              <inertia-link :href="data.url_dismiss" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                Dismiss your application
              </inertia-link>
            </div>
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="'Submit your application'" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Uploadcare from 'uploadcare-vue/src/Uploadcare.vue';

export default {
  components: {
    Errors,
    LoadingButton,
    Uploadcare,
  },

  props: {
    data: {
      type: Object,
      default: null,
    },
    candidate: {
      type: Object,
      default: null,
    },
    uploadcarePublicKey: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      form: {
        uuid: null,
        name: null,
        original_url: null,
        cdn_url: null,
        mime_type: null,
        size: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
    onSuccess(file) {
      this.form.uuid = file.uuid;
      this.form.name = file.name;
      this.form.original_url = file.originalUrl;
      this.form.cdn_url = file.cdnUrl;
      this.form.mime_type = file.mimeType;
      this.form.size = file.size;

      this.uploadFile();
    },

    onError() {
    },

    uploadFile() {
      axios.post(`jobs/${this.data.company.slug}/jobs/${this.data.job_opening.slug}/apply/${this.candidate.slug}/cv`, this.form)
        .then(response => {

        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
