(window.webpackJsonp=window.webpackJsonp||[]).push([[30],{F1ul:function(t,e,s){var a=s("qaDU");"string"==typeof a&&(a=[[t.i,a,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};s("aET+")(a,o);a.locals&&(t.exports=a.locals)},Z84v:function(t,e,s){"use strict";s("zHN7");var a={components:{BallPulseLoader:s("mM8D").a},props:{text:{type:String,default:""},state:{type:String,default:""},classes:{type:String,default:""},cypressSelector:{type:String,default:""}}},o=s("KHd+"),r=Object(o.a)(a,function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"di"},[s("button",{class:t.classes,attrs:{name:"save",type:"submit","data-cy":t.cypressSelector}},["loading"==t.state?s("ball-pulse-loader",{attrs:{color:"#218b8a",size:"7px"}}):t._e(),t._v(" "),"loading"!=t.state?s("span",[t._v(t._s(t.text))]):t._e()],1)])},[],!1,null,null,null);e.a=r.exports},dAlk:function(t,e,s){"use strict";s.r(e);var a=s("Z84v"),o={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-input-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},datacy:{type:String,default:""},label:{type:String,default:""},help:{type:String,default:""},required:{type:Boolean,default:!1},errors:{type:Array,default:function(){return[]}}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,e){this.$refs.input.setSelectionRange(t,e)}}},r=(s("zyPt"),s("KHd+")),n=Object(r.a)(o,function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"mb3"},[t.label?s("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:t.id}},[t._v(t._s(t.label))]):t._e(),t._v(" "),s("textarea",t._b({ref:"input",staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",class:{error:t.errors.length},attrs:{id:t.id,required:t.required?"required":"",type:t.type,"data-cy":t.datacy},domProps:{value:t.value},on:{input:function(e){return t.$emit("input",e.target.value)}}},"textarea",t.$attrs,!1)),t._v(" "),t.errors.length?s("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[t._v("\n    "+t._s(t.errors[0])+"\n  ")]):t._e(),t._v(" "),t.help?s("p",{staticClass:"f7 mb3 lh-title"},[t._v("\n    "+t._s(t.help)+"\n  ")]):t._e()])},[],!1,null,"183097eb",null).exports,l={components:{LoadingButton:a.a,TextArea:n},props:{teams:{type:Array,default:null},worklogCount:{type:Number,default:0}},data:function(){return{showEditor:!1,form:{content:null,errors:[]},updatedWorklogCount:0,updatedEmployee:null,loadingState:"",successMessage:!1}},created:function(){this.updatedWorklogCount=this.worklogCount,this.updatedEmployee=this.$page.auth.employee},methods:{updateText:function(t){this.form.content=t},store:function(){var t=this;this.loadingState="loading",axios.post("/"+this.$page.auth.company.id+"/dashboard/worklog",this.form).then(function(e){t.$snotify.success(t.$t("dashboard.worklog_success_message"),{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0}),t.updatedWorklogCount=t.updatedWorklogCount+1,t.updatedEmployee=e.data.data,t.showEditor=!1,t.loadingState=null,t.successMessage=!0}).catch(function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))})}}},i=Object(r.a)(l,function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{staticClass:"cf mw7 center br3 mb3 bg-white box"},[s("div",{staticClass:"pa3"},[s("h2",{staticClass:"mt0 fw5 f4"},[t._v("\n        🔨 "+t._s(t.$t("dashboard.worklog_title"))+"\n      ")]),t._v(" "),s("p",{directives:[{name:"show",rawName:"v-show",value:!t.showEditor&&!t.updatedEmployee.has_logged_worklog_today,expression:"!showEditor && !updatedEmployee.has_logged_worklog_today"}],staticClass:"db"},[s("span",{staticClass:"dib-ns db mb0-ns mb2"},[t._v(t._s(t.$t("dashboard.worklog_placeholder")))]),t._v(" "),s("a",{directives:[{name:"show",rawName:"v-show",value:0!=t.updatedWorklogCount,expression:"updatedWorklogCount != 0"}],staticClass:"ml2-ns pointer"},[t._v(t._s(t.$t("dashboard.worklog_read_previous_entries")))])]),t._v(" "),s("p",{directives:[{name:"show",rawName:"v-show",value:!t.showEditor&&t.updatedEmployee.has_logged_worklog_today&&!t.successMessage,expression:"!showEditor && updatedEmployee.has_logged_worklog_today && !successMessage"}],staticClass:"db mb0"},[s("span",{staticClass:"dib-ns db mb0-ns mb2"},[t._v(t._s(t.$t("dashboard.worklog_already_logged")))]),t._v(" "),s("a",{directives:[{name:"show",rawName:"v-show",value:0!=t.updatedWorklogCount,expression:"updatedWorklogCount != 0"}],staticClass:"ml2-ns pointer"},[t._v(t._s(t.$t("dashboard.worklog_read_previous_entries")))])]),t._v(" "),s("p",{directives:[{name:"show",rawName:"v-show",value:!t.showEditor&&!t.updatedEmployee.has_logged_worklog_today,expression:"!showEditor && !updatedEmployee.has_logged_worklog_today"}],staticClass:"ma0"},[s("a",{staticClass:"btn btn-secondary dib",attrs:{"data-cy":"log-worklog-cta"},on:{click:function(e){e.preventDefault(),t.showEditor=!0}}},[t._v(t._s(t.$t("dashboard.worklog_cta")))])]),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:t.showEditor,expression:"showEditor"}]},[s("form",{on:{submit:function(e){return e.preventDefault(),t.store()}}},[s("text-area",{attrs:{datacy:"worklog-content"},model:{value:t.form.content,callback:function(e){t.$set(t.form,"content",e)},expression:"form.content"}}),t._v(" "),s("p",{staticClass:"db lh-copy f6"},[t._v("\n            👋 "+t._s(t.$t("dashboard.worklog_entry_description"))+"\n          ")]),t._v(" "),s("p",{staticClass:"ma0"},[s("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 pv2 ph3 mr2",state:t.loadingState,text:t.$t("app.save"),"cypress-selector":"submit-log-worklog"}}),t._v(" "),s("a",{staticClass:"pointer",on:{click:function(e){e.preventDefault(),t.showEditor=!1}}},[t._v(t._s(t.$t("app.cancel")))])],1)],1)]),t._v(" "),s("p",{directives:[{name:"show",rawName:"v-show",value:t.successMessage,expression:"successMessage"}],staticClass:"db mb3 mt4 tc"},[t._v("\n        "+t._s(t.$t("dashboard.worklog_added"))+"\n      ")])])])])},[],!1,null,"7d3f6ff4",null);e.default=i.exports},qaDU:function(t,e,s){(t.exports=s("I1BE")(!1)).push([t.i,".error-explanation[data-v-183097eb] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-183097eb]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}",""])},zyPt:function(t,e,s){"use strict";var a=s("F1ul");s.n(a).a}}]);