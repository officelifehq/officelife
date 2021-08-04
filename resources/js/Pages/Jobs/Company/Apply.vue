<style lang="scss" scoped>
img {
  max-height: 100px;
  max-width: 100px;
}
</style>

<template>
  <div class="ph2 ph5-ns">
    <div class="mw7 center br3 mb5 bg-white box relative z-1">
      <div class="flex">
        <img :src="data.company.logo" :alt="data.company.name" />

        <div>
          <h3>{{ data.company.name }}</h3>
          <ul class="list pl0 ma0">
            <li class="di mr2">{{ data.company.location }}</li>
          </ul>
        </div>
      </div>

      <h3>{{ data.job_opening.title }} <span>{{ data.job_opening.reference_number }}</span></h3>

      <p>Step 1 - Candidate information</p>
      <p>Step 2 - Upload your CV</p>

      <form @submit.prevent="submit">
        <errors :errors="form.errors" />

        <!-- Name -->
        <text-input :id="'name'"
                    v-model="form.name"
                    :name="'name'"
                    :errors="$page.props.errors.name"
                    :label="'Your name'"
                    :help="'First name and last name'"
                    :required="true"
        />

        <!-- Email -->
        <text-input :id="'email'"
                    v-model="form.email"
                    :name="'email'"
                    :type="'email'"
                    :errors="$page.props.errors.email"
                    :label="'Your email address'"
                    :help="'We will use this email address for any follow ups.'"
                    :required="true"
        />

        <!-- Desired salary -->
        <text-input :id="'salary'"
                    v-model="form.salary"
                    :name="'salary'"
                    :errors="$page.props.errors.salary"
                    :label="'Your desired salary'"
                    :help="'So we know if we will be able to match your expectations.'"
                    :required="true"
                    :placeholder="'$50 000'"
        />

        <!-- URLs -->
        <text-input :id="'url'"
                    v-model="form.url"
                    :name="'url'"
                    :errors="$page.props.errors.url"
                    :label="'Would you like to point to one of your portfolio or any relevant website?'"
                    :required="false"
                    :placeholder="'https://my-portfolio.com'"
        />

        <!-- Notes -->
        <text-area v-model="form.notes"
                   :label="'Do you need to add any other relevant information?'"
                   :required="false"
                   :rows="10"
        />

        <!-- Actions -->
        <div class="mv4">
          <div class="flex-ns justify-between">
            <div>
              <inertia-link :href="data.url_back" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                {{ $t('app.cancel') }}
              </inertia-link>
            </div>
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="'Next step'" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import TextArea from '@/Shared/TextArea';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    TextInput,
    TextArea,
    Errors,
    LoadingButton,
  },

  props: {
    data: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        name: null,
        salary: null,
        email: null,
        url: null,
        notes: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(`jobs/${this.data.company.slug}/jobs/${this.data.job_opening.slug}`, this.form)
        .then(response => {
          this.$inertia.visit(response.data.url);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
