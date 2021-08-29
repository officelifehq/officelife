<style lang="scss" scoped>
.history-item {
  padding:  1px 6px;
}

.title {
  margin-bottom: -15px;

  p {
    background-color: #fff;
    padding-right: 4px;
    top: -27px;
  }
}

</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/hardware'"
                  :previous="$t('app.breadcrumb_account_manage_hardware')"
      >
        {{ $t('app.breadcrumb_account_show_hardware') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="normal mb3" data-cy="item-name">
            {{ hardware.name }}
          </h2>

          <p v-if="hardware.serial_number" class="relative mb3" data-cy="item-serial-number">
            <svg class="relative mr1" style="top: 3px;" width="15" height="15" viewBox="0 0 15 15"
                 fill="none" xmlns="http://www.w3.org/2000/svg"
            >
              <path d="M3.88889 11.1111H3.89611M3.88889 14C3.12271 14 2.38791 13.6956 1.84614 13.1539C1.30436 12.6121 1 11.8773 1 11.1111V2.44444C1 2.06135 1.15218 1.69395 1.42307 1.42307C1.69395 1.15218 2.06135 1 2.44444 1H5.33333C5.71642 1 6.08382 1.15218 6.35471 1.42307C6.6256 1.69395 6.77778 2.06135 6.77778 2.44444V11.1111C6.77778 11.8773 6.47341 12.6121 5.93164 13.1539C5.38987 13.6956 4.65507 14 3.88889 14V14ZM3.88889 14H12.5556C12.9386 14 13.306 13.8478 13.5769 13.5769C13.8478 13.306 14 12.9386 14 12.5556V9.66667C14 9.28358 13.8478 8.91618 13.5769 8.64529C13.306 8.3744 12.9386 8.22222 12.5556 8.22222H10.8634L3.88889 14ZM6.77778 4.13661L7.9745 2.93989C8.24537 2.6691 8.61271 2.51698 8.99572 2.51698C9.37874 2.51698 9.74607 2.6691 10.0169 2.93989L12.0601 4.98306C12.3309 5.25393 12.483 5.62126 12.483 6.00428C12.483 6.38729 12.3309 6.75463 12.0601 7.0255L5.93133 13.1536L6.77778 4.13661Z" stroke="#C9C4C4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ hardware.serial_number }}
          </p>

          <div class="mr3 db mb3" :data-cy="'hardware-item-lend-' + hardware.id">
            <small-name-and-avatar
              v-if="hardware.employee"
              :name="hardware.employee.name"
              :avatar="hardware.employee.avatar"
              :top="'0px'"
              :margin-between-name-avatar="'29px'"
            />

            <span v-else class="gray f6 i">
              {{ $t('account.hardware_not_lent') }}
            </span>
          </div>

          <ul class="list pl0 mb5">
            <li class="di mr2"><inertia-link :href="'/' + $page.props.auth.company.id + '/account/hardware/' + hardware.id + '/edit'" :data-cy="'hardware-edit-link-' + hardware.id">{{ $t('app.edit') }}</inertia-link></li>

            <!-- DELETE AN ITEM -->
            <li v-if="idToDelete == hardware.id" class="di">
              {{ $t('app.sure') }}
              <a class="c-delete mr1 pointer" :data-cy="'delete-confirm-button'" @click.prevent="destroy(hardware.id)">
                {{ $t('app.yes') }}
              </a>
              <a class="pointer" :data-cy="'delete-cancel-button'" @click.prevent="idToDelete = 0">
                {{ $t('app.no') }}
              </a>
            </li>
            <li v-else class="di">
              <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'delete-button'" @click.prevent="idToDelete = hardware.id">
                {{ $t('app.delete') }}
              </a>
            </li>
          </ul>

          <div class="bt bb-gray title">
            <p class="relative dib">{{ $t('account.hardware_show_history') }}</p>
          </div>

          <ul class="pl0 list ma0">
            <li v-for="historyItem in history" :key="historyItem.id" class="pl0 mb3 relative">
              <span class="f7 relative br3 bb-gray ba dib mr3 history-item">{{ historyItem.date }}</span> {{ historyItem.sentence }}
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
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    SmallNameAndAvatar
  },

  props: {
    hardware: {
      type: Object,
      default: null,
    },
    history: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      form: {
        name: null,
        serial: null,
        employee_id: null,
        lend_hardware: false,
        errors: [],
      },
      idToDelete: 0,
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
    destroy(id) {
      axios.delete('/' + this.$page.props.auth.company.id + '/account/hardware/' + id)
        .then(response => {
          this.flash(this.$t('account.position_success_destroy'), 'success');
          this.$inertia.visit('/' + this.$page.props.auth.company.id + '/account/hardware');
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
