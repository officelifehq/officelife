(window.webpackJsonp=window.webpackJsonp||[]).push([[23],{"1krx":function(e,t,s){(e.exports=s("I1BE")(!1)).push([e.i,".error-explanation[data-v-baf4edc2] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-baf4edc2]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}",""])},"3QM4":function(e,t,s){"use strict";var o={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-area-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},datacy:{type:String,default:""},label:{type:String,default:""},help:{type:String,default:""},required:{type:Boolean,default:!1},rows:{type:Number,default:3},errors:{type:Array,default:function(){return[]}}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(e,t){this.$refs.input.setSelectionRange(e,t)}}},a=(s("9cby"),s("KHd+")),r=Object(a.a)(o,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"mb3"},[e.label?s("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:e.id}},[e._v("\n    "+e._s(e.label)+"\n  ")]):e._e(),e._v(" "),s("textarea",e._b({ref:"input",staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",class:{error:e.errors.length},attrs:{id:e.id,required:e.required?"required":"",type:e.type,"data-cy":e.datacy,rows:e.rows},domProps:{value:e.value},on:{input:function(t){return e.$emit("input",t.target.value)}}},"textarea",e.$attrs,!1)),e._v(" "),e.errors.length?s("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[e._v("\n    "+e._s(e.errors[0])+"\n  ")]):e._e(),e._v(" "),e.help?s("p",{staticClass:"f7 mb3 lh-title"},[e._v("\n    "+e._s(e.help)+"\n  ")]):e._e()])}),[],!1,null,"baf4edc2",null);t.a=r.exports},"9cby":function(e,t,s){"use strict";var o=s("Y36v");s.n(o).a},Y36v:function(e,t,s){var o=s("1krx");"string"==typeof o&&(o=[[e.i,o,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};s("aET+")(o,a);o.locals&&(e.exports=o.locals)},Z84v:function(e,t,s){"use strict";s("zHN7");var o={components:{BallPulseLoader:s("mM8D").a},props:{text:{type:String,default:""},state:{type:String,default:""},classes:{type:String,default:""},cypressSelector:{type:String,default:""}}},a=s("KHd+"),r=Object(a.a)(o,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"di"},[s("button",{class:e.classes,attrs:{name:"save",type:"submit","data-cy":e.cypressSelector}},["loading"==e.state?s("ball-pulse-loader",{attrs:{color:"#fff",size:"7px"}}):e._e(),e._v(" "),"loading"!=e.state?s("span",[e._v("\n      "+e._s(e.text)+"\n    ")]):e._e()],1)])}),[],!1,null,null,null);t.a=r.exports},dAlk:function(e,t,s){"use strict";s.r(t);var o=s("Z84v"),a=s("3QM4"),r={components:{LoadingButton:o.a,TextArea:a.a},props:{worklogCount:{type:Number,default:0},employee:{type:Object,default:null}},data:function(){return{editorShown:!1,form:{content:null,errors:[]},updatedWorklogCount:0,updatedEmployee:null,loadingState:"",successMessage:!1}},created:function(){this.updatedWorklogCount=this.worklogCount,this.updatedEmployee=this.employee},methods:{updateText:function(e){this.form.content=e},showEditor:function(){var e=this;this.editorShown=!0,this.$nextTick((function(){e.$refs.editor.$refs.input.focus()}))},store:function(){var e=this;this.loadingState="loading",this.successMessage=!0,this.editorShown=!1,this.updatedEmployee.has_logged_worklog_today=!0,axios.post("/"+this.$page.auth.company.id+"/dashboard/worklog",this.form).then((function(t){e.$snotify.success(e.$t("dashboard.worklog_success_message"),{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0}),e.updatedWorklogCount=e.updatedWorklogCount+1,e.loadingState=null})).catch((function(t){e.loadingState=null,e.successMessage=!1,e.editorShown=!0,e.updatedEmployee.has_logged_worklog_today=!1,e.form.errors=_.flatten(_.toArray(t.response.data))}))}}},n=s("KHd+"),l=Object(n.a)(r,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("div",{staticClass:"cf mw7 center mb2 fw5"},[e._v("\n    🔨 "+e._s(e.$t("dashboard.worklog_title"))+"\n  ")]),e._v(" "),s("div",{staticClass:"cf mw7 center br3 mb3 bg-white box"},[s("div",{staticClass:"pa3"},[s("p",{directives:[{name:"show",rawName:"v-show",value:!e.editorShown&&!e.updatedEmployee.has_logged_worklog_today,expression:"!editorShown && !updatedEmployee.has_logged_worklog_today"}],staticClass:"db mt0"},[s("span",{staticClass:"dib-ns db mb0-ns mb2"},[e._v("\n          "+e._s(e.$t("dashboard.worklog_placeholder"))+"\n        ")]),e._v(" "),s("a",{directives:[{name:"show",rawName:"v-show",value:0!=e.updatedWorklogCount,expression:"updatedWorklogCount != 0"}],staticClass:"ml2-ns pointer"},[e._v("\n          "+e._s(e.$t("dashboard.worklog_read_previous_entries"))+"\n        ")])]),e._v(" "),s("p",{directives:[{name:"show",rawName:"v-show",value:!e.editorShown&&e.updatedEmployee.has_logged_worklog_today&&!e.successMessage,expression:"!editorShown && updatedEmployee.has_logged_worklog_today && !successMessage"}],staticClass:"db mb0 mt0"},[s("span",{staticClass:"dib-ns db mb0-ns mb2"},[e._v("\n          "+e._s(e.$t("dashboard.worklog_already_logged"))+"\n        ")]),e._v(" "),s("inertia-link",{directives:[{name:"show",rawName:"v-show",value:0!=e.updatedWorklogCount,expression:"updatedWorklogCount != 0"}],staticClass:"ml2-ns pointer",attrs:{href:"/"+e.$page.auth.company.id+"/employees/"+e.employee.id+"/worklogs"}},[e._v("\n          "+e._s(e.$t("dashboard.worklog_read_previous_entries"))+"\n        ")])],1),e._v(" "),s("p",{directives:[{name:"show",rawName:"v-show",value:!e.editorShown&&!e.updatedEmployee.has_logged_worklog_today,expression:"!editorShown && !updatedEmployee.has_logged_worklog_today"}],staticClass:"ma0"},[s("a",{staticClass:"btn btn-secondary dib",attrs:{"data-cy":"log-worklog-cta"},on:{click:function(t){return t.preventDefault(),e.showEditor(t)}}},[e._v("\n          "+e._s(e.$t("dashboard.worklog_cta"))+"\n        ")])]),e._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:e.editorShown&&!e.successMessage,expression:"editorShown && !successMessage"}]},[s("form",{on:{submit:function(t){return t.preventDefault(),e.store()}}},[s("text-area",{ref:"editor",attrs:{datacy:"worklog-content"},model:{value:e.form.content,callback:function(t){e.$set(e.form,"content",t)},expression:"form.content"}}),e._v(" "),s("p",{staticClass:"db lh-copy f6"},[e._v("\n            👋 "+e._s(e.$t("dashboard.worklog_entry_description"))+"\n          ")]),e._v(" "),s("p",{staticClass:"ma0"},[s("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 pv2 ph3 mr2",state:e.loadingState,text:e.$t("app.save"),"cypress-selector":"submit-log-worklog"}}),e._v(" "),s("a",{staticClass:"pointer",on:{click:function(t){t.preventDefault(),e.editorShown=!1}}},[e._v("\n              "+e._s(e.$t("app.cancel"))+"\n            ")])],1)],1)]),e._v(" "),s("p",{directives:[{name:"show",rawName:"v-show",value:e.successMessage,expression:"successMessage"}],staticClass:"db mb3 mt4 tc"},[e._v("\n        "+e._s(e.$t("dashboard.worklog_added"))+"\n      ")])])])])}),[],!1,null,"ad8e8cce",null);t.default=l.exports}}]);
//# sourceMappingURL=23.js.map?id=d8f01eead7030e0dcdf5