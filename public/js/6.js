(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{"+SZM":function(t,e,a){"use strict";var n=a("IQOQ"),s=a.n(n),i={props:{notifications:{type:Array,default:null},show:{type:Boolean,default:!1}},data:function(){return{showNotifications:!1}},created:function(){this.showNotifications=this.show},methods:{}},r=a("KHd+"),o=Object(r.a)(i,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"bg-white tl br2 absolute z-max"},[a("div",{directives:[{name:"show",rawName:"v-show",value:t.show,expression:"show"}]},[a("div",{directives:[{name:"show",rawName:"v-show",value:0==t.notifications.length,expression:"notifications.length == 0"}]},[a("img",{staticClass:"db center mb2",attrs:{srcset:"/img/header/notification_blank.png, /img/header/notitication_blank@2x.png 2x"}}),t._v(" "),a("p",{staticClass:"tc"},[t._v("\n        All is clear!\n      ")])]),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:t.notifications.length>0,expression:"notifications.length > 0"}],staticClass:"pa0 ma0 list"},t._l(t.notifications,function(e){return a("li",{key:e.id,staticClass:"bb pa2 notification-item bb-gray"},[a("span",{staticClass:"db mb1"},[t._v(t._s(e.localized_content))]),t._v(" "),a("span",{staticClass:"f7 dib notification-date"},[t._v(t._s(t._f("moment")(e.created_at,"from","now")))])])}),0)])])},[],!1,null,"2183c67a",null).exports,l={directives:{clickOutside:s.a.directive},components:{NotificationsComponent:o},props:{notifications:{type:Array,default:null}},data:function(){return{menu:!1,showNotifications:!1}},methods:{}},c=(a("hDtB"),{components:{UserMenu:Object(r.a)(l,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"relative"},[a("div",{staticClass:"relative"},[a("span",{staticClass:"mr2 f6 notifications pv1 ph2 br3 pointer",on:{click:function(e){e.preventDefault(),t.showNotifications=!t.showNotifications}}},[t._v("🔥 "+t._s(t.notifications.length))]),t._v(" "),a("a",{staticClass:"no-color no-underline relative pointer",attrs:{"data-cy":"header-menu"},on:{click:function(e){e.preventDefault(),t.menu=!t.menu}}},[t._v("\n      "+t._s(t.$page.auth.user.email)+" "),a("span",{staticClass:"dropdown-caret"})])]),t._v(" "),1==t.menu?a("div",{staticClass:"absolute menu br2 bg-white z-max tl pv2 ph3 bounceIn faster"},[a("ul",{staticClass:"list ma0 pa0"},[a("li",{staticClass:"pv2"},[a("inertia-link",{staticClass:"no-color no-underline",attrs:{href:"/home","data-cy":"switch-company-button"}},[t._v("\n          "+t._s(t.$t("app.header_switch_company"))+"\n        ")])],1),t._v(" "),a("li",{staticClass:"pv2"},[a("inertia-link",{staticClass:"no-color no-underline",attrs:{href:"/logout","data-cy":"logout-button"}},[t._v("\n          "+t._s(t.$t("app.header_logout"))+"\n        ")])],1)])]):t._e(),t._v(" "),a("notifications-component",{attrs:{notifications:t.notifications,show:t.showNotifications}})],1)},[],!1,null,"2862e068",null).exports,LoadingButton:a("Z84v").a},directives:{clickOutside:s.a.directive},props:{title:{type:String,default:""},noMenu:{type:Boolean,default:!1},notifications:{type:Array,default:null}},data:function(){return{loadingState:"",modalFind:!1,showModalNotifications:!0,dataReturnedFromSearch:!1,form:{searchTerm:null,errors:[]},employees:[],teams:[]}},watch:{title:function(t){this.updatePageTitle(t)}},mounted:function(){this.updatePageTitle(this.title)},methods:{updatePageTitle:function(t){document.title=t?"".concat(t," | Example app"):"Example app"},showFindModal:function(){var t=this;this.dataReturnedFromSearch=!1,this.form.searchTerm=null,this.employees=[],this.teams=[],this.modalFind=!this.modalFind,this.$nextTick(function(){t.$refs.search.focus()})},showNotifications:function(){var t=this;this.showModalNotifications=!this.showModalNotifications,axios.post("/notifications/read").catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))})},hideNotifications:function(){this.showModalNotifications=!1},submit:function(){var t=this;axios.post("/search/employees",this.form).then(function(e){t.dataReturnedFromSearch=!0,t.employees=e.data.data}).catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))}),axios.post("/search/teams",this.form).then(function(e){t.dataReturnedFromSearch=!0,t.teams=e.data.data}).catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))})}}}),d=(a("idHB"),Object(r.a)(c,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("vue-snotify"),t._v(" "),a("header",{staticClass:"bg-white dn db-m db-l mb3 relative"},[a("div",{staticClass:"ph3 pt1 w-100"},[a("div",{staticClass:"cf"},[t._m(0),t._v(" "),a("div",{staticClass:"fl w-60 tc"},[a("div",{directives:[{name:"show",rawName:"v-show",value:t.noMenu,expression:"noMenu"}],staticClass:"dib w-100"}),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:!t.noMenu,expression:"!noMenu"}],staticClass:"mv2"},[a("li",{staticClass:"di header-menu-item pa2 pointer mr2"},[a("inertia-link",{attrs:{href:"/home"}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-home.svg"}}),t._v("\n                  "+t._s(t.$t("app.header_home"))+"\n                ")])])],1),t._v(" "),a("li",{staticClass:"di header-menu-item pa2 pointer mr2",attrs:{"data-cy":"header-find-link"},on:{click:t.showFindModal}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-find.svg"}}),t._v("\n                "+t._s(t.$t("app.header_find"))+"\n              ")])]),t._v(" "),a("li",{staticClass:"di header-menu-item pa2 pointer",attrs:{"data-cy":"header-notifications-link"},on:{click:t.showNotifications}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-notification.svg"}}),t._v("\n                "+t._s(t.$t("app.header_notifications"))+"\n              ")])]),t._v(" "),t.$page.auth.company&&t.$page.auth.employee.permission_level<=200?a("li",{staticClass:"di header-menu-item pa2 pointer",attrs:{"data-cy":"header-notifications-link"}},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/account"}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-notification.svg"}}),t._v("\n                  Adminland\n                ")])])],1):t._e()])]),t._v(" "),a("div",{staticClass:"fl w-20 pa2 tr relative header-menu-settings"},[a("user-menu",{attrs:{notifications:t.notifications}})],1)])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.modalFind,expression:"modalFind"}],staticClass:"absolute z-max find-box"},[a("div",{staticClass:"br2 bg-white tl pv3 ph3 bounceIn faster"},[a("form",{on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[a("div",{staticClass:"relative"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.searchTerm,expression:"form.searchTerm"}],ref:"search",staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:"search",type:"text",name:"search",placeholder:t.$t("app.header_search_placeholder"),required:""},domProps:{value:t.form.searchTerm},on:{keydown:function(e){if(!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"]))return null;t.modalFind=!1},input:function(e){e.target.composing||t.$set(t.form,"searchTerm",e.target.value)}}}),t._v(" "),a("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 mb2 pv2 ph3 absolute top-0 right-0",state:t.loadingState,text:t.$t("app.search"),"cypress-selector":"header-find-submit"}})],1)]),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:t.dataReturnedFromSearch,expression:"dataReturnedFromSearch"}],staticClass:"pl0 list ma0 mt3",attrs:{"data-cy":"results"}},[a("li",{staticClass:"b mb3"},[a("span",{staticClass:"f6 mb2 dib"},[t._v(t._s(t.$t("app.header_search_employees")))]),t._v(" "),t.employees.length>0?a("ul",{staticClass:"list ma0 pl0"},t._l(t.employees,function(e){return a("li",{key:e.id},[a("a",{attrs:{href:"/"+e.company.id+"/employees/"+e.id}},[t._v(t._s(e.name))])])}),0):a("div",{staticClass:"silver"},[t._v("\n              "+t._s(t.$t("app.header_search_no_employee_found"))+"\n            ")])]),t._v(" "),a("li",{staticClass:"fw5"},[a("span",{staticClass:"f6 mb2 dib"},[t._v(t._s(t.$t("app.header_search_teams")))]),t._v(" "),t.teams.length>0?a("ul",{staticClass:"list ma0 pl0"},t._l(t.teams,function(e){return a("li",{key:e.id},[a("a",{attrs:{href:"/"+e.company.id+"/teams/"+e.id}},[t._v(t._s(e.name))])])}),0):a("div",{staticClass:"silver"},[t._v("\n              "+t._s(t.$t("app.header_search_no_team_found"))+"\n            ")])])])])])]),t._v(" "),t._m(1),t._v(" "),a("div",{class:[t.modalFind?"bg-modal-find":""]}),t._v(" "),t._t("default")],2)},[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"fl w-20 pa2"},[e("a",{staticClass:"relative header-logo",attrs:{href:""}},[e("img",{attrs:{src:"/img/logo.svg",height:"30"}})])])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("header",{staticClass:"bg-white mobile dn-ns mb3"},[a("div",{staticClass:"ph2 pv2 w-100 relative"},[a("div",{staticClass:"pv2 relative menu-toggle"},[a("label",{staticClass:"dib b relative",attrs:{for:"menu-toggle"}},[t._v("Menu")]),t._v(" "),a("input",{attrs:{id:"menu-toggle",type:"checkbox"}}),t._v(" "),a("ul",{staticClass:"list pa0 mt4 mb0",attrs:{id:"mobile-menu"}},[a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              Home\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_people\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_journal\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_find\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_changelog\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_settings\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_signout\n            ")])])])]),t._v(" "),a("div",{staticClass:"absolute pa2 header-logo"},[a("a",{attrs:{href:""}},[a("img",{attrs:{src:"/img/logo.svg",width:"30",height:"27"}})])])])])}],!1,null,"d407c658",null));e.a=d.exports},"1VY+":function(t,e,a){var n=a("H/p6");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},"4oIC":function(t,e,a){var n=a("9VpW");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},"7pdS":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,"\ninput[type=checkbox][data-v-5ec08026] {\n  top: 5px;\n}\ninput[type=radio][data-v-5ec08026] {\n  top: -2px;\n}\n",""])},"9VpW":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".error-explanation[data-v-462e0c97] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-462e0c97]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}",""])},Gqdg:function(t,e,a){var n=a("K1O4");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},"H/p6":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".find-box[data-v-d407c658] {\n  border: 1px solid rgba(27, 31, 35, 0.15);\n  box-shadow: 0 3px 12px rgba(27, 31, 35, 0.15);\n  top: 63px;\n  width: 500px;\n  left: 0;\n  right: 0;\n  margin: 0 auto;\n}\n.notifications-box[data-v-d407c658] {\n  border: 1px solid rgba(27, 31, 35, 0.15);\n  box-shadow: 0 3px 12px rgba(27, 31, 35, 0.15);\n  top: 63px;\n  width: 500px;\n  left: 0;\n  right: 0;\n  margin: 0 auto;\n}\n.notification-item[data-v-d407c658]:last-child {\n  border-bottom: 0;\n}\n.notification-date[data-v-d407c658] {\n  color: #777A88;\n}\n.bg-modal-find[data-v-d407c658] {\n  position: fixed;\n  z-index: 100;\n  top: 0;\n  bottom: 0;\n  left: 0;\n  right: 0;\n  background-color: rgba(0, 0, 0, 0.3);\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}",""])},IQOQ:function(t,e,a){t.exports=function(){var t="undefined"!=typeof window,e="undefined"!=typeof navigator,a=t&&("ontouchstart"in window||e&&navigator.msMaxTouchPoints>0)?["touchstart","click"]:["click"],n=function(t){return t},s={instances:[]};function i(t){var e="function"==typeof t;if(!e&&"object"!=typeof t)throw new Error("v-click-outside: Binding value must be a function or an object");return{handler:e?t:t.handler,middleware:t.middleware||n,events:t.events||a,isActive:!(!1===t.isActive)}}function r(t){var e=t.el,a=t.event,n=t.handler,s=t.middleware;a.target!==e&&!e.contains(a.target)&&s(a,e)&&n(a,e)}function o(t){var e=t.el,a=t.handler,n=t.middleware;return{el:e,eventHandlers:t.events.map(function(t){return{event:t,handler:function(t){return r({event:t,el:e,handler:a,middleware:n})}}})}}function l(t){var e=s.instances.findIndex(function(e){return e.el===t});-1!==e&&(s.instances[e].eventHandlers.forEach(function(t){return document.removeEventListener(t.event,t.handler)}),s.instances.splice(e,1))}return s.bind=function(t,e){var a=i(e.value);if(a.isActive){var n=o({el:t,events:a.events,handler:a.handler,middleware:a.middleware});n.eventHandlers.forEach(function(t){var e=t.event,a=t.handler;return setTimeout(function(){return document.addEventListener(e,a)},0)}),s.instances.push(n)}},s.update=function(t,e){var a=e.value,n=e.oldValue;if(JSON.stringify(a)!==JSON.stringify(n)){var c=i(a),d=c.events,u=c.handler,p=c.middleware;if(c.isActive){var m=s.instances.find(function(e){return e.el===t});m?(m.eventHandlers.forEach(function(t){return document.removeEventListener(t.event,t.handler)}),m.eventHandlers=d.map(function(e){return{event:e,handler:function(e){return r({event:e,el:t,handler:u,middleware:p})}}})):(m=o({el:t,events:d,handler:u,middleware:p}),s.instances.push(m)),m.eventHandlers.forEach(function(t){var e=t.event,a=t.handler;return setTimeout(function(){return document.addEventListener(e,a)},0)})}else l(t)}},s.unbind=l,{install:function(t){t.directive("click-outside",s)},directive:s}}()},K1O4:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,'\n.menu[data-v-2862e068] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  top: 36px;\n}\n.menu[data-v-2862e068]:after,\n.menu[data-v-2862e068]:before {\n  content: "";\n  display: inline-block;\n  position: absolute;\n}\n.menu[data-v-2862e068]:after {\n  border: 7px solid transparent;\n  border-bottom-color: #fff;\n  left: auto;\n  right: 10px;\n  top: -14px;\n}\n.menu[data-v-2862e068]:before {\n  border: 8px solid transparent;\n  border-bottom-color: rgba(27,31,35,.15);\n  left: auto;\n  right: 9px;\n  top: -16px;\n}\n.notifications[data-v-2862e068] {\n  background-color: #FAE19A;\n}\n',""])},UMxu:function(t,e,a){"use strict";a.r(e);var n=a("pF+r"),s=a("rrJu"),i=a("Z84v"),r={components:{Layout:a("+SZM").a,TextInput:n.a,Errors:s.a,LoadingButton:i.a},props:{notifications:{type:Array,default:null}},data:function(){return{form:{first_name:null,last_name:null,email:null,permission_level:null,send_invitation:!1,errors:[]},loadingState:"",errorTemplate:Error}},methods:{submit:function(){var t=this;this.loadingState="loading",axios.post("/"+this.$page.auth.company.id+"/account/employees",this.form).then(function(e){localStorage.success="The employee has been added",t.$inertia.visit("/"+e.data.company_id+"/account/employees")}).catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))})}}},o=(a("oSTP"),a("KHd+")),l=Object(o.a)(r,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("layout",{attrs:{title:"Home",notifications:t.notifications}},[a("div",{staticClass:"ph2 ph0-ns"},[a("div",{staticClass:"mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"},[a("ul",{staticClass:"list ph0 tc-l tl"},[a("li",{staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/dashboard"}},[t._v("\n            "+t._s(t.$page.auth.company.name)+"\n          ")])],1),t._v(" "),a("li",{staticClass:"di"},[t._v("\n          ...\n        ")]),t._v(" "),a("li",{staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/account/employees"}},[t._v("\n            "+t._s(t.$t("app.breadcrumb_account_manage_employees"))+"\n          ")])],1),t._v(" "),a("li",{staticClass:"di"},[t._v("\n          "+t._s(t.$t("app.breadcrumb_account_add_employee"))+"\n        ")])])]),t._v(" "),a("div",{staticClass:"mw7 center br3 mb5 bg-white box restricted relative z-1"},[a("div",{staticClass:"pa3 mt5 measure center"},[a("h2",{staticClass:"tc normal mb4"},[t._v("\n          "+t._s(t.$t("account.employee_new_title",{name:t.$page.auth.company.name}))+"\n        ")]),t._v(" "),a("form",{on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[a("errors",{attrs:{errors:t.form.errors}}),t._v(" "),a("text-input",{attrs:{id:"first_name",name:"first_name",errors:t.$page.errors.first_name,label:t.$t("account.employee_new_firstname")},model:{value:t.form.first_name,callback:function(e){t.$set(t.form,"first_name",e)},expression:"form.first_name"}}),t._v(" "),a("text-input",{attrs:{id:"last_name",name:"last_name",errors:t.$page.errors.last_name,label:t.$t("account.employee_new_lastname")},model:{value:t.form.last_name,callback:function(e){t.$set(t.form,"last_name",e)},expression:"form.last_name"}}),t._v(" "),a("text-input",{attrs:{id:"email",name:"email",type:"email",errors:t.$page.errors.email,label:t.$t("account.employee_new_email")},model:{value:t.form.email,callback:function(e){t.$set(t.form,"email",e)},expression:"form.email"}}),t._v(" "),a("hr"),t._v(" "),a("div",{staticClass:"mb3"},[a("p",[t._v(t._s(t.$t("account.employee_new_permission_level")))]),t._v(" "),a("div",{staticClass:"db relative"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.permission_level,expression:"form.permission_level"}],staticClass:"mr1 relative",attrs:{id:"administrator",type:"radio",name:"permission_level",value:"100"},domProps:{checked:t._q(t.form.permission_level,"100")},on:{change:function(e){return t.$set(t.form,"permission_level","100")}}}),t._v(" "),a("label",{staticClass:"pointer",attrs:{for:"administrator"}},[t._v(t._s(t.$t("account.employee_new_administrator")))]),t._v(" "),a("p",{staticClass:"ma0 lh-copy f6 mb3"},[t._v("\n                "+t._s(t.$t("account.employee_new_administrator_desc"))+"\n              ")])]),t._v(" "),a("div",{staticClass:"db relative"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.permission_level,expression:"form.permission_level"}],staticClass:"mr1 relative",attrs:{id:"hr",type:"radio",name:"permission_level",value:"200"},domProps:{checked:t._q(t.form.permission_level,"200")},on:{change:function(e){return t.$set(t.form,"permission_level","200")}}}),t._v(" "),a("label",{staticClass:"pointer",attrs:{for:"hr"}},[t._v(t._s(t.$t("account.employee_new_hr")))]),t._v(" "),a("p",{staticClass:"ma0 lh-copy f6 mb3"},[t._v("\n                "+t._s(t.$t("account.employee_new_hr_desc"))+"\n              ")])]),t._v(" "),a("div",{staticClass:"db relative"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.permission_level,expression:"form.permission_level"}],staticClass:"mr1 relative",attrs:{id:"user",type:"radio",name:"permission_level",value:"300"},domProps:{checked:t._q(t.form.permission_level,"300")},on:{change:function(e){return t.$set(t.form,"permission_level","300")}}}),t._v(" "),a("label",{staticClass:"pointer",attrs:{for:"user"}},[t._v(t._s(t.$t("account.employee_new_user")))]),t._v(" "),a("p",{staticClass:"ma0 lh-copy f6 mb3"},[t._v("\n                "+t._s(t.$t("account.employee_new_user_desc"))+"\n              ")])])]),t._v(" "),a("div",{staticClass:"mb3 ba bb-gray bg-gray pa3"},[a("div",{staticClass:"flex items-start relative"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.send_invitation,expression:"form.send_invitation"}],staticClass:"mr2 relative",attrs:{id:"send_email",type:"checkbox",name:"send_email"},domProps:{checked:Array.isArray(t.form.send_invitation)?t._i(t.form.send_invitation,null)>-1:t.form.send_invitation},on:{change:function(e){var a=t.form.send_invitation,n=e.target,s=!!n.checked;if(Array.isArray(a)){var i=t._i(a,null);n.checked?i<0&&t.$set(t.form,"send_invitation",a.concat([null])):i>-1&&t.$set(t.form,"send_invitation",a.slice(0,i).concat(a.slice(i+1)))}else t.$set(t.form,"send_invitation",s)}}}),t._v(" "),a("label",{staticClass:"lh-copy ma0",attrs:{for:"send_email"}},[t._v(t._s(t.$t("account.employee_new_send_email")))])])]),t._v(" "),a("div",{staticClass:"mv4"},[a("div",{staticClass:"flex-ns justify-between"},[a("div",[a("inertia-link",{staticClass:"btn btn-secondary dib tc w-auto-ns w-100 mb2 pv2 ph3",attrs:{href:"/"+t.$page.auth.company.id+"/account/employees"}},[t._v("\n                  "+t._s(t.$t("app.cancel"))+"\n                ")])],1),t._v(" "),a("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 mb2 pv2 ph3",state:t.loadingState,text:t.$t("app.save"),"cypress-selector":"submit-add-employee-button"}})],1)])],1)])])])])},[],!1,null,"5ec08026",null);e.default=l.exports},XJyQ:function(t,e,a){"use strict";var n=a("4oIC");a.n(n).a},Z84v:function(t,e,a){"use strict";a("zHN7");var n={components:{BallPulseLoader:a("mM8D").a},props:{text:{type:String,default:""},state:{type:String,default:""},classes:{type:String,default:""},cypressSelector:{type:String,default:""}}},s=a("KHd+"),i=Object(s.a)(n,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"di"},[a("button",{class:t.classes,attrs:{name:"save",type:"submit","data-cy":t.cypressSelector}},["loading"==t.state?a("ball-pulse-loader",{attrs:{color:"#fff",size:"7px"}}):t._e(),t._v(" "),"loading"!=t.state?a("span",[t._v(t._s(t.text))]):t._e()],1)])},[],!1,null,null,null);e.a=i.exports},hDtB:function(t,e,a){"use strict";var n=a("Gqdg");a.n(n).a},idHB:function(t,e,a){"use strict";var n=a("1VY+");a.n(n).a},oSTP:function(t,e,a){"use strict";var n=a("oamw");a.n(n).a},oamw:function(t,e,a){var n=a("7pdS");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},"pF+r":function(t,e,a){"use strict";var n={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-input-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},customRef:{type:String,default:"input"},name:{type:String,default:"input"},datacy:{type:String,default:""},placeholder:{type:String,default:""},help:{type:String,default:""},label:{type:String,default:""},required:{type:Boolean,default:!1},extraClassUpperDiv:{type:String,default:"mb3"},errors:{type:Array,default:function(){return[]}}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,e){this.$refs.input.setSelectionRange(t,e)},sendEscKey:function(){this.$emit("esc-key-pressed")}}},s=(a("XJyQ"),a("KHd+")),i=Object(s.a)(n,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{class:t.extraClassUpperDiv},[t.label?a("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:t.id}},[t._v(t._s(t.label))]):t._e(),t._v(" "),a("input",t._b({ref:t.customRef,staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",class:{error:t.errors.length},attrs:{id:t.id,required:t.required?"required":"",type:t.type,name:t.name,placeholder:t.placeholder,"data-cy":t.datacy},domProps:{value:t.value},on:{input:function(e){return t.$emit("input",e.target.value)},keydown:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"])?null:t.sendEscKey(e)}}},"input",t.$attrs,!1)),t._v(" "),t.errors.length?a("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[t._v("\n    "+t._s(t.errors[0])+"\n  ")]):t._e(),t._v(" "),t.help?a("p",{staticClass:"f7 mb3 lh-title"},[t._v("\n    "+t._s(t.help)+"\n  ")]):t._e()])},[],!1,null,"462e0c97",null);e.a=i.exports},rrJu:function(t,e,a){"use strict";var n={props:{errors:{type:Object,default:null}}},s=a("KHd+"),i=Object(s.a)(n,function(){var t=this,e=t.$createElement,a=t._self._c||e;return Object.keys(t.errors).length>0?a("div",[a("p",[t._v("app.error_title")]),t._v(" "),a("br"),t._v(" "),t._l(t.errors,function(e){return a("ul",{key:e.id},t._l(e,function(e){return a("li",{key:e.id},[t._v("\n      "+t._s(e)+"\n    ")])}),0)})],2):t._e()},[],!1,null,null,null);e.a=i.exports}}]);
//# sourceMappingURL=6.js.map?id=3624651cce9cee921e6e