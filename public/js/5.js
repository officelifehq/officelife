(window.webpackJsonp=window.webpackJsonp||[]).push([[5,28],{"+SZM":function(t,e,a){"use strict";var s=a("IQOQ"),n=a.n(s),o={props:{notifications:{type:Array,default:null},show:{type:Boolean,default:!1}},data:function(){return{showNotifications:!1}},created:function(){this.showNotifications=this.show},methods:{}},i=a("KHd+"),r=Object(i.a)(o,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"bg-white tl br2 absolute z-max"},[a("div",{directives:[{name:"show",rawName:"v-show",value:t.show,expression:"show"}]},[a("div",{directives:[{name:"show",rawName:"v-show",value:0==t.notifications.length,expression:"notifications.length == 0"}]},[a("img",{staticClass:"db center mb2",attrs:{srcset:"/img/header/notification_blank.png, /img/header/notitication_blank@2x.png 2x"}}),t._v(" "),a("p",{staticClass:"tc"},[t._v("\n        All is clear!\n      ")])]),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:t.notifications.length>0,expression:"notifications.length > 0"}],staticClass:"pa0 ma0 list"},t._l(t.notifications,function(e){return a("li",{key:e.id,staticClass:"bb pa2 notification-item bb-gray"},[a("span",{staticClass:"db mb1"},[t._v(t._s(e.localized_content))]),t._v(" "),a("span",{staticClass:"f7 dib notification-date"},[t._v(t._s(t._f("moment")(e.created_at,"from","now")))])])}),0)])])},[],!1,null,"2183c67a",null).exports,l={directives:{clickOutside:n.a.directive},components:{NotificationsComponent:r},props:{notifications:{type:Array,default:null}},data:function(){return{menu:!1,showNotifications:!1}},methods:{}},c=(a("hDtB"),{components:{UserMenu:Object(i.a)(l,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"relative"},[a("div",{staticClass:"relative"},[a("span",{staticClass:"mr2 f6 notifications pv1 ph2 br3 pointer",on:{click:function(e){e.preventDefault(),t.showNotifications=!t.showNotifications}}},[t._v("🔥 "+t._s(t.notifications.length))]),t._v(" "),a("a",{staticClass:"no-color no-underline relative pointer",attrs:{"data-cy":"header-menu"},on:{click:function(e){e.preventDefault(),t.menu=!t.menu}}},[t._v("\n      "+t._s(t.$page.auth.user.email)+" "),a("span",{staticClass:"dropdown-caret"})])]),t._v(" "),1==t.menu?a("div",{staticClass:"absolute menu br2 bg-white z-max tl pv2 ph3 bounceIn faster"},[a("ul",{staticClass:"list ma0 pa0"},[a("li",{staticClass:"pv2"},[a("inertia-link",{staticClass:"no-color no-underline",attrs:{href:"/home","data-cy":"switch-company-button"}},[t._v("\n          "+t._s(t.$t("app.header_switch_company"))+"\n        ")])],1),t._v(" "),a("li",{staticClass:"pv2"},[a("inertia-link",{staticClass:"no-color no-underline",attrs:{href:"/logout","data-cy":"logout-button"}},[t._v("\n          "+t._s(t.$t("app.header_logout"))+"\n        ")])],1)])]):t._e(),t._v(" "),a("notifications-component",{attrs:{notifications:t.notifications,show:t.showNotifications}})],1)},[],!1,null,"2862e068",null).exports,LoadingButton:a("Z84v").a},directives:{clickOutside:n.a.directive},props:{title:{type:String,default:""},noMenu:{type:Boolean,default:!1},notifications:{type:Array,default:null}},data:function(){return{loadingState:"",modalFind:!1,showModalNotifications:!0,dataReturnedFromSearch:!1,form:{searchTerm:null,errors:[]},employees:[],teams:[]}},watch:{title:function(t){this.updatePageTitle(t)}},mounted:function(){this.updatePageTitle(this.title)},methods:{updatePageTitle:function(t){document.title=t?"".concat(t," | Example app"):"Example app"},showFindModal:function(){var t=this;this.dataReturnedFromSearch=!1,this.form.searchTerm=null,this.employees=[],this.teams=[],this.modalFind=!this.modalFind,this.$nextTick(function(){t.$refs.search.focus()})},showNotifications:function(){var t=this;this.showModalNotifications=!this.showModalNotifications,axios.post("/notifications/read").catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))})},hideNotifications:function(){this.showModalNotifications=!1},submit:function(){var t=this;axios.post("/search/employees",this.form).then(function(e){t.dataReturnedFromSearch=!0,t.employees=e.data.data}).catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))}),axios.post("/search/teams",this.form).then(function(e){t.dataReturnedFromSearch=!0,t.teams=e.data.data}).catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))})}}}),d=(a("idHB"),Object(i.a)(c,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("vue-snotify"),t._v(" "),a("header",{staticClass:"bg-white dn db-m db-l mb3 relative"},[a("div",{staticClass:"ph3 pt1 w-100"},[a("div",{staticClass:"cf"},[t._m(0),t._v(" "),a("div",{staticClass:"fl w-60 tc"},[a("div",{directives:[{name:"show",rawName:"v-show",value:t.noMenu,expression:"noMenu"}],staticClass:"dib w-100"}),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:!t.noMenu,expression:"!noMenu"}],staticClass:"mv2"},[a("li",{staticClass:"di header-menu-item pa2 pointer mr2"},[a("inertia-link",{attrs:{href:"/home"}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-home.svg"}}),t._v("\n                  "+t._s(t.$t("app.header_home"))+"\n                ")])])],1),t._v(" "),a("li",{staticClass:"di header-menu-item pa2 pointer mr2",attrs:{"data-cy":"header-find-link"},on:{click:t.showFindModal}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-find.svg"}}),t._v("\n                "+t._s(t.$t("app.header_find"))+"\n              ")])]),t._v(" "),a("li",{staticClass:"di header-menu-item pa2 pointer",attrs:{"data-cy":"header-notifications-link"},on:{click:t.showNotifications}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-notification.svg"}}),t._v("\n                "+t._s(t.$t("app.header_notifications"))+"\n              ")])]),t._v(" "),t.$page.auth.company&&t.$page.auth.employee.permission_level<=200?a("li",{staticClass:"di header-menu-item pa2 pointer",attrs:{"data-cy":"header-notifications-link"}},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/account"}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-notification.svg"}}),t._v("\n                  Adminland\n                ")])])],1):t._e()])]),t._v(" "),a("div",{staticClass:"fl w-20 pa2 tr relative header-menu-settings"},[a("user-menu",{attrs:{notifications:t.notifications}})],1)])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.modalFind,expression:"modalFind"}],staticClass:"absolute z-max find-box"},[a("div",{staticClass:"br2 bg-white tl pv3 ph3 bounceIn faster"},[a("form",{on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[a("div",{staticClass:"relative"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.searchTerm,expression:"form.searchTerm"}],ref:"search",staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:"search",type:"text",name:"search",placeholder:t.$t("app.header_search_placeholder"),required:""},domProps:{value:t.form.searchTerm},on:{keydown:function(e){if(!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"]))return null;t.modalFind=!1},input:function(e){e.target.composing||t.$set(t.form,"searchTerm",e.target.value)}}}),t._v(" "),a("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 mb2 pv2 ph3 absolute top-0 right-0",state:t.loadingState,text:t.$t("app.search"),"cypress-selector":"header-find-submit"}})],1)]),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:t.dataReturnedFromSearch,expression:"dataReturnedFromSearch"}],staticClass:"pl0 list ma0 mt3",attrs:{"data-cy":"results"}},[a("li",{staticClass:"b mb3"},[a("span",{staticClass:"f6 mb2 dib"},[t._v(t._s(t.$t("app.header_search_employees")))]),t._v(" "),t.employees.length>0?a("ul",{staticClass:"list ma0 pl0"},t._l(t.employees,function(e){return a("li",{key:e.id},[a("a",{attrs:{href:"/"+e.company.id+"/employees/"+e.id}},[t._v(t._s(e.name))])])}),0):a("div",{staticClass:"silver"},[t._v("\n              "+t._s(t.$t("app.header_search_no_employee_found"))+"\n            ")])]),t._v(" "),a("li",{staticClass:"fw5"},[a("span",{staticClass:"f6 mb2 dib"},[t._v(t._s(t.$t("app.header_search_teams")))]),t._v(" "),t.teams.length>0?a("ul",{staticClass:"list ma0 pl0"},t._l(t.teams,function(e){return a("li",{key:e.id},[a("a",{attrs:{href:"/"+e.company.id+"/teams/"+e.id}},[t._v(t._s(e.name))])])}),0):a("div",{staticClass:"silver"},[t._v("\n              "+t._s(t.$t("app.header_search_no_team_found"))+"\n            ")])])])])])]),t._v(" "),t._m(1),t._v(" "),a("div",{class:[t.modalFind?"bg-modal-find":""]}),t._v(" "),t._t("default")],2)},[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"fl w-20 pa2"},[e("a",{staticClass:"relative header-logo",attrs:{href:""}},[e("img",{attrs:{src:"/img/logo.svg",height:"30"}})])])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("header",{staticClass:"bg-white mobile dn-ns mb3"},[a("div",{staticClass:"ph2 pv2 w-100 relative"},[a("div",{staticClass:"pv2 relative menu-toggle"},[a("label",{staticClass:"dib b relative",attrs:{for:"menu-toggle"}},[t._v("Menu")]),t._v(" "),a("input",{attrs:{id:"menu-toggle",type:"checkbox"}}),t._v(" "),a("ul",{staticClass:"list pa0 mt4 mb0",attrs:{id:"mobile-menu"}},[a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              Home\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_people\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_journal\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_find\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_changelog\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_settings\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_signout\n            ")])])])]),t._v(" "),a("div",{staticClass:"absolute pa2 header-logo"},[a("a",{attrs:{href:""}},[a("img",{attrs:{src:"/img/logo.svg",width:"30",height:"27"}})])])])])}],!1,null,"d407c658",null));e.a=d.exports},"1VY+":function(t,e,a){var s=a("H/p6");"string"==typeof s&&(s=[[t.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,n);s.locals&&(t.exports=s.locals)},"3QM4":function(t,e,a){"use strict";var s={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-area-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},datacy:{type:String,default:""},label:{type:String,default:""},help:{type:String,default:""},required:{type:Boolean,default:!1},rows:{type:Number,default:3},errors:{type:Array,default:function(){return[]}}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,e){this.$refs.input.setSelectionRange(t,e)}}},n=(a("J0qz"),a("KHd+")),o=Object(n.a)(s,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"mb3"},[t.label?a("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:t.id}},[t._v(t._s(t.label))]):t._e(),t._v(" "),a("textarea",t._b({ref:"input",staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",class:{error:t.errors.length},attrs:{id:t.id,required:t.required?"required":"",type:t.type,"data-cy":t.datacy,rows:t.rows},domProps:{value:t.value},on:{input:function(e){return t.$emit("input",e.target.value)}}},"textarea",t.$attrs,!1)),t._v(" "),t.errors.length?a("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[t._v("\n    "+t._s(t.errors[0])+"\n  ")]):t._e(),t._v(" "),t.help?a("p",{staticClass:"f7 mb3 lh-title"},[t._v("\n    "+t._s(t.help)+"\n  ")]):t._e()])},[],!1,null,"1e74279b",null);e.a=o.exports},Gqdg:function(t,e,a){var s=a("K1O4");"string"==typeof s&&(s=[[t.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,n);s.locals&&(t.exports=s.locals)},"H/p6":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".find-box[data-v-d407c658] {\n  border: 1px solid rgba(27, 31, 35, 0.15);\n  box-shadow: 0 3px 12px rgba(27, 31, 35, 0.15);\n  top: 63px;\n  width: 500px;\n  left: 0;\n  right: 0;\n  margin: 0 auto;\n}\n.notifications-box[data-v-d407c658] {\n  border: 1px solid rgba(27, 31, 35, 0.15);\n  box-shadow: 0 3px 12px rgba(27, 31, 35, 0.15);\n  top: 63px;\n  width: 500px;\n  left: 0;\n  right: 0;\n  margin: 0 auto;\n}\n.notification-item[data-v-d407c658]:last-child {\n  border-bottom: 0;\n}\n.notification-date[data-v-d407c658] {\n  color: #777A88;\n}\n.bg-modal-find[data-v-d407c658] {\n  position: fixed;\n  z-index: 100;\n  top: 0;\n  bottom: 0;\n  left: 0;\n  right: 0;\n  background-color: rgba(0, 0, 0, 0.3);\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}",""])},IQOQ:function(t,e,a){t.exports=function(){var t="undefined"!=typeof window,e="undefined"!=typeof navigator,a=t&&("ontouchstart"in window||e&&navigator.msMaxTouchPoints>0)?["touchstart","click"]:["click"],s=function(t){return t},n={instances:[]};function o(t){var e="function"==typeof t;if(!e&&"object"!=typeof t)throw new Error("v-click-outside: Binding value must be a function or an object");return{handler:e?t:t.handler,middleware:t.middleware||s,events:t.events||a,isActive:!(!1===t.isActive)}}function i(t){var e=t.el,a=t.event,s=t.handler,n=t.middleware;a.target!==e&&!e.contains(a.target)&&n(a,e)&&s(a,e)}function r(t){var e=t.el,a=t.handler,s=t.middleware;return{el:e,eventHandlers:t.events.map(function(t){return{event:t,handler:function(t){return i({event:t,el:e,handler:a,middleware:s})}}})}}function l(t){var e=n.instances.findIndex(function(e){return e.el===t});-1!==e&&(n.instances[e].eventHandlers.forEach(function(t){return document.removeEventListener(t.event,t.handler)}),n.instances.splice(e,1))}return n.bind=function(t,e){var a=o(e.value);if(a.isActive){var s=r({el:t,events:a.events,handler:a.handler,middleware:a.middleware});s.eventHandlers.forEach(function(t){var e=t.event,a=t.handler;return setTimeout(function(){return document.addEventListener(e,a)},0)}),n.instances.push(s)}},n.update=function(t,e){var a=e.value,s=e.oldValue;if(JSON.stringify(a)!==JSON.stringify(s)){var c=o(a),d=c.events,u=c.handler,p=c.middleware;if(c.isActive){var v=n.instances.find(function(e){return e.el===t});v?(v.eventHandlers.forEach(function(t){return document.removeEventListener(t.event,t.handler)}),v.eventHandlers=d.map(function(e){return{event:e,handler:function(e){return i({event:e,el:t,handler:u,middleware:p})}}})):(v=r({el:t,events:d,handler:u,middleware:p}),n.instances.push(v)),v.eventHandlers.forEach(function(t){var e=t.event,a=t.handler;return setTimeout(function(){return document.addEventListener(e,a)},0)})}else l(t)}},n.unbind=l,{install:function(t){t.directive("click-outside",n)},directive:n}}()},J0qz:function(t,e,a){"use strict";var s=a("J85L");a.n(s).a},J85L:function(t,e,a){var s=a("Z9Ib");"string"==typeof s&&(s=[[t.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,n);s.locals&&(t.exports=s.locals)},K1O4:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,'\n.menu[data-v-2862e068] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  top: 36px;\n}\n.menu[data-v-2862e068]:after,\n.menu[data-v-2862e068]:before {\n  content: "";\n  display: inline-block;\n  position: absolute;\n}\n.menu[data-v-2862e068]:after {\n  border: 7px solid transparent;\n  border-bottom-color: #fff;\n  left: auto;\n  right: 10px;\n  top: -14px;\n}\n.menu[data-v-2862e068]:before {\n  border: 8px solid transparent;\n  border-bottom-color: rgba(27,31,35,.15);\n  left: auto;\n  right: 9px;\n  top: -16px;\n}\n.notifications[data-v-2862e068] {\n  background-color: #FAE19A;\n}\n',""])},"OOk+":function(t,e,a){"use strict";a.r(e);var s=a("dAlk"),n=a("mObZ"),o={components:{Layout:a("+SZM").a,MyWorklogs:s.default,MyMorale:n.default},props:{worklogCount:{type:Number,default:0},moraleCount:{type:Number,default:0},notifications:{type:Array,default:null},ownerPermissionLevel:{type:Number,default:0}}},i=(a("oQho"),a("KHd+")),r=Object(i.a)(o,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("layout",{attrs:{title:"Home",notifications:t.notifications}},[a("div",{staticClass:"ph2 ph0-ns"},[a("div",{staticClass:"cf mt4 mw7 center"},[a("h2",{staticClass:"tc fw5"},[t._v("\n        "+t._s(t.$page.auth.company.name)+"\n      ")])]),t._v(" "),a("div",{staticClass:"cf mw7 center br3 mb5 tc"},[a("div",{staticClass:"cf dib btn-group"},[a("inertia-link",{staticClass:"f6 fl ph3 pv2 dib pointer",class:{selected:"me"==t.$page.auth.user.default_dashboard_view},attrs:{href:"/"+t.$page.auth.company.id+"/dashboard/me"}},[t._v("\n          Me\n        ")]),t._v(" "),a("inertia-link",{staticClass:"f6 fl ph3 pv2 dib pointer",class:{selected:"team"==t.$page.auth.user.default_dashboard_view},attrs:{href:"/"+t.$page.auth.company.id+"/dashboard/team","data-cy":"dashboard-team-tab"}},[t._v("\n          My team\n        ")]),t._v(" "),a("inertia-link",{staticClass:"f6 fl ph3 pv2 dib pointer",class:{selected:"company"==t.$page.auth.user.default_dashboard_view},attrs:{href:"/"+t.$page.auth.company.id+"/dashboard/company","data-cy":"dashboard-company-tab"}},[t._v("\n          My company\n        ")]),t._v(" "),a("inertia-link",{staticClass:"f6 fl ph3 pv2 dib pointer",class:{selected:"hr"==t.$page.auth.user.default_dashboard_view},attrs:{href:"/"+t.$page.auth.company.id+"/dashboard/hr","data-cy":"dashboard-hr-tab"}},[t._v("\n          HR area\n        ")])],1)]),t._v(" "),a("my-worklogs",{staticClass:"mb5",attrs:{"worklog-count":t.worklogCount}}),t._v(" "),a("my-morale",{attrs:{"morale-count":t.moraleCount}}),t._v(" "),a("div",{staticClass:"cf mt4 mw7 center br3 mb3 bg-white box"},[a("div",{staticClass:"pa3"},[a("h2",[t._v("Team")]),t._v(" "),a("ul",[a("li",[t._v("team agenda")]),t._v(" "),a("li",[t._v("anniversaires")]),t._v(" "),a("li",[t._v("latest news")]),t._v(" "),a("li",[t._v("view who is at work or from home")]),t._v(" "),a("li",[t._v("manager: view time off requests")]),t._v(" "),a("li",[t._v("manager: view morale")]),t._v(" "),a("li",[t._v("manager: expense approval")]),t._v(" "),a("li",[t._v("manager: one on one")]),t._v(" "),a("li",[t._v("revue 360 de son boss ou d'employées")])])])]),t._v(" "),a("div",{staticClass:"cf mt4 mw7 center br3 mb3 bg-white box"},[a("div",{staticClass:"pa3"},[a("h2",[t._v("Me")]),t._v(" "),a("ul",[a("li",[t._v("View holidays")]),t._v(" "),a("li",[t._v("Book time off")]),t._v(" "),a("li",[t._v("Log morale")]),t._v(" "),a("li",[t._v("Reply to what you've done this week")]),t._v(" "),a("li",[t._v("Log an expense")]),t._v(" "),a("li",[t._v("View one on ones")]),t._v(" "),a("li",[t._v("View all my tasks")])])])])],1)])},[],!1,null,"da58a1f6",null);e.default=r.exports},Z84v:function(t,e,a){"use strict";a("zHN7");var s={components:{BallPulseLoader:a("mM8D").a},props:{text:{type:String,default:""},state:{type:String,default:""},classes:{type:String,default:""},cypressSelector:{type:String,default:""}}},n=a("KHd+"),o=Object(n.a)(s,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"di"},[a("button",{class:t.classes,attrs:{name:"save",type:"submit","data-cy":t.cypressSelector}},["loading"==t.state?a("ball-pulse-loader",{attrs:{color:"#fff",size:"7px"}}):t._e(),t._v(" "),"loading"!=t.state?a("span",[t._v(t._s(t.text))]):t._e()],1)])},[],!1,null,null,null);e.a=o.exports},Z9Ib:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".error-explanation[data-v-1e74279b] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-1e74279b]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}",""])},dAlk:function(t,e,a){"use strict";a.r(e);var s=a("Z84v"),n=a("3QM4"),o={components:{LoadingButton:s.a,TextArea:n.a},props:{worklogCount:{type:Number,default:0}},data:function(){return{editorShown:!1,form:{content:null,errors:[]},updatedWorklogCount:0,updatedEmployee:null,loadingState:"",successMessage:!1}},created:function(){this.updatedWorklogCount=this.worklogCount,this.updatedEmployee=this.$page.auth.employee},methods:{updateText:function(t){this.form.content=t},showEditor:function(){var t=this;this.editorShown=!0,this.$nextTick(function(){t.$refs.editor.$refs.input.focus()})},store:function(){var t=this;this.loadingState="loading",this.successMessage=!0,this.editorShown=!1,this.updatedEmployee.has_logged_worklog_today=!0,axios.post("/"+this.$page.auth.company.id+"/dashboard/worklog",this.form).then(function(e){t.$snotify.success(t.$t("dashboard.worklog_success_message"),{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0}),t.updatedWorklogCount=t.updatedWorklogCount+1,t.updatedEmployee=e.data.data,t.loadingState=null}).catch(function(e){t.loadingState=null,t.successMessage=!1,t.editorShown=!0,t.updatedEmployee.has_logged_worklog_today=!1,t.form.errors=_.flatten(_.toArray(e.response.data))})}}},i=a("KHd+"),r=Object(i.a)(o,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"cf mw7 center mb2 fw5"},[t._v("\n    🔨 "+t._s(t.$t("dashboard.worklog_title"))+"\n  ")]),t._v(" "),a("div",{staticClass:"cf mw7 center br3 mb3 bg-white box"},[a("div",{staticClass:"pa3"},[a("p",{directives:[{name:"show",rawName:"v-show",value:!t.editorShown&&!t.updatedEmployee.has_logged_worklog_today,expression:"!editorShown && !updatedEmployee.has_logged_worklog_today"}],staticClass:"db mt0"},[a("span",{staticClass:"dib-ns db mb0-ns mb2"},[t._v(t._s(t.$t("dashboard.worklog_placeholder")))]),t._v(" "),a("a",{directives:[{name:"show",rawName:"v-show",value:0!=t.updatedWorklogCount,expression:"updatedWorklogCount != 0"}],staticClass:"ml2-ns pointer"},[t._v(t._s(t.$t("dashboard.worklog_read_previous_entries")))])]),t._v(" "),a("p",{directives:[{name:"show",rawName:"v-show",value:!t.editorShown&&t.updatedEmployee.has_logged_worklog_today&&!t.successMessage,expression:"!editorShown && updatedEmployee.has_logged_worklog_today && !successMessage"}],staticClass:"db mb0 mt0"},[a("span",{staticClass:"dib-ns db mb0-ns mb2"},[t._v(t._s(t.$t("dashboard.worklog_already_logged")))]),t._v(" "),a("a",{directives:[{name:"show",rawName:"v-show",value:0!=t.updatedWorklogCount,expression:"updatedWorklogCount != 0"}],staticClass:"ml2-ns pointer"},[t._v(t._s(t.$t("dashboard.worklog_read_previous_entries")))])]),t._v(" "),a("p",{directives:[{name:"show",rawName:"v-show",value:!t.editorShown&&!t.updatedEmployee.has_logged_worklog_today,expression:"!editorShown && !updatedEmployee.has_logged_worklog_today"}],staticClass:"ma0"},[a("a",{staticClass:"btn btn-secondary dib",attrs:{"data-cy":"log-worklog-cta"},on:{click:function(e){return e.preventDefault(),t.showEditor(e)}}},[t._v(t._s(t.$t("dashboard.worklog_cta")))])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.editorShown&&!t.successMessage,expression:"editorShown && !successMessage"}]},[a("form",{on:{submit:function(e){return e.preventDefault(),t.store()}}},[a("text-area",{ref:"editor",attrs:{datacy:"worklog-content"},model:{value:t.form.content,callback:function(e){t.$set(t.form,"content",e)},expression:"form.content"}}),t._v(" "),a("p",{staticClass:"db lh-copy f6"},[t._v("\n            👋 "+t._s(t.$t("dashboard.worklog_entry_description"))+"\n          ")]),t._v(" "),a("p",{staticClass:"ma0"},[a("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 pv2 ph3 mr2",state:t.loadingState,text:t.$t("app.save"),"cypress-selector":"submit-log-worklog"}}),t._v(" "),a("a",{staticClass:"pointer",on:{click:function(e){e.preventDefault(),t.editorShown=!1}}},[t._v(t._s(t.$t("app.cancel")))])],1)],1)]),t._v(" "),a("p",{directives:[{name:"show",rawName:"v-show",value:t.successMessage,expression:"successMessage"}],staticClass:"db mb3 mt4 tc"},[t._v("\n        "+t._s(t.$t("dashboard.worklog_added"))+"\n      ")])])])])},[],!1,null,"3d0d29e4",null);e.default=r.exports},e0PL:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,"\n.dummy[data-v-da58a1f6] {\n  right: 40px;\n  bottom: 20px;\n}\n",""])},hDtB:function(t,e,a){"use strict";var s=a("Gqdg");a.n(s).a},idHB:function(t,e,a){"use strict";var s=a("1VY+");a.n(s).a},mObZ:function(t,e,a){"use strict";a.r(e);var s={components:{Errors:a("rrJu").a},props:{moraleCount:{type:Number,default:0}},data:function(){return{showEditor:!1,form:{emotion:null,errors:[]},updatedEmployee:null,successMessage:!1}},created:function(){this.updatedEmployee=this.$page.auth.employee},methods:{store:function(t){var e=this;this.successMessage=!0,this.form.emotion=t,axios.post("/"+this.$page.auth.company.id+"/dashboard/morale",this.form).then(function(t){e.moraleCount=e.moraleCount+1,e.updatedEmployee=t.data.data}).catch(function(t){e.successMessage=!1,e.form.errors=t.response.data.errors})}}},n=a("KHd+"),o=Object(n.a)(s,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"cf mw7 center mb2 fw5"},[t._v("\n    🙃 "+t._s(t.$t("dashboard.morale_title"))+"\n  ")]),t._v(" "),a("div",{staticClass:"cf mw7 center br3 mb3 bg-white box"},[a("div",{staticClass:"pa3"},[t.successMessage?a("div",{staticClass:"tc"},[a("p",[t._v("🙌")]),t._v(" "),a("p",[t._v(t._s(t.$t("dashboard.morale_success_message")))])]):t._e(),t._v(" "),t.updatedEmployee.has_logged_morale_today&&!t.successMessage?a("div",{staticClass:"tc"},[a("p",[t._v("🙌")]),t._v(" "),a("p",[t._v(t._s(t.$t("dashboard.morale_already_logged")))])]):t._e(),t._v(" "),t.updatedEmployee.has_logged_morale_today||t.successMessage?t._e():a("div",[a("errors",{attrs:{errors:t.form.errors}}),t._v(" "),a("div",{staticClass:"flex-ns justify-center mt3 mb3"},[a("span",{staticClass:"btn mr3-ns mb0-ns mb2 dib-l db",attrs:{"data-cy":"log-morale-bad"},on:{click:function(e){return e.preventDefault(),t.store(1)}}},[t._v("😡 "+t._s(t.$t("dashboard.morale_emotion_bad")))]),t._v(" "),a("span",{staticClass:"btn mr3-ns mb0-ns mb2 dib-l db",attrs:{"data-cy":"log-morale-normal"},on:{click:function(e){return e.preventDefault(),t.store(2)}}},[t._v("😌 "+t._s(t.$t("dashboard.morale_emotion_normal")))]),t._v(" "),a("span",{staticClass:"btn dib-l db mb0-ns",attrs:{"data-cy":"log-morale-good"},on:{click:function(e){return e.preventDefault(),t.store(3)}}},[t._v("🥳 "+t._s(t.$t("dashboard.morale_emotion_good")))])])],1),t._v(" "),t.updatedEmployee.has_logged_morale_today||t.successMessage?t._e():a("p",{staticClass:"f7 mb0"},[t._v("\n        "+t._s(t.$t("dashboard.morale_rules"))+"\n      ")])])])])},[],!1,null,"8300388a",null);e.default=o.exports},oQho:function(t,e,a){"use strict";var s=a("xzsh");a.n(s).a},rrJu:function(t,e,a){"use strict";var s={props:{errors:{type:Object,default:null}}},n=a("KHd+"),o=Object(n.a)(s,function(){var t=this,e=t.$createElement,a=t._self._c||e;return Object.keys(t.errors).length>0?a("div",[a("p",[t._v("app.error_title")]),t._v(" "),a("br"),t._v(" "),t._l(t.errors,function(e){return a("ul",{key:e.id},t._l(e,function(e){return a("li",{key:e.id},[t._v("\n      "+t._s(e)+"\n    ")])}),0)})],2):t._e()},[],!1,null,null,null);e.a=o.exports},xzsh:function(t,e,a){var s=a("e0PL");"string"==typeof s&&(s=[[t.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,n);s.locals&&(t.exports=s.locals)}}]);
//# sourceMappingURL=5.js.map?id=e42383971ab1ee5ce6b2