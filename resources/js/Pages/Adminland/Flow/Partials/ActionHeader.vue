<style lang="scss" scoped>
.title-menu {
  background-color: #ffe8e8;
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
}

.complete {
  background-color: #e2f6f9;
}

.delete-menu {
  right: 12px;
  top: 10px;
}
</style>

<template>
  <div :class="{complete: isComplete}" class="title-menu relative">
    <div class="pa3 bb bb-gray fw5">
      {{ title }}
    </div>

    <!-- Actions available -->
    <ul class="list ma0 pa0 delete-menu absolute">
      <li v-show="!deleteActionConfirmation" class="pv2 relative">
        <icon-delete :class="'icon-delete relative pointer'" :width="15" :height="15" @click.prevent="deleteActionConfirmation = true" />
      </li>
      <li v-show="deleteActionConfirmation" class="pv2">
        {{ $t('app.sure') }}
        <a class="c-delete mr1 pointer" @click.prevent="destroy">
          {{ $t('app.yes') }}
        </a>
        <a class="pointer" @click.prevent="deleteActionConfirmation = false">
          {{ $t('app.no') }}
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
import IconDelete from '@/Shared/IconDelete';

export default {
  components: {
    IconDelete,
  },

  props: {
    title: {
      type: String,
      default: '',
    },
    isComplete: {
      type: Boolean,
      default: false,
    }
  },

  emits: [
    'destroy'
  ],

  data() {
    return {
      deleteActionConfirmation: false,
    };
  },

  methods: {
    destroy() {
      this.$emit('destroy');
    },
  }
};

</script>
