<style lang="scss" scoped>
.title {
  min-width: 230px;
}
</style>

<template>
  <div class="bb bb-gray">
    <h3 class="ph3 fw5">
      {{ $t('account.general_location_information') }}

      <help :url="$page.props.help_links.account_general_location" :top="'2px'" />
    </h3>

    <!-- location of the company -->
    <ul v-if="!editMode" class="list ph3">
      <li class="mb3 flex items-start">
        <span class="dib title mr3">{{ $t('account.general_location_label') }}</span>
        <span v-if="localLocation" class="fw6">{{ localLocation }}</span>
        <span v-else class="fw6">{{ $t('account.general_location_blank') }}</span>
      </li>
    </ul>

    <div v-if="!editMode" class="ph3 mb3">
      <a data-cy="update-currency-company-button" class="btn tc relative dib" @click.prevent="displayEditMode()">
        {{ $t('account.general_location_cta') }}
      </a>
    </div>

    <!-- change date -->
    <div v-if="editMode" class="ph3">
      <form @submit.prevent="submit">
        <errors :errors="form.errors" :class="'mb3'" />

        <ul class="list pl0">
          <li class="mb3 flex-ns items-center">
            <span class="dib-ns db mb0-ns mb2 title">{{ $t('account.general_location_label') }}</span>
            <span>
              <text-input
                :id="'name'"
                :ref="'setLocation'"
                v-model="form.location"
                :name="'name'"
                :extra-class-upper-div="'mb0'"
                :errors="$page.props.errors.title"
                :required="true"
                :autofocus="true"
                :maxlength="191"
                autocomplete="off"
                @esc-key-pressed="editMode = false"
              />
            </span>
          </li>
        </ul>

        <!-- Actions -->
        <div class="mt4 mb3">
          <div class="flex-ns justify-between">
            <div>
              <a href="#" class="btn dib tc w-auto-ns w-100 pv2 ph3" @click.prevent="editMode = false">
                {{ $t('app.cancel') }}
              </a>
            </div>
            <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.update')" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Help from '@/Shared/Help';
import TextInput from '@/Shared/TextInput';

export default {
  components: {
    Errors,
    Help,
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
      localLocation: '',
      editMode: false,
      form: {
        id: 0,
        location: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  mounted: function() {
    this.localLocation = this.information.location;
  },

  methods: {
    displayEditMode() {
      this.editMode = true;

      if (this.localLocation) {
        this.form.location = this.localLocation;
      }

      this.$nextTick(() => {
        this.$refs.setLocation.focus();
      });
    },

    submit() {
      axios.post('/' + this.$page.props.auth.company.id + '/account/general/location', this.form)
        .then(response => {
          this.localLocation = this.form.location;
          this.editMode = false;
          this.flash(this.$t('account.general_location_success'), 'success');
        })
        .catch(error => {
          this.editMode = true;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
