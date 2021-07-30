<style lang="scss" scoped>
.box-bottom {
  border-bottom-left-radius: 11px;
  border-bottom-right-radius: 11px;
}

.warning {
  box-shadow: 0 0 0 1px #e3e8ee;
  background-color: #f7fafc;
}

.badge {
  padding: 1px 6px;
  border-radius: 4px;

  &.active {
    background-color: #cbf4c9;
  }

  &.closed {
    background-color: #e3e8ee;
  }
}

.sidebar {
  svg {
    width: 20px;
    top: 3px;
    color: #9da3ae;
  }

  span {
    top: -2px;
  }

  .active {
    background-color: #e5e7ea;

    svg {
      color: #6c727f;
    }

    span {
      color: #121826;
    }
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/hr/job-openings'">{{ $t('app.breadcrumb_hr_job_openings_active') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_job_opening_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <!-- header -->
        <div class="box bg-white mb4">
          <div class="pa3 flex justify-between items-center bb bb-gray">
            <!-- name + summary -->
            <div class="">
              <h2 class="mt0 mb2 relative fw4">
                Regis Freyd
              </h2>

              <ul class="list pa0 ma0 f7 gray">
                <li class="di mr3">
                  Applied Dec 23, 2018
                </li>
                <li class="di mr3">email address</li>
                <li class="di">telephone number</li>
              </ul>
            </div>
          </div>

          <div class="bg-gray pa3 f7 box-bottom">
            <p class="ma0">Job opening: <inertia-link>Financier</inertia-link></p>
          </div>
        </div>

        <!-- has the candidate applied to other jobs in the company -->
        <div class="warning pa3 br3 mb4">
          <p class="ma0 mb3 f6"><span class="mr1">⚠️</span> Has likely applied to 3 other jobs (based on the email address used)</p>
          <ul class="list ma0 pl0">
            <li class="mb2 mr2"><inertia-link>Finance</inertia-link> <span class="badge f7 active">active</span></li>
            <li class="mr2"><inertia-link>Finance</inertia-link> <span class="badge f7 closed">closed</span></li>
          </ul>
        </div>

        <div class="center br3 mb5 tc">
          <div class="cf dib btn-group">
            <inertia-link :href="''" class="f6 fl ph3 pv2 dib pointer no-underline">
              Curriculum vitae
            </inertia-link>
            <inertia-link :href="''" class="f6 fl ph3 pv2 dib pointer no-underline">
              Recruiting process
            </inertia-link>
          </div>
        </div>

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-20-l w-100">
            <!-- sidebar -->
            <ul class="list ma0 pl0 sidebar">
              <!-- selected candidates -->
              <li class="pa2 br3 relative f6">
                <span class="mb2 db f7 gray fw5">Stage 1</span>
                <span class="relative">Selected</span>
              </li>

              <!-- to sort candidates -->
              <li class="pa2 active br3 relative f6">
                <span class="mb2 db f7 gray fw5">Stage 1</span>
                <span class="relative">To sort</span>
              </li>

              <!-- rejected candidates -->
              <li class="pa2 br3 relative f6">
                <span class="mb2 db f7 gray fw5">Stage 1</span>
                <span class="relative">Rejected</span>
              </li>
            </ul>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-80-l w-100 pl4-l">
            <div class="bg-white box">
              <!-- actions -->
              <div class="pa3 bb bb-gray">
                <ul class="list ma0 pl0">
                  <li class="di mr1"><loading-button :class="'btn w-auto-ns w-100 pv2 ph3 mr2 mb0-ns mb2'" :state="loadingPauseState" :text="'Reject'" @click="pause()" /></li>
                  <li class="di"><loading-button :class="'btn w-auto-ns w-100 pv2 ph3 mr2 mb0-ns mb2'" :state="loadingPauseState" :text="'Qualifies for next stage'" @click="pause()" /></li>
                </ul>
              </div>

              <div class="pa3 bb bb-gray">
                <!-- employees -->
                <h3 class="mt0 fw5 f6">
                  Employees needed to be talked to
                </h3>
                <div class="flex items-center">
                  <div class="mr3 relative" data-v-cff706f6="">
                    <span class="db ma0" data-v-cff706f6="">
                      <a href="https://officelife.test/1/employees/1" data-v-cff706f6="">Michael Scott</a>
                    </span>
                  </div>
                </div>
                <span>Ask employee to assist for this stage</span>
              </div>

              <!-- add a note -->
              <div class="pa3 bb bb-gray">
                <text-area v-model="form.content"
                           :label="$t('account.company_news_new_content')"
                           :datacy="'news-content-textarea'"
                           :required="true"
                           :rows="10"
                           :help="$t('account.company_news_new_content_help')"
                />
              </div>

              <div class="pa3 bb bb-gray">
                <div class="lh-copy">
                  Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?
                </div>

                <!-- poster information -->
                <p class="flex justify-between f7 gray mb1">
                  <span>Submitted by Regis Freyd</span>
                  <span>3:30pm</span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';

export default {
  components: {
    Layout,
    LoadingButton,
    TextArea,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    jobOpening: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        title: null,
        content: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created() {
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
