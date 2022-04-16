<style lang="scss" scoped>
.link-icon {
  width: 18px;
  top: 1px;
}

.link {
  max-width: calc(100% - 22px);
}
</style>

<template>
  <div class="ma0 pa3">
    <p class="silver f6 ma0 mb1">{{ $t('project.summary_project_link_title') }}</p>

    <!-- list of links -->
    <ul class="list pl0 mb0">
      <li v-for="link in localLinks" :key="link.id" class="mb2 relative" :data-cy="'project-link-' + link.id">
        <div class="flex">
          <!-- icon types -->
          <img v-if="link.type == 'slack'" loading="lazy" src="/img/slack.svg" class="relative link-icon mr1" :data-cy="'project-link-logo-slack-' + link.id"
               alt="link to slack" :class="editMode ? 'dn' : ''"
          />
          <img v-if="link.type == 'email'" loading="lazy" src="/img/mail.svg" class="relative link-icon mr1" :data-cy="'project-link-logo-email-' + link.id"
               alt="link to email" :class="editMode ? 'dn' : ''"
          />
          <img v-if="link.type == 'url'" loading="lazy" src="/img/url.svg" class="relative link-icon mr1" :data-cy="'project-link-logo-url-' + link.id"
               alt="link to url" :class="editMode ? 'dn' : ''"
          />

          <template v-if="!editMode">
            <!-- case of a url or slack link -->
            <a v-if="link.type == 'url' || link.type == 'slack'" :href="link.url" class="relative ml1 truncate di link">{{ labelOrLink(link) }}</a>

            <!-- case of email -->
            <a v-if="link.type == 'email'" :href="'mailto:' + link.url" class="relative ml1 truncate di link">{{ labelOrLink(link) }}</a>
          </template>
        </div>

        <!-- delete button -->
        <div v-if="editMode">
          <span class="f6">{{ labelOrLink(link) }}</span> <a href="#" :data-cy="'project-link-' + link.id + '-destroy'" class="f6" @click.prevent="removeLink(link)">{{ $t('app.remove') }}</a>
        </div>
      </li>

      <!-- add a new link / edit links -->
      <li v-if="permissions.can_manage_links && addMode == false" class="mt3">
        <a v-if="!editMode" class="bb b--dotted bt-0 bl-0 br-0 pointer f6" data-cy="add-new-link" @click.prevent="addMode = true"><span>+</span> {{ $t('team.useful_link_cta') }}</a>
        <span v-if="!editMode && localLinks.length > 0" class="mr1 ml1 moon-gray">|</span>
        <a v-if="!editMode && localLinks.length > 0" class="bb b--dotted bt-0 bl-0 br-0 pointer f6" data-cy="edit-links" @click.prevent="editMode = true">{{ $t('team.useful_link_edit') }}</a>
        <a v-if="editMode" class="bb b--dotted bt-0 bl-0 br-0 pointer f6" data-cy="exit-edit-link" @click.prevent="editMode = false">{{ $t('team.useful_link_exit_edit_mode') }}</a>
      </li>
    </ul>

    <!-- add a new link form -->
    <div v-if="addMode">
      <form class="mt3" @submit.prevent="submit()">
        <div v-if="form.errors.length > 0">
          <div class="cf pb1 w-100 mb2">
            <errors :errors="form.errors" />
          </div>
        </div>

        <label class="lh-copy f6">
          {{ $t('team.useful_link_type_of_link') }}
        </label>
        <select v-model="form.type" class="mb3" required data-cy="link-type">
          <option value="url">
            {{ $t('team.useful_link_form_url') }}
          </option>
          <option value="email">
            {{ $t('team.useful_link_form_email') }}
          </option>
          <option value="slack">
            {{ $t('team.useful_link_form_slack') }}
          </option>
        </select>

        <text-input :id="'label'"
                    v-model="form.label"
                    :name="'label'"
                    :datacy="'link-label-input'"
                    :errors="$page.props.errors.label"
                    :label="$t('team.useful_link_new_label')"
                    :help="$t('team.useful_link_new_label_help')"
        />

        <text-input :id="'url'"
                    v-model="form.url"
                    :name="'url'"
                    :datacy="'link-url-input'"
                    :errors="$page.props.errors.url"
                    :label="$t('team.useful_link_new_url')"
                    :help="$t('team.useful_link_new_url_help')"
                    :required="true"
        />

        <div class="mb0">
          <div class="flex-ns justify-between">
            <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addMode = false">
              {{ $t('app.cancel') }}
            </a>
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'link-submit-button'" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';
import Errors from '@/Shared/Errors';

export default {
  components: {
    TextInput,
    LoadingButton,
    Errors,
  },

  props: {
    project: {
      type: Object,
      default: null,
    },
    permissions: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      addMode: false,
      editMode: false,
      form: {
        url: null,
        label: null,
        type: null,
        errors: [],
      },
      loadingState: '',
      localLinks: [],
    };
  },

  created: function() {
    this.localLinks = this.project.links;
  },

  methods: {
    labelOrLink(link) {
      if (link.label) {
        return link.label;
      }

      return link.url;
    },

    showEditMode() {
      this.editMode = true;
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/links`, this.form)
        .then(response => {
          this.localLinks.push(response.data.data);

          this.addMode = false;
          this.loadingState = null;
          this.form.url = null;
          this.form.type = null;
          this.form.label = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    removeLink(link) {
      axios.delete(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/links/${link.id}`)
        .then(response => {
          this.localLinks.splice(this.localLinks.findIndex(i => i.id == link.id), 1);
          this.editMode = false;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
