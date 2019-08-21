(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{"+SZM":function(t,e,a){"use strict";var s=a("5n2/"),n=a.n(s),i={props:{notifications:{type:Array,default:null}},data:function(){return{menu:!1}},created:function(){window.addEventListener("click",this.close)},beforeDestroy:function(){window.removeEventListener("click",this.close)},methods:{close:function(t){this.$el.contains(t.target)||(this.menu=!1)}}},o=(a("z6qC"),a("KHd+")),r={components:{UserMenu:Object(o.a)(i,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("a",{staticClass:"no-color no-underline relative pointer",attrs:{"data-cy":"header-menu"},on:{click:function(e){e.preventDefault(),t.menu=!t.menu}}},[t._v("\n    "+t._s(t.$page.auth.user.email)+" "),a("span",{staticClass:"dropdown-caret"})]),t._v(" "),1==t.menu?a("div",{staticClass:"absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"},[a("ul",{staticClass:"list ma0 pa0"},[a("li",{staticClass:"pv2"},[a("inertia-link",{staticClass:"no-color no-underline",attrs:{href:"/home","data-cy":"switch-company-button"}},[t._v("\n          "+t._s(t.$t("app.header_switch_company"))+"\n        ")])],1),t._v(" "),a("li",{staticClass:"pv2"},[a("inertia-link",{staticClass:"no-color no-underline",attrs:{href:"/logout","data-cy":"logout-button"}},[t._v("\n          "+t._s(t.$t("app.header_logout"))+"\n        ")])],1)])]):t._e()])},[],!1,null,"8612cfca",null).exports,LoadingButton:a("Z84v").a},directives:{ClickOutside:n.a},props:{title:{type:String,default:""},noMenu:{type:Boolean,default:!1},notifications:{type:Array,default:null}},data:function(){return{loadingState:"",modalFind:!1,showModalNotifications:!1,dataReturnedFromSearch:!1,form:{searchTerm:null,errors:[]},employees:[],teams:[]}},watch:{title:function(t){this.updatePageTitle(t)}},mounted:function(){this.updatePageTitle(this.title),this.popupItem=this.$el},methods:{updatePageTitle:function(t){document.title=t?"".concat(t," | Example app"):"Example app"},showFindModal:function(){var t=this;this.dataReturnedFromSearch=!1,this.form.searchTerm=null,this.employees=[],this.teams=[],this.modalFind=!this.modalFind,this.$nextTick(function(){t.$refs.search.focus()})},showNotifications:function(){var t=this;this.showModalNotifications=!this.showModalNotifications,axios.post("/notifications/read").catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))})},hideNotifications:function(){this.showModalNotifications=!1},submit:function(){var t=this;axios.post("/search/employees",this.form).then(function(e){t.dataReturnedFromSearch=!0,t.employees=e.data.data}).catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))}),axios.post("/search/teams",this.form).then(function(e){t.dataReturnedFromSearch=!0,t.teams=e.data.data}).catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))})}}},l=(a("22nh"),Object(o.a)(r,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("vue-snotify"),t._v(" "),a("header",{staticClass:"bg-white dn db-m db-l mb3 relative"},[a("div",{staticClass:"ph3 pt1 w-100"},[a("div",{staticClass:"cf"},[t._m(0),t._v(" "),a("div",{staticClass:"fl w-60 tc"},[a("div",{directives:[{name:"show",rawName:"v-show",value:t.noMenu,expression:"noMenu"}],staticClass:"dib w-100"}),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:!t.noMenu,expression:"!noMenu"}],staticClass:"mv2"},[a("li",{staticClass:"di header-menu-item pa2 pointer mr2"},[a("inertia-link",{attrs:{href:"/home"}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-home.svg"}}),t._v("\n                  "+t._s(t.$t("app.header_home"))+"\n                ")])])],1),t._v(" "),a("li",{staticClass:"di header-menu-item pa2 pointer mr2",attrs:{"data-cy":"header-find-link"},on:{click:t.showFindModal}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-find.svg"}}),t._v("\n                "+t._s(t.$t("app.header_find"))+"\n              ")])]),t._v(" "),a("li",{staticClass:"di header-menu-item pa2 pointer",attrs:{"data-cy":"header-notifications-link"},on:{click:t.showNotifications}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-notification.svg"}}),t._v("\n                "+t._s(t.$t("app.header_notifications"))+"\n              ")])]),t._v(" "),t.$page.auth.company&&t.$page.auth.employee.permission_level<=200?a("li",{staticClass:"di header-menu-item pa2 pointer",attrs:{"data-cy":"header-notifications-link"}},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/account"}},[a("span",{staticClass:"fw5"},[a("img",{staticClass:"relative",attrs:{src:"/img/header/icon-notification.svg"}}),t._v("\n                  Adminland\n                ")])])],1):t._e()])]),t._v(" "),a("div",{staticClass:"fl w-20 pa2 tr relative header-menu-settings"},[a("user-menu")],1)])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.modalFind,expression:"modalFind"}],staticClass:"absolute z-max find-box"},[a("div",{staticClass:"br2 bg-white tl pv3 ph3 bounceIn faster"},[a("form",{on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[a("div",{staticClass:"relative"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.searchTerm,expression:"form.searchTerm"}],ref:"search",staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:"search",type:"text",name:"search",placeholder:t.$t("app.header_search_placeholder"),required:""},domProps:{value:t.form.searchTerm},on:{keydown:function(e){if(!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"]))return null;t.modalFind=!1},input:function(e){e.target.composing||t.$set(t.form,"searchTerm",e.target.value)}}}),t._v(" "),a("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 mb2 pv2 ph3 absolute top-0 right-0",state:t.loadingState,text:t.$t("app.search"),"cypress-selector":"header-find-submit"}})],1)]),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:t.dataReturnedFromSearch,expression:"dataReturnedFromSearch"}],staticClass:"pl0 list ma0 mt3",attrs:{"data-cy":"results"}},[a("li",{staticClass:"b mb3"},[a("span",{staticClass:"f6 mb2 dib"},[t._v(t._s(t.$t("app.header_search_employees")))]),t._v(" "),t.employees.length>0?a("ul",{staticClass:"list ma0 pl0"},t._l(t.employees,function(e){return a("li",{key:e.id},[a("a",{attrs:{href:"/"+e.company.id+"/employees/"+e.id}},[t._v(t._s(e.name))])])}),0):a("div",{staticClass:"silver"},[t._v("\n              "+t._s(t.$t("app.header_search_no_employee_found"))+"\n            ")])]),t._v(" "),a("li",{staticClass:"fw5"},[a("span",{staticClass:"f6 mb2 dib"},[t._v(t._s(t.$t("app.header_search_teams")))]),t._v(" "),t.teams.length>0?a("ul",{staticClass:"list ma0 pl0"},t._l(t.teams,function(e){return a("li",{key:e.id},[a("a",{attrs:{href:"/"+e.company.id+"/teams/"+e.id}},[t._v(t._s(e.name))])])}),0):a("div",{staticClass:"silver"},[t._v("\n              "+t._s(t.$t("app.header_search_no_team_found"))+"\n            ")])])])])]),t._v(" "),t.showModalNotifications?a("div",{directives:[{name:"click-outside",rawName:"v-click-outside",value:t.hideNotifications,expression:"hideNotifications"}],staticClass:"absolute z-max notifications-box"},[a("div",{staticClass:"br2 bg-white tl pv3 ph3 bounceIn faster"},[a("div",{directives:[{name:"show",rawName:"v-show",value:0==t.notifications.length,expression:"notifications.length == 0"}]},[a("img",{staticClass:"db center mb2",attrs:{srcset:"/img/header/notification_blank.png, /img/header/notitication_blank@2x.png 2x"}}),t._v(" "),a("p",{staticClass:"tc"},[t._v("\n            All is clear!\n          ")])]),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:t.notifications.length>0,expression:"notifications.length > 0"}]},t._l(t.notifications,function(e){return a("li",{key:e.id},[t._v("\n            "+t._s(e.action)+"\n          ")])}),0)])]):t._e()]),t._v(" "),t._m(1),t._v(" "),a("div",{class:[t.modalFind?"bg-modal-find":""]}),t._v(" "),t._t("default")],2)},[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"fl w-20 pa2"},[e("a",{staticClass:"relative header-logo",attrs:{href:""}},[e("img",{attrs:{src:"/img/logo.svg",height:"30"}})])])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("header",{staticClass:"bg-white mobile dn-ns mb3"},[a("div",{staticClass:"ph2 pv2 w-100 relative"},[a("div",{staticClass:"pv2 relative menu-toggle"},[a("label",{staticClass:"dib b relative",attrs:{for:"menu-toggle"}},[t._v("Menu")]),t._v(" "),a("input",{attrs:{id:"menu-toggle",type:"checkbox"}}),t._v(" "),a("ul",{staticClass:"list pa0 mt4 mb0",attrs:{id:"mobile-menu"}},[a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              Home\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_people\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_journal\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_find\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_changelog\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_settings\n            ")])]),t._v(" "),a("li",{staticClass:"pv2 bt b--light-gray"},[a("a",{staticClass:"no-color b no-underline",attrs:{href:""}},[t._v("\n              app.main_nav_signout\n            ")])])])]),t._v(" "),a("div",{staticClass:"absolute pa2 header-logo"},[a("a",{attrs:{href:""}},[a("img",{attrs:{src:"/img/logo.svg",width:"30",height:"27"}})])])])])}],!1,null,"7ee324ac",null));e.a=l.exports},"22nh":function(t,e,a){"use strict";var s=a("mRVG");a.n(s).a},"5n2/":function(t,e){function a(t){return"function"==typeof t.value||(console.warn("[Vue-click-outside:] provided expression",t.expression,"is not a function."),!1)}function s(t){return void 0!==t.componentInstance&&t.componentInstance.$isServer}t.exports={bind:function(t,e,n){function i(e){if(n.context){var a=e.path||e.composedPath&&e.composedPath();a&&a.length>0&&a.unshift(e.target),t.contains(e.target)||function(t,e){if(!t||!e)return!1;for(var a=0,s=e.length;a<s;a++)try{if(t.contains(e[a]))return!0;if(e[a].contains(t))return!1}catch(t){return!1}return!1}(n.context.popupItem,a)||t.__vueClickOutside__.callback(e)}}a(e)&&(t.__vueClickOutside__={handler:i,callback:e.value},!s(n)&&document.addEventListener("click",i))},update:function(t,e){a(e)&&(t.__vueClickOutside__.callback=e.value)},unbind:function(t,e,a){!s(a)&&document.removeEventListener("click",t.__vueClickOutside__.handler),delete t.__vueClickOutside__}}},A1EK:function(t,e,a){var s=a("SDNF");"string"==typeof s&&(s=[[t.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,n);s.locals&&(t.exports=s.locals)},Ddpy:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,'\n.absolute[data-v-8612cfca] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  top: 36px;\n}\n.absolute[data-v-8612cfca]:after,\n.absolute[data-v-8612cfca]:before {\n  content: "";\n  display: inline-block;\n  position: absolute;\n}\n.absolute[data-v-8612cfca]:after {\n  border: 7px solid transparent;\n  border-bottom-color: #fff;\n  left: auto;\n  right: 10px;\n  top: -14px;\n}\n.absolute[data-v-8612cfca]:before {\n  border: 8px solid transparent;\n  border-bottom-color: rgba(27,31,35,.15);\n  left: auto;\n  right: 9px;\n  top: -16px;\n}\n',""])},JXeP:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".error-explanation[data-v-462e0c97] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-462e0c97]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}",""])},SDNF:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,"\n.list li[data-v-7f5d8c51]:last-child {\n  border-bottom: 0;\n}\n",""])},XJyQ:function(t,e,a){"use strict";var s=a("iNuz");a.n(s).a},Z84v:function(t,e,a){"use strict";a("zHN7");var s={components:{BallPulseLoader:a("mM8D").a},props:{text:{type:String,default:""},state:{type:String,default:""},classes:{type:String,default:""},cypressSelector:{type:String,default:""}}},n=a("KHd+"),i=Object(n.a)(s,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"di"},[a("button",{class:t.classes,attrs:{name:"save",type:"submit","data-cy":t.cypressSelector}},["loading"==t.state?a("ball-pulse-loader",{attrs:{color:"#218b8a",size:"7px"}}):t._e(),t._v(" "),"loading"!=t.state?a("span",[t._v(t._s(t.text))]):t._e()],1)])},[],!1,null,null,null);e.a=i.exports},aMQO:function(t,e,a){"use strict";a.r(e);var s=a("pF+r"),n=a("rrJu"),i=a("Z84v"),o={components:{Layout:a("+SZM").a,TextInput:s.a,Errors:n.a,LoadingButton:i.a},props:{notifications:{type:Array,default:null},positions:{type:Array,default:null}},data:function(){return{modal:!1,deleteModal:!1,updateModal:!1,loadingState:"",updateModalId:0,idToUpdate:0,idToDelete:0,form:{title:null,errors:[]}}},methods:{displayAddModal:function(){var t=this;this.modal=!0,this.$nextTick(function(){t.$refs.newPositionModal.$refs.input.focus()})},displayUpdateModal:function(t){var e=this;this.idToUpdate=t.id,this.$nextTick(function(){e.$refs["title".concat(t.id)][0].$refs["title".concat(t.id)].focus()})},submit:function(){var t=this;this.loadingState="loading",axios.post("/"+this.$page.auth.company.id+"/account/positions",this.form).then(function(e){t.$snotify.success(t.$t("account.position_success_new"),{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0}),t.loadingState=null,t.form.title=null,t.modal=!1,t.positions.push(e.data.data)}).catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))})},update:function(t){var e=this;axios.put("/"+this.$page.auth.company.id+"/account/positions/"+t,this.form).then(function(a){e.$snotify.success(e.$t("account.position_success_update"),{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0}),e.idToUpdate=0,e.form.title=null,t=e.positions.findIndex(function(e){return e.id===t}),e.$set(e.positions,t,a.data.data)}).catch(function(t){e.form.errors=_.flatten(_.toArray(t.response.data))})},destroy:function(t){var e=this;axios.delete("/"+this.$page.auth.company.id+"/account/positions/"+t).then(function(a){e.$snotify.success(e.$t("account.position_success_destroy"),{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0}),e.idToDelete=0,t=e.positions.findIndex(function(e){return e.id===t}),e.positions.splice(t,1)}).catch(function(t){e.form.errors=_.flatten(_.toArray(t.response.data))})}}},r=(a("peQz"),a("KHd+")),l=Object(r.a)(o,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("layout",{attrs:{title:"Home",notifications:t.notifications}},[a("div",{staticClass:"ph2 ph0-ns"},[a("div",{staticClass:"mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"},[a("ul",{staticClass:"list ph0 tc-l tl"},[a("li",{staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/dashboard"}},[t._v("\n            "+t._s(t.$page.auth.company.name)+"\n          ")])],1),t._v(" "),a("li",{staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/account"}},[t._v("\n            "+t._s(t.$t("app.breadcrumb_account_home"))+"\n          ")])],1),t._v(" "),a("li",{staticClass:"di"},[t._v("\n          "+t._s(t.$t("app.breadcrumb_account_manage_positions"))+"\n        ")])])]),t._v(" "),a("div",{staticClass:"mw7 center br3 mb5 bg-white box restricted relative z-1"},[a("div",{staticClass:"pa3 mt5"},[a("h2",{staticClass:"tc normal mb4"},[t._v("\n          "+t._s(t.$t("account.positions_title",{company:t.$page.auth.company.name}))+"\n        ")]),t._v(" "),a("p",{staticClass:"relative"},[a("span",{staticClass:"dib mb3 di-l",class:0==t.positions.length?"white":""},[t._v(t._s(t.$tc("account.positions_number_positions",t.positions.length,{company:t.$page.auth.company.name,count:t.positions.length})))]),t._v(" "),a("a",{staticClass:"btn absolute-l relative dib-l db right-0",attrs:{"data-cy":"add-position-button"},on:{click:function(e){return e.preventDefault(),t.displayAddModal(e)}}},[t._v(t._s(t.$t("account.positions_cta")))])]),t._v(" "),a("p",[t._v("The job position is what you would probably put on a resume to show what work you actually did.")]),t._v(" "),a("form",{directives:[{name:"show",rawName:"v-show",value:t.modal,expression:"modal"}],staticClass:"mb3 pa3 ba br2 bb-gray bg-gray",on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[a("errors",{attrs:{errors:t.form.errors}}),t._v(" "),a("div",{staticClass:"cf"},[a("div",{staticClass:"fl w-100 w-70-ns mb3 mb0-ns"},[a("text-input",{ref:"newPositionModal",attrs:{placeholder:"Marketing coordinator",datacy:"add-title-input",errors:t.$page.errors.first_name,"extra-class-upper-div":"mb0"},on:{"esc-key-pressed":function(e){t.modal=!1}},model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),t._v(" "),a("div",{staticClass:"fl w-30-ns w-100 tr"},[a("a",{staticClass:"btn dib-l db mb2 mb0-ns",on:{click:function(e){e.preventDefault(),t.modal=!1}}},[t._v(t._s(t.$t("app.cancel")))]),t._v(" "),a("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 pv2 ph3","data-cy":"modal-add-cta",state:t.loadingState,text:t.$t("app.add")}})],1)])],1),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:0!=t.positions.length,expression:"positions.length != 0"}],staticClass:"list pl0 mv0 center ba br2 bb-gray",attrs:{"data-cy":"positions-list"}},t._l(t.positions,function(e){return a("li",{key:e.id,staticClass:"pv3 ph2 bb bb-gray bb-gray-hover"},[t._v("\n            "+t._s(e.title)+"\n\n            "),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.idToUpdate==e.id,expression:"idToUpdate == position.id"}],staticClass:"cf mt3"},[a("form",{on:{submit:function(a){return a.preventDefault(),t.update(e.id)}}},[a("div",{staticClass:"fl w-100 w-70-ns mb3 mb0-ns"},[a("text-input",{ref:"title"+e.id,refInFor:!0,attrs:{id:"title-"+e.id,placeholder:"Marketing coordinator","custom-ref":"title"+e.id,datacy:"list-rename-input-name-"+e.id,errors:t.$page.errors.first_name,required:"","extra-class-upper-div":"mb0"},on:{"esc-key-pressed":function(e){t.idToUpdate=0}},model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),t._v(" "),a("div",{staticClass:"fl w-30-ns w-100 tr"},[a("a",{staticClass:"btn dib-l db mb2 mb0-ns",attrs:{"data-cy":"list-rename-cancel-button-"+e.id},on:{click:function(e){e.preventDefault(),t.idToUpdate=0}}},[t._v(t._s(t.$t("app.cancel")))]),t._v(" "),a("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 mb2 pv2 ph3","data-cy":"list-rename-cta-button-"+e.id,state:t.loadingState,text:t.$t("app.update")}})],1)])]),t._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:t.idToUpdate!=e.id,expression:"idToUpdate != position.id"}],staticClass:"list pa0 ma0 di-ns db fr-ns mt2 mt0-ns"},[a("li",{staticClass:"di mr2"},[a("a",{staticClass:"pointer",attrs:{"data-cy":"list-rename-button-"+e.id},on:{click:function(a){a.preventDefault(),t.displayUpdateModal(e),t.form.title=e.title}}},[t._v(t._s(t.$t("app.rename")))])]),t._v(" "),t.idToDelete==e.id?a("li",{staticClass:"di"},[t._v("\n                "+t._s(t.$t("app.sure"))+"\n                "),a("a",{staticClass:"c-delete mr1 pointer",attrs:{"data-cy":"list-delete-confirm-button-"+e.id},on:{click:function(a){return a.preventDefault(),t.destroy(e.id)}}},[t._v(t._s(t.$t("app.yes")))]),t._v(" "),a("a",{staticClass:"pointer",attrs:{"data-cy":"list-delete-cancel-button-"+e.id},on:{click:function(e){e.preventDefault(),t.idToDelete=0}}},[t._v(t._s(t.$t("app.no")))])]):a("li",{staticClass:"di"},[a("a",{staticClass:"pointer",attrs:{"data-cy":"list-delete-button-"+e.id},on:{click:function(a){a.preventDefault(),t.idToDelete=e.id}}},[t._v(t._s(t.$t("app.delete")))])])])])}),0),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:0==t.positions.length,expression:"positions.length == 0"}],staticClass:"pa3 mt5"},[a("p",{staticClass:"tc measure center mb4 lh-copy"},[t._v("\n            "+t._s(t.$t("account.positions_blank"))+"\n          ")]),t._v(" "),a("img",{staticClass:"db center mb4",attrs:{srcset:"/img/company/account/blank-position-1x.png, /img/company/account/blank-position-2x.png 2x"}})])])])])])},[],!1,null,"7f5d8c51",null);e.default=l.exports},iNuz:function(t,e,a){var s=a("JXeP");"string"==typeof s&&(s=[[t.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,n);s.locals&&(t.exports=s.locals)},mRVG:function(t,e,a){var s=a("ngXL");"string"==typeof s&&(s=[[t.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,n);s.locals&&(t.exports=s.locals)},ngXL:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,"\n.find-box[data-v-7ee324ac] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  top: 63px;\n  width: 500px;\n  left: 0;\n  right: 0;\n  margin: 0 auto;\n}\n.notifications-box[data-v-7ee324ac] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  top: 63px;\n  width: 500px;\n  left: 0;\n  right: 0;\n  margin: 0 auto;\n}\n.bg-modal-find[data-v-7ee324ac] {\n  position: fixed;\n  z-index: 100;\n  top: 0;\n  bottom: 0;\n  left: 0;\n  right: 0;\n  background-color: rgba(0, 0, 0, 0.3);\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\n",""])},"pF+r":function(t,e,a){"use strict";var s={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-input-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},customRef:{type:String,default:"input"},name:{type:String,default:"input"},datacy:{type:String,default:""},placeholder:{type:String,default:""},help:{type:String,default:""},label:{type:String,default:""},required:{type:Boolean,default:!1},extraClassUpperDiv:{type:String,default:"mb3"},errors:{type:Array,default:function(){return[]}}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,e){this.$refs.input.setSelectionRange(t,e)},sendEscKey:function(){this.$emit("esc-key-pressed")}}},n=(a("XJyQ"),a("KHd+")),i=Object(n.a)(s,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{class:t.extraClassUpperDiv},[t.label?a("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:t.id}},[t._v(t._s(t.label))]):t._e(),t._v(" "),a("input",t._b({ref:t.customRef,staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",class:{error:t.errors.length},attrs:{id:t.id,required:t.required?"required":"",type:t.type,name:t.name,placeholder:t.placeholder,"data-cy":t.datacy},domProps:{value:t.value},on:{input:function(e){return t.$emit("input",e.target.value)},keydown:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"])?null:t.sendEscKey(e)}}},"input",t.$attrs,!1)),t._v(" "),t.errors.length?a("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[t._v("\n    "+t._s(t.errors[0])+"\n  ")]):t._e(),t._v(" "),t.help?a("p",{staticClass:"f7 mb3 lh-title"},[t._v("\n    "+t._s(t.help)+"\n  ")]):t._e()])},[],!1,null,"462e0c97",null);e.a=i.exports},peQz:function(t,e,a){"use strict";var s=a("A1EK");a.n(s).a},qQxn:function(t,e,a){var s=a("Ddpy");"string"==typeof s&&(s=[[t.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,n);s.locals&&(t.exports=s.locals)},rrJu:function(t,e,a){"use strict";var s={props:{errors:{type:Object,default:null}}},n=a("KHd+"),i=Object(n.a)(s,function(){var t=this,e=t.$createElement,a=t._self._c||e;return Object.keys(t.errors).length>0?a("div",[a("p",[t._v("app.error_title")]),t._v(" "),a("br"),t._v(" "),t._l(t.errors,function(e){return a("ul",{key:e.id},t._l(e,function(e){return a("li",{key:e.id},[t._v("\n      "+t._s(e)+"\n    ")])}),0)})],2):t._e()},[],!1,null,null,null);e.a=i.exports},z6qC:function(t,e,a){"use strict";var s=a("qQxn");a.n(s).a}}]);