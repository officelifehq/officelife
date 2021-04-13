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
    <!-- information about the company -->
    <ul v-if="!editMode" class="list ph3">
      <li class="mb3 flex-ns items-start">
        <span class="dib-ns db title mb0-ns mb2">{{ $t('account.general_name') }}</span>
        <span class="fw6" data-cy="company-name">{{ updatedName }}</span>
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
        <errors :errors="form.errors" :class="'mb3'" />

        <ul class="list pl0">
          <li class="flex-ns items-center">
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
        <div class="mt2 mb3">
          <div class="flex-ns justify-between">
            <div>
              <a href="#" class="btn dib tc w-auto-ns w-100 pv2 ph3" data-cy="cancel-rename-company-button" @click.prevent="editMode = false">
                {{ $t('app.cancel') }}
              </a>
            </div>
            <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.rename')" :cypress-selector="'submit-rename-company-button'" />
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

export default {
  components: {
    Errors,
    LoadingButton,
    TextInput,
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
        this.$refs.renameField.focus();
      });
    },

    submit() {
      axios.post('/' + this.$page.props.auth.company.id + '/account/general/rename', this.form)
        .then(response => {
          this.updatedName = this.form.name;
          this.editMode = false;
          this.form.errors = null;
          this.flash(this.$t('account.general_rename_success'), 'success');
        })
        .catch(error => {
          this.editMode = true;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
