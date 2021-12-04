<style lang="scss" scoped>
.icon-type {
  display: inline-block;
  top: 0;
  border-radius: 3px;
}

.icon-metrics {
  width: 16px;
  top: 4px;
}

.button-metrics {
  padding: 2px 4px;
  border-radius: 5px;
}

.story-points {
  font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen,Ubuntu,'Fira Sans','Droid Sans','Helvetica Neue',sans-serif;
  color: #5e6c84;
  border-radius: 2em;
  padding: 3px 5px 2px 5px;
  font-size: 12px;
  font-weight: 600;
  line-height: 16px;
  background-color: #dfe1e6;
  height: 16px;
  max-height: 16px;
  min-width: 12px;
  padding-left: 7px;
  padding-right: 7px;
}

.issue-list {
  .issue-list-item:last-child {
    border-bottom: 0;
  }

  .issue-list-item:first-child:hover {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  .issue-list-item:last-child {
    border-bottom: 0;

    &:hover {
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }
  }

  .issue-list-item-separator {
    &:last-child {
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }

    &:first-child {
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }
  }
}

.flex-grow {
  flex-grow: 1
}

::v-deep(.modal-container) {
  display: flex;
  justify-content: center;
  align-items: center;
}
::v-deep(.modal-content) {
  display: flex;
  flex-direction: column;
  box-shadow: rgb(0 0 0 / 50%) 0px 16px 70px;
  margin: 0 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  background: #fff;
  width: 650px;
}
::v-deep(.overlay) {
  background-color: rgb(193 193 193 / 50%);
}
::v-deep(.modal-body) {
  padding: 10px 12px 12px 12px;

  .board-name {
    background-color: #f5f7f8;
    padding: 3px 8px;
    color: #9f9f9f;
    border-radius: 4px;
  }

  .close-icon {
    top: 11px;
    right: 13px;

    svg {
      width: 15px;
    }
  }
}

::v-deep(.modal-footer) {
  text-align: right;
  padding: 8px 13px;
}

.dot {
  height: 11px;
  width: 11px;
  top: 4px;
  background-color: #56bb76;
}

.icon-separator {
  width: 12px;
  top: 5px;
  right: 8px;
}

.handle-icon {
  width: 16px;
  left: -9px;
  top: 3px;
}

.icon-sprint {
  width: 14px;
  top: 2px;
}
</style>

<template>
  <div>
    <!-- new issue -->
    <vue-final-modal v-model="showModal" overlay-class="overlay" classes="modal-container" content-class="modal-content">
      <form @submit.prevent="submit">
        <div class="modal-body relative bb bb-gray">
          <div class="mb3">
            <span class="f7">
              <span class="board-name mr1">
                {{ board.name }}
              </span> <span class="mr1">
                ›
              </span> New issue
            </span>
          </div>

          <div class="absolute close-icon pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" @click="showModal = false">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>

          <text-input :ref="'newIssue'"
                      v-model="form.title"
                      :required="true"
                      :errors="$page.props.errors.title"
                      :extra-class-upper-div="'mb3 flex-grow'"
                      @esc-key-pressed="hideAddModal"
          />

          <text-area v-model="form.description"
                     :required="false"
                     :rows="6"
                     :placeholder="'Add description'"
                     @esc-key-pressed="hideAddModal"
          />
        </div>
        <div class="modal-footer">
          <errors :errors="errors" />

          <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.save')" />
        </div>
      </form>
    </vue-final-modal>

    <div :class="collapsed ? 'mb3' : ''">
      <!-- cycle -->
      <div class="flex justify-between items-center">
        <!-- cycle name and actions -->
        <h3 class="fw6 mt0 mb2 f5 relative">
          <!-- arrow right -->
          <svg v-if="collapsed" xmlns="http://www.w3.org/2000/svg" class="icon-sprint inline mr1 relative pointer" fill="none" viewBox="0 0 24 24"
               stroke="currentColor" @click="toggle"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>

          <!-- arrow down -->
          <svg v-if="!collapsed" xmlns="http://www.w3.org/2000/svg" class="icon-sprint inline mr1 relative pointer" fill="none" viewBox="0 0 24 24"
               stroke="currentColor" @click="toggle"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>

          <span v-if="sprint.active" class="dib dot br-100 relative mr1">
&nbsp;
          </span>

          {{ sprint.name }}
        </h3>

        <!-- actions for cycles not being the backlog -->
        <div v-if="!sprint.is_board_backlog" class="mb2">
          <!-- start cycle -->
          <span class="pointer mr2 relative ba bb-gray f7 button-metrics">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon-metrics relative" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Start cycle
          </span>

          <!-- view metrics -->
          <span class="pointer mr2 relative ba bb-gray f7 button-metrics">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon-metrics relative" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            View metrics
          </span>
          <span class="story-points">
            32
          </span>
        </div>
      </div>

      <!-- issue list -->
      <div v-if="!collapsed && localIssues.length != 0" class="bg-white box issue-list">
        <draggable
          :list="localIssues"
          item-key="id"
          :component-data="{name:'fade'}"
          handle=".handle"
          @change="updatePosition"
        >
          <template #item="{ element }">
            <div v-if="!element.is_separator" class="bb bb-gray bb-gray-hover issue-list-item">
              <div class="pa3 relative flex-ns justify-between items-center">
                <div>
                  <!-- handle -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="relative cursor-grab handle handle-icon inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                  </svg>

                  <!-- issue type -->
                  <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                  <!-- key -->
                  <inertia-link :href="element.url.show" class="f7 project-key mr2 code">
                    {{ element.key }}
                  </inertia-link>

                  <!-- title -->
                  <span>{{ element.title }}</span>
                </div>

                <div>
                  <!-- date -->
                  <span class="f7 code mr2">
                    {{ element.created_at }}
                  </span>

                  <!-- points -->
                  <span v-if="element.story_points" class="story-points">
                    {{ element.story_points }}
                  </span>
                  <span v-else class="story-points">
                    -
                  </span>
                </div>
              </div>
            </div>

            <div v-else class="ph3 pv1 tc relative bg-light-gray ttu f7 handle issue-list-item-separator">
              <!-- separator -->
              <span class="cursor-grab">
                {{ element.title }}
              </span>

              <svg xmlns="http://www.w3.org/2000/svg" class="pointer absolute icon-separator" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                   @click="destroySeparator(element)"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
          </template>
        </draggable>

        <!-- Add separator modal -->
        <div v-if="showSeparatorModal" class="bb bb-gray pa3 relative">
          <form class="flex justify-between items-center" @submit.prevent="submitSeparator">
            <text-input :ref="'newIssueSeparator'"
                        v-model="form.title"
                        :required="true"
                        :errors="$page.props.errors.title"
                        :extra-class-upper-div="'mb0 mr2 flex-grow'"
                        @esc-key-pressed="modal = false"
            />
            <div class="">
              <a class="btn dib-l db mb2 mb0-ns mr2-ns" @click.prevent="hideAddSeparatorModal">
                {{ $t('app.cancel') }}
              </a>
              <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.add')" />
            </div>
          </form>
        </div>
      </div>

      <!-- blank state -->
      <div v-if="localIssues.length == 0" class="bg-white box issue-list">
        <p class="tc measure center mb4 lh-copy">
          {{ $t('project.sprint_blank_state') }}
        </p>

        <img loading="lazy" class="db center mb4" alt="team" src="/img/streamline-icon-extrinsic-drive-5@100x100.png" />
      </div>

      <!-- cycle actions -->
      <ul v-if="!collapsed" class="list pl0 mt1 mb4">
        <li class="di mr3"><span class="f7 pointer b--dotted bb bt-0 br-0 bl-0" @click="displayAddModal">+ New issue</span></li>
        <li v-if="!showSeparatorModal" class="di mr2"><span class="f7 pointer b--dotted bb bt-0 br-0 bl-0" @click="displayAddSeparatorModal">+ New separator</span></li>
      </ul>
    </div>
  </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';
import Errors from '@/Shared/Errors';
import { $vfm, VueFinalModal } from 'vue-final-modal';
import draggable from 'vuedraggable';

export default {
  components: {
    LoadingButton,
    TextInput,
    TextArea,
    Errors,
    VueFinalModal,
    draggable,
  },

  props: {
    board: {
      type: Object,
      default: null,
    },
    sprint: {
      type: Object,
      default: null,
    },
    issueTypes: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      loadingState: '',
      localIssues: [],
      errors: null,
      collapsed: false,
      drag: false,
      showModal: false,
      showSeparatorModal: false,
      form: {
        title: null,
        description: null,
        is_separator: false,
        position: 0,
      },
    };
  },

  mounted() {
    this.localIssues = this.sprint.issues;
    this.collapsed = this.sprint.collapsed;
  },

  methods: {
    displayAddModal() {
      this.showModal = true;
      this.form.title = null;
      this.form.description = null;
      this.errors = null;

      this.$nextTick(() => {
        this.$refs.newIssue.focus();
      });
    },

    displayAddSeparatorModal() {
      this.showSeparatorModal = true;
      this.form.title = null;
      this.errors = null;

      this.$nextTick(() => {
        this.$refs.newIssueSeparator.focus();
      });
    },

    hideAddModal() {
      this.showModal = false;
    },

    hideAddSeparatorModal() {
      this.showSeparatorModal = false;
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.sprint.url.store, this.form)
        .then(response => {
          this.flash(this.$t('project.issue_created'), 'success');

          this.loadingState = null;
          this.hideAddModal();

          this.localIssues.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.errors = error.response.data;
        });
    },

    submitSeparator() {
      this.loadingState = 'loading';
      this.form.is_separator = true;

      axios.post(this.sprint.url.store, this.form)
        .then(response => {
          this.flash(this.$t('project.separator_created'), 'success');

          this.loadingState = null;
          this.hideAddSeparatorModal();
          this.form.is_separator = false;

          this.localIssues.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.errors = error.response.data;
          this.form.is_separator = false;
        });
    },

    destroySeparator(issue) {
      axios.delete(issue.url.destroy)
        .then(response => {
          this.flash(this.$t('project.separator_destroyed'), 'success');
          var id = this.localIssues.findIndex(x => x.id === issue.id);
          this.localIssues.splice(id, 1);
        })
        .catch(error => {
          this.loadingState = null;
          this.errors = error.response.data;
        });
    },

    updatePosition(event) {
      // the event object comes from the draggable component
      this.form.position = event.moved.newIndex + 1;

      axios.post(event.moved.element.url.reorder, this.form)
        .then(response => {
        })
        .catch(error => {
          this.loadingState = null;
          this.errors = error.response.data;
        });
    },

    toggle() {
      axios.post(this.sprint.url.toggle)
        .then(response => {
          this.collapsed = !this.collapsed;
        })
        .catch(error => {
          this.loadingState = null;
          this.errors = error.response.data;
        });
    }
  }
};

</script>
