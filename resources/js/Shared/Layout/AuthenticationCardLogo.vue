<style lang="scss" scoped>
.logo {
  width: 102px;
  top: -78px;
}
</style>

<template>
  <inertia-link :href="'/'">
    <img loading="lazy" class="logo absolute left-0 right-0 mr-auto ml-auto"
         alt="officelife logo"
         srcset="/img/logo.png 1x, /img/logo-2x.png 2x"
    />
  </inertia-link>
</template>
