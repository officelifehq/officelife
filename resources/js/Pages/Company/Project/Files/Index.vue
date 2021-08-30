<style lang="scss" scoped>
.files-list:last-child {
  border-bottom: 0;
}

.edit-cta {
  top: -2px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb :has-more="false"
                  :previous-url="route('projects.index', { company: $page.props.auth.company.id})"
                  :previous="$t('app.breadcrumb_project_list')"
      >
        {{ $t('app.breadcrumb_project_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <!-- Menu -->
        <project-menu :project="project" :tab="tab" />
      </div>

      <div class="mw7 center br3 mb5 relative z-1">
        <p class="db fw5 mb2 flex justify-between items-center">
          <span>
            <span class="mr1">🗄</span> {{ $t('project.file_title') }}

            <help :url="$page.props.help_links.project_files" :top="'3px'" />
          </span>

          <uploadcare :public-key="uploadcarePublicKey"
                      :tabs="'file'"
                      :preview-step="false"
                      @success="onSuccess"
                      @error="onError"
          >
            <button class="btn">{{ $t('app.upload') }}</button>
          </uploadcare>
        </p>

        <div class="bg-white box">
          <!-- list of files -->
          <ul v-if="localFiles.length > 0" class="list ma0 pa0">
            <li v-for="file in localFiles" :key="file.id" class="files-list di pa3 flex justify-between bb bb-gray">
              <!-- filename -->
              <span><a :href="file.download_url" :download="file.download_url">{{ file.filename }}</a>
                <ul class="f6 mt2 pa0 list">
                  <li class="di mr2">{{ file.size }}</li>

                  <!-- DELETE A FILE -->
                  <li v-if="idToDelete == file.id" class="di">
                    {{ $t('app.sure') }}
                    <a class="c-delete mr1 pointer" @click.prevent="destroy(file.id)">
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
              </span>

              <div class="">
                <!-- date -->
                <span class="db mb2 f6 gray">{{ file.created_at }}</span>

                <!-- uploader info -->
                <span>
                  <small-name-and-avatar
                    v-if="file.uploader.name"
                    :name="file.uploader.name"
                    :avatar="file.uploader.avatar"
                    :class="'gray'"
                    :size="'18px'"
                    :top="'0px'"
                    :margin-between-name-avatar="'25px'"
                  />
                  <span v-else>{{ file.uploader }}</span>
                </span>
              </div>
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
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import ProjectMenu from '@/Pages/Company/Project/Partials/ProjectMenu';
import Help from '@/Shared/Help';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import Uploadcare from 'uploadcare-vue/src/Uploadcare.vue';

export default {
  components: {
    Layout,
    Breadcrumb,
    ProjectMenu,
    Help,
    SmallNameAndAvatar,
    Uploadcare,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    project: {
      type: Object,
      default: null,
    },
    tab: {
      type: String,
      default: 'summary',
    },
    uploadcarePublicKey: {
      type: String,
      default: null,
    },
    files: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localFiles: null,
      idToDelete: 0,
      form: {
        uuid: null,
        name: null,
        original_url: null,
        cdn_url: null,
        mime_type: null,
        size: null,
        errors: [],
      },
    };
  },

  created() {
    this.localFiles = this.files;

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
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
      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/files`, this.form)
        .then(response => {
          this.localFiles.unshift(response.data.data);
          this.flash(this.$t('project.file_upload_success'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroy(id) {
      axios.delete(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/files/${id}`)
        .then(response => {
          this.flash(this.$t('project.file_deletion_success'), 'success');

          this.idToDelete = 0;
          id = this.localFiles.findIndex(x => x.id === id);
          this.localFiles.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
