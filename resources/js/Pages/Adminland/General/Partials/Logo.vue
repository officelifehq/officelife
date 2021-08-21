<style lang="scss" scoped>
.title {
  min-width: 240px;
}

.admin:not(:last-child) {
  margin-bottom: 0;
}
</style>

<template>
  <div>
    <h3 class="ph3 fw5">
      {{ $t('account.general_logo_title') }}

      <help :url="$page.props.help_links.account_general_logo" :datacy="'help-icon-general'" :top="'2px'" />
    </h3>

    <ul class="list ph3">
      <li v-if="localLogo" class="mb3 flex-ns items-start">
        <span class="dib-ns db mb0-ns mb2 title">{{ $t('account.general_logo_current') }}</span>
        <img :src="localLogo" alt="logo" class="ba bb-gray pa2 br3 bg-white mr3-ns" />
      </li>

      <uploadcare :public-key="information.uploadcare_public_key"
                  :tabs="'file'"
                  @success="onSuccess"
                  @error="onError"
      >
        <button class="btn">
          {{ $t('account.general_logo_cta') }}
        </button>
      </uploadcare>
    </ul>
  </div>
</template>

<script>
import Help from '@/Shared/Help';
import Uploadcare from 'uploadcare-vue/src/Uploadcare.vue';

export default {
  components: {
    Help,
    Uploadcare,
  },

  props: {
    information: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      localLogo: null,
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

  mounted() {
    this.localLogo = this.information.logo;
  },

  methods: {
    onSuccess(file) {
      this.form.uuid = file.uuid;
      this.form.name = file.name;
      this.form.original_url = file.originalUrl;
      this.form.cdn_url = file.cdnUrl;
      this.form.mime_type = file.mimeType;
      this.form.size = file.size;

      this.uploadLogo();
    },

    onError() {
    },

    uploadLogo() {
      axios.post(`/${this.$page.props.auth.company.id}/account/general/logo`, this.form)
        .then(response => {
          this.localLogo = response.data.data;
          this.flash(this.$t('account.general_logo_success'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
