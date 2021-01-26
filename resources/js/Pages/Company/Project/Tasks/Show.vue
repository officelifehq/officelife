<style lang="scss" scoped>
.task-list:not(:last-child) {
  margin-bottom: 35px;
}

.add-list-section {
  top: 5px;
  background-color: #f5f5f5;
}

.edit-cta {
  top: -2px;
}

.icon-date {
  width: 15px;
  top: 2px;
}

.icon-role {
  width: 15px;
  top: 2px;
}

input[type=checkbox] {
  background-color: #f6f7f7;
  border: 2px solid #a3a9ac;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  cursor: pointer;
  height: 20px;
  padding: 0;
  width: 20px;
  top: 1px;
}

.stat-left-corner {
  border-bottom-left-radius: 10px;
}

.stat-right-corner {
  border-bottom-right-radius: 10px;
}

.time-tracking-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.time-tracking-item:last-child {
  border-bottom-width: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects'">{{ $t('app.breadcrumb_project_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_project_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative cf">
        <!-- Menu -->
        <project-menu :project="project" :tab="tab" />

        <!-- LEFT COLUMN -->
        <div class="fl w-70-l w-100">
          <div class="bg-white box mb3">
            <!-- task title + checkbox -->
            <div class="bb bb-gray">
              <div class="pa3 f4">
                <input
                  v-model="localTask.completed"
                  type="checkbox"
                  class="relative"
                  @click.prevent="toggle()"
                />

                {{ localTask.title }}
              </div>
            </div>

            <div v-if="task.description" class="bb bb-gray pa3 lh-copy">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.
            </div>

            <!-- information about the task -->
            <div class="cf">
              <!-- assigned to -->
              <div class="fl w-third br bb-gray pa3 bg-gray stat-left-corner">
                <p class="mt0 mb2 f7">Assigned to</p>
                <p v-if="localTask.assignee" class="ma0">{{ localTask.assignee.name }}</p>
                <p v-else class="ma0">No assignee</p>
              </div>

              <!-- time spent so far -->
              <div class="fl w-third br bb-gray pa3 bg-gray">
                <p class="mt0 mb2 f7 relative">
                  Time spent so far
                  <a v-if="!displayTimeTrackingEntries" class="absolute right-0 f7 bb b--dotted bt-0 bl-0 br-0 pointer" @click="showTimeTrackingEntries">View details</a>
                  <a v-else class="absolute right-0 f7 bb b--dotted bt-0 bl-0 br-0 pointer" @click="hideTimeTrackingEntries">{{ $t('app.hide') }}</a>
                </p>
                <p class="ma0 duration">{{ task.total_duration }}</p>
              </div>

              <!-- part of list -->
              <div class="fl w-third pa3 bg-gray stat-right-corner">
                <p class="mt0 mb2 f7">Part of</p>
                <p v-if="task.list.name" class="ma0">{{ task.list.name }}</p>
                <p v-else class="ma0">No list</p>
              </div>
            </div>
          </div>

          <!-- loading time tracking entries -->
          <div v-if="loadingTimeTrackingEntries" class="ba br3 bb-gray pa3 tc bg-white">
            <div class="di">
              <ball-clip-rotate color="#222" size="5px" />
            </div>
            Fetching information
          </div>

          <!-- time tracking entries -->
          <div v-if="displayTimeTrackingEntries && timeTrackingEntries" class="ba br3 bb-gray bg-white">
            <div v-for="entry in timeTrackingEntries" :key="entry.id" class="pa3 bb bb-gray bb-gray-hover relative time-tracking-item">
              <span class="f7 mr2">
                {{ entry.created_at }}
              </span>
              <small-name-and-avatar
                :name="entry.employee.name"
                :avatar="entry.employee.avatar"
                :classes="'f4 fw4 mr3'"
                :top="'0px'"
                :margin-between-name-avatar="'29px'"
              />
              <span class="mr3 absolute right-0 duration">
                {{ entry.duration }}
              </span>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-30-l w-100 pl4-l">
          <!-- added by -->
          <h3 v-if="localTask.author" class="ttc f7 gray mt0 mb2 fw4">
            Added by
          </h3>

          <!-- information about the author -->
          <div v-if="localTask.author" class="flex mb4">
            <div class="mr2">
              <img :src="localTask.author.avatar" alt="avatar" height="35" width="35" class="br-100" />
            </div>

            <div>
              <inertia-link :href="localTask.author.url" class="mb2 dib">{{ localTask.author.name }}</inertia-link>

              <span v-if="localTask.author.role" class="db f7 mb2 relative">
                <ul class="list pa0 ma0">
                  <li class="mb2">
                    <!-- role -->
                    {{ localTask.author.role }}
                  </li>

                  <li>
                    <!-- in the project since -->
                    <span class="gray">
                      {{ $t('project.members_index_role', { date: localTask.author.added_at }) }}
                    </span>
                  </li>
                </ul>
              </span>
              <span v-if="localTask.author.position && localTask.author.role" class="db f7 gray">
                {{ $t('project.members_index_position_with_role', { role: localTask.author.position }) }}
              </span>
              <span v-if="localTask.author.position && !localTask.author.role" class="db f7 gray">
                {{ $t('project.members_index_position', { role: localTask.author.position }) }}
              </span>
            </div>
          </div>

          <!-- written on -->
          <h3 class="ttc f7 gray mt0 mb2 fw4">
            Created on
          </h3>
          <p class="mt0 mb4">{{ localTask.created_at }}</p>

          <!-- actions -->
          <h3 class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.message_show_actions') }}
          </h3>
          <ul class="list pl0 ma0 f6">
            <!-- edit -->
            <li class="mb2">Edit</li>

            <!-- add time tracking entries -->
            <li class="mb2">Log time</li>

            <!-- delete -->
            <li class="mb2">Delete</li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import ProjectMenu from '@/Pages/Company/Project/Partials/ProjectMenu';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import BallClipRotate from 'vue-loaders/dist/loaders/ball-clip-rotate';

export default {
  components: {
    Layout,
    ProjectMenu,
    SmallNameAndAvatar,
    'ball-clip-rotate': BallClipRotate.component,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    project: {
      type: Object,
      default: null,
    },
    task: {
      type: Object,
      default: null,
    },
    tab: {
      type: String,
      default: 'summary',
    },
  },

  data() {
    return {
      localTask: null,
      displayTimeTrackingEntries: false,
      loadingTimeTrackingEntries: false,
      timeTrackingEntries: null,
      editMode: false,
    };
  },

  created() {
    this.localTask = this.task.task;
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    toggle() {
      axios.put(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/${this.localTask.id}/toggle`)
        .then(response => {
          flash(this.$t('project.task_show_status'), 'success');
          this.localTask.completed = !this.localTask.completed;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    hideTimeTrackingEntries() {
      this.displayTimeTrackingEntries = false;
    },

    showTimeTrackingEntries() {
      this.loadingTimeTrackingEntries = true;

      axios.get(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/${this.localTask.id}/timeTrackingEntries`)
        .then(response => {
          this.timeTrackingEntries = response.data.data;
          this.displayTimeTrackingEntries = true;
          this.loadingTimeTrackingEntries = false;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
      this.displayTimeTrackingEntries = true;
    }
  }
};

</script>
