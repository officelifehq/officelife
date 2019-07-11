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
            <!-- Email -->
            <div class="">
              <label class="db fw4 lh-copy f6" for="name">Name</label>
              <input v-model="form.name" type="text" name="name" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required />
            </div>

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

export default {
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

      axios.post('/company', this.form)
        .then(response => {
          Turbolinks.visit('/' + response.data.company_id + '/dashboard/me');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};
</script>
