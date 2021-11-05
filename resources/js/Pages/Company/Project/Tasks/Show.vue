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
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb :has-more="false"
                  :previous-url="route('projects.index', { company: $page.props.auth.company.id})"
                  :previous="$t('app.breadcrumb_project_list')"
      >
        {{ $t('app.breadcrumb_project_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative cf">
        <!-- Menu -->
        <project-menu :project="project" :tab="tab" />

        <!-- LEFT COLUMN -->
        <div class="fl w-70-l w-100">
          <!-- task information - normal mode -->
          <div v-if="!editMode" class="bg-white box mb3">
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

            <!-- task description -->
            <div v-if="localTask.description && !editMode" class="bb bb-gray pa3 lh-copy">
              {{ localTask.description }}
            </div>

            <!-- information about the task -->
            <div class="cf">
              <!-- assigned to -->
              <div class="fl w-third br bb-gray pa3 bg-gray stat-left-corner">
                <p class="mt0 mb2 f7">{{ $t('project.task_show_assigned_to') }}</p>
                <p v-if="localTask.assignee" class="ma0">{{ localTask.assignee.name }}</p>
                <p v-else class="ma0">{{ $t('project.task_show_no_assignee') }}</p>
              </div>

              <!-- time spent so far -->
              <div class="fl w-third br bb-gray pa3 bg-gray">
                <p class="mt0 mb2 f7 relative">
                  {{ $t('project.task_show_time_spent_so_far') }}
                  <a v-if="!displayTimeTrackingEntriesMode" class="absolute right-0 f7 bb b--dotted bt-0 bl-0 br-0 pointer" @click="showTimeTrackingEntries">{{ $t('project.task_show_time_spent_view_details') }}</a>
                  <a v-else class="absolute right-0 f7 bb b--dotted bt-0 bl-0 br-0 pointer" @click="hideTimeTrackingEntries">{{ $t('app.hide') }}</a>
                </p>
                <p class="ma0 duration">{{ totalDuration }}</p>
              </div>

              <!-- part of list -->
              <div class="fl w-third pa3 bg-gray stat-right-corner">
                <p class="mt0 mb2 f7">{{ $t('project.task_show_part_of_list') }}</p>
                <p v-if="task.list.name" class="ma0">{{ task.list.name }}</p>
                <p v-else class="ma0">{{ $t('project.task_show_no_list') }}</p>
              </div>
            </div>
          </div>

          <!-- task information - edit mode -->
          <div v-if="editMode" class="bg-white box mb3">
            <form @submit.prevent="update">
              <!-- task title + checkbox -->
              <div class="bb bb-gray pa3">
                <text-input :id="'title'"
                            :ref="'newName'"
                            v-model="form.title"
                            :name="'title'"
                            :datacy="'task-title-input'"
                            :errors="$page.props.errors.title"
                            :label="'Name of the task'"
                            :required="true"
                            @esc-key-pressed="editMode = false"
                />
              </div>

              <!-- task description -->
              <div class="bb bb-gray pa3 lh-copy">
                <text-area v-model="form.description"
                           :label="$t('account.company_news_new_content')"
                           :datacy="'news-content-textarea'"
                           :required="false"
                           :rows="10"
                />
              </div>

              <!-- information about the task -->
              <div class="cf bb bb-gray">
                <!-- assigned to -->
                <div class="fl w-50 br bb-gray pa3 bg-gray stat-left-corner">
                  <label class="db mb-2">
                    {{ $t('project.task_edit_assignee') }}
                  </label>
                  <a-select
                    v-model:value="form.assignee_id"
                    :placeholder="$t('app.choose')"
                    style="width: 200px"
                    :options="members"
                    show-search
                    option-filter-prop="label"
                  />
                </div>

                <!-- part of list -->
                <div class="fl w-50 pa3 bg-gray stat-right-corner">
                  <p v-if="lists.length == 0" class="ma0">{{ $t('project.task_show_no_list') }}</p>
                  <div v-else>
                    <label class="db mb-2">
                      {{ $t('project.task_show_part_of_list') }}
                    </label>
                    <a-select
                      v-model:value="form.task_list_id"
                      :placeholder="$t('app.choose_value')"
                      style="width: 200px"
                      :options="lists"
                      show-search
                      option-filter-prop="label"
                    />
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="cf pa3">
                <div class="flex-ns justify-between">
                  <div>
                    <inertia-link :href="''" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2" @click.prevent="editMode = false">
                      {{ $t('app.cancel') }}
                    </inertia-link>
                  </div>
                  <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.save')" />
                </div>
              </div>
            </form>
          </div>

          <!-- loading time tracking entries -->
          <div v-if="loadingTimeTrackingEntries" class="ba br3 bb-gray pa3 tc bg-white">
            <div class="di">
              <ball-clip-rotate color="#222" size="5px" />
            </div>
            {{ $t('project.task_show_fetching_info') }}
          </div>

          <!-- time tracking entries -->
          <div v-if="displayTimeTrackingEntriesMode && timeTrackingEntries.length != 0" class="ba br3 bb-gray bg-white mb3">
            <!-- list of existing entries -->
            <div v-for="entry in timeTrackingEntries" :key="entry.id" class="pa3 bb bb-gray bb-gray-hover relative time-tracking-item">
              <span class="f7 mr2">
                {{ entry.created_at }}
              </span>
              <span>{{ entry.employee.name }}</span>
              <span class="mr3 absolute right-0 duration">
                {{ entry.duration }}
              </span>
            </div>
          </div>

          <!-- no time tracking entries - blank state -->
          <div v-if="displayTimeTrackingEntriesMode && timeTrackingEntries.length == 0" class="ba br3 bb-gray bg-white pa3 mb3">
            {{ $t('project.task_show_no_time_tracking') }}
          </div>

          <!-- comments -->
          <comments
            :comments="localTask.comments"
            :post-url="`/${$page.props.auth.company.id}/company/projects/${project.id}/tasks/${localTask.id}/comments/`"
          />
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-30-l w-100 pl4-l">
          <!-- added by -->
          <h3 v-if="localTask.author" class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.task_show_added_by') }}
          </h3>

          <!-- information about the author -->
          <div v-if="localTask.author" class="flex mb4">
            <div class="mr2">
              <avatar :avatar="localTask.author.avatar" :size="35" :class="'br-100'" />
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
                {{ localTask.author.position }}
              </span>
            </div>
          </div>

          <!-- written on -->
          <h3 class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.task_show_created_on') }}
          </h3>
          <p class="mt0 mb4">{{ localTask.created_at }}</p>

          <!-- actions -->
          <h3 class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.message_show_actions') }}
          </h3>
          <ul class="list pl0 ma0 f6">
            <!-- edit -->
            <li class="mb2" @click="showEditMode"><span class="bb b--dotted bt-0 bl-0 br-0 pointer di f7">{{ $t('app.edit') }}</span></li>

            <!-- add time tracking entries -->
            <li v-if="!logTimeMode" class="mb2" @click="showLogTimeMode"><span class="bb b--dotted bt-0 bl-0 br-0 pointer di f7">{{ $t('project.task_show_action_log') }}</span></li>

            <div v-if="logTimeMode" class="bg-white box pa3">
              <form @submit.prevent="logTime">
                <div class="mb3">
                  <span class="lh-copy mb2 dib">
                    {{ $t('project.task_edit_time') }}
                  </span>
                  <text-duration
                    @update:model-value="updateDuration($event)"
                  />
                </div>

                <div class="flex-ns justify-between mb0">
                  <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" data-cy="cancel-add-description" @click="logTimeMode = false">
                    {{ $t('app.cancel') }}
                  </a>
                  <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.save')" />
                </div>
              </form>
            </div>

            <!-- delete -->
            <li v-if="!deleteMode" class="mb2" @click="deleteMode = true"><span class="bb b--dotted bt-0 bl-0 br-0 pointer di f7 c-delete">{{ $t('app.delete') }}</span></li>
            <li v-if="deleteMode" class="mb2">
              {{ $t('app.sure') }}
              <a class="c-delete mr1 pointer" @click.prevent="destroy">
                {{ $t('app.yes') }}
              </a>
              <a class="pointer" @click.prevent="deleteMode = false">
                {{ $t('app.no') }}
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import ProjectMenu from '@/Pages/Company/Project/Partials/ProjectMenu';
import BallClipRotate from 'vue-loaders/dist/loaders/ball-clip-rotate';
import TextInput from '@/Shared/TextInput';
import TextArea from '@/Shared/TextArea';
import LoadingButton from '@/Shared/LoadingButton';
import TextDuration from '@/Shared/TextDuration';
import Avatar from '@/Shared/Avatar';
import Comments from '@/Shared/Comments';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    ProjectMenu,
    'ball-clip-rotate': BallClipRotate.component,
    TextInput,
    TextArea,
    LoadingButton,
    TextDuration,
    Comments,
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
    members: {
      type: Array,
      default: null,
    },
    lists: {
      type: Array,
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
      displayTimeTrackingEntriesMode: false,
      loadingTimeTrackingEntries: false,
      timeTrackingEntries: [],
      editMode: false,
      deleteMode: false,
      logTimeMode: false,
      loadingState: null,
      totalDuration: 0,
      form: {
        assignee_id: null,
        title: null,
        description: null,
        task_list_id: null,
        duration: null,
        errors: [],
      },
    };
  },

  created() {
    this.localTask = this.task.task;
    this.form.title = this.localTask.title;
    this.form.description = this.localTask.description;
    this.form.title = this.localTask.title;
    this.totalDuration = this.task.total_duration;
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    hideTimeTrackingEntries() {
      this.displayTimeTrackingEntriesMode = false;
    },

    showEditMode() {
      this.editMode = true;
      this.hideTimeTrackingEntries = false;

      this.$nextTick(() => {
        this.$refs.newName.focus();
      });
    },

    showLogTimeMode() {
      this.logTimeMode = true;
      this.duration = 0;
    },

    toggle() {
      axios.put(this.localTask.url.toggle)
        .then(response => {
          this.flash(this.$t('project.task_show_status'), 'success');
          this.localTask.completed = !this.localTask.completed;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    showTimeTrackingEntries() {
      this.loadingTimeTrackingEntries = true;

      axios.get(this.localTask.url.entries)
        .then(response => {
          this.timeTrackingEntries = response.data.data;
          this.displayTimeTrackingEntriesMode = true;
          this.loadingTimeTrackingEntries = false;
        })
        .catch(error => {
          this.form.errors = error.response.data;
          this.loadingTimeTrackingEntries = false;
        });
      this.displayTimeTrackingEntriesMode = true;
    },

    update() {
      this.loadingState = 'loading';
      var newAssigneeName = null;

      if (this.form.assignee_id) {
        newAssigneeName = this.$refs.assignee.labelValue;
      }

      if (this.form.task_list_id == 0) {
        this.form.task_list_id = null;
      }

      axios.put(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/${this.localTask.id}`, this.form)
        .then(response => {
          this.localTask.title = this.form.title;
          this.localTask.description = this.form.description;

          if (newAssigneeName) {
            this.localTask.assignee.name = newAssigneeName;
          }

          this.loadingState = null;
          this.editMode = false;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroy() {
      axios.delete(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/${this.localTask.id}`)
        .then(response => {
          localStorage.success = this.$t('account.employee_lock_success');
          this.$inertia.visit(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks`);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    updateDuration(payload) {
      this.form.duration = parseInt(payload);
    },

    logTime() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/${this.localTask.id}/log`, this.form)
        .then(response => {
          // if the log of time tracking entries was opened, refresh the table so it shows the latest entry that was just saved
          if (this.displayTimeTrackingEntriesMode) {
            this.showTimeTrackingEntries();
          }

          this.totalDuration = response.data.data;
          this.logTimeMode = false;
          this.loadingState = null;
        })
        .catch(error => {
          this.form.errors = error.response.data;
          this.loadingState = null;
        });
    }
  }
};

</script>
