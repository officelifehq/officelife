<template>
  <layout title="Home" :no-menu="true" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center br3 mb3 bg-white box">
        <div class="fn fl-ns w-50-ns pa3">
          Create a company
        </div>
        <div class="fn fl-ns w-50-ns pa3">
          <!-- Form Errors -->
          <errors :errors="form.errors" />

          <form @submit.prevent="submit">
            <text-input v-model="form.name" :name="'name'" :errors="$page.errors.name" :label="$t('company.new_name')" />

            <!-- Actions -->
            <div class="">
              <div class="flex-ns justify-between">
                <div>
                  <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="'register'" data-cy="create-company-submit" />
                </div>
              </div>
            </div>
          </form>
        </div>
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
