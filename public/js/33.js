(window.webpackJsonp=window.webpackJsonp||[]).push([[33,61],{CEKA:function(e,t,n){(e.exports=n("I1BE")(!1)).push([e.i,"\n.pronouns-list[data-v-4f12de0e] {\n  max-height: 150px;\n}\n.popupmenu[data-v-4f12de0e] {\n  right: 2px;\n  top: 26px;\n  width: 280px;\n}\n.c-delete[data-v-4f12de0e]:hover {\n  border-bottom-width: 0;\n}\n.existing-pronouns li[data-v-4f12de0e]:not(:last-child) {\n  margin-right: 5px;\n}\n",""])},IQOQ:function(e,t,n){e.exports=function(){var e="__v-click-outside",t="undefined"!=typeof window,n="undefined"!=typeof navigator,o=t&&("ontouchstart"in window||n&&navigator.msMaxTouchPoints>0)?["touchstart"]:["click"];function a(t,n){var a=function(e){var t="function"==typeof e;if(!t&&"object"!=typeof e)throw new Error("v-click-outside: Binding value must be a function or an object");return{handler:t?e:e.handler,middleware:e.middleware||function(e){return e},events:e.events||o,isActive:!(!1===e.isActive)}}(n.value),i=a.handler,r=a.middleware;a.isActive&&(t[e]=a.events.map((function(e){return{event:e,handler:function(e){return function(e){var t=e.el,n=e.event,o=e.handler,a=e.middleware;n.target!==t&&!t.contains(n.target)&&a(n,t)&&o(n,t)}({event:e,el:t,handler:i,middleware:r})}}})),t[e].forEach((function(e){var t=e.event,n=e.handler;return setTimeout((function(){return document.documentElement.addEventListener(t,n,!1)}),0)})))}function i(t){(t[e]||[]).forEach((function(e){return document.documentElement.removeEventListener(e.event,e.handler,!1)})),delete t[e]}var r={bind:a,update:function(e,t){var n=t.value,o=t.oldValue;JSON.stringify(n)!==JSON.stringify(o)&&(i(e),a(e,{value:n}))},unbind:i};return{install:function(e){e.directive("click-outside",r)},directive:r}}()},"KHd+":function(e,t,n){"use strict";function o(e,t,n,o,a,i,r,s){var l,u="function"==typeof e?e.options:e;if(t&&(u.render=t,u.staticRenderFns=n,u._compiled=!0),o&&(u.functional=!0),i&&(u._scopeId="data-v-"+i),r?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),a&&a.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(r)},u._ssrRegister=l):a&&(l=s?function(){a.call(this,this.$root.$options.shadowRoot)}:a),l)if(u.functional){u._injectStyles=l;var d=u.render;u.render=function(e,t){return l.call(t),d(e,t)}}else{var c=u.beforeCreate;u.beforeCreate=c?[].concat(c,l):[l]}return{exports:e,options:u}}n.d(t,"a",(function(){return o}))},jwlh:function(e,t,n){var o=n("CEKA");"string"==typeof o&&(o=[[e.i,o,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(o,a);o.locals&&(e.exports=o.locals)},uJbv:function(e,t,n){"use strict";var o=n("jwlh");n.n(o).a},wtWF:function(e,t,n){"use strict";n.r(t);var o=n("IQOQ"),a={directives:{clickOutside:n.n(o).a.directive},props:{employee:{type:Object,default:null},pronouns:{type:Array,default:null}},data:function(){return{modal:!1,search:"",updatedEmployee:Object}},computed:{filteredList:function(){var e=this;return this.pronouns.filter((function(t){return t.label.toLowerCase().includes(e.search.toLowerCase())})).sort((function(e,t){return e.label<t.label?-1:e.label>t.label?1:0}))}},created:function(){this.updatedEmployee=this.employee},methods:{toggleModal:function(){this.modal=!1},assign:function(e){var t=this;axios.post("/"+this.$page.auth.company.id+"/employees/"+this.employee.id+"/pronoun",e).then((function(e){flash(t.$t("employee.pronoun_modal_assign_success"),"success"),t.updatedEmployee=e.data.data})).catch((function(e){t.form.errors=_.flatten(_.toArray(e.response.data))}))},reset:function(e){var t=this;axios.delete("/"+this.$page.auth.company.id+"/employees/"+this.employee.id+"/pronoun/"+e.id).then((function(e){flash(t.$t("employee.pronoun_modal_unassign_success"),"success"),t.updatedEmployee=e.data.data})).catch((function(e){t.form.errors=_.flatten(_.toArray(e.response.data))}))},isAssigned:function(e){return!!this.updatedEmployee.pronoun&&this.updatedEmployee.pronoun.id==e},employeeOrAtLeastHR:function(){return this.$page.auth.employee.permission_level<=200||!!this.employee.user&&(this.$page.auth.user.id==this.employee.user.id||void 0)}}},i=(n("uJbv"),n("KHd+")),r=Object(i.a)(a,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"di relative"},[e.employeeOrAtLeastHR()&&e.updatedEmployee.pronoun?n("ul",{staticClass:"ma0 pa0 di existing-pronouns"},[n("li",{staticClass:"bb b--dotted bt-0 bl-0 br-0 pointer di",attrs:{"data-cy":"open-pronoun-modal"},on:{click:function(t){t.preventDefault(),e.modal=!0}}},[e._v(e._s(e.$t("employee.pronoun_title")))]),e._v(" "),n("li",{staticClass:"di",attrs:{"data-cy":"pronoun-name-right-permission"}},[e._v("\n      "+e._s(e.updatedEmployee.pronoun.label)+"\n    ")])]):e._e(),e._v(" "),!e.employeeOrAtLeastHR()&&e.updatedEmployee.pronoun?n("ul",{staticClass:"ma0 pa0 existing-pronouns di"},[n("li",{staticClass:"di"},[e._v(e._s(e.$t("employee.pronoun_title")))]),e._v(" "),n("li",{staticClass:"di",attrs:{"data-cy":"pronoun-name-wrong-permission"}},[e._v("\n      "+e._s(e.updatedEmployee.pronoun.label)+"\n    ")])]):e._e(),e._v(" "),e.employeeOrAtLeastHR()?n("a",{directives:[{name:"show",rawName:"v-show",value:!e.updatedEmployee.pronoun,expression:"!updatedEmployee.pronoun"}],staticClass:"bb b--dotted bt-0 bl-0 br-0 pointer",attrs:{"data-cy":"open-pronoun-modal-blank"},on:{click:function(t){t.preventDefault(),e.modal=!0}}},[e._v(e._s(e.$t("employee.pronoun_modal_cta")))]):e._e(),e._v(" "),e.modal?n("div",{directives:[{name:"click-outside",rawName:"v-click-outside",value:e.toggleModal,expression:"toggleModal"}],staticClass:"popupmenu absolute br2 bg-white z-max tl bounceIn faster"},[n("p",{staticClass:"pa2 ma0 bb bb-gray"},[e._v("\n      "+e._s(e.$t("employee.pronoun_modal_title"))+"\n    ")]),e._v(" "),n("form",{on:{submit:function(t){return t.preventDefault(),e.search(t)}}},[n("div",{staticClass:"relative pv2 ph2 bb bb-gray"},[n("input",{directives:[{name:"model",rawName:"v-model",value:e.search,expression:"search"}],staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:"search",type:"text",name:"search",placeholder:e.$t("employee.pronoun_modal_filter")},domProps:{value:e.search},on:{keydown:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"esc",27,t.key,["Esc","Escape"])?null:e.toggleModal(t)},input:function(t){t.target.composing||(e.search=t.target.value)}}})])]),e._v(" "),n("ul",{staticClass:"pl0 list ma0 overflow-auto relative pronouns-list"},[e._l(e.filteredList,(function(t){return n("li",{key:t.id,attrs:{"data-cy":"list-pronoun-"+t.id}},[e.isAssigned(t.id)?n("div",{staticClass:"pv2 ph3 bb bb-gray-hover bb-gray pointer relative",on:{click:function(n){return e.reset(t)}}},[e._v("\n          "+e._s(t.label)+"\n\n          "),n("img",{staticClass:"pr1 absolute right-1",attrs:{src:"/img/check.svg"}})]):n("div",{staticClass:"pv2 ph3 bb bb-gray-hover bb-gray pointer relative",on:{click:function(n){return e.assign(t)}}},[e._v("\n          "+e._s(t.label)+"\n        ")])])})),e._v(" "),n("li",[e.updatedEmployee.pronoun?n("a",{staticClass:"pointer pv2 ph3 db no-underline c-delete bb-0",attrs:{"data-cy":"pronoun-reset-button"},on:{click:function(t){return e.reset(e.updatedEmployee.pronoun)}}},[e._v("\n          "+e._s(e.$t("employee.pronoun_modal_reset"))+"\n        ")]):e._e()])],2)]):e._e()])}),[],!1,null,"4f12de0e",null);t.default=r.exports}}]);
//# sourceMappingURL=33.js.map?id=68ed000af459857470b1