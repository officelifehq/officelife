(window.webpackJsonp=window.webpackJsonp||[]).push([[36,58],{"1hdn":function(e,t,a){(e.exports=a("I1BE")(!1)).push([e.i,".actions-dots[data-v-20bf1c9c] {\n  top: 15px;\n}\n.employee-modal[data-v-20bf1c9c] {\n  top: 30px;\n  left: -120px;\n  right: 290px;\n}\n.confirmation-menu[data-v-20bf1c9c] {\n  top: 30px;\n  left: -160px;\n  right: initial;\n  width: 310px;\n}\n.action-menu[data-v-20bf1c9c] {\n  right: -6px;\n  top: 31px;\n}\n.icon-delete[data-v-20bf1c9c] {\n  top: 2px;\n}\n.ball-pulse[data-v-20bf1c9c] {\n  right: 8px;\n  top: 10px;\n  position: absolute;\n}",""])},IQOQ:function(e,t,a){e.exports=function(){var e="__v-click-outside",t="undefined"!=typeof window,a="undefined"!=typeof navigator,s=t&&("ontouchstart"in window||a&&navigator.msMaxTouchPoints>0)?["touchstart"]:["click"];function n(t,a){var n=function(e){var t="function"==typeof e;if(!t&&"object"!=typeof e)throw new Error("v-click-outside: Binding value must be a function or an object");return{handler:t?e:e.handler,middleware:e.middleware||function(e){return e},events:e.events||s,isActive:!(!1===e.isActive)}}(a.value),i=n.handler,o=n.middleware;n.isActive&&(t[e]=n.events.map((function(e){return{event:e,handler:function(e){return function(e){var t=e.el,a=e.event,s=e.handler,n=e.middleware;a.target!==t&&!t.contains(a.target)&&n(a,t)&&s(a,t)}({event:e,el:t,handler:i,middleware:o})}}})),t[e].forEach((function(e){var t=e.event,a=e.handler;return setTimeout((function(){return document.documentElement.addEventListener(t,a,!1)}),0)})))}function i(t){(t[e]||[]).forEach((function(e){return document.documentElement.removeEventListener(e.event,e.handler,!1)})),delete t[e]}var o={bind:n,update:function(e,t){var a=t.value,s=t.oldValue;JSON.stringify(a)!==JSON.stringify(s)&&(i(e),n(e,{value:a}))},unbind:i};return{install:function(e){e.directive("click-outside",o)},directive:o}}()},aYEy:function(e,t,a){"use strict";a.r(t);var s=a("IQOQ"),n=a.n(s),i=(a("zHN7"),{components:{BallPulseLoader:a("mM8D").a},directives:{clickOutside:n.a.directive},props:{action:{type:Object,default:null}},data:function(){return{who:"",message:"",updatedMessage:"",notification:{id:0,type:"",target:"",employeeId:0,teamId:0,message:"",complete:!1},form:{searchTerm:null,errors:[]},processingSearch:!1,searchEmployees:[],searchTeams:[],displayModal:!1,actionsModal:!1,showEveryoneConfirmationModal:!1,showSeachEmployeeModal:!1,showSeachTeamModal:!1,showEditMessage:!1,deleteActionConfirmation:!1}},computed:{charactersLeft:function(){return"Characters remaining: "+(255-this.updatedMessage.length)+" / 255"}},mounted:function(){this.popupItem=this.$el,this.notification=this.action,this.who="an employee",this.setMessage(this.$t("account.flow_new_action_label_unknown_message"))},methods:{displayConfirmationModal:function(){this.showEveryoneConfirmationModal=!0,this.displayModal=!1},displayEmployeeSearchBox:function(){this.displayModal=!1,this.showSeachEmployeeModal=!0},displayTeamSearchBox:function(){this.displayModal=!1,this.showSeachTeamModal=!0},displayEditMessageTextarea:function(){this.notification.message==this.$t("account.flow_new_action_label_unknown_message")?this.updatedMessage="":this.updatedMessage=this.notification.message,this.showEditMessage=!0},toggleModals:function(){this.showEveryoneConfirmationModal=!1,this.displayModal=!1,this.showSeachEmployeeModal=!1,this.showSeachTeamModal=!1,this.actionsModal=!1,this.showEditMessage=!1},checkComplete:function(){""!=this.notification.message&&this.notification.message!=this.$t("account.flow_new_action_label_unknown_message")&&this.notification.target&&(this.notification.complete=!0)},setTarget:function(e){switch(this.notification.target=e,this.toggleModals(),e){case"actualEmployee":this.who=this.$t("account.flow_new_action_label_actual_employee");break;case"everyone":this.who=this.$t("account.flow_new_action_label_everyone");break;case"managers":this.who=this.$t("account.flow_new_action_label_managers");break;case"directReports":this.who=this.$t("account.flow_new_action_label_reports");break;case"employeeTeam":this.who=this.$t("account.flow_new_action_label_team_employee");break;case"specificTeam":case"specificEmployee":break;default:this.who=this.$t("account.flow_new_action_label_employee")}this.checkComplete(),this.$emit("update",this.notification)},searchEmployee:_.debounce((function(){var e=this;""!=this.form.searchTerm&&(this.processingSearch=!0,axios.post("/search/employees/",this.form).then((function(t){e.searchEmployees=t.data.data,e.processingSearch=!1})).catch((function(t){e.form.errors=_.flatten(_.toArray(t.response.data)),e.processingSearch=!1})))}),500),searchTeam:_.debounce((function(){var e=this;""!=this.form.searchTerm&&(this.processingSearch=!0,axios.post("/search/teams/",this.form).then((function(t){e.searchTeams=t.data.data,e.processingSearch=!1})).catch((function(t){e.form.errors=_.flatten(_.toArray(t.response.data)),e.processingSearch=!1})))}),500),assignEmployee:function(e){this.notification.employeeId=e.id,this.who=e.name,this.setTarget("specificEmployee"),this.toggleModals()},assignTeam:function(e){this.notification.teamId=e.id,this.who=e.name,this.setTarget("specificTeam"),this.toggleModals()},destroyAction:function(){this.$emit("destroy")},setMessage:function(e){this.notification.message=""==e?this.$t("account.flow_new_action_label_unknown_message"):e,this.message=this.notification.message,this.toggleModals(),this.checkComplete(),this.$emit("update",this.notification)}}}),o=(a("ugOm"),a("KHd+")),c=Object(o.a)(i,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"relative pr3 lh-copy"},[e._v("\n  Notify "),a("span",{staticClass:"bb b--dotted bt-0 bl-0 br-0 pointer",on:{click:function(t){e.displayModal=!0}}},[e._v("\n    "+e._s(e.who)+"\n  ")]),e._v(" with "),a("span",{staticClass:"bb b--dotted bt-0 bl-0 br-0 pointer",on:{click:e.displayEditMessageTextarea}},[e._v("\n    "+e._s(e.message)+"\n  ")]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.displayModal,expression:"displayModal"},{name:"click-outside",rawName:"v-click-outside",value:e.toggleModals,expression:"toggleModals"}],staticClass:"popupmenu employee-modal absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"},[a("ul",{staticClass:"list ma0 pa0"},[a("li",{staticClass:"pv1"},[a("a",{staticClass:"pointer",on:{click:function(t){return t.preventDefault(),e.setTarget("actualEmployee")}}},[e._v("\n          "+e._s(e.$t("account.flow_new_action_notification_actual_employee"))+"\n        ")])]),e._v(" "),a("li",{staticClass:"pv1"},[a("a",{staticClass:"pointer",on:{click:function(t){return t.preventDefault(),e.displayEmployeeSearchBox(t)}}},[e._v("\n          "+e._s(e.$t("account.flow_new_action_notification_specific_employee"))+"\n        ")])]),e._v(" "),a("li",{staticClass:"pv1"},[a("a",{staticClass:"pointer",on:{click:function(t){return t.preventDefault(),e.setTarget("managers")}}},[e._v("\n          "+e._s(e.$t("account.flow_new_action_notification_manager"))+"\n        ")])]),e._v(" "),a("li",{staticClass:"pv1"},[a("a",{staticClass:"pointer",on:{click:function(t){return t.preventDefault(),e.setTarget("directReports")}}},[e._v("\n          "+e._s(e.$t("account.flow_new_action_notification_report"))+"\n        ")])]),e._v(" "),a("li",{staticClass:"pv1"},[a("a",{staticClass:"pointer",on:{click:function(t){return t.preventDefault(),e.setTarget("employeeTeam")}}},[e._v("\n          "+e._s(e.$t("account.flow_new_action_notification_team_members"))+"\n        ")])]),e._v(" "),a("li",{staticClass:"pv1"},[a("a",{staticClass:"pointer",on:{click:function(t){return t.preventDefault(),e.displayTeamSearchBox(t)}}},[e._v("\n          "+e._s(e.$t("account.flow_new_action_notification_specific_team"))+"\n        ")])]),e._v(" "),a("li",{staticClass:"pv1"},[a("a",{staticClass:"pointer",on:{click:function(t){return t.preventDefault(),e.displayConfirmationModal(t)}}},[e._v("\n          "+e._s(e.$t("account.flow_new_action_notification_everyone"))+"\n        ")])])])]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.showEveryoneConfirmationModal,expression:"showEveryoneConfirmationModal"},{name:"click-outside",rawName:"v-click-outside",value:e.toggleModals,expression:"toggleModals"}],staticClass:"popupmenu confirmation-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"},[a("p",{staticClass:"lh-copy"},[e._v("\n      "+e._s(e.$t("account.flow_new_action_notification_confirmation"))+"\n    ")]),e._v(" "),a("ul",{staticClass:"list ma0 pa0 pb2"},[a("li",{staticClass:"pv2 di relative mr2"},[a("a",{staticClass:"pointer ml1",on:{click:function(t){return t.preventDefault(),e.setTarget("everyone")}}},[e._v("\n          "+e._s(e.$t("app.yes_sure"))+"\n        ")])]),e._v(" "),a("li",{staticClass:"pv2 di"},[a("a",{staticClass:"pointer",on:{click:function(t){t.preventDefault(),e.showEveryoneConfirmationModal=!1,e.displayModal=!0}}},[e._v("\n          "+e._s(e.$t("app.no"))+"\n        ")])])])]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.showSeachEmployeeModal,expression:"showSeachEmployeeModal"},{name:"click-outside",rawName:"v-click-outside",value:e.toggleModals,expression:"toggleModals"}],staticClass:"popupmenu confirmation-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"},[a("form",{on:{submit:function(t){return t.preventDefault(),e.searchEmployee(t)}}},[a("div",{staticClass:"mb3 relative"},[a("p",[e._v(e._s(e.$t("account.flow_new_action_notification_search_employees")))]),e._v(" "),a("div",{staticClass:"relative"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.form.searchTerm,expression:"form.searchTerm"}],ref:"search",staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:"search",type:"text",name:"search",placeholder:e.$t("account.flow_new_action_notification_search_hint"),required:""},domProps:{value:e.form.searchTerm},on:{keyup:e.searchEmployee,keydown:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"esc",27,t.key,["Esc","Escape"])?null:e.toggleModals()},input:function(t){t.target.composing||e.$set(e.form,"searchTerm",t.target.value)}}}),e._v(" "),e.processingSearch?a("ball-pulse-loader",{attrs:{color:"#5c7575",size:"7px"}}):e._e()],1)])]),e._v(" "),a("ul",{staticClass:"pl0 list ma0"},[a("li",{staticClass:"fw5 mb3"},[a("span",{staticClass:"f6 mb2 dib"},[e._v("\n          "+e._s(e.$t("employee.hierarchy_search_results"))+"\n        ")]),e._v(" "),e.searchEmployees.length>0?a("ul",{staticClass:"list ma0 pl0"},e._l(e.searchEmployees,(function(t){return a("li",{key:t.id,staticClass:"bb relative pv2 ph1 bb-gray bb-gray-hover"},[e._v("\n            "+e._s(t.name)+"\n            "),a("a",{staticClass:"absolute right-1 pointer",attrs:{"data-cy":"potential-manager-button"},on:{click:function(a){return a.preventDefault(),e.assignEmployee(t)}}},[e._v("\n              "+e._s(e.$t("app.choose"))+"\n            ")])])})),0):a("div",{staticClass:"silver"},[e._v("\n          "+e._s(e.$t("app.no_results"))+"\n        ")])])])]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.showSeachTeamModal,expression:"showSeachTeamModal"},{name:"click-outside",rawName:"v-click-outside",value:e.toggleModals,expression:"toggleModals"}],staticClass:"popupmenu confirmation-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"},[a("form",{on:{submit:function(t){return t.preventDefault(),e.searchTeam(t)}}},[a("div",{staticClass:"mb3 relative"},[a("p",[e._v(e._s(e.$t("account.flow_new_action_notification_search_teams")))]),e._v(" "),a("div",{staticClass:"relative"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.form.searchTerm,expression:"form.searchTerm"}],ref:"search",staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:"search",type:"text",name:"search",placeholder:e.$t("account.flow_new_action_notification_search_hint"),required:""},domProps:{value:e.form.searchTerm},on:{keyup:e.searchTeam,keydown:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"esc",27,t.key,["Esc","Escape"])?null:e.toggleModals()},input:function(t){t.target.composing||e.$set(e.form,"searchTerm",t.target.value)}}}),e._v(" "),e.processingSearch?a("ball-pulse-loader",{attrs:{color:"#5c7575",size:"7px"}}):e._e()],1)])]),e._v(" "),a("ul",{staticClass:"pl0 list ma0"},[a("li",{staticClass:"fw5 mb3"},[a("span",{staticClass:"f6 mb2 dib"},[e._v("\n          "+e._s(e.$t("employee.hierarchy_search_results"))+"\n        ")]),e._v(" "),e.searchTeams.length>0?a("ul",{staticClass:"list ma0 pl0"},e._l(e.searchTeams,(function(t){return a("li",{key:t.id,staticClass:"bb relative pv2 ph1 bb-gray bb-gray-hover"},[e._v("\n            "+e._s(t.name)+"\n            "),a("a",{staticClass:"absolute right-1 pointer",attrs:{"data-cy":"potential-manager-button"},on:{click:function(a){return a.preventDefault(),e.assignTeam(t)}}},[e._v("\n              "+e._s(e.$t("app.choose"))+"\n            ")])])})),0):a("div",{staticClass:"silver"},[e._v("\n          "+e._s(e.$t("app.no_results"))+"\n        ")])])])]),e._v(" "),a("img",{staticClass:"absolute right-0 pointer actions-dots",attrs:{src:"/img/common/triple-dots.svg"},on:{click:function(t){e.actionsModal=!0}}}),e._v(" "),e.actionsModal?a("div",{directives:[{name:"click-outside",rawName:"v-click-outside",value:e.toggleModals,expression:"toggleModals"}],staticClass:"popupmenu action-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal"},[a("ul",{staticClass:"list ma0 pa0"},[a("li",{directives:[{name:"show",rawName:"v-show",value:!e.deleteActionConfirmation,expression:"!deleteActionConfirmation"}],staticClass:"pv2 relative"},[a("icon-delete",{attrs:{classes:"icon-delete relative",width:15,height:15}}),e._v(" "),a("a",{staticClass:"pointer ml1 c-delete",on:{click:function(t){t.preventDefault(),e.deleteActionConfirmation=!0}}},[e._v("\n          "+e._s(e.$t("account.flow_new_action_remove"))+"\n        ")])],1),e._v(" "),a("li",{directives:[{name:"show",rawName:"v-show",value:e.deleteActionConfirmation,expression:"deleteActionConfirmation"}],staticClass:"pv2"},[e._v("\n        "+e._s(e.$t("app.sure"))+"\n        "),a("a",{staticClass:"c-delete mr1 pointer",on:{click:function(t){return t.preventDefault(),e.destroyAction(t)}}},[e._v("\n          "+e._s(e.$t("app.yes"))+"\n        ")]),e._v(" "),a("a",{staticClass:"pointer",on:{click:function(t){t.preventDefault(),e.deleteActionConfirmation=!1}}},[e._v("\n          "+e._s(e.$t("app.no"))+"\n        ")])])])]):e._e(),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.showEditMessage,expression:"showEditMessage"}],staticClass:"mt2"},[a("p",{staticClass:"mb1 f6"},[e._v("\n      "+e._s(e.charactersLeft)+"\n    ")]),e._v(" "),a("textarea",{directives:[{name:"model",rawName:"v-model",value:e.updatedMessage,expression:"updatedMessage"}],staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{cols:"30",rows:"3",maxlength:"255"},domProps:{value:e.updatedMessage},on:{input:function(t){t.target.composing||(e.updatedMessage=t.target.value)}}}),e._v(" "),a("div",{staticClass:"mv1"},[a("div",{staticClass:"flex-ns justify-between"},[a("a",{staticClass:"btn dib tc w-auto-ns w-100 mb2 pv2 ph3",on:{click:function(t){e.showEditMessage=!1}}},[e._v("\n          "+e._s(e.$t("app.cancel"))+"\n        ")]),e._v(" "),a("a",{staticClass:"btn dib tc w-auto-ns w-100 mb2 pv2 ph3",on:{click:function(t){return e.setMessage(e.updatedMessage)}}},[e._v("\n          "+e._s(e.$t("app.save"))+"\n        ")])])])])])}),[],!1,null,"20bf1c9c",null);t.default=c.exports},"r+53":function(e,t,a){var s=a("1hdn");"string"==typeof s&&(s=[[e.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,n);s.locals&&(e.exports=s.locals)},ugOm:function(e,t,a){"use strict";var s=a("r+53");a.n(s).a}}]);
//# sourceMappingURL=36.js.map?id=1bb9d2339ec89704c5cd