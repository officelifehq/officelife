(window.webpackJsonp=window.webpackJsonp||[]).push([[34,58],{IQOQ:function(e,t,a){e.exports=function(){var e="__v-click-outside",t="undefined"!=typeof window,a="undefined"!=typeof navigator,s=t&&("ontouchstart"in window||a&&navigator.msMaxTouchPoints>0)?["touchstart"]:["click"];function n(t,a){var n=function(e){var t="function"==typeof e;if(!t&&"object"!=typeof e)throw new Error("v-click-outside: Binding value must be a function or an object");return{handler:t?e:e.handler,middleware:e.middleware||function(e){return e},events:e.events||s,isActive:!(!1===e.isActive)}}(a.value),i=n.handler,o=n.middleware;n.isActive&&(t[e]=n.events.map((function(e){return{event:e,handler:function(e){return function(e){var t=e.el,a=e.event,s=e.handler,n=e.middleware;a.target!==t&&!t.contains(a.target)&&n(a,t)&&s(a,t)}({event:e,el:t,handler:i,middleware:o})}}})),t[e].forEach((function(e){var t=e.event,a=e.handler;return setTimeout((function(){return document.documentElement.addEventListener(t,a,!1)}),0)})))}function i(t){(t[e]||[]).forEach((function(e){return document.documentElement.removeEventListener(e.event,e.handler,!1)})),delete t[e]}var o={bind:n,update:function(e,t){var a=t.value,s=t.oldValue;JSON.stringify(a)!==JSON.stringify(s)&&(i(e),n(e,{value:a}))},unbind:i};return{install:function(e){e.directive("click-outside",o)},directive:o}}()},"KHd+":function(e,t,a){"use strict";function s(e,t,a,s,n,i,o,r){var l,u="function"==typeof e?e.options:e;if(t&&(u.render=t,u.staticRenderFns=a,u._compiled=!0),s&&(u.functional=!0),i&&(u._scopeId="data-v-"+i),o?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),n&&n.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(o)},u._ssrRegister=l):n&&(l=r?function(){n.call(this,this.$root.$options.shadowRoot)}:n),l)if(u.functional){u._injectStyles=l;var d=u.render;u.render=function(e,t){return l.call(t),d(e,t)}}else{var c=u.beforeCreate;u.beforeCreate=c?[].concat(c,l):[l]}return{exports:e,options:u}}a.d(t,"a",(function(){return s}))},"Nmk/":function(e,t,a){(e.exports=a("I1BE")(!1)).push([e.i,"\n.statuses-list[data-v-e823a2c6] {\n  max-height: 150px;\n}\n.popupmenu[data-v-e823a2c6] {\n  right: 2px;\n  top: 26px;\n  width: 280px;\n}\n.c-delete[data-v-e823a2c6]:hover {\n  border-bottom-width: 0;\n}\n.existing-statuses li[data-v-e823a2c6]:not(:last-child) {\n  margin-right: 5px;\n}\n",""])},Pr0x:function(e,t,a){var s=a("Nmk/");"string"==typeof s&&(s=[[e.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,n);s.locals&&(e.exports=s.locals)},T9ux:function(e,t,a){"use strict";var s=a("Pr0x");a.n(s).a},ilUr:function(e,t,a){"use strict";a.r(t);var s=a("IQOQ"),n={directives:{clickOutside:a.n(s).a.directive},props:{employee:{type:Object,default:null},statuses:{type:Array,default:null}},data:function(){return{modal:!1,search:"",updatedEmployee:Object}},computed:{filteredList:function(){var e=this;return this.statuses.filter((function(t){return t.name.toLowerCase().includes(e.search.toLowerCase())})).sort((function(e,t){return e.name<t.name?-1:e.name>t.name?1:0}))}},created:function(){this.updatedEmployee=this.employee},methods:{toggleModal:function(){this.modal=!1},assign:function(e){var t=this;axios.post("/"+this.$page.auth.company.id+"/employees/"+this.employee.id+"/employeestatuses",e).then((function(e){flash(t.$t("employee.status_modal_assign_success"),"success"),t.updatedEmployee=e.data.data})).catch((function(e){t.form.errors=_.flatten(_.toArray(e.response.data))}))},reset:function(e){var t=this;axios.delete("/"+this.$page.auth.company.id+"/employees/"+this.employee.id+"/employeestatuses/"+e.id).then((function(e){flash(t.$t("employee.status_modal_unassign_success"),"success"),t.updatedEmployee=e.data.data})).catch((function(e){t.form.errors=_.flatten(_.toArray(e.response.data))}))},isAssigned:function(e){return!!this.updatedEmployee.status&&this.updatedEmployee.status.id==e}}},i=(a("T9ux"),a("KHd+")),o=Object(i.a)(n,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"di relative"},[e.$page.auth.employee.permission_level<=200&&e.updatedEmployee.status?a("ul",{staticClass:"ma0 pa0 di existing-statuses"},[a("li",{staticClass:"bb b--dotted bt-0 bl-0 br-0 pointer di",attrs:{"data-cy":"open-status-modal"},on:{click:function(t){t.preventDefault(),e.modal=!0}}},[e._v("\n      "+e._s(e.$t("employee.status_title"))+"\n    ")]),e._v(" "),a("li",{staticClass:"di",attrs:{"data-cy":"status-name-right-permission"}},[e._v("\n      "+e._s(e.updatedEmployee.status.name)+"\n    ")])]):e._e(),e._v(" "),e.$page.auth.employee.permission_level>200&&e.updatedEmployee.status?a("ul",{staticClass:"ma0 pa0 existing-statuses di"},[a("li",{staticClass:"di"},[e._v("\n      "+e._s(e.$t("employee.status_title"))+"\n    ")]),e._v(" "),a("li",{staticClass:"di",attrs:{"data-cy":"status-name-wrong-permission"}},[e._v("\n      "+e._s(e.updatedEmployee.status.name)+"\n    ")])]):e._e(),e._v(" "),e.$page.auth.employee.permission_level<=200?a("a",{directives:[{name:"show",rawName:"v-show",value:!e.updatedEmployee.status,expression:"!updatedEmployee.status"}],staticClass:"bb b--dotted bt-0 bl-0 br-0 pointer",attrs:{"data-cy":"open-status-modal-blank"},on:{click:function(t){t.preventDefault(),e.modal=!0}}},[e._v("\n    "+e._s(e.$t("employee.status_modal_cta"))+"\n  ")]):a("span",{directives:[{name:"show",rawName:"v-show",value:!e.updatedEmployee.status,expression:"!updatedEmployee.status"}]},[e._v("\n    "+e._s(e.$t("employee.status_modal_blank"))+"\n  ")]),e._v(" "),e.modal?a("div",{directives:[{name:"click-outside",rawName:"v-click-outside",value:e.toggleModal,expression:"toggleModal"}],staticClass:"popupmenu absolute br2 bg-white z-max tl bounceIn faster"},[a("div",{directives:[{name:"show",rawName:"v-show",value:0!=e.statuses.length,expression:"statuses.length != 0"}]},[a("p",{staticClass:"pa2 ma0 bb bb-gray"},[e._v("\n        "+e._s(e.$t("employee.status_modal_title"))+"\n      ")]),e._v(" "),a("form",{on:{submit:function(t){return t.preventDefault(),e.search(t)}}},[a("div",{staticClass:"relative pv2 ph2 bb bb-gray"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.search,expression:"search"}],staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:"search",type:"text",name:"search",placeholder:e.$t("employee.status_modal_filter")},domProps:{value:e.search},on:{keydown:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"esc",27,t.key,["Esc","Escape"])?null:e.toggleModal(t)},input:function(t){t.target.composing||(e.search=t.target.value)}}})])]),e._v(" "),a("ul",{staticClass:"pl0 list ma0 overflow-auto relative statuses-list"},[e._l(e.filteredList,(function(t){return a("li",{key:t.id,attrs:{"data-cy":"list-status-"+t.id}},[e.isAssigned(t.id)?a("div",{staticClass:"pv2 ph3 bb bb-gray-hover bb-gray pointer relative",on:{click:function(a){return e.reset(t)}}},[e._v("\n            "+e._s(t.name)+"\n\n            "),a("img",{staticClass:"pr1 absolute right-1",attrs:{src:"/img/check.svg"}})]):a("div",{staticClass:"pv2 ph3 bb bb-gray-hover bb-gray pointer relative",on:{click:function(a){return e.assign(t)}}},[e._v("\n            "+e._s(t.name)+"\n          ")])])})),e._v(" "),a("li",[e.updatedEmployee.status?a("a",{staticClass:"pointer pv2 ph3 db no-underline c-delete bb-0",attrs:{"data-cy":"status-reset-button"},on:{click:function(t){return e.reset(e.updatedEmployee.status)}}},[e._v("\n            "+e._s(e.$t("employee.status_modal_reset"))+"\n          ")]):e._e()])],2)]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:0==e.statuses.length,expression:"statuses.length == 0"}]},[a("p",{staticClass:"pa2 tc lh-copy",attrs:{"data-cy":"modal-blank-state-copy"}},[e._v("\n        "+e._s(e.$t("employee.status_modal_blank_title"))+" "),a("inertia-link",{attrs:{href:"/"+e.$page.auth.company.id+"/account/employeestatuses","data-cy":"modal-blank-state-cta"}},[e._v("\n          "+e._s(e.$t("employee.status_modal_blank_cta"))+"\n        ")])],1)])]):e._e()])}),[],!1,null,"e823a2c6",null);t.default=o.exports}}]);
//# sourceMappingURL=34.js.map?id=177b2280a59e02025c9c