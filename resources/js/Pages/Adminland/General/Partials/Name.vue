<style lang="scss" scoped>
.title {
  min-width: 240px;
}

.admin:not(:last-child) {
  margin-bottom: 0;
}
</style>

<template>
  <div class="bb bb-gray">
    <h3 class="ph3 fw5">
      {{ $t('account.general_information') }}

      <help :url="$page.props.help_links.account_general_company_name" :datacy="'help-icon-general'" :top="'2px'" />
    </h3>

    <!-- information about the company -->
    <ul v-if="!editMode" class="list ph3">
      <li class="mb3 flex-ns items-start">
        <span class="dib-ns db title mb0-ns mb2">{{ $t('account.general_name') }}</span>
        <span class="fw6" data-cy="company-name">{{ updatedName }}</span>
      </li>
      <li class="mb3 flex-ns items-start">
        <span class="dib-ns db mb0-ns mb2 title">{{ $t('account.general_administrators') }}</span>
        <ul class="dib list pl0">
          <li v-for="admin in information.administrators" :key="admin.id" class="db pb2 admin">
            <small-name-and-avatar
              :name="admin.name"
              :avatar="admin.avatar"
              :classes="'f4 fw4'"
              :top="'0px'"
              :margin-between-name-avatar="'29px'"
            />
          </li>
        </ul>
      </li>
      <li class="mb4">
        <span class="dib-ns db mb0-ns mb2 title">{{ $t('account.general_creation_date') }}</span>
        <span class="fw6">{{ information.creation_date }}</span>
      </li>
      <li class="mb4">
        <span class="dib-ns db mb0-ns mb2 title">{{ $t('account.general_creation_size') }}</span>
        <span class="fw6">{{ $t('account.general_creation_size_kb', { size: information.total_size }) }}</span>
      </li>
    </ul>

    <div v-if="!editMode" class="ph3 mb3">
      <a data-cy="rename-company-button" class="btn tc relative dib" @click.prevent="displayEditMode()">
        {{ $t('account.general_rename_company') }}
      </a>
    </div>

    <!-- rename company form -->
    <div v-if="editMode" class="ph3">
      <form @submit.prevent="submit">
        <errors :errors="form.errors" :classes="'mb3'" />

        <ul class="list pl0">
          <li class="mb3 flex-ns items-center">
            <span class="dib-ns db mb0-ns mb2 title">{{ $t('account.general_rename_input') }}</span>
            <span>
              <text-input
                :id="'name'"
                :ref="'renameField'"
                v-model="form.name"
                :name="'name'"
                :datacy="'company-name-input'"
                :extra-class-upper-div="'mb0'"
                :errors="$page.props.errors.title"
                :required="true"
                :autofocus="true"
                @esc-key-pressed="editMode = false"
              />
            </span>
          </li>
        </ul>

        <!-- Actions -->
        <div class="mt4 mb3">
          <div class="flex-ns justify-between">
            <div>
              <a href="#" class="btn dib tc w-auto-ns w-100 pv2 ph3" data-cy="cancel-rename-company-button" @click.prevent="editMode = false">
                {{ $t('app.cancel') }}
              </a>
            </div>
            <loading-button :classes="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.rename')" :cypress-selector="'submit-rename-company-button'" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import Help from '@/Shared/Help';

export default {
  components: {
    Errors,
    Help,
    LoadingButton,
    TextInput,
    SmallNameAndAvatar,
  },

  props: {
    information: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      editMode: false,
      form: {
        id: 0,
        name: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created: function() {
    this.updatedName = this.information.name;
  },

  methods: {
    displayEditMode() {
      this.editMode = true;
      this.form.name = this.updatedName;
      this.form.errors = null;

      this.$nextTick(() => {
        this.$refs['renameField'].$refs['input'].focus();
      });
    },

    submit() {
      axios.post('/' + this.$page.props.auth.company.id + '/account/general/rename', this.form)
        .then(response => {
          this.updatedName = this.form.name;
          this.editMode = false;
          this.form.errors = null;
          flash(this.$t('account.general_rename_success'), 'success');
        })
        .catch(error => {
          this.editMode = true;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
