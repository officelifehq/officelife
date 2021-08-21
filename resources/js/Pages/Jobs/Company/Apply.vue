<style lang="scss" scoped>
img {
  max-height: 100px;
  max-width: 100px;
}

svg {
  width: 16px;
  top: 3px;
  color: #b7b7b7;
}

.box-top {
  border-top-left-radius: 11px;
  border-top-right-radius: 11px;
}

.reference-number {
  padding: 2px 6px;
  border-radius: 6px;
  top: -4px;
  background-color: #ddf4ff;
  color: #0969da;
}

li + li:before {
  content: '>';
  padding-left: 5px;
  padding-right: 5px;
}
</style>

<template>
  <div class="ph2 ph5-ns mt5">
    <!-- job title -->
    <div class="mw7 center box bg-white mb4">
      <div class="bg-gray pa3 f7 box-top">
        <ul class="list pl0 ma0">
          <li class="mr2 di"><inertia-link :href="data.url_all">All jobs</inertia-link></li>
          <li class="mr2 di"><inertia-link :href="data.url_company">{{ data.company.name }}</inertia-link></li>
          <li class="di">{{ data.job_opening.title }}</li>
        </ul>
      </div>
      <div class="pa3 flex justify-between items-center bt bb-gray">
        <!-- name + summary -->
        <div class="">
          <h2 class="ma0 relative fw4">
            {{ data.job_opening.title }}
            <span v-if="data.job_opening.reference_number" class="reference-number f7 code fw4">
              {{ data.job_opening.reference_number }}
            </span>
          </h2>
        </div>
      </div>
    </div>

    <div class="mw8 center br3 mb5 relative z-1">
      <div class="cf center">
        <!-- LEFT COLUMN -->
        <div class="fl w-70-l w-100 bg-white box pa3">
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
            <div class="mt4">
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

        <!-- RIGHT COLUMN -->
        <div class="fl w-30-l w-100 pl4-l">
          <div class="bg-white box mb4 pa3">
            <div v-if="data.company.logo" class="tc">
              <img :src="data.company.logo" :alt="data.company.name" class="mb3" />
            </div>

            <div class="">
              <h3 class="mt0 fw5 f4 mb2">
                {{ data.company.name }}
              </h3>
              <ul class="list pl0 ma0 f7 gray relative">
                <li v-if="data.company.location" class="di mr2 relative">
                  <svg xmlns="http://www.w3.org/2000/svg" class="mr1 relative" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                  </svg>
                  {{ data.company.location }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
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
