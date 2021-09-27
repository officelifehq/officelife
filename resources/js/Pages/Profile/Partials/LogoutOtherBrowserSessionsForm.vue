<template>
  <action-section>
    <template #title>
      {{ $t('profile.browser_sessions_title') }}
    </template>

    <template #description>
      {{ $t('profile.browser_sessions_description') }}
    </template>

    <template #content>
      <div class="mw6 f6 silver">
        {{ $t('profile.browser_sessions_message') }}
      </div>

      <!-- Other Browser Sessions -->
      <div v-if="sessions.length > 0" class="mt3">
        <div v-for="(session, i) in sessions" :key="i" class="flex items-center">
          <div>
            <svg v-if="session.agent.is_desktop" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs"
                 viewBox="0 0 19 19" width="19" height="19"
            >
              <g transform="matrix(1.9,0,0,1.9,0,0)">
                <path d="M10,1.1A1.1,1.1,0,0,0,8.9,0H1.1A1.1,1.1,0,0,0,0,1.1V6.4A1.1,1.1,0,0,0,1.1,7.5H3.681a.25.25,0,0,1,.232.343l-.337.843A.5.5,0,0,1,3.112,9H2a.5.5,0,0,0,0,1H8A.5.5,0,0,0,8,9H6.888a.5.5,0,0,1-.464-.314l-.337-.843A.25.25,0,0,1,6.319,7.5H8.9A1.1,1.1,0,0,0,10,6.4ZM8.5,5.75A.25.25,0,0,1,8.25,6H1.75a.25.25,0,0,1-.25-.25v-4a.25.25,0,0,1,.25-.25h6.5a.25.25,0,0,1,.25.25Z" fill="#8ca3ce" stroke="none" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="0"
                />
              </g>
            </svg>

            <svg v-else xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs"
                 viewBox="0 0 19 19" width="19" height="19"
            >
              <g transform="matrix(1.9,0,0,1.9,0,0)">
                <path d="M7.5,10h-5A1.752,1.752,0,0,1,.75,8.25V1.75A1.752,1.752,0,0,1,2.5,0h5A1.752,1.752,0,0,1,9.25,1.75v6.5A1.752,1.752,0,0,1,7.5,10Zm-5-8.5a.25.25,0,0,0-.25.25v6.5a.25.25,0,0,0,.25.25h5a.25.25,0,0,0,.25-.25V1.75A.25.25,0,0,0,7.5,1.5Z" fill="#8ca3ce" stroke="none" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="0"
                /><path d="M5.5,7.85h-1a.6.6,0,0,1,0-1.2h1a.6.6,0,0,1,0,1.2Z" fill="#8ca3ce" stroke="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="0"
                />
              </g>
            </svg>
          </div>

          <div class="ml3">
            <div class="f6 silver">
              {{ session.agent.platform }} — {{ session.agent.browser }}
            </div>

            <div>
              <div class="f5 silver">
                {{ session.ip_address }},

                <span v-if="session.is_current_device" class="dark-green fw6">
                  {{ $t('profile.browser_sessions_this_device') }}
                </span>
                <span v-else>
                  {{ $t('profile.browser_sessions_last_active', { last_active: session.last_active }) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex items-center mt3">
        <loading-button class="add mb2" type="button" :state="confirmingLogout" @click="confirmLogout">
          {{ $t('profile.browser_sessions_logout') }}
        </loading-button>
      </div>


      <!-- Log Out Other Devices Confirmation Modal -->
      <dialog-modal :show="confirmingLogout" @close="closeModal">
        <template #title>
          {{ $t('profile.browser_sessions_logout') }}
        </template>

        <template #content>
          {{ $t('profile.browser_sessions_confirm_password_title') }}

          <text-input :ref="'password'"
                      v-model="form.password"
                      :name="'password'"
                      :errors="form.errors.password"
                      type="password"
                      :placeholder="$t('profile.browser_sessions_confirm_password')"
                      :required="true"
                      :default-class="'br2 f5 w-75 ba b--black-40 pa2 outline-0'"
                      :extra-class-upper-div="'mt2'"
                      autocomplete="password"
                      @keyup.enter="logoutOtherBrowserSessions"
          />
        </template>

        <template #footer>
          <loading-button type="button" @click="closeModal">
            {{ $t('app.cancel') }}
          </loading-button>

          <loading-button class="add mb2" :state="form.processing" @click="logoutOtherBrowserSessions">
            {{ $t('profile.browser_sessions_logout_action') }}
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
      default: () => {return {};},
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
        onSuccess: () => {
          this.flash(this.$t('app.flash_done'), 'success');
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
