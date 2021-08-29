<style lang="scss" scoped>
.product-key {
  .code {
    font-family: Menlo,Consolas,monospace;
  }
}

.add-section {
  background-color: #EBF4FF;
}

.option {
  background-color: #06B6D4;
  width: 24px;
  color: #fff;
  height: 24px;
  padding: 3px 10px 5px 8px;
}

.seats-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.seats-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}

.files-list {
  li:last-child {
    border-bottom: 0;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/softwares'"
                  :previous="$t('app.breadcrumb_account_manage_softwares')"
      >
        {{ $t('app.breadcrumb_account_show_software') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="mt5">
          <h2 class="pa3 normal ma0 mb2" data-cy="item-name">
            {{ software.name }}
          </h2>

          <ul class="list pb3 pr3 pl3 mb2 mt0 f6">
            <li class="di mr3"><inertia-link :href="'/' + $page.props.auth.company.id + '/account/softwares/' + software.id + '/edit'">{{ $t('app.edit') }}</inertia-link></li>

            <!-- DELETE AN ITEM -->
            <li v-if="deleteSoftware" class="di">
              {{ $t('app.sure') }}
              <a class="c-delete mr1 pointer" @click.prevent="destroy()">
                {{ $t('app.yes') }}
              </a>
              <a class="pointer" @click.prevent="deleteSoftware = false">
                {{ $t('app.no') }}
              </a>
            </li>
            <li v-else class="di">
              <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click.prevent="deleteSoftware = true">
                {{ $t('app.delete') }}
              </a>
            </li>
          </ul>

          <div class="product-key pa3">
            <p class="mb2 mt0 f6 fw5"><span class="mr1">🗝</span> {{ $t('account.software_show_product_key') }}</p>
            <div class="code br3 ba bb-gray pa3">
              {{ software.product_key }}
            </div>
          </div>

          <div class="pa3">
            <p class="mb2 f6 fw5"><span class="mr1">👨‍🎓</span> {{ $t('account.software_show_purchase_information') }}</p>
            <div>
              {{ $t('account.software_show_licensed_to') }} {{ software.licensed_to_name }} <span v-if="software.licensed_to_email_address">
                ({{ software.licensed_to_email_address }})
              </span>
            </div>
          </div>

          <div v-if="software.purchase_amount" class="pa3">
            <p class="mb1 f6 fw5"><span class="mr1">💰</span> {{ $t('account.software_show_price') }}</p>
            <div class="">
              {{ software.currency }} {{ software.purchase_amount }} <span v-if="software.exchange_rate">
                ({{ software.converted_to_currency }} {{ software.converted_purchase_amount }} — {{ $t('account.software_show_exchange_rate') }} {{ software.exchange_rate }})
              </span>
            </div>
          </div>

          <div v-if="software.purchased_at" class="pa3">
            <p class="mb1 f6 fw5"><span class="mr1">📆</span> {{ $t('account.software_show_purchase_date') }}</p>
            <div>{{ software.purchased_at }}</div>
          </div>

          <div v-if="software.website" class="pa3">
            <p class="mb1 f6 fw5"><span class="mr1">🔗</span> {{ $t('account.software_show_website') }}</p>
            <div>{{ software.website }}</div>
          </div>

          <div v-if="software.order_number" class="pa3">
            <p class="mb1 f6 fw5"><span class="mr1">🐝</span> {{ $t('account.software_show_order_number') }}</p>
            <div>{{ software.order_number }}</div>
          </div>

          <!-- files -->
          <div class="pa3">
            <div class="flex justify-between items-center mb1 fw5">
              <div class="f6">
                <span class="mr1">
                  🗄
                </span> {{ $t('account.software_show_files') }}
              </div>

              <uploadcare :public-key="uploadcarePublicKey"
                          :tabs="'file'"
                          :preview-step="false"
                          @success="onSuccess"
                          @error="onError"
              >
                <button class="btn">
                  {{ $t('app.upload') }}
                </button>
              </uploadcare>
            </div>

            <!-- list of files -->
            <ul v-if="localFiles.length > 0" class="list ma0 pa0 ba bb-gray files-list br3">
              <li v-for="file in localFiles" :key="file.id" class="bb bb-gray di pa3 flex justify-between bb bb-gray">
                <!-- filename -->
                <span><a :href="file.download_url" :download="file.download_url">{{ file.filename }}</a>
                  <ul class="f6 mt2 pa0 list">
                    <li class="di mr2">{{ file.size }}</li>

                    <!-- DELETE A FILE -->
                    <li v-if="fileIdToDelete == file.id" class="di">
                      {{ $t('app.sure') }}
                      <a class="c-delete mr1 pointer" @click.prevent="destroyFile(file.id)">
                        {{ $t('app.yes') }}
                      </a>
                      <a class="pointer" @click.prevent="fileIdToDelete = 0">
                        {{ $t('app.no') }}
                      </a>
                    </li>
                    <li v-else class="di">
                      <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click.prevent="fileIdToDelete = file.id">
                        {{ $t('app.delete') }}
                      </a>
                    </li>
                  </ul>
                </span>

                <div class="">
                  <!-- date -->
                  <span class="db mb2 f6 gray">{{ file.created_at }}</span>

                  <!-- uploader info -->
                  <span>
                    <small-name-and-avatar
                      v-if="file.uploader.name"
                      :name="file.uploader.name"
                      :avatar="file.uploader.avatar"
                      :class="'gray'"
                      :size="'18px'"
                      :top="'0px'"
                      :margin-between-name-avatar="'25px'"
                    />
                    <span v-else>{{ file.uploader }}</span>
                  </span>
                </div>
              </li>
            </ul>

            <!-- files blank state -->
            <p v-if="localFiles.length == 0" class="lh-copy gray mt0">{{ $t('account.software_show_files_blank') }}</p>
          </div>

          <!-- seats -->
          <div class="pa3">
            <div class="flex justify-between items-center mb1 fw5">
              <div class="f6">
                <span class="mr1">
                  🪑
                </span> {{ $t('account.software_show_seats') }}
              </div>

              <a v-if="!assignSeatMode" href="#" class="btn" @click.prevent="setAssignMode()">
                {{ $t('account.software_show_use_seat') }}
              </a>
              <a v-if="assignSeatMode" href="#" class="btn" @click.prevent="hideAssignMode()">
                {{ $t('app.cancel') }}
              </a>
            </div>

            <!-- modal to use a seat -->
            <div v-if="assignSeatMode" class="add-section pa3 br3 mb3">
              <p v-if="!searchMode && !giveSeatToEveryOneMode" class="fw5 mt0">{{ $t('account.software_show_two_options') }}</p>
              <div v-if="!searchMode && !giveSeatToEveryOneMode" class="flex justify-between">
                <div class="w-50 mr4 flex items-start">
                  <p class="ma0 option br-100 mr2">a</p>
                  <div>
                    <div class="lh-copy mb3">
                      {{ $t('account.software_show_option_a', {count: employeesWithoutSofware}) }}
                    </div>
                    <a class="dib btn" href="#" @click.prevent="showGiveSeatToEveryOne()">{{ $t('account.software_show_start') }}</a>
                  </div>
                </div>
                <div class="w-50 flex items-start">
                  <p class="ma0 option br-100 mr2">b</p>
                  <div>
                    <div class="mb3">
                      {{ $t('account.software_show_give_seat_specific') }}
                    </div>
                    <a class="dib btn" href="#" @click.prevent="showSearch()">{{ $t('app.choose') }}</a>
                  </div>
                </div>
              </div>

              <!-- option a: give a seat to every employee -->
              <div v-if="giveSeatToEveryOneMode">
                <form class="relative" @submit.prevent="assignAll()">
                  <p class="lh-copy mt0">{{ $tc('account.software_show_confirm', employeesWithoutSofware, {count: employeesWithoutSofware}) }}</p>

                  <!-- note in case we don't have enough remaining seats -->
                  <div v-if="employeesWithoutSofware > software.remaining_seats" class="flex items-center">
                    <span class="mr2">
                      ⚠️
                    </span>
                    <p class="ma0 lh-copy">{{ $t('account.software_show_not_enough_seats') }}</p>
                  </div>

                  <div class="w-100 tr">
                    <a class="btn dib-l db mb2 mb0-ns mr2" @click.prevent="hideAssignMode()">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </div>

              <!-- option b: give a seat to a specific employee -->
              <div v-if="searchMode">
                <form class="relative" @submit.prevent="search">
                  <text-input :id="'name'"
                              :ref="'search'"
                              v-model="form.searchTerm"
                              :name="'name'"
                              :errors="$page.props.errors.name"
                              :label="$t('account.software_show_who')"
                              :required="true"
                              @keyup="search"
                              @input="search"
                              @esc-key-pressed="hideSearch()"
                  />
                  <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
                </form>

                <!-- search results -->
                <div class="pl0 list ma0">
                  <ul v-if="potentialEmployees.length > 0" class="list ma0 pl0">
                    <li v-for="employee in potentialEmployees" :key="employee.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
                      {{ employee.name }}

                      <a class="absolute right-1 pointer" @click.prevent="attach(employee)">
                        {{ $t('app.choose') }}
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="mb2">
              {{ $t('account.software_show_stat', {localUsedSeats: localUsedSeats, seats: software.seats }) }}
            </div>

            <!-- list of employees -->
            <ul v-if="localEmployees.length > 0" class="mb3 list pl0 mv0 center ba br2 bb-gray">
              <li v-for="employee in localEmployees" :key="employee.id" class="pv3 ph2 bb bb-gray bb-gray-hover seats-item flex justify-between">
                <small-name-and-avatar
                  :name="employee.name"
                  :avatar="employee.avatar"
                  :class="'f4 fw4'"
                  :top="'0px'"
                  :margin-between-name-avatar="'29px'"
                />

                <!-- remove employee -->
                <div class="f7">
                  <div v-if="idToDelete == employee.id">
                    {{ $t('app.sure') }}
                    <a class="c-delete mr1 pointer" @click.prevent="detach(employee)">
                      {{ $t('app.yes') }}
                    </a>
                    <a class="pointer" @click.prevent="idToDelete = 0">
                      {{ $t('app.no') }}
                    </a>
                  </div>
                  <div v-else>
                    <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click.prevent="idToDelete = employee.id">{{ $t('app.delete') }}</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>

          <p v-if="software.serial_number" class="relative mb3" data-cy="item-serial-number">
            <svg class="relative mr1" style="top: 3px;" width="15" height="15" viewBox="0 0 15 15"
                 fill="none" xmlns="http://www.w3.org/2000/svg"
            >
              <path d="M3.88889 11.1111H3.89611M3.88889 14C3.12271 14 2.38791 13.6956 1.84614 13.1539C1.30436 12.6121 1 11.8773 1 11.1111V2.44444C1 2.06135 1.15218 1.69395 1.42307 1.42307C1.69395 1.15218 2.06135 1 2.44444 1H5.33333C5.71642 1 6.08382 1.15218 6.35471 1.42307C6.6256 1.69395 6.77778 2.06135 6.77778 2.44444V11.1111C6.77778 11.8773 6.47341 12.6121 5.93164 13.1539C5.38987 13.6956 4.65507 14 3.88889 14V14ZM3.88889 14H12.5556C12.9386 14 13.306 13.8478 13.5769 13.5769C13.8478 13.306 14 12.9386 14 12.5556V9.66667C14 9.28358 13.8478 8.91618 13.5769 8.64529C13.306 8.3744 12.9386 8.22222 12.5556 8.22222H10.8634L3.88889 14ZM6.77778 4.13661L7.9745 2.93989C8.24537 2.6691 8.61271 2.51698 8.99572 2.51698C9.37874 2.51698 9.74607 2.6691 10.0169 2.93989L12.0601 4.98306C12.3309 5.25393 12.483 5.62126 12.483 6.00428C12.483 6.38729 12.3309 6.75463 12.0601 7.0255L5.93133 13.1536L6.77778 4.13661Z" stroke="#C9C4C4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ software.serial_number }}
          </p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import TextInput from '@/Shared/TextInput';
import 'vue-loaders/dist/vue-loaders.css';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';
import LoadingButton from '@/Shared/LoadingButton';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import Uploadcare from 'uploadcare-vue/src/Uploadcare.vue';

export default {
  components: {
    Layout,
    Breadcrumb,
    SmallNameAndAvatar,
    TextInput,
    'ball-pulse-loader': BallPulseLoader.component,
    LoadingButton,
    Uploadcare,
  },

  props: {
    software: {
      type: Object,
      default: null,
    },
    employees: {
      type: Array,
      default: null,
    },
    paginator: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    files: {
      type: Array,
      default: null,
    },
    uploadcarePublicKey: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      form: {
        searchTerm: null,
        employeeId: 0,
        uuid: null,
        name: null,
        original_url: null,
        cdn_url: null,
        mime_type: null,
        size: null,
        errors: [],
      },
      potentialEmployees: [],
      processingSearch: false,
      loadingState: '',
      errorTemplate: Error,
      localEmployees: [],
      localUsedSeats: 0,
      employeesWithoutSofware: 0,
      assignSeatMode: false,
      searchMode: false,
      giveSeatToEveryOneMode: false,
      idToDelete: 0,
      deleteSoftware: false,
      localFiles: null,
      fileIdToDelete: 0,
    };
  },

  created() {
    this.localFiles = this.files;
  },

  mounted() {
    this.localEmployees = this.employees;
    this.localUsedSeats = this.software.used_seats;

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    setAssignMode() {
      this.getNumberOfEmployeesWithoutSoftware();
      this.assignSeatMode = true;
      this.hideSearch();
      this.giveSeatToEveryOneMode = false;
    },

    showSearch() {
      this.searchMode = true;

      this.$nextTick(() => {
        this.$refs.search.focus();
      });
    },

    showGiveSeatToEveryOne() {
      this.giveSeatToEveryOneMode = true;
    },

    hideAssignMode() {
      this.assignSeatMode = false;
      this.hideSearch();
      this.giveSeatToEveryOneMode = false;
    },

    hideSearch() {
      this.form.searchTerm = '';
      this.potentialEmployees = [];
      this.searchMode = false;
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}/search`, this.form)
            .then(response => {
              this.potentialEmployees = response.data.data;
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
            });
        }
      },
      500),

    attach(employee) {
      this.loadingState = 'loading';
      this.form.employeeId = employee.id;

      axios.post(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}/attach`, this.form)
        .then(response => {
          this.loadingState = null;
          this.flash(this.$t('account.software_show_add_employee'), 'success');

          this.localEmployees.unshift(response.data.data);
          this.localUsedSeats = this.localUsedSeats + 1;
          this.hideAssignMode();
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    getNumberOfEmployeesWithoutSoftware() {
      axios.get(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}/numberOfEmployeesWhoDontHaveSoftware`)
        .then(response => {
          this.employeesWithoutSofware = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    detach(employee) {
      axios.delete(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}/${employee.id}`)
        .then(response => {
          this.flash(this.$t('account.software_show_remove_employee'), 'success');

          this.idToDelete = 0;
          var id = this.localEmployees.findIndex(member => member.id === employee.id);
          this.localEmployees.splice(id, 1);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroy() {
      axios.delete(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}`)
        .then(response => {
          localStorage.success = this.$t('account.software_delete');
          this.$inertia.visit('/' + this.$page.props.auth.company.id + '/account/softwares');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    assignAll() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}/attachAll`)
        .then(response => {
          this.loadingState = null;
          this.flash(this.$t('account.software_show_add_all_employees'), 'success');
          this.localEmployees = response.data.data;

          this.localUsedSeats = this.localUsedSeats + this.employeesWithoutSofware;
          this.hideAssignMode();
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    onSuccess(file) {
      this.form.uuid = file.uuid;
      this.form.name = file.name;
      this.form.original_url = file.originalUrl;
      this.form.cdn_url = file.cdnUrl;
      this.form.mime_type = file.mimeType;
      this.form.size = file.size;

      this.uploadFile();
    },

    onError() {},

    uploadFile() {
      axios.post(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}/files`, this.form)
        .then(response => {
          this.localFiles.unshift(response.data.data);
          this.flash(this.$t('project.file_upload_success'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroyFile(id) {
      axios.delete(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}/files/${id}`)
        .then(response => {
          this.flash(this.$t('project.file_deletion_success'), 'success');

          this.idToDelete = 0;
          id = this.localFiles.findIndex(x => x.id === id);
          this.localFiles.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
