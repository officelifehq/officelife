(window.webpackJsonp=window.webpackJsonp||[]).push([[1],{"+GtX":function(t,e,a){var n=a("V0/w");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},"+SZM":function(t,e,a){"use strict";var n=a("IQOQ"),s=a.n(n),i=a("8L3F"),o={props:{popoverOptions:{type:Object,required:!0}},data:function(){return{popperInstance:null}},mounted:function(){this.initPopper(),this.updateOverlayPosition()},methods:{initPopper:function(){var t={},e=this.popoverOptions,a=e.popoverReference,n=e.offset,s=e.placement;n&&(t.offset={offset:n}),s&&(t.placement=s),this.popperInstance=new i.a(a,this.$refs.basePopoverContent,{placement:s,modifiers:{modifiers:t}})},destroyPopover:function(){this.popperInstance&&(this.popperInstance.destroy(),this.popperInstance=null,this.$emit("closePopover"))},updateOverlayPosition:function(){var t=this.$refs.basePopoverOverlay,e=t.getBoundingClientRect();t.style.transform="translate(-".concat(e.x,"px, -").concat(e.y,"px)")}}},r=(a("j8tc"),a("KHd+")),l={components:{BasePopover:Object(r.a)(o,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{ref:"basePopoverContent",staticClass:"base-popover"},[t._t("default")],2),t._v(" "),a("div",{ref:"basePopoverOverlay",staticClass:"base-popover__overlay",on:{click:function(e){return e.stopPropagation(),t.destroyPopover(e)}}})])}),[],!1,null,"98acc004",null).exports},props:{},data:function(){return{isPopoverVisible:!1,popoverOptions:{popoverReference:null,placement:"bottom-end",offset:"0,0"}}},mounted:function(){this.popoverOptions.popoverReference=this.$refs.popoverReference},methods:{closePopover:function(){this.isPopoverVisible=!1},openPopover:function(){this.isPopoverVisible=!0}}},c=(a("UQcS"),Object(r.a)(l,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"relative di"},[a("a",{ref:"popoverReference",staticClass:"no-color no-underline pointer",attrs:{"data-cy":"header-menu"},on:{click:function(e){return e.preventDefault(),t.openPopover(e)}}},[t._v("\n    "+t._s(t.$page.auth.user.email)+"\n  ")]),t._v(" "),t.isPopoverVisible?a("base-popover",{attrs:{"popover-options":t.popoverOptions},on:{closePopover:t.closePopover}},[a("div",{staticClass:"menu pa3 f5 br3"},[a("ul",{staticClass:"list ma0 pa0"},[a("li",{staticClass:"pb2"},[a("inertia-link",{staticClass:"no-color no-underline",attrs:{href:"/home","data-cy":"switch-company-button"}},[t._v("\n            "+t._s(t.$t("app.header_switch_company"))+"\n          ")])],1),t._v(" "),a("li",{staticClass:"pv1"},[a("inertia-link",{staticClass:"no-color no-underline",attrs:{href:"/logout","data-cy":"logout-button"}},[t._v("\n            "+t._s(t.$t("app.header_logout"))+"\n          ")])],1)])])]):t._e()],1)}),[],!1,null,"57847b9a",null).exports),p=a("Z84v"),d={directives:{clickOutside:s.a.directive},props:{notifications:{type:Array,default:null}},data:function(){return{showMenu:!1,numberOfNotifications:0}},created:function(){this.notifications&&(this.numberOfNotifications=this.notifications.length)},methods:{toggleModal:function(){this.showMenu=!1},markRead:function(){var t=this;this.showMenu=!0,axios.post("/"+this.$page.auth.company.id+"/notifications/read").then((function(e){t.numberOfNotifications=0})).catch((function(e){t.form.errors=_.flatten(_.toArray(e.response.data))}))}}},u=(a("ylk4"),Object(r.a)(d,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"relative"},[a("div",{staticClass:"di",on:{click:function(e){return e.preventDefault(),t.markRead()}}},[t.notifications?a("span",{staticClass:"mr2 f6 notifications pv1 ph2 br3 pointer",class:{more:t.numberOfNotifications>0}},[t._v("\n      🔥 "+t._s(t.numberOfNotifications)+"\n    ")]):a("span",{staticClass:"mr2 f6 notifications pv1 ph2 br3 pointer"},[t._v("\n      🔥 0\n    ")])]),t._v(" "),t.showMenu?a("ul",{directives:[{name:"click-outside",rawName:"v-click-outside",value:t.toggleModal,expression:"toggleModal"}],staticClass:"popupmenu absolute right-0 content-menu list z-999 bg-white ba bb-gray br3 pa0"},[t._l(t.notifications,(function(e){return a("li",{key:e.id,staticClass:"pv2 ph3 bb bb-gray lh-copy"},[t._v("\n      "+t._s(e.localized_content)+"\n    ")])})),t._v(" "),a("li",{directives:[{name:"show",rawName:"v-show",value:0==t.notifications,expression:"notifications == 0"}],staticClass:"pv2 ph3 bb bb-gray lh-copy"},[t._v(t._s(t.$t("app.notification_blank_state"))+" 🎉")]),t._v(" "),a("li",{staticClass:"pv2 ph3 f6 tc"},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/notifications"}},[t._v(t._s(t.$t("app.notification_view_all")))])],1)],2):t._e()])}),[],!1,null,"a0e01ffa",null).exports),f={props:["level","message"],data:function(){return{isOpen:!1,isVisibleClass:"is-visible",closeAfter:1e4,levelClass:null,messageText:null}},created:function(){this.level&&(this.levelClass="is-"+this.level),this.message&&(this.messageText=this.message,this.flash());var t=this;window.events.$on("flash",(function(e){return t.flash(e)}))},methods:{flash:function(t){t&&(this.messageText=t.message,this.levelClass="is-"+t.level);var e=this;setTimeout((function(){e.isOpen=!0}),100),this.hide()},hide:function(){var t=this;setTimeout((function(){t.isOpen=!1}),t.closeAfter)}}},v=(a("lA/y"),Object(r.a)(f,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"flash notification fixed o-0 ba pa2 bg-black",class:[t.levelClass,t.isOpen?t.isVisibleClass:""]},[a("button",{staticClass:"delete",on:{click:function(e){t.isOpen=!1}}}),t._v("\n    "+t._s(t.messageText)+"\n")])}),[],!1,null,"d778bc2e",null).exports),h={components:{UserMenu:c,LoadingButton:p.a,NotificationsComponent:u,Toaster:v},directives:{clickOutside:s.a.directive},props:{title:{type:String,default:""},noMenu:{type:Boolean,default:!1},notifications:{type:Array,default:null}},data:function(){return{loadingState:"",modalFind:!1,showModalNotifications:!0,dataReturnedFromSearch:!1,form:{searchTerm:null,errors:{type:Array,default:null}},employees:[],teams:[]}},watch:{title:function(t){this.updatePageTitle(t)}},mounted:function(){this.updatePageTitle(this.title)},methods:{updatePageTitle:function(t){document.title=t?"".concat(t," | OfficeLife"):"OfficeLife"},showFindModal:function(){var t=this;this.dataReturnedFromSearch=!1,this.form.searchTerm=null,this.employees=[],this.teams=[],this.modalFind=!this.modalFind,this.$nextTick((function(){t.$refs.search.focus()}))},submit:function(){var t=this;axios.post("/search/employees",this.form).then((function(e){t.dataReturnedFromSearch=!0,t.employees=e.data.data})).catch((function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))})),axios.post("/search/teams",this.form).then((function(e){t.dataReturnedFromSearch=!0,t.teams=e.data.data})).catch((function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))}))}}},m=(a("R5UP"),Object(r.a)(h,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"dn db-m db-l"},[a("nav",{staticClass:"flex justify-between bb b--white-10"},[a("div",{staticClass:"flex-grow pa2 flex items-center"},[a("inertia-link",{staticClass:"mr3 no-underline pa2 bb-0",attrs:{href:"/home"}},[a("img",{attrs:{src:"/img/logo.svg",height:"30",width:"30"}})]),t._v(" "),t.noMenu?t._e():a("div",[a("inertia-link",{staticClass:"mr2 no-underline pa2 bb-0 special",attrs:{href:"/"+t.$page.auth.company.id+"/dashboard"}},[t._v("\n            🏡 "+t._s(t.$t("app.header_home"))+"\n          ")]),t._v(" "),a("inertia-link",{staticClass:"mr2 no-underline pa2 bb-0 special",attrs:{href:"/"+t.$page.auth.company.id+"/employees"}},[t._v("\n            🧑 "+t._s(t.$t("app.header_employees"))+"\n          ")]),t._v(" "),a("inertia-link",{staticClass:"mr2 no-underline pa2 bb-0 special",attrs:{href:"/"+t.$page.auth.company.id+"/teams","data-cy":"header-teams-link"}},[t._v("\n            👫 "+t._s(t.$t("app.header_teams"))+"\n          ")]),t._v(" "),a("a",{staticClass:"mr2 no-underline pa2 bb-0 special pointer",attrs:{"data-cy":"header-find-link"},on:{click:t.showFindModal}},[t._v("\n            🔍 "+t._s(t.$t("app.header_find"))+"\n          ")]),t._v(" "),t.$page.auth.company&&t.$page.auth.employee.permission_level<=200?a("inertia-link",{staticClass:"no-underline pa2 bb-0 special",attrs:{href:"/"+t.$page.auth.company.id+"/account","data-cy":"header-adminland-link"}},[t._v("\n            👮‍♂️ Adminland\n          ")]):t._e()],1)],1),t._v(" "),a("div",{staticClass:"flex-grow pa2 flex items-center"},[a("notifications-component",{attrs:{notifications:t.notifications}}),t._v(" "),a("user-menu")],1)])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.modalFind,expression:"modalFind"}],staticClass:"absolute z-max find-box"},[a("div",{staticClass:"br2 bg-white tl pv3 ph3 bounceIn faster"},[a("form",{on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[a("div",{staticClass:"relative"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.searchTerm,expression:"form.searchTerm"}],ref:"search",staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:"search",type:"text",name:"search",placeholder:t.$t("app.header_search_placeholder"),required:""},domProps:{value:t.form.searchTerm},on:{keydown:function(e){if(!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"]))return null;t.modalFind=!1},input:function(e){e.target.composing||t.$set(t.form,"searchTerm",e.target.value)}}}),t._v(" "),a("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 mb2 pv2 ph3 absolute top-0 right-0",state:t.loadingState,text:t.$t("app.search"),"cypress-selector":"header-find-submit"}})],1)]),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:t.dataReturnedFromSearch,expression:"dataReturnedFromSearch"}],staticClass:"pl0 list ma0 mt3",attrs:{"data-cy":"results"}},[a("li",{staticClass:"b mb3"},[a("span",{staticClass:"f6 mb2 dib"},[t._v("\n            "+t._s(t.$t("app.header_search_employees"))+"\n          ")]),t._v(" "),t.employees.length>0?a("ul",{staticClass:"list ma0 pl0"},t._l(t.employees,(function(e){return a("li",{key:e.id},[a("inertia-link",{attrs:{href:"/"+e.company.id+"/employees/"+e.id}},[t._v("\n                "+t._s(e.name)+"\n              ")])],1)})),0):a("div",{staticClass:"silver"},[t._v("\n            "+t._s(t.$t("app.header_search_no_employee_found"))+"\n          ")])]),t._v(" "),a("li",{staticClass:"fw5"},[a("span",{staticClass:"f6 mb2 dib"},[t._v("\n            "+t._s(t.$t("app.header_search_teams"))+"\n          ")]),t._v(" "),t.teams.length>0?a("ul",{staticClass:"list ma0 pl0"},t._l(t.teams,(function(e){return a("li",{key:e.id},[a("inertia-link",{attrs:{href:"/"+e.company.id+"/teams/"+e.id}},[t._v("\n                "+t._s(e.name)+"\n              ")])],1)})),0):a("div",{staticClass:"silver"},[t._v("\n            "+t._s(t.$t("app.header_search_no_team_found"))+"\n          ")])])])])]),t._v(" "),t._m(0),t._v(" "),a("div",{class:[t.modalFind?"bg-modal-find":""]}),t._v(" "),t._t("default"),t._v(" "),a("toaster")],2)}),[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("header",{staticClass:"bg-white mobile dn-ns mb3"},[a("div",{staticClass:"ph2 pv2 w-100 relative"},[a("div",{staticClass:"pv2 relative menu-toggle"},[a("label",{staticClass:"dib b relative",attrs:{for:"menu-toggle"}},[t._v("\n          Menu\n        ")]),t._v(" "),a("input",{attrs:{id:"menu-toggle",type:"checkbox"}}),t._v(" "),a("ul",{staticClass:"list pa0 mt4 mb0",attrs:{id:"mobile-menu"}},[a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              Home\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_people\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_journal\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_find\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_changelog\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_settings\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_signout\n            ")])])])]),t._v(" "),a("div",{staticClass:"absolute pa2 header-logo"},[a("a",{attrs:{href:""}},[a("img",{attrs:{src:"/img/logo.svg",width:"30",height:"27"}})])])])])}],!1,null,"af54d5b0",null));e.a=m.exports},FnhU:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,'\n.menu[data-v-57847b9a] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  background-color: #fff;\n}\n.menu[data-v-57847b9a]:after,\n.menu[data-v-57847b9a]:before {\n  content: "";\n  display: inline-block;\n  position: absolute;\n}\n.menu[data-v-57847b9a]:after {\n  border: 7px solid transparent;\n  border-bottom-color: #fff;\n  left: auto;\n  right: 10px;\n  top: -13px;\n}\n.menu[data-v-57847b9a]:before {\n  border: 8px solid transparent;\n  border-bottom-color: rgba(27,31,35,.15);\n  left: auto;\n  right: 9px;\n  top: -16px;\n}\n',""])},KHPh:function(t,e,a){var n=a("TPc8");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},L87s:function(t,e,a){var n=a("W6Fa");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},R5UP:function(t,e,a){"use strict";var n=a("+GtX");a.n(n).a},TPc8:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".notifications[data-v-a0e01ffa] {\n  background-color: #f4f3fb;\n}\n.notifications.more[data-v-a0e01ffa] {\n  background-color: #fde19d;\n}\n.content-menu[data-v-a0e01ffa] {\n  left: auto;\n  width: 240px;\n  box-shadow: 0 1px 15px rgba(27, 31, 35, 0.15);\n  right: 12px;\n  top: 17px;\n}",""])},UQcS:function(t,e,a){"use strict";var n=a("esOP");a.n(n).a},"V0/w":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".find-box[data-v-af54d5b0] {\n  border: 1px solid rgba(27, 31, 35, 0.15);\n  box-shadow: 0 3px 12px rgba(27, 31, 35, 0.15);\n  top: 63px;\n  width: 500px;\n  left: 0;\n  right: 0;\n  margin: 0 auto;\n}\n.bg-modal-find[data-v-af54d5b0] {\n  position: fixed;\n  z-index: 100;\n  top: 0;\n  bottom: 0;\n  left: 0;\n  right: 0;\n  background-color: rgba(0, 0, 0, 0.3);\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\nnav[data-v-af54d5b0] {\n  border-bottom: 1px solid #e0e0e0;\n  background-color: #fff;\n}\nnav a[data-v-af54d5b0] {\n  color: #4d4d4f;\n}\nnav a[data-v-af54d5b0]:hover {\n  border-bottom-width: 0;\n}\nnav a.special[data-v-af54d5b0]:hover {\n  border-radius: 11px;\n  box-shadow: 1px 0px 1px rgba(43, 45, 80, 0.16), -1px 1px 1px rgba(43, 45, 80, 0.16), 0px 1px 4px rgba(43, 45, 80, 0.18);\n}",""])},W6Fa:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".flash.notification[data-v-d778bc2e] {\n  z-index: 99999999999;\n  bottom: 30px;\n  right: 30px;\n  transform: translate(100%);\n  transition: all 0.8s ease-in-out;\n}\n.flash.notification.is-visible[data-v-d778bc2e] {\n  transform: translate(0);\n  opacity: 1;\n}",""])},Z84v:function(t,e,a){"use strict";a("zHN7");var n={components:{BallPulseLoader:a("mM8D").a},props:{text:{type:String,default:""},state:{type:String,default:""},classes:{type:String,default:""},cypressSelector:{type:String,default:""}}},s=a("KHd+"),i=Object(s.a)(n,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"di"},[a("button",{class:t.classes,attrs:{name:"save",type:"submit","data-cy":t.cypressSelector}},["loading"==t.state?a("ball-pulse-loader",{attrs:{color:"#fff",size:"7px"}}):t._e(),t._v(" "),"loading"!=t.state?a("span",[t._v("\n      "+t._s(t.text)+"\n    ")]):t._e()],1)])}),[],!1,null,null,null);e.a=i.exports},esOP:function(t,e,a){var n=a("FnhU");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},j8tc:function(t,e,a){"use strict";var n=a("kse5");a.n(n).a},kse5:function(t,e,a){var n=a("n/6Y");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},"lA/y":function(t,e,a){"use strict";var n=a("L87s");a.n(n).a},"n/6Y":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".base-popover[data-v-98acc004] {\n  position: relative;\n  z-index: 50;\n}\n.base-popover__overlay[data-v-98acc004] {\n  position: absolute;\n  top: 0;\n  left: 0;\n  z-index: 40;\n  width: calc(100vw - 1rem);\n  height: 100vh;\n}",""])},ylk4:function(t,e,a){"use strict";var n=a("KHPh");a.n(n).a}}]);
//# sourceMappingURL=1.js.map?id=296f4f5307b0cc4254d7