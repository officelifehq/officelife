<style lang="scss" scoped>
.company-logo {
  top: -40px;
  width: 225px;
}
.company-name {
  margin-bottom: 17px;
}

.progress-bar {
	border-radius: 3px;
	overflow: hidden;
  width: 100%;

	span {
		display: block;
	}

}

.bar {
  background: rgba(0,0,0,0.075);
  width: 100%
}

.progress {
  animation: loader 8s cubic-bezier(.45,.05,.55,.95) forwards;
  background: #75b800;
  color: #fff;
  padding: 5px;
}

</style>

<template>
  <layout :notifications="notifications">
    <!-- company cover -->
    <div class="cover mb3" :style="'height: 25vh; background: url(https://images.unsplash.com/photo-1531973576160-7125cd663d86?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1500&q=80) no-repeat center center'"></div>

    <!-- company name + data -->
    <div class="ph2 ph5-ns mb2">
      <div class="flex relative">
        <div v-if="statistics.logo" class="company-logo relative ba bb-gray pa2 br3 bg-white mr3-ns">
          <img :src="statistics.logo" alt="logo" />
        </div>
        <div class="mt3">
          <h2 class="mt0 fw4 f3 mb4 company-name">
            {{ $page.props.auth.company.name }} <a href="https://dundermifflin.com" class="ml3 f6 fw4 no-underline gray bb-0">https://dundermifflin.com</a>
          </h2>
          <div class="flex">
            <div class="">
              <p class="ttu f7 gray mb1 mt0 mr5">number of employees</p>
              <p class="mt0">{{ statistics.number_of_employees }}</p>
            </div>
            <div class="">
              <p class="ttu f7 gray mb1 mt0 mr5">number of teams</p>
              <p class="mt0">{{ statistics.number_of_teams }}</p>
            </div>
            <div class="">
              <p class="ttu f7 gray mb1 mt0 mr5">founded</p>
              <p class="mt0">1987</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="ph2 ph5-ns">
      <!-- central content -->
      <tab :tab="tab" />

      <div class="cf mw9 center">
        <div class="fl w-third-l w-100">
          <e-coffees
            v-if="eCoffees"
            :e-coffees="eCoffees"
          />
        </div>

        <div class="fl w-third-l w-100 pl4-l">
          <genders
            :genders="genders"
          />
        </div>

        <div class="fl w-third-l w-100 pl4-l">
          3
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import eCoffees from '@/Pages/Company/HR/Partials/eCoffees';
import Genders from '@/Pages/Company/HR/Partials/Genders';
import Tab from '@/Pages/Company/Partials/Tab';

export default {
  components: {
    Layout,
    eCoffees,
    Genders,
    Tab,
  },

  props: {
    tab: {
      type: String,
      default: null,
    },
    statistics: {
      type: Object,
      default: null,
    },
    eCoffees: {
      type: Object,
      default: null,
    },
    genders: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },
};
</script>
