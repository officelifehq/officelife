<style lang="scss" scoped>
img {
  max-height: 100px;
  max-width: 100px;
}

.files-list:last-child {
  border-bottom: 0;
}

.warning {
  box-shadow: 0 0 0 1px #e3e8ee;
  background-color: #fdf0ec;
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

        <div class="bg-white box">
          <!-- list of files -->
          <ul v-if="localFiles.length > 0" class="list ma0 pa0">
            <li v-for="file in localFiles" :key="file.id" class="files-list di pa3 flex justify-between bb bb-gray">
              <!-- filename -->
              <a :href="file.download_url" :download="file.download_url">{{ file.filename }}</a>
              <ul class="f6 mt2 pa0 list">
                <li class="di mr2">{{ file.size }}</li>

                <!-- DELETE A FILE -->
                <li v-if="idToDelete == file.id" class="di">
                  {{ $t('app.sure') }}
                  <a class="c-delete mr1 pointer" @click.prevent="destroyFile(file.id)">
                    {{ $t('app.yes') }}
                  </a>
                  <a class="pointer" @click.prevent="idToDelete = 0">
                    {{ $t('app.no') }}
                  </a>
                </li>
                <li v-else class="di">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click.prevent="idToDelete = file.id">
                    {{ $t('app.delete') }}
                  </a>
                </li>
              </ul>
            </li>
          </ul>

          <!-- blank state -->
          <div v-else class="tc pa3">
            <img loading="lazy" src="/img/streamline-icon-content-filter@140x140.png" alt="file symbol to upload" height="140"
                 width="140"
            />
            <p class="mb3">
              <span class="db mb4">{{ $t('project.file_blank_state') }}</span>
            </p>
          </div>
        </div>

        <div v-if="showWarningMessage" class="warning pa3 br3 mb4 mt3">
          <p class="ma0 f6">
            <span class="mr1">⚠️</span> Please upload at least one document.
          </p>
        </div>

        <!-- Actions -->
        <div class="mv4">
          <div class="flex-ns justify-between">
            <a href="#" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="destroy()">
              Dismiss your application
            </a>
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
      localFiles: [],
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
      idToDelete: 0,
      showWarningMessage: false,
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
          this.localFiles.unshift(response.data.data);
          this.showWarningMessage = false;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroyFile(id) {
      axios.delete(`jobs/${this.data.company.slug}/jobs/${this.data.job_opening.slug}/apply/${this.candidate.slug}/cv/${id}`)
        .then(response => {
          this.idToDelete = 0;
          id = this.localFiles.findIndex(x => x.id === id);
          this.localFiles.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    submit() {
      if (this.localFiles.length == 0) {
        this.showWarningMessage = true;
      }

      axios.post(`jobs/${this.data.company.slug}/jobs/${this.data.job_opening.slug}/apply/${this.candidate.slug}`, this.form)
        .then(response => {
          this.$inertia.visit(response.data.url);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroy() {
      axios.delete(`jobs/${this.data.company.slug}/jobs/${this.data.job_opening.slug}/apply/${this.candidate.slug}`)
        .then(response => {
          this.$inertia.visit(response.data.url);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    }
  }
};
</script>
