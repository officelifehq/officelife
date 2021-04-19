<template>
  <div>
    <div v-show="updatedTeam.parsed_description && !editMode" class="bb bb-gray">
      <div class="lh-copy ma0 pl3 pt3 pr3 parsed-content" :class="{ mb3: !teamMemberOrAtLeastHR }" v-html="updatedTeam.parsed_description">
      </div>

      <p v-if="teamMemberOrAtLeastHR" class="pl3 pb3 pr3 f6 mb0">
        <a class="bb b--dotted bt-0 bl-0 br-0 pointer" data-cy="team-description-edit" @click.prevent="showEditMode()">{{ $t('app.edit') }}</a>
      </p>
    </div>

    <!-- team description blank -->
    <div v-show="!updatedTeam.parsed_description && !editMode" class="lh-copy ma0 pa3 bb bb-gray">
      <a v-if="teamMemberOrAtLeastHR" class="bb b--dotted bt-0 bl-0 br-0 pointer" data-cy="add-description-blank-state" @click.prevent="showEditMode()">{{ $t('team.description_cta') }}</a>

      <span v-if="!teamMemberOrAtLeastHR" class="f6">
        {{ $t('team.description_blank') }}
      </span>
    </div>

    <!-- form to add the description -->
    <div v-show="editMode" class="pa3 bb bb-gray">
      <form @submit.prevent="submit">
        <template v-if="form.errors.length > 0">
          <div class="cf pb1 w-100 mb2">
            <errors :errors="form.errors" />
          </div>
        </template>

        <text-area ref="description"
                   v-model="form.description"
                   :label="$t('team.description_title')"
                   :datacy="'team-description-textarea'"
                   :required="true"
                   :rows="10"
                   :help="$t('team.description_help')"
        />

        <div class="mb0">
          <div class="flex-ns justify-between">
            <div>
              <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="editMode = false">
                {{ $t('app.cancel') }}
              </a>
            </div>
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.publish')" :cypress-selector="'team-description-submit-description-button'" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import TextArea from '@/Shared/TextArea';
import LoadingButton from '@/Shared/LoadingButton';
import Errors from '@/Shared/Errors';

export default {
  components: {
    TextArea,
    LoadingButton,
    Errors,
  },

  props: {
    team: {
      type: Object,
      default: null,
    },
    userBelongsToTheTeam: {
      type: Boolean,
      default: false,
    }
  },

  data() {
    return {
      editMode: false,
      form: {
        description: null,
        errors: [],
      },
      loadingState: '',
      updatedTeam: null,
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
    this.updatedTeam = this.team;
    this.form.description = this.team.raw_description;
  },

  methods: {
    showEditMode() {
      this.editMode = true;

      this.$nextTick(() => {
        this.$refs.description.focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/teams/${this.team.id}/description`, this.form)
        .then(response => {
          this.flash(this.$t('team.description_success'), 'success');

          this.updatedTeam = response.data.data;
          this.form.description = this.updatedTeam.raw_description;
          this.editMode = false;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
