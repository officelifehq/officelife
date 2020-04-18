<style scoped>
.menu {
  border: 1px solid rgba(27,31,35,.15);
  box-shadow: 0 3px 12px rgba(27,31,35,.15);
  background-color: #fff;
}

.menu:after,
.menu:before {
  content: "";
  display: inline-block;
  position: absolute;
}

.menu:after {
  border: 7px solid transparent;
  border-bottom-color: #fff;
  left: auto;
  right: 10px;
  top: -13px;
}

.menu:before {
  border: 8px solid transparent;
  border-bottom-color: rgba(27,31,35,.15);
  left: auto;
  right: 9px;
  top: -16px;
}
</style>

<template>
  <div class="relative di">
    <a ref="popoverReference" class="no-color no-underline pointer" data-cy="header-menu" @click.prevent="openPopover">
      {{ $page.auth.user.name }}
    </a>

    <base-popover
      v-if="isPopoverVisible"
      :popover-options="popoverOptions"
      @closePopover="closePopover"
    >
      <div class="menu pa3 f5 br3">
        <ul class="list ma0 pa0">
          <li class="pb2">
            <inertia-link :href="'/' + $page.auth.company.id + '/employees/' + $page.auth.employee.id" class="no-color no-underline" data-cy="switch-company-button">
              {{ $t('app.header_go_to_employee_profile') }}
            </inertia-link>
          </li>
          <li class="pb2">
            <inertia-link :href="'/home'" class="no-color no-underline" data-cy="switch-company-button">
              {{ $t('app.header_switch_company') }}
            </inertia-link>
          </li>
          <li class="pv1">
            <inertia-link href="/logout" class="no-color no-underline" data-cy="logout-button">
              {{ $t('app.header_logout') }}
            </inertia-link>
          </li>
        </ul>
      </div>
    </base-popover>
  </div>
</template>

<script>
import BasePopover from '@/Shared/BasePopover';

export default {
  components: {
    BasePopover,
  },

  props: {
  },

  data() {
    return {
      isPopoverVisible: false,
      popoverOptions: {
        popoverReference: null,
        placement: 'bottom-end',
        offset: '0,0'
      }
    };
  },

  mounted() {
    this.popoverOptions.popoverReference = this.$refs.popoverReference;
  },

  methods: {
    closePopover() {
      this.isPopoverVisible = false;
    },

    openPopover() {
      this.isPopoverVisible = true;
    }
  }
};
</script>
