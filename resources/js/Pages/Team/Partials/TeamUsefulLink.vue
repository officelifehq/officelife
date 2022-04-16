<style lang="scss" scoped>
.useful-link {
  top: 4px;
  width: 16px;
}
</style>

<template>
  <div>
    <div class="ma0 pa3">
      <p class="silver f6 ma0 mb1">{{ $t('team.useful_link_title') }}</p>

      <!-- list of links -->
      <ul class="list pl0 mb0">
        <li v-for="link in updatedLinks" :key="link.id" class="mb2 relative" :data-cy="'team-useful-link-' + link.id">
          <!-- icon types -->
          <img v-if="link.type == 'slack'" loading="lazy" src="/img/slack.svg" class="relative useful-link mr1" :data-cy="'team-useful-link-logo-slack-' + link.id"
               alt="link to slack"
          />
          <img v-if="link.type == 'email'" loading="lazy" src="/img/mail.svg" class="relative useful-link mr1" :data-cy="'team-useful-link-logo-email-' + link.id"
               alt="link to email"
          />
          <img v-if="link.type == 'url'" loading="lazy" src="/img/url.svg" class="relative useful-link mr1" :data-cy="'team-useful-link-logo-url-' + link.id"
               alt="link to url"
          />

          <template v-if="!editMode">
            <!-- case of a url or slack link -->
            <a v-if="link.type == 'url' || link.type == 'slack'" :href="link.url" class="relative ml1">{{ labelOrLink(link) }}</a>

            <!-- case of email -->
            <a v-if="link.type == 'email'" :href="'mailto:' + link.url" class="relative ml1">{{ labelOrLink(link) }}</a>
          </template>

          <!-- delete button -->
          <template v-if="editMode">
            <span class="f6">{{ labelOrLink(link) }}</span> <a href="#" :data-cy="'team-useful-link-' + link.id + '-destroy'" class="f6" @click.prevent="removeLink(link)">{{ $t('app.remove') }}</a>
          </template>
        </li>

        <!-- add a new link / edit links -->
        <li v-if="addMode == false && teamMemberOrAtLeastHR" class="mt3">
          <a v-if="!editMode" href="" class="bb b--dotted bt-0 bl-0 br-0 pointer f6" data-cy="useful-link-add-new-link" @click.prevent="addMode = true"><span>+</span> {{ $t('team.useful_link_cta') }}</a>
          <span v-if="!editMode && updatedLinks.length > 0" class="moon-gray mr1 ml1">|</span>
          <a v-if="!editMode && updatedLinks.length > 0" href="" class="bb b--dotted bt-0 bl-0 br-0 pointer f6" data-cy="useful-link-edit-links" @click.prevent="editMode = true">{{ $t('team.useful_link_edit') }}</a>
          <a v-if="editMode" href="" class="bb b--dotted bt-0 bl-0 br-0 pointer f6" data-cy="useful-link-exit-edit-link" @click.prevent="editMode = false">{{ $t('team.useful_link_exit_edit_mode') }}</a>
        </li>
      </ul>

      <!-- add a new link form -->
      <template v-if="addMode">
        <form class="mt3" @submit.prevent="submit()">
          <template v-if="form.errors.length > 0">
            <div class="cf pb1 w-100 mb2">
              <errors :errors="form.errors" />
            </div>
          </template>

          <label class="lh-copy f6">
            {{ $t('team.useful_link_type_of_link') }}
          </label>
          <select v-model="form.type" class="mb3" required data-cy="useful-link-type">
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
              <div>
                <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addMode = false">
                  {{ $t('app.cancel') }}
                </a>
              </div>
              <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'useful-link-submit-button'" />
            </div>
          </div>
        </form>
      </template>
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
    team: {
      type: Object,
      default: null,
    },
    links: {
      type: Array,
      default: null,
    },
    userBelongsToTheTeam: {
      type: Boolean,
      default: false,
    }
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
      updatedLinks: [],
    };
  },

  computed: {
    teamMemberOrAtLeastHR() {
      if (this.$page.props.auth.employee.permission_level <= 200) {
        return true;
      }

      if (this.userBelongsToTheTeam == false) {
        return false;
      }

      return true;
    },
  },

  created: function() {
    this.updatedLinks = this.links;
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

      axios.post('/' + this.$page.props.auth.company.id + '/teams/' + this.team.id + '/links', this.form)
        .then(response => {
          this.updatedLinks.push(response.data.data);
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
      axios.delete('/' + this.$page.props.auth.company.id + '/teams/' + this.team.id + '/links/' + link.id)
        .then(response => {
          this.flash(this.$t('team.team_lead_removed'), 'success');

          this.updatedLinks.splice(this.updatedLinks.findIndex(i => i.id === response.data.data), 1);
          this.editMode = false;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
