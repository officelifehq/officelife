<style lang="scss" scoped>
.files-list {
  border-bottom-left-radius: 0.5rem;
  border-bottom-right-radius: 0.5rem;

  li:last-child {
    border-bottom: 0;
  }
}

.police-line {
  border-color: #666768;
}

.police-photo {
  filter: grayscale(80%) sepia(10%) brightness(90%);
}

.icon-file {
  width: 13px;
  height: 13px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/dashboard/hr/discipline-cases'"
                  :previous="$t('app.breadcrumb_dashboard_hr_discipline_cases')"
      >
        {{ $t('app.breadcrumb_hr_discipline_case_show') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="cf mw7 center br3 mb4 bg-white box relative pa3">
        <div class="flex-ns items-center">
          <!-- avatar -->
          <div class="mr3 db">
            <avatar :avatar="data.employee.avatar" :size="200" :class="'br3 police-photo'" />
          </div>

          <div class="flex-grow">
            <!-- information -->
            <ul class="ml0 pa0 list">
              <!-- nom -->
              <li class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_name') }}</span>
                <span class="bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <span class="mr1">{{ data.employee.name }}</span>
                  <inertia-link v-if="data.employee.url" :href="data.employee.url" class="di no-underline"><svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="12px" height="12px"><path d="M 41.470703 4.9863281 A 1.50015 1.50015 0 0 0 41.308594 5 L 27.5 5 A 1.50015 1.50015 0 1 0 27.5 8 L 37.878906 8 L 22.439453 23.439453 A 1.50015 1.50015 0 1 0 24.560547 25.560547 L 40 10.121094 L 40 20.5 A 1.50015 1.50015 0 1 0 43 20.5 L 43 6.6894531 A 1.50015 1.50015 0 0 0 41.470703 4.9863281 z M 12.5 8 C 8.3754991 8 5 11.375499 5 15.5 L 5 35.5 C 5 39.624501 8.3754991 43 12.5 43 L 32.5 43 C 36.624501 43 40 39.624501 40 35.5 L 40 25.5 A 1.50015 1.50015 0 1 0 37 25.5 L 37 35.5 C 37 38.003499 35.003499 40 32.5 40 L 12.5 40 C 9.9965009 40 8 38.003499 8 35.5 L 8 15.5 C 8 12.996501 9.9965009 11 12.5 11 L 22.5 11 A 1.50015 1.50015 0 1 0 22.5 8 L 12.5 8 z" /></svg></inertia-link>
                </span>
              </li>
              <!-- position -->
              <li v-if="data.employee.position" class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_position') }}</span>
                <span class="bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <span>{{ data.employee.position }}</span>
                </span>
              </li>
              <!-- teams -->
              <li v-if="data.employee.teams" class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_team') }}</span>
                <ul class="ma0 pl0 list di bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <li v-for="team in data.employee.teams" :key="team.id" class="di pointer mr2">
                    <span class="mr1">{{ team.name }}</span>
                    <inertia-link :href="team.url" class="di"><svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="12px" height="12px"><path d="M 41.470703 4.9863281 A 1.50015 1.50015 0 0 0 41.308594 5 L 27.5 5 A 1.50015 1.50015 0 1 0 27.5 8 L 37.878906 8 L 22.439453 23.439453 A 1.50015 1.50015 0 1 0 24.560547 25.560547 L 40 10.121094 L 40 20.5 A 1.50015 1.50015 0 1 0 43 20.5 L 43 6.6894531 A 1.50015 1.50015 0 0 0 41.470703 4.9863281 z M 12.5 8 C 8.3754991 8 5 11.375499 5 15.5 L 5 35.5 C 5 39.624501 8.3754991 43 12.5 43 L 32.5 43 C 36.624501 43 40 39.624501 40 35.5 L 40 25.5 A 1.50015 1.50015 0 1 0 37 25.5 L 37 35.5 C 37 38.003499 35.003499 40 32.5 40 L 12.5 40 C 9.9965009 40 8 38.003499 8 35.5 L 8 15.5 C 8 12.996501 9.9965009 11 12.5 11 L 22.5 11 A 1.50015 1.50015 0 1 0 22.5 8 L 12.5 8 z" /></svg></inertia-link>
                  </li>
                </ul>
              </li>
              <!-- hired at -->
              <li v-if="data.employee.hired_at" class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_hired_at') }}</span>
                <span class="bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <span>{{ data.employee.hired_at }}</span>
                </span>
              </li>
              <!-- opened at -->
              <li class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_case_opened') }}</span>
                <span class="bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <span>{{ data.opened_at }}</span>
                </span>
              </li>
              <!-- opened by -->
              <li class="mb2 flex">
                <span class="mr2 f6">{{ $t('dashboard.hr_discipline_case_show_case_opened_by') }}</span>
                <span class="bb bt-0 bl-0 br-0 flex-grow b--dotted police-line">
                  <span class="mr1">{{ data.author.name }}</span>
                  <inertia-link v-if="data.author.url" :href="data.author.url" class="di no-underline"><svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="12px" height="12px"><path d="M 41.470703 4.9863281 A 1.50015 1.50015 0 0 0 41.308594 5 L 27.5 5 A 1.50015 1.50015 0 1 0 27.5 8 L 37.878906 8 L 22.439453 23.439453 A 1.50015 1.50015 0 1 0 24.560547 25.560547 L 40 10.121094 L 40 20.5 A 1.50015 1.50015 0 1 0 43 20.5 L 43 6.6894531 A 1.50015 1.50015 0 0 0 41.470703 4.9863281 z M 12.5 8 C 8.3754991 8 5 11.375499 5 15.5 L 5 35.5 C 5 39.624501 8.3754991 43 12.5 43 L 32.5 43 C 36.624501 43 40 39.624501 40 35.5 L 40 25.5 A 1.50015 1.50015 0 1 0 37 25.5 L 37 35.5 C 37 38.003499 35.003499 40 32.5 40 L 12.5 40 C 9.9965009 40 8 38.003499 8 35.5 L 8 15.5 C 8 12.996501 9.9965009 11 12.5 11 L 22.5 11 A 1.50015 1.50015 0 1 0 22.5 8 L 12.5 8 z" /></svg></inertia-link>
                </span>
              </li>
            </ul>

            <!-- actions -->
            <div class="flex">
              <a class="btn dib tc w-auto-ns w-100 pv2 ph3 mr2">Close case</a>
              <a class="btn dib tc w-auto-ns w-100 pv2 ph3 mr2 destroy">Delete case</a>
            </div>
          </div>
        </div>
      </div>

      <!-- events -->
      <div>
        <a href="" class="cf mw7 center dib mb5">+ Add event</a>
        <!-- add new event -->
        <div v-if="showAddMode" class="bg-white box pa3">
        </div>

        <!-- list of events -->
        <div class="cf mw7 center mb3">
          <!-- date -->
          <div class="flex justify-between items-center mb2">
            <span># March 12, 2019</span>
            <ul class="ma0 pl0 list">
              <li class="di mr2 bb b--dotted bt-0 bl-0 br-0 pointer f6">Edit</li>
              <li class="di bb b--dotted c-delete bt-0 bl-0 br-0 pointer f6">Delete</li>
            </ul>
          </div>

          <!-- content -->
          <div class="parsed-content br3 bg-white box pa3">
            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
          </div>

          <!-- files -->
          <div class="w-90 center">
            <ul class="ma0 list bb bl br bb-gray files-list f6 pl0">
              <li class="bb pa2 bb-gray">
                <svg id="Layer_1" class="icon-file di mr1" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     x="0px" y="0px"
                     viewBox="0 0 511.36 511.36" style="enable-background:new 0 0 511.36 511.36;" xml:space="preserve"
                >
                  <g>
                    <path d="M454.827,35.2c-46.933-46.933-122.027-46.933-168.96,0L63.147,258.773c-3.413,3.413-3.413,8.533,0,11.947
                    s8.533,3.413,11.947,0l222.72-223.573c40.107-40.107,104.96-40.107,145.067,0c40.107,40.107,40.107,104.96,0,145.067
                    L162.133,472.96c-28.16,28.16-74.24,28.16-102.4,0c-28.16-28.16-28.16-74.24,0-102.4l226.133-226.987
                    c17.067-16.213,43.52-17.067,60.587,0c16.213,17.067,16.213,44.373,0,60.587l-168.96,169.813c-3.413,3.413-3.413,8.533,0,11.947
                    c3.413,3.413,8.533,3.413,11.947,0L358.4,216.96c23.04-23.04,23.04-61.44,0-84.48c-23.04-23.04-61.44-23.04-84.48,0
                    L47.787,358.613c-34.987,34.133-34.987,91.307,0,126.293c17.067,17.92,40.107,26.453,63.147,26.453
                    c23.04,0,46.08-8.533,63.147-26.453L454.827,204.16c22.187-22.187,34.987-52.907,34.987-84.48
                    C489.813,88.107,477.013,57.387,454.827,35.2z"
                    />
                  </g></svg>
                <span>File 1</span>
              </li>
              <li class="bb pa2 bb-gray">
                <svg id="Layer_1" class="icon-file di mr1" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     x="0px" y="0px"
                     viewBox="0 0 511.36 511.36" style="enable-background:new 0 0 511.36 511.36;" xml:space="preserve"
                >
                  <g>
                    <path d="M454.827,35.2c-46.933-46.933-122.027-46.933-168.96,0L63.147,258.773c-3.413,3.413-3.413,8.533,0,11.947
                    s8.533,3.413,11.947,0l222.72-223.573c40.107-40.107,104.96-40.107,145.067,0c40.107,40.107,40.107,104.96,0,145.067
                    L162.133,472.96c-28.16,28.16-74.24,28.16-102.4,0c-28.16-28.16-28.16-74.24,0-102.4l226.133-226.987
                    c17.067-16.213,43.52-17.067,60.587,0c16.213,17.067,16.213,44.373,0,60.587l-168.96,169.813c-3.413,3.413-3.413,8.533,0,11.947
                    c3.413,3.413,8.533,3.413,11.947,0L358.4,216.96c23.04-23.04,23.04-61.44,0-84.48c-23.04-23.04-61.44-23.04-84.48,0
                    L47.787,358.613c-34.987,34.133-34.987,91.307,0,126.293c17.067,17.92,40.107,26.453,63.147,26.453
                    c23.04,0,46.08-8.533,63.147-26.453L454.827,204.16c22.187-22.187,34.987-52.907,34.987-84.48
                    C489.813,88.107,477.013,57.387,454.827,35.2z"
                    />
                  </g></svg>
                <span>File 1</span>
              </li>
              <li class="bb pa2 bb-gray">
                <svg id="Layer_1" class="icon-file di mr1" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     x="0px" y="0px"
                     viewBox="0 0 511.36 511.36" style="enable-background:new 0 0 511.36 511.36;" xml:space="preserve"
                >
                  <g>
                    <path d="M454.827,35.2c-46.933-46.933-122.027-46.933-168.96,0L63.147,258.773c-3.413,3.413-3.413,8.533,0,11.947
                    s8.533,3.413,11.947,0l222.72-223.573c40.107-40.107,104.96-40.107,145.067,0c40.107,40.107,40.107,104.96,0,145.067
                    L162.133,472.96c-28.16,28.16-74.24,28.16-102.4,0c-28.16-28.16-28.16-74.24,0-102.4l226.133-226.987
                    c17.067-16.213,43.52-17.067,60.587,0c16.213,17.067,16.213,44.373,0,60.587l-168.96,169.813c-3.413,3.413-3.413,8.533,0,11.947
                    c3.413,3.413,8.533,3.413,11.947,0L358.4,216.96c23.04-23.04,23.04-61.44,0-84.48c-23.04-23.04-61.44-23.04-84.48,0
                    L47.787,358.613c-34.987,34.133-34.987,91.307,0,126.293c17.067,17.92,40.107,26.453,63.147,26.453
                    c23.04,0,46.08-8.533,63.147-26.453L454.827,204.16c22.187-22.187,34.987-52.907,34.987-84.48
                    C489.813,88.107,477.013,57.387,454.827,35.2z"
                    />
                  </g></svg>
                <span>File 1</span>
              </li>
            </ul>
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

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
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
      events: Array,
      showAddMode: false,
      form: {
        id: 0,
      },
    };
  },

  methods: {
  },
};

</script>
