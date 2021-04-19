<style scoped>
  html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5
  }*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}

  .bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}
  .border-gray-400{--border-opacity:1;border-color:#cbd5e0;border-color:rgba(203,213,224,var(--border-opacity))}
  .border-r{border-right-width:1px}

  .flex{display:flex}
  .items-center{align-items:center}
  .justify-center{justify-content:center}
  .relative{position:relative}

  .mx-auto{margin-left:auto;margin-right:auto}
  .ml-4{margin-left:1rem}
  .max-w-xl{max-width:36rem}
  .min-h-screen{min-height:100vh}
  .px-4{padding-left:1rem;padding-right:1rem}
  .pt-8{padding-top:2rem}

  .text-lg{font-size:1.125rem}
  .text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}
  .uppercase{text-transform:uppercase}
  .antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
  .tracking-wider{letter-spacing:.05em}

  @media (min-width:640px){
    .sm\:items-center{align-items:center}
    .sm\:justify-start{justify-content:flex-start}
    .sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}
    .sm\:pt-0{padding-top:0}
  }
  @media (min-width:1024px){
    .lg\:px-8{padding-left:2rem;padding-right:2rem}
  }
  @media (prefers-color-scheme:dark){
    .dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}
    .dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}
  }

</style>

<template>
  <main class="antialiased">
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 dark:text-gray-400 sm:items-center sm:pt-0">
      <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
          <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
            {{ status }}
          </div>

          <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
            {{ title }}
          </div>
        </div>
        <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
          <div class="px-4 pt-8 text-gray-500">
            {{ description }}
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  props: {
    status: {
      type: Number,
      default: 0,
    },
  },
  computed: {
    title() {
      return this.$t(`errors.error_${this.status}`);
    },
    description() {
      return this.$t(`errors.message_${this.status}`);
    },
  },
};
</script>
