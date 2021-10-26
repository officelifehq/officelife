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

        <!-- right column -->
        <div>
          <!-- cycle -->
          <div class="flex justify-between items-center">
            <h3 class="normal mb2 f5">
              Backlog
            </h3>

            <div>
              <span class="story-points">
                32
              </span>
            </div>
          </div>
          <div class="bg-white box issue-list">
            <div class="bb bb-gray bb-gray-hover pa3 relative flex-ns justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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
            <li class="di mr3"><span class="f7 pointer b--dotted bb bt-0 br-0 bl-0">+ New issue</span></li>
            <li class="di mr2"><span class="f7 pointer b--dotted bb bt-0 br-0 bl-0">+ New separator</span></li>
          </ul>

          <!-- cycle -->
          <div class="flex justify-between items-center">
            <h3 class="normal mb2 f5">
              Backlog
            </h3>
            <div>
              <span class="story-points">
                32
              </span>
            </div>
          </div>
          <div class="bg-white box issue-list">
            <div class="bb bb-gray bb-gray-hover pa3 relative flex-ns justify-between items-center">
              <div>
                <!-- issue type -->
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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
                <span class="relative icon-type mr2" style="width: 13px; height: 13px; background-color: rgb(86, 82, 179);"></span>

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

export default {
  components: {
    Layout,
    Breadcrumb,
    LoadingButton,
    TextInput,
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
      showAddModal: false,
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
