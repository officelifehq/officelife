(window.webpackJsonp=window.webpackJsonp||[]).push([[1],{"+SZM":function(t,e,a){"use strict";var n=a("IQOQ"),s=a.n(n),o=a("8L3F"),i={props:{popoverOptions:{type:Object,required:!0}},data:function(){return{popperInstance:null}},mounted:function(){this.initPopper(),this.updateOverlayPosition()},methods:{initPopper:function(){var t={},e=this.popoverOptions,a=e.popoverReference,n=e.offset,s=e.placement;n&&(t.offset={offset:n}),s&&(t.placement=s),this.popperInstance=new o.a(a,this.$refs.basePopoverContent,{placement:s,modifiers:{modifiers:t}})},destroyPopover:function(){this.popperInstance&&(this.popperInstance.destroy(),this.popperInstance=null,this.$emit("closePopover"))},updateOverlayPosition:function(){var t=this.$refs.basePopoverOverlay,e=t.getBoundingClientRect();t.style.transform="translate(-".concat(e.x,"px, -").concat(e.y,"px)")}}},r=(a("j8tc"),a("KHd+")),l={components:{BasePopover:Object(r.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{ref:"basePopoverContent",staticClass:"base-popover"},[t._t("default")],2),t._v(" "),a("div",{ref:"basePopoverOverlay",staticClass:"base-popover__overlay",on:{click:function(e){return e.stopPropagation(),t.destroyPopover(e)}}})])}),[],!1,null,"98acc004",null).exports},props:{},data:function(){return{isPopoverVisible:!1,popoverOptions:{popoverReference:null,placement:"bottom-end",offset:"0,0"}}},mounted:function(){this.popoverOptions.popoverReference=this.$refs.popoverReference},methods:{closePopover:function(){this.isPopoverVisible=!1},openPopover:function(){this.isPopoverVisible=!0}}},c=(a("UQcS"),Object(r.a)(l,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"relative di"},[a("a",{ref:"popoverReference",staticClass:"no-color no-underline pointer",attrs:{"data-cy":"header-menu"},on:{click:function(e){return e.preventDefault(),t.openPopover(e)}}},[t._v("\n    "+t._s(t.$page.auth.user.email)+"\n  ")]),t._v(" "),t.isPopoverVisible?a("base-popover",{attrs:{"popover-options":t.popoverOptions},on:{closePopover:t.closePopover}},[a("div",{staticClass:"menu pa3 f5 br3"},[a("ul",{staticClass:"list ma0 pa0"},[a("li",{staticClass:"pb2"},[a("inertia-link",{staticClass:"no-color no-underline",attrs:{href:"/home","data-cy":"switch-company-button"}},[t._v("\n            "+t._s(t.$t("app.header_switch_company"))+"\n          ")])],1),t._v(" "),a("li",{staticClass:"pv1"},[a("inertia-link",{staticClass:"no-color no-underline",attrs:{href:"/logout","data-cy":"logout-button"}},[t._v("\n            "+t._s(t.$t("app.header_logout"))+"\n          ")])],1)])])]):t._e()],1)}),[],!1,null,"57847b9a",null).exports),p=a("Z84v"),d={props:{notifications:{type:Array,default:null}},data:function(){return{}}},u=(a("VWfj"),Object(r.a)(d,(function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"di"},[this.notifications?e("span",{staticClass:"mr2 f6 notifications pv1 ph2 br3 pointer",class:{more:this.notifications.length>0}},[this._v("\n    🔥 "+this._s(this.notifications.length)+"\n  ")]):e("span",{staticClass:"mr2 f6 notifications pv1 ph2 br3 pointer"},[this._v("\n    🔥 0\n  ")])])}),[],!1,null,"2ac86979",null).exports),v={components:{UserMenu:c,LoadingButton:p.a,NotificationsComponent:u},directives:{clickOutside:s.a.directive},props:{title:{type:String,default:""},noMenu:{type:Boolean,default:!1},notifications:{type:Array,default:null}},data:function(){return{loadingState:"",modalFind:!1,showModalNotifications:!0,dataReturnedFromSearch:!1,form:{searchTerm:null,errors:{type:Array,default:null}},employees:[],teams:[]}},watch:{title:function(t){this.updatePageTitle(t)}},mounted:function(){this.updatePageTitle(this.title)},methods:{updatePageTitle:function(t){document.title=t?"".concat(t," | Example app"):"Example app"},showFindModal:function(){var t=this;this.dataReturnedFromSearch=!1,this.form.searchTerm=null,this.employees=[],this.teams=[],this.modalFind=!this.modalFind,this.$nextTick((function(){t.$refs.search.focus()}))},showNotifications:function(){var t=this;this.showModalNotifications=!this.showModalNotifications,axios.post("/notifications/read").catch((function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))}))},hideNotifications:function(){this.showModalNotifications=!1},submit:function(){var t=this;axios.post("/search/employees",this.form).then((function(e){t.dataReturnedFromSearch=!0,t.employees=e.data.data})).catch((function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))})),axios.post("/search/teams",this.form).then((function(e){t.dataReturnedFromSearch=!0,t.teams=e.data.data})).catch((function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))}))}}},f=(a("d4+H"),Object(r.a)(v,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("vue-snotify"),t._v(" "),a("div",{staticClass:"dn db-m db-l"},[a("nav",{staticClass:"flex justify-between bb b--white-10"},[a("div",{staticClass:"flex-grow pa2 flex items-center"},[a("inertia-link",{staticClass:"mr3 no-underline pa2 bb-0",attrs:{href:"/home"}},[a("img",{attrs:{src:"/img/logo.svg",height:"30",width:"30"}})]),t._v(" "),t.noMenu?t._e():a("div",[a("inertia-link",{staticClass:"mr2 no-underline pa2 bb-0 special",attrs:{href:"/"+t.$page.auth.company.id+"/dashboard"}},[t._v("\n            🏡 "+t._s(t.$t("app.header_home"))+"\n          ")]),t._v(" "),a("inertia-link",{staticClass:"mr2 no-underline pa2 bb-0 special",attrs:{href:"/"+t.$page.auth.company.id+"/employees"}},[t._v("\n            👫 "+t._s(t.$t("app.header_employees_teams"))+"\n          ")]),t._v(" "),a("a",{staticClass:"mr2 no-underline pa2 bb-0 special",attrs:{"data-cy":"header-find-link"},on:{click:t.showFindModal}},[t._v("\n            🔍 "+t._s(t.$t("app.header_find"))+"\n          ")]),t._v(" "),t.$page.auth.company&&t.$page.auth.employee.permission_level<=200?a("inertia-link",{staticClass:"no-underline pa2 bb-0 special",attrs:{href:"/"+t.$page.auth.company.id+"/account","data-cy":"header-notifications-link"}},[t._v("\n            👮‍♂️ Adminland\n          ")]):t._e()],1)],1),t._v(" "),a("div",{staticClass:"flex-grow pa2 flex items-center"},[a("notifications-component",{attrs:{notifications:t.notifications}}),t._v(" "),a("user-menu")],1)])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.modalFind,expression:"modalFind"}],staticClass:"absolute z-max find-box"},[a("div",{staticClass:"br2 bg-white tl pv3 ph3 bounceIn faster"},[a("form",{on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[a("div",{staticClass:"relative"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.searchTerm,expression:"form.searchTerm"}],ref:"search",staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:"search",type:"text",name:"search",placeholder:t.$t("app.header_search_placeholder"),required:""},domProps:{value:t.form.searchTerm},on:{keydown:function(e){if(!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"]))return null;t.modalFind=!1},input:function(e){e.target.composing||t.$set(t.form,"searchTerm",e.target.value)}}}),t._v(" "),a("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 mb2 pv2 ph3 absolute top-0 right-0",state:t.loadingState,text:t.$t("app.search"),"cypress-selector":"header-find-submit"}})],1)]),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:t.dataReturnedFromSearch,expression:"dataReturnedFromSearch"}],staticClass:"pl0 list ma0 mt3",attrs:{"data-cy":"results"}},[a("li",{staticClass:"b mb3"},[a("span",{staticClass:"f6 mb2 dib"},[t._v("\n            "+t._s(t.$t("app.header_search_employees"))+"\n          ")]),t._v(" "),t.employees.length>0?a("ul",{staticClass:"list ma0 pl0"},t._l(t.employees,(function(e){return a("li",{key:e.id},[a("inertia-link",{attrs:{href:"/"+e.company.id+"/employees/"+e.id}},[t._v("\n                "+t._s(e.name)+"\n              ")])],1)})),0):a("div",{staticClass:"silver"},[t._v("\n            "+t._s(t.$t("app.header_search_no_employee_found"))+"\n          ")])]),t._v(" "),a("li",{staticClass:"fw5"},[a("span",{staticClass:"f6 mb2 dib"},[t._v("\n            "+t._s(t.$t("app.header_search_teams"))+"\n          ")]),t._v(" "),t.teams.length>0?a("ul",{staticClass:"list ma0 pl0"},t._l(t.teams,(function(e){return a("li",{key:e.id},[a("inertia-link",{attrs:{href:"/"+e.company.id+"/teams/"+e.id}},[t._v("\n                "+t._s(e.name)+"\n              ")])],1)})),0):a("div",{staticClass:"silver"},[t._v("\n            "+t._s(t.$t("app.header_search_no_team_found"))+"\n          ")])])])])]),t._v(" "),t._m(0),t._v(" "),a("div",{class:[t.modalFind?"bg-modal-find":""]}),t._v(" "),t._t("default")],2)}),[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("header",{staticClass:"bg-white mobile dn-ns mb3"},[a("div",{staticClass:"ph2 pv2 w-100 relative"},[a("div",{staticClass:"pv2 relative menu-toggle"},[a("label",{staticClass:"dib b relative",attrs:{for:"menu-toggle"}},[t._v("\n          Menu\n        ")]),t._v(" "),a("input",{attrs:{id:"menu-toggle",type:"checkbox"}}),t._v(" "),a("ul",{staticClass:"list pa0 mt4 mb0",attrs:{id:"mobile-menu"}},[a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              Home\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_people\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_journal\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_find\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_changelog\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_settings\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_signout\n            ")])])])]),t._v(" "),a("div",{staticClass:"absolute pa2 header-logo"},[a("a",{attrs:{href:""}},[a("img",{attrs:{src:"/img/logo.svg",width:"30",height:"27"}})])])])])}],!1,null,"9c62121a",null));e.a=f.exports},"6u/b":function(t,e,a){var n=a("uDV6");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},"9Q1H":function(t,e,a){var n=a("L6O5");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},FnhU:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,'\n.menu[data-v-57847b9a] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  background-color: #fff;\n}\n.menu[data-v-57847b9a]:after,\n.menu[data-v-57847b9a]:before {\n  content: "";\n  display: inline-block;\n  position: absolute;\n}\n.menu[data-v-57847b9a]:after {\n  border: 7px solid transparent;\n  border-bottom-color: #fff;\n  left: auto;\n  right: 10px;\n  top: -13px;\n}\n.menu[data-v-57847b9a]:before {\n  border: 8px solid transparent;\n  border-bottom-color: rgba(27,31,35,.15);\n  left: auto;\n  right: 9px;\n  top: -16px;\n}\n',""])},L6O5:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".notifications[data-v-2ac86979] {\n  background-color: #f4f3fb;\n}\n.notifications.more[data-v-2ac86979] {\n  background-color: #fde19d;\n}",""])},UQcS:function(t,e,a){"use strict";var n=a("esOP");a.n(n).a},VWfj:function(t,e,a){"use strict";var n=a("9Q1H");a.n(n).a},Z84v:function(t,e,a){"use strict";a("zHN7");var n={components:{BallPulseLoader:a("mM8D").a},props:{text:{type:String,default:""},state:{type:String,default:""},classes:{type:String,default:""},cypressSelector:{type:String,default:""}}},s=a("KHd+"),o=Object(s.a)(n,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"di"},[a("button",{class:t.classes,attrs:{name:"save",type:"submit","data-cy":t.cypressSelector}},["loading"==t.state?a("ball-pulse-loader",{attrs:{color:"#fff",size:"7px"}}):t._e(),t._v(" "),"loading"!=t.state?a("span",[t._v("\n      "+t._s(t.text)+"\n    ")]):t._e()],1)])}),[],!1,null,null,null);e.a=o.exports},"d4+H":function(t,e,a){"use strict";var n=a("6u/b");a.n(n).a},esOP:function(t,e,a){var n=a("FnhU");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},j8tc:function(t,e,a){"use strict";var n=a("kse5");a.n(n).a},kse5:function(t,e,a){var n=a("n/6Y");"string"==typeof n&&(n=[[t.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(t.exports=n.locals)},"n/6Y":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".base-popover[data-v-98acc004] {\n  position: relative;\n  z-index: 50;\n}\n.base-popover__overlay[data-v-98acc004] {\n  position: absolute;\n  top: 0;\n  left: 0;\n  z-index: 40;\n  width: calc(100vw - 1rem);\n  height: 100vh;\n}",""])},uDV6:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".find-box[data-v-9c62121a] {\n  border: 1px solid rgba(27, 31, 35, 0.15);\n  box-shadow: 0 3px 12px rgba(27, 31, 35, 0.15);\n  top: 63px;\n  width: 500px;\n  left: 0;\n  right: 0;\n  margin: 0 auto;\n}\n.bg-modal-find[data-v-9c62121a] {\n  position: fixed;\n  z-index: 100;\n  top: 0;\n  bottom: 0;\n  left: 0;\n  right: 0;\n  background-color: rgba(0, 0, 0, 0.3);\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\nnav[data-v-9c62121a] {\n  border-bottom: 1px solid #e0e0e0;\n  background-color: #fff;\n}\nnav a[data-v-9c62121a] {\n  color: #4d4d4f;\n}\nnav a[data-v-9c62121a]:hover {\n  border-bottom-width: 0;\n}\nnav a.special[data-v-9c62121a]:hover {\n  border-radius: 11px;\n  box-shadow: 1px 0px 1px rgba(43, 45, 80, 0.16), -1px 1px 1px rgba(43, 45, 80, 0.16), 0px 1px 4px rgba(43, 45, 80, 0.18);\n}",""])}}]);
//# sourceMappingURL=1.js.map?id=6c53a04e921286076679