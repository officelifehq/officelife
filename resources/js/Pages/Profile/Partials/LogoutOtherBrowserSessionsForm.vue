<template>
  <action-section>
    <template #title>
      Browser Sessions
    </template>

    <template #description>
      Manage and log out your active sessions on other browsers and devices.
    </template>

    <template #content>
      <div class="mw6 f6 silver">
        If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
      </div>

      <!-- Other Browser Sessions -->
      <div v-if="sessions.length > 0" class="mt3">
        <div v-for="(session, i) in sessions" :key="i" class="flex items-center">
          <div>
            <svg v-if="session.agent.is_desktop" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-gray-500"
            >
              <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>

            <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                 fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-gray-500"
            >
              <path d="M0 0h24v24H0z" stroke="none" /><rect x="7" y="4" width="10" height="16" rx="1" /><path d="M11 5h2M12 17v.01" />
            </svg>
          </div>

          <div class="ml-3">
            <div class="f6 silver">
              {{ session.agent.platform }} - {{ session.agent.browser }}
            </div>

            <div>
              <div class="f5 silver">
                {{ session.ip_address }},

                <span v-if="session.is_current_device" class="dark-green fw6">
                  This device
                </span>
                <span v-else>
                  Last active {{ session.last_active }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex items-center mt3">
        <loading-button class="add mb2" type="button" :state="enabling" @click="confirmLogout">
          Log Out Other Browser Sessions
        </loading-button>
      </div>


      <!-- Log Out Other Devices Confirmation Modal -->
      <dialog-modal :show="confirmingLogout" @close="closeModal">
        <template #title>
          Log Out Other Browser Sessions
        </template>

        <template #content>
          Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.

          <text-input :ref="'password'"
                      v-model="form.password"
                      :name="'password'"
                      :errors="form.errors.password"
                      type="password"
                      placeholder="Password"
                      :required="true"
                      :default-class="'br2 f5 w-75 ba b--black-40 pa2 outline-0'"
                      :extra-class-upper-div="'mt2'"
                      autocomplete="password"
                      @keyup.enter="logoutOtherBrowserSessions"
          />
        </template>

        <template #footer>
          <loading-button type="button" @click="closeModal">
            Cancel
          </loading-button>

          <loading-button class="add mb2" :state="form.processing" @click="logoutOtherBrowserSessions">
            Log Out Other Browser Sessions
          </loading-button>
        </template>
      </dialog-modal>
    </template>
  </action-section>
</template>

<script>
import ActionSection from '@/Shared/Layout/ActionSection';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import DialogModal from '@/Shared/DialogModal';

export default {
  components: {
    ActionSection,
    LoadingButton,
    DialogModal,
    TextInput,
  },

  props: {
    sessions: {
      type: Object,
      default: () => {},
    },
  },

  data() {
    return {
      confirmingLogout: false,

      form: this.$inertia.form({
        password: '',
      })
    };
  },

  methods: {
    confirmLogout() {
      this.confirmingLogout = true;
      setTimeout(() => this.$refs.password.focus(), 250);
    },

    logoutOtherBrowserSessions() {
      this.form.delete(route('other-browser-sessions.destroy'), {
        preserveScroll: true,
        onSuccess: () => this.closeModal(),
        onSuccess: () => {
          this.flash('Done.', 'success');
          this.closeModal();
        },
        onError: () => this.$refs.password.focus(),
        onFinish: () => this.form.reset(),
      });
    },

    closeModal() {
      this.confirmingLogout = false;
      this.form.reset();
    },
  },

};
</script>
