<style lang="scss" scoped>
.police-line {
  border-color: #666768;
}

.police-photo {
  filter: grayscale(80%) sepia(10%) brightness(90%);
}

.closed-lane {
  background-color: #f9f9fa;
}

.icon-date {
  width: 17px;
  height: 17px;
  top: 2px;
}

.icon-closed {
  width: 17px;
  height: 17px;
  top: 2px;
}

.actions {
  background-color: #eef3f9;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/dashboard/manager'"
                  :previous="$t('app.breadcrumb_dashboard_hr_discipline_cases')"
      >
        {{ $t('app.breadcrumb_hr_discipline_case_show') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="cf mw7 center br3 mb4 bg-white box relative">
        <!-- case closed -->
        <div v-if="!localCase.active" class="ph3 pv2 bb bb-gray relative tc closed-lane">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon-closed relative mr1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>

          <span>{{ $t('dashboard.hr_discipline_case_show_case_closed') }}</span>
        </div>

        <div class="flex-ns items-center pa3">
          <!-- avatar -->
          <div class="mr3 db">
            <avatar :avatar="localCase.employee.avatar" :size="200" :class="'br3 police-photo'" />
          </div>

          <div class="flex-grow">
            <!-- information -->
            <ul class="ml0 pa0 list">
              <!-- nom -->
              <li class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_name') }}</span>
                <span class="bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <span class="mr1">{{ localCase.employee.name }}</span>
                  <inertia-link v-if="localCase.employee.url" :href="localCase.employee.url" class="di no-underline"><svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="12px" height="12px"><path d="M 41.470703 4.9863281 A 1.50015 1.50015 0 0 0 41.308594 5 L 27.5 5 A 1.50015 1.50015 0 1 0 27.5 8 L 37.878906 8 L 22.439453 23.439453 A 1.50015 1.50015 0 1 0 24.560547 25.560547 L 40 10.121094 L 40 20.5 A 1.50015 1.50015 0 1 0 43 20.5 L 43 6.6894531 A 1.50015 1.50015 0 0 0 41.470703 4.9863281 z M 12.5 8 C 8.3754991 8 5 11.375499 5 15.5 L 5 35.5 C 5 39.624501 8.3754991 43 12.5 43 L 32.5 43 C 36.624501 43 40 39.624501 40 35.5 L 40 25.5 A 1.50015 1.50015 0 1 0 37 25.5 L 37 35.5 C 37 38.003499 35.003499 40 32.5 40 L 12.5 40 C 9.9965009 40 8 38.003499 8 35.5 L 8 15.5 C 8 12.996501 9.9965009 11 12.5 11 L 22.5 11 A 1.50015 1.50015 0 1 0 22.5 8 L 12.5 8 z" /></svg></inertia-link>
                </span>
              </li>
              <!-- position -->
              <li v-if="localCase.employee.position" class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_position') }}</span>
                <span class="bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <span>{{ localCase.employee.position }}</span>
                </span>
              </li>
              <!-- teams -->
              <li v-if="localCase.employee.teams" class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_team') }}</span>
                <ul class="ma0 pl0 list di bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <li v-for="team in localCase.employee.teams" :key="team.id" class="di pointer mr2">
                    <span class="mr1">{{ team.name }}</span>
                    <inertia-link :href="team.url" class="di"><svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="12px" height="12px"><path d="M 41.470703 4.9863281 A 1.50015 1.50015 0 0 0 41.308594 5 L 27.5 5 A 1.50015 1.50015 0 1 0 27.5 8 L 37.878906 8 L 22.439453 23.439453 A 1.50015 1.50015 0 1 0 24.560547 25.560547 L 40 10.121094 L 40 20.5 A 1.50015 1.50015 0 1 0 43 20.5 L 43 6.6894531 A 1.50015 1.50015 0 0 0 41.470703 4.9863281 z M 12.5 8 C 8.3754991 8 5 11.375499 5 15.5 L 5 35.5 C 5 39.624501 8.3754991 43 12.5 43 L 32.5 43 C 36.624501 43 40 39.624501 40 35.5 L 40 25.5 A 1.50015 1.50015 0 1 0 37 25.5 L 37 35.5 C 37 38.003499 35.003499 40 32.5 40 L 12.5 40 C 9.9965009 40 8 38.003499 8 35.5 L 8 15.5 C 8 12.996501 9.9965009 11 12.5 11 L 22.5 11 A 1.50015 1.50015 0 1 0 22.5 8 L 12.5 8 z" /></svg></inertia-link>
                  </li>
                </ul>
              </li>
              <!-- hired at -->
              <li v-if="localCase.employee.hired_at" class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_hired_at') }}</span>
                <span class="bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <span>{{ localCase.employee.hired_at }}</span>
                </span>
              </li>
              <!-- opened at -->
              <li class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_case_opened') }}</span>
                <span class="bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <span>{{ localCase.opened_at }}</span>
                </span>
              </li>
              <!-- opened by -->
              <li class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_case_opened_by') }}</span>
                <span class="bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <span class="mr1">{{ localCase.author.name }}</span>
                  <inertia-link v-if="localCase.author.url" :href="localCase.author.url" class="di no-underline"><svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="12px" height="12px"><path d="M 41.470703 4.9863281 A 1.50015 1.50015 0 0 0 41.308594 5 L 27.5 5 A 1.50015 1.50015 0 1 0 27.5 8 L 37.878906 8 L 22.439453 23.439453 A 1.50015 1.50015 0 1 0 24.560547 25.560547 L 40 10.121094 L 40 20.5 A 1.50015 1.50015 0 1 0 43 20.5 L 43 6.6894531 A 1.50015 1.50015 0 0 0 41.470703 4.9863281 z M 12.5 8 C 8.3754991 8 5 11.375499 5 15.5 L 5 35.5 C 5 39.624501 8.3754991 43 12.5 43 L 32.5 43 C 36.624501 43 40 39.624501 40 35.5 L 40 25.5 A 1.50015 1.50015 0 1 0 37 25.5 L 37 35.5 C 37 38.003499 35.003499 40 32.5 40 L 12.5 40 C 9.9965009 40 8 38.003499 8 35.5 L 8 15.5 C 8 12.996501 9.9965009 11 12.5 11 L 22.5 11 A 1.50015 1.50015 0 1 0 22.5 8 L 12.5 8 z" /></svg></inertia-link>
                </span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- events -->
      <div>
        <div v-if="!showAddMode && localCase.active" class="cf mw7 center tc mb5">
          <a href="#" class="btn dib" @click.prevent="displayAddMode">+ {{ $t('dashboard.hr_discipline_case_show_case_event_cta') }}</a>
        </div>

        <!-- add new event -->
        <div v-if="showAddMode" class="cf mw7 center bg-white box pa3 mb5">
          <form @submit.prevent="submit">
            <div class="mb2">
              <p class="mt0">{{ $t('dashboard.hr_discipline_case_show_case_event_take_place') }}</p>
              <v-date-picker v-model="form.happened_at" class="inline-block h-full" :model-config="modelConfig">
                <template #default="{ inputValue, inputEvents }">
                  <input class="rounded border bg-white px-2 py-1" :value="inputValue" v-on="inputEvents" />
                </template>
              </v-date-picker>
            </div>

            <text-area v-model="form.description"
                       :label="$t('dashboard.hr_discipline_case_show_case_event_what_happened')"
                       :datacy="'news-content-textarea'"
                       :required="true"
                       :rows="10"
                       :help="$t('dashboard.hr_discipline_case_show_case_event_description')"
            />

            <div class="flex-ns justify-between">
              <div>
                <span class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2" @click="showAddMode = false">
                  {{ $t('app.cancel') }}
                </span>
              </div>
              <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.save')" />
            </div>
          </form>
        </div>

        <!-- list of events -->
        <div v-for="event in localEvents" :key="event.id" class="cf mw7 center mb4">
          <!-- date -->
          <div class="flex justify-between items-center mb2 f6 gray">
            <span class="relative">
              <svg xmlns="http://www.w3.org/2000/svg" class="relative icon-date" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              {{ $t('dashboard.hr_discipline_case_show_case_happened_at_by', {date: event.happened_at, name: event.author.name}) }}
            </span>
            <ul class="ma0 pl0 list">
              <!-- delete option -->
              <li v-if="idToDelete == event.id" class="di f6">
                {{ $t('app.sure') }}
                <a class="c-delete mr1 pointer" @click.prevent="destroy(event)">
                  {{ $t('app.yes') }}
                </a>
                <a class="pointer" @click.prevent="idToDelete = 0">
                  {{ $t('app.no') }}
                </a>
              </li>
              <li v-else class="di">
                <a class="di bb b--dotted c-delete bt-0 bl-0 br-0 pointer f6" @click.prevent="idToDelete = event.id">
                  {{ $t('app.delete') }}
                </a>
              </li>
            </ul>
          </div>

          <!-- content -->
          <div class="parsed-content br3 bg-white box pa3" v-html="event.description">
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    LoadingButton,
    TextArea,
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
      showAddMode: false,
      loadingState: false,
      localCase: [],
      localEvents: [],
      idToDelete: 0,
      modelConfig: {
        type: 'string',
        mask: 'YYYY-MM-DD',
      },
      form: {
        happened_at: null,
        description: null,
      },
    };
  },

  created() {
    this.localCase = this.data;
    this.localEvents = this.data.events;

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');

      localStorage.removeItem('success');
    }
  },

  methods: {
    displayAddMode() {
      this.form.happened_at = null;
      this.form.description = null;
      this.showAddMode = true;
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.data.url.events.store, this.form)
        .then(response => {
          this.flash(this.$t('dashboard.hr_discipline_case_show_case_event_created'), 'success');
          this.localEvents.unshift(response.data.data);
          this.loadingState = null;
          this.showAddMode = false;
        })
        .catch(error => {
          this.loadingState = null;
        });
    },

    destroy(event) {
      axios.delete(event.url.delete)
        .then(response => {
          this.flash(this.$t('dashboard.hr_discipline_case_show_case_event_deleted'), 'success');

          this.idToDelete = 0;
          var id = this.localEvents.findIndex(x => x.id === event.id);
          this.localEvents.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  },
};

</script>
