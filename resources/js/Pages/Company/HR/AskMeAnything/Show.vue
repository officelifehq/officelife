<style lang="scss" scoped>
.question-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.question-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/hr/ask-me-anything'"
                  :previous="$t('app.breadcrumb_ama_list')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_ama_show') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 relative z-1">
        <!-- title -->
        <div class="bg-white box pa3 center mb4">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('company.hr_ama_show_title', {date: data.happened_at}) }}

            <help :url="$page.props.help_links.wiki" :top="'1px'" />
          </h2>

          <!-- session theme -->
          <p v-if="data.theme" class="mb3 tc">{{ data.theme }}</p>
        </div>

        <!-- tabs -->
        <div class="cf mw7 center br3 mb5 tc">
          <div class="cf dib btn-group">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(tab == 'company')}">
              Unanswered
            </inertia-link>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(tab == 'projects')}">
              Answered
            </inertia-link>
          </div>
        </div>

        <!-- questions -->
        <ul class="list pl0 ma0 bg-white box">
          <li class="flex items-center justify-between pa3 question-item bb bb-gray bb-gray-hover">
            <span class="f4">sadlfkjlsd fjlas djfl sjd</span>

            <a class="btn">Mark answered</a>
          </li>
          <li class="flex items-center justify-between pa3 question-item">
            <span class="f4">sadlfkjlsd fjlas djfl sjd</span>

            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="'Mark answered'" />
          </li>
        </ul>
      </div>
    </div>
  </layout>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    LoadingButton,
    Help,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    data: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        year: null,
        month: null,
        day: null,
        theme: null,
        date: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  mounted() {
    this.form.year = this.data.year;
    this.form.month = this.data.month;
    this.form.day = this.data.day;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(this.data.url_submit, this.form)
        .then(response => {
          localStorage.success = this.$t('company.hr_ama_new_success');
          this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
