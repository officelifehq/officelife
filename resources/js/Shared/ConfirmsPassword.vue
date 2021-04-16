<template>
  <span>
    <span @click="startConfirmingPassword">
      <slot></slot>
    </span>

    <dialog-modal :show="confirmingPassword" @close="closeModal">
      <template #title>
        {{ title }}
      </template>

      <template #content>
        {{ content }}

        <text-input :ref="'password'"
                    v-model="form.password"
                    :name="'password'"
                    :errors="form.errors.password"
                    type="password"
                    placeholder="Password"
                    :required="true"
                    :extra-class-upper-div="'mt2'"
                    autocomplete="password"
                    @keyup.enter="confirmPassword"
        />
      </template>

      <template #footer>
        <loading-button type="button" @click="closeModal">
          Cancel
        </loading-button>

        <loading-button :class="'add ml2'" :state="form.processing" @click="confirmPassword">
          {{ button }}
        </loading-button>
      </template>
    </dialog-modal>
  </span>
</template>

<script>
import LoadingButton from './LoadingButton';
import DialogModal from './DialogModal';
import TextInput from './TextInput';
import { useForm } from '@inertiajs/inertia-vue3';

export default {

  components: {
    LoadingButton,
    DialogModal,
    TextInput,
  },

  props: {
    title: {
      type: String,
      default: 'Confirm Password',
    },
    content: {
      type: String,
      default: 'For your security, please confirm your password to continue.',
    },
    button: {
      type: String,
      default: 'Confirm',
    }
  },
  emits: ['confirmed'],

  data() {
    return {
      confirmingPassword: false,
      form: useForm({
        password: '',
      }),
    };
  },

  methods: {
    startConfirmingPassword() {
      axios.get(route('password.confirmation')).then(response => {
        if (response.data.confirmed) {
          this.$emit('confirmed');
        } else {
          this.confirmingPassword = true;

          setTimeout(() => this.$refs.password.focus(), 250);
        }
      });
    },

    confirmPassword() {
      this.form.processing = true;

      axios.post(route('password.confirm'), {
        password: this.form.password,
      }).then(() => {
        this.form.processing = false;
        this.closeModal();
        this.$nextTick(() => this.$emit('confirmed'));
      }).catch(error => {
        this.form.processing = false;
        this.form.errors = { password: error.response.data.errors.password[0] };
        this.$refs.password.focus();
      });
    },

    closeModal() {
      this.confirmingPassword = false;
      this.form.password = '';
      this.form.error = '';
    },
  }
};
</script>
