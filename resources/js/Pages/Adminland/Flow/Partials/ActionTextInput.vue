<style lang="scss" scoped>
.icon-pen {
  color: #acaa19;
  width: 16px;
  top: 2px;
}
</style>

<template>
  <div class="fl w-two-thirds">
    <!-- blank state -->
    <p v-if="!editMode && !form.content" class="mt0 mb0 relative i gray" @click="editMode = true">
      {{ $t('account.flow_new_action_text_input_define_text') }}

      <svg class="ml2 icon-pen relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
      </svg>
    </p>

    <!-- non blank state -->
    <p v-if="!editMode && form.content" class="mt0 mb0 relative" @click="editMode = true">
      {{ form.content }}

      <svg class="ml2 icon-pen relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
      </svg>
    </p>

    <!-- edit mode -->
    <div v-if="editMode" class="mb4">
      <text-input
        :ref="'input'"
        v-model="form.content"
        :errors="$page.props.errors.content"
        :required="true"
        :class="'w-100'"
        :maxlength="191"
        :extra-class-upper-div="'mb2'"
        @esc-key-pressed="editMode = false"
      />

      <!-- placeholders that can be used in the input text -->
      <p v-if="placeholders" class="mt0 mb1 lh-copy f6 gray">You can use those placeholders to populate your message above: <code>{{ placeholders }}</code></p>

      <p class="flex">
        <a class="btn add mr2" @click.prevent="save()">{{ $t('app.save') }}</a>
        <a class="btn" @click.prevent="editMode = false">{{ $t('app.cancel') }}</a>
      </p>
    </div>
  </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';

export default {
  components: {
    TextInput,
  },

  props: {
    placeholders: {
      type: String,
      default: null,
    },
  },

  emits: [
    'destroy'
  ],

  data() {
    return {
      editMode: false,
      form: {
        content: null,
        errors: [],
      },
    };
  },

  methods: {
    showEditMode() {
      this.editMode = true;

      this.$nextTick(() => {
        this.$refs.input.focus();
      });
    },

    save() {
      this.editMode = false;
      this.$emit('update', this.form.content);
    }
  }
};

</script>
