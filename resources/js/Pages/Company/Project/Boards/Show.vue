<style lang="scss" scoped>
.grid {
  display: grid;
}
.column-gap-10 {
  column-gap: 10px;
}
.cycle {
  grid-template-columns: 200px 3fr;
}

@media (max-width: 480px) {
  .cycle {
    grid-template-columns: 1fr;
  }
}

.icon-type {
  display: inline-block;
  top: 0;
  border-radius: 3px;
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
  color: #172b4d;
  height: 16px;
  max-height: 16px;
  min-width: 12px;
  padding-left: 7px;
  padding-right: 7px;
}

.issue-list {
  div:last-child {
    border-bottom: 0;
  }

  div:first-child:hover {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  div:last-child {
    border-bottom: 0;

    &:hover {
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }
  }
}

.flex-grow {
  flex-grow: 1
}

::v-deep .modal-container {
  display: flex;
  justify-content: center;
  align-items: center;
}
::v-deep .modal-content {
  display: flex;
  flex-direction: column;
  box-shadow: rgb(0 0 0 / 50%) 0px 16px 70px;
  margin: 0 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  background: #fff;
  width: 650px;
}
::v-deep .overlay {
  background-color: rgb(193 193 193 / 50%);
}
::v-deep .modal-body {
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

::v-deep .modal-footer {
  text-align: right;
  padding: 8px 13px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb :has-more="true"
                  :previous-url="route('projects.index', { company: $page.props.auth.company.id})"
                  :previous="'Project name'"
                  :custom-class="'mt0-l'"
                  :center-box="false"
                  :custom-margin-top="'mt1'"
                  :custom-margin-bottom="'mb3'"
      >
        Board name
      </breadcrumb>

      <!-- BODY -->
      <div class="grid column-gap-10 grid-cols-2 br3 mb5 relative z-1 cycle">
        <!-- left column -->
        <div>
          <ul class="list pl0 ma0">
            <li class="mb3"><inertia-link>Summary</inertia-link></li>
            <li class="mb3"><inertia-link>Backlog</inertia-link></li>
            <li class="mb3"><inertia-link>Active cycles</inertia-link></li>
            <li class="mb3"><inertia-link>Past cycles</inertia-link></li>
            <li class="mb3"><inertia-link>Refinement</inertia-link></li>
            <li class="mb3"><inertia-link>My issues</inertia-link></li>
            <li class="mb3"><inertia-link>My views</inertia-link></li>
          </ul>
        </div>

        <!-- new issue -->
        <vue-final-modal v-model="showModal" overlay-class="overlay" classes="modal-container" content-class="modal-content">
          <div class="modal-body relative bb bb-gray">
            <div class="mb3">
              <span class="f7">
                <span class="board-name">
                  Board name
                </span> › New issue
              </span>
            </div>

            <div class="absolute close-icon pointer">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" @click="showModal = false">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>

            <text-input :ref="'newIssueType'"
                        v-model="form.name"
                        :required="true"
                        :errors="$page.props.errors.name"
                        :extra-class-upper-div="'mb3 flex-grow'"
                        @esc-key-pressed="modal = false"
            />

            <text-area v-model="form.content"
                       :datacy="'news-content-textarea'"
                       :required="true"
                       :rows="10"
                       :help="$t('account.company_news_new_content_help')"
            />
          </div>
          <div class="modal-footer">
            <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.save')" />
          </div>
        </vue-final-modal>

        <!-- right column -->
        <div>
          <!-- cycle -->
          <div class="flex justify-between items-center">
            <h3 class="normal mt0 mb2 f5">
              Backlog
            </h3>

            <div class="mb2">
              <span class="story-points">
                32
              </span>
            </div>
          </div>
          <div class="bg-white box issue-list">
            <div class="bb bb-gray bb-gray-hover pa3 relative flex-ns justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
              </div>
            </div>
            <div class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
              </div>
            </div>

            <!-- separator -->
            <div class="bb bb-gray bb-gray-hover ph3 pv1 tc relative bg-light-gray ttu f7">
              dsfs
            </div>

            <div class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
              </div>
            </div>
            <div class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
              </div>
            </div>
            <div class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
              </div>
            </div>
            <div class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
              </div>
            </div>

            <!-- new -->
            <div class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <text-input :ref="'newIssueType'"
                          v-model="form.name"
                          :required="true"
                          :errors="$page.props.errors.name"
                          :extra-class-upper-div="'mb0 mr2 flex-grow'"
                          @esc-key-pressed="modal = false"
              />
              <div class="">
                <a class="btn dib-l db mb2 mb0-ns mr2-ns" @click.prevent="modal = false">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.add')" />
              </div>
            </div>
          </div>
          <ul class="list pl0 mt1 mb4">
            <li class="di mr3"><span class="f7 pointer b--dotted bb bt-0 br-0 bl-0" @click="showModal = true">+ New issue</span></li>
            <li class="di mr2"><span class="f7 pointer b--dotted bb bt-0 br-0 bl-0">+ New separator</span></li>
          </ul>

          <!-- cycle -->
          <div class="flex justify-between items-center">
            <h3 class="normal mt0 mb2 f5">
              Backlog
            </h3>

            <div class="mb2">
              <span class="story-points">
                32
              </span>
            </div>
          </div>
          <div class="bg-white box issue-list">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.project_management_blank') }}
            </p>

            <img loading="lazy" class="db center mb4" alt="team" src="/img/streamline-icon-extrinsic-drive-5@100x100.png" />
          </div>

          <!-- cycle -->
          <div class="flex justify-between items-center">
            <h3 class="normal mt0 mb2 f5">
              Backlog
            </h3>
            <div class="mb2">
              <span class="story-points">
                32
              </span>
            </div>
          </div>
          <div class="bg-white box issue-list">
            <div class="bb bb-gray bb-gray-hover pa3 relative flex-ns justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
              </div>
            </div>
            <div class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
              </div>
            </div>
            <div class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
              </div>
            </div>
            <div class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
              </div>
            </div>
            <div class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
              </div>
            </div>
            <div class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                <!-- key -->
                <span class="f7 project-key mr2 code">
                  OFF-12
                </span>

                <!-- title -->
                <span>add ability to manage date based flows</span>
              </div>

              <div>
                <!-- date -->
                <span class="f7 code mr2">
                  Oct 29
                </span>

                <!-- points -->
                <span class="story-points">
                  3
                </span>
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';
import TextArea from '@/Shared/TextArea';
import { $vfm, VueFinalModal } from 'vue-final-modal';

export default {
  components: {
    Layout,
    Breadcrumb,
    LoadingButton,
    TextInput,
    TextArea,
    VueFinalModal
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
      loadingState: '',
      localBoards: [],
      showModal: false,
      form: {
        name: null,
        errors: [],
      },
    };
  },

  mounted() {
    this.localBoards =  this.data.boards;

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    displayAddModal() {
      this.showAddModal = true;
      this.form.name = '';
      this.form.errors = null;

      this.$nextTick(() => {
        this.$refs.newBoard.focus();
      });
    },

    hideAddModal() {
      this.showAddModal = false;
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.data.url.store, this.form)
        .then(response => {
          this.flash(this.$t('project.board_cta_success'), 'success');

          this.loadingState = null;
          this.hideAddModal();
          this.localBoards.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
