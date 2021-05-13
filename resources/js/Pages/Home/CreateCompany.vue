<style lang="scss" scoped>
img {
  width: 90px;
}
</style>
<template>
  <layout :no-menu="true" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="mt4 mw6 center mb1">
        <p class="mt0 mb3 f6">← <inertia-link :href="'/'">{{ $t('app.back') }}</inertia-link></p>
      </div>
      <div class="mw6 center br3 mb3 bg-white box pa3">
        <div class="tc">
          <img src="/img/streamline-icon-construction-house-5@130x130.png" loading="lazy" class="db center mb1" alt="company" />
          <h2 class="fw5 f4 lh-copy mt0 mb1">
            {{ $t('company.title') }}
          </h2>
          <p class="mt0 mb4 gray">{{ $t('company.subtitle') }}</p>
        </div>

        <!-- Form Errors -->
        <errors :errors="form.errors" />

        <form @submit.prevent="submit">
          <text-input v-model="form.name"
                      :name="'name'"
                      :errors="$page.props.errors.name"
                      :label="$t('company.new_name')"
                      :required="true"
                      :autofocus="true"
          />

          <!-- Actions -->
          <div class="flex-ns justify-between">
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('company.next_step_cta')" data-cy="create-company-submit" />
          </div>
        </form>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    company: {
      type: Object,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      form: {
        name: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      this.$inertia.post(this.route('company.store'), this.form)
        .then(() =>
          this.loadingState = null
        );
    },
  }
};
</script>
