<style lang="scss" scoped>
.duration-200 {
  transition-duration: .20s;
}
.duration-300 {
  transition-duration: .30s;
}
.ease-out {
  transition-timing-function:cubic-bezier(0,0,.2,1)
}
.ease-in {
  transition-timing-function:cubic-bezier(.4,0,1,1)
}
.transform {
  --tw-translate-x: 0;
  --tw-translate-y: 0;
  --tw-rotate: 0;
  --tw-skew-x: 0;
  --tw-skew-y: 0;
  --tw-scale-x: 1;
  --tw-scale-y: 1;
  transform:translateX(var(--tw-translate-x)) translateY(var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
}
.translate-y-0 {
  --tw-translate-y:0px
}
.translate-y-4 {
  --tw-translate-y:1rem
}
</style>

<template>
  <teleport to="body">
    <transition leave-active-class="duration-200">
      <div v-show="show" class="fixed absolute--fill-ns overflow-y-auto-ns pa3-ns z-5">
        <transition enter-active-class="ease-out duration-300"
                    enter-from-class="o-0"
                    enter-to-class="o-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="o-100"
                    leave-to-class="o-0"
        >
          <div v-show="show" class="fixed absolute--fill-ns transform transition-all" @click="close">
            <div class="absolute absolute--fill-ns bg-mid-gray o-60"></div>
          </div>
        </transition>

        <transition enter-active-class="ease-out duration-300"
                    enter-from-class="o-0 translate-y-4"
                    enter-to-class="o-100 translate-y-0"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="o-100 translate-y-0"
                    leave-to-class="o-0 translate-y-4"
        >
          <div v-show="show" class="mb6 bg-white br3 overflow-hidden shadow-2 transform transition-all center ml-auto mr-auto" :class="maxWidthClass">
            <slot></slot>
          </div>
        </transition>
      </div>
    </transition>
  </teleport>
</template>

<script>
import { onMounted, onUnmounted } from 'vue';

export default {

  props: {
    show: {
      type: Boolean,
      default: false
    },
    maxWidth: {
      type: String,
      default: 'xl'
    },
    closeable: {
      type: Boolean,
      default: true
    },
  },
  emits: ['close'],

  setup(props, {emit}) {
    const close = () => {
      if (props.closeable) {
        emit('close');
      }
    };

    const closeOnEscape = (e) => {
      if (e.key === 'Escape' && props.show) {
        close();
      }
    };

    onMounted(() => document.addEventListener('keydown', closeOnEscape));
    onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

    return {
      close,
    };
  },

  computed: {
    maxWidthClass() {
      return {
        'sm': 'mv4',
        'md': 'mv5',
        'lg': 'mv6',
        'xl': 'mw7',
      }[this.maxWidth];
    }
  },

  watch: {
    show: {
      immediate: true,
      handler: function(show) {
        if (show) {
          document.body.style.overflow = 'hidden';
        } else {
          document.body.style.overflow = null;
        }
      }
    }
  }
};
</script>
