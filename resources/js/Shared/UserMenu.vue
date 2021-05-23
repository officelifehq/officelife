<style lang="scss" scoped>
.menu {
  border: 1px solid rgba(27,31,35,.15);
  box-shadow: 0 3px 12px rgba(27,31,35,.15);
  background-color: #fff;
  width: 190px;

  li:first-child:hover {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
  }

  li:last-child:hover {
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
  }
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

.icon {
  width: 20px;
  top: 4px;
  margin-right: 6px;
}

.no-underline {
  border-bottom: 0;
  text-decoration: none;
}

svg {
  color: #8CA3CE;
}
</style>

<template>
  <div class="relative di">
    <a ref="popoverReference" class="bb b--dotted bt-0 bl-0 br-0 pointer" data-cy="header-menu" @click.prevent="openPopover">
      {{ $page.props.auth.user.name }}
    </a>

    <base-popover
      v-if="isPopoverVisible"
      :popover-options="popoverOptions"
      @close-popover="closePopover"
    >
      <div class="menu f5 br3">
        <ul class="list ma0 pl0">
          <!-- Go to employee profile -->
          <li v-if="$page.props.auth.company" class="pa2 relative bb bb-gray bb-gray-hover">
            <span class="dib icon relative">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
              </svg>
            </span>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + $page.props.auth.employee.id" class="no-color no-underline" data-cy="go-to-employee-button">
              {{ $t('app.header_go_to_employee_profile') }}
            </inertia-link>
          </li>

          <!-- Switch companies -->
          <li v-if="$page.props.auth.company" class="pa2 relative bb bb-gray bb-gray-hover">
            <span class="dib icon relative">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z" />
              </svg>
            </span>
            <inertia-link :href="'/home'" class="no-color no-underline" data-cy="switch-company-button">
              {{ $t('app.header_switch_company') }}
            </inertia-link>
          </li>

          <!-- Toggle help -->
          <li class="pa2 relative bb bb-gray bb-gray-hover">
            <span class="dib icon relative">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
            </span>
            <a v-if="$page.props.auth.user.show_help" href="#" class="pointer no-underline" @click.prevent="toggleHelp()">
              {{ $t('app.hide_help') }}
            </a>
            <a v-else href="#" class="pointer no-underline" @click.prevent="toggleHelp()">
              {{ $t('app.show_help') }}
            </a>
          </li>

          <!-- Logout -->
          <li class="pa2 relative bb-gray-hover">
            <span class="dib icon relative">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
              </svg>
            </span>
            <inertia-link href="#" class="no-color no-underline" data-cy="logout-button" @click.prevent="logout">
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
    showHelpOnPage: {
      type: Boolean,
      default: true,
    },
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
    },

    logout() {
      axios.post(this.route('logout'))
        .then(response => {
          if (response.status === 204) {
            this.$inertia.visit('/');
          }
        })
        .catch(error => {
          this.$inertia.visit('/logout');
        });
    },

    toggleHelp() {
      axios.post('/help')
        .then(response => {
          this.$page.props.auth.user.show_help = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    }
  }
};
</script>
