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
          <div class="bg-white box">
            <!-- task title + checkbox -->
            <div class="bb bb-gray">
              <div class="pa3 f4">
                <input
                  :id="id"
                  v-model="localTask.completed"
                  :data-cy="datacy + '-single-item'"
                  type="checkbox"
                  class="relative"
                  :class="classes"
                  :required="required"
                  :name="name"
                  @click.prevent="toggle()"
                />

                {{ localTask.title }}
              </div>
            </div>

            <!-- information about the task -->
            <div class="cf">
              <div class="fl w-third br bb-gray pa3 bg-gray">
                <p class="mt0 mb2 f7">Assigned to</p>
                <p class="ma0">{{ localTask.assignee.name }}</p>
              </div>

              <div class="fl w-third br bb-gray pa3 bg-gray">
                <p class="mt0 mb2 f7">Time spent so far</p>
                <p class="ma0">{{ data.total_duration }}</p>
              </div>

              <div class="fl w-third pa3 bg-gray">
                <p class="mt0 mb2 f7">Part of</p>
                <p class="ma0">{{ data.list.name }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-30-l w-100 pl4-l">
          <!-- written by -->
          <h3 v-if="localTask.author" class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.message_show_written_by') }}
          </h3>

          <div v-if="localTask.author" class="flex items-center mb4">
            <div class="mr3">
              <img :src="localTask.author.avatar" alt="avatar" height="64" width="64" class="br-100" />
            </div>

            <div>
              <inertia-link :href="localTask.author.url" class="mb2 dib">{{ localTask.author.name }}</inertia-link>

              <span v-if="localTask.author.role" class="db f7 mb2 relative">

                <ul class="list pa0 ma0">
                  <li class="di">
                    <!-- role -->
                    <svg class="relative icon-role gray" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span class="mr2">
                      {{ localTask.author.role }}
                    </span>
                  </li>

                  <li>
                    <!-- in the project since -->
                    <span>
                      <svg class="relative icon-date gray" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                      </svg>
                    </span>
                    <span class="gray">
                      {{ $t('project.members_index_role', { date: localTask.author.added_at }) }}
                    </span>
                  </li>
                </ul>
              </span>
              <span v-if="localTask.author.position && localTask.author.role" class="db f7 gray">
                {{ $t('project.members_index_position_with_role', { role: localTask.author.position.title }) }}
              </span>
              <span v-if="localTask.author.position && !localTask.author.role" class="db f7 gray">
                {{ $t('project.members_index_position', { role: localTask.author.position.title }) }}
              </span>
            </div>
          </div>

          <!-- written on -->
          <!-- <h3 class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.message_show_written_on') }}
          </h3>
          <p class="mt0 mb4">{{ message.written_at }} <span class="f6 gray">({{ message.written_at_human }})</span></p> -->

          <!-- actions -->
          <!-- <h3 class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.message_show_actions') }}
          </h3>
          <ul class="list pl0 ma0"> -->
          <!-- edit -->
          <!-- <li class="mb2"><inertia-link :href="message.url_edit" data-cy="project-edit" class="f6 gray">{{ $t('project.message_show_edit') }}</inertia-link></li> -->

          <!-- delete -->
          <!-- <li v-if="!removalConfirmation"><a href="#" data-cy="project-delete" class="f6 gray" @click.prevent="removalConfirmation = true">{{ $t('project.message_show_destroy') }}</a></li>
            <li v-if="removalConfirmation" class="pv2 f6">
              {{ $t('app.sure') }}
              <a data-cy="confirm-project-deletion" class="c-delete mr1 pointer" @click.prevent="destroy()">
                {{ $t('app.yes') }}
              </a>
              <a data-cy="cancel-project-deletion" class="pointer" @click.prevent="removalConfirmation = false">
                {{ $t('app.no') }}
              </a>
            </li>
          </ul> -->
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import ProjectMenu from '@/Pages/Company/Project/Partials/ProjectMenu';

export default {
  components: {
    Layout,
    ProjectMenu,
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
    data: {
      type: Array,
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
    };
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }

    this.localTask = this.data.task;
  },

  methods: {
    toggle() {
      axios.put(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/${this.localTask.id}/toggle`)
        .then(response => {
          this.localTask.completed = !this.localTask.completed;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
