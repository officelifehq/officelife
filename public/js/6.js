(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{"16MF":function(t,e,n){var a=n("SVIm");"string"==typeof a&&(a=[[t.i,a,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(a,r);a.locals&&(t.exports=a.locals)},"1krx":function(t,e,n){(t.exports=n("I1BE")(!1)).push([t.i,".error-explanation[data-v-baf4edc2] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-baf4edc2]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}",""])},"3QM4":function(t,e,n){"use strict";var a={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-area-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},datacy:{type:String,default:""},label:{type:String,default:""},help:{type:String,default:""},required:{type:Boolean,default:!1},rows:{type:Number,default:3},errors:{type:Array,default:function(){return[]}}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,e){this.$refs.input.setSelectionRange(t,e)}}},r=(n("9cby"),n("KHd+")),s=Object(r.a)(a,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"mb3"},[t.label?n("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:t.id}},[t._v("\n    "+t._s(t.label)+"\n  ")]):t._e(),t._v(" "),n("textarea",t._b({ref:"input",staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",class:{error:t.errors.length},attrs:{id:t.id,required:t.required?"required":"",type:t.type,"data-cy":t.datacy,rows:t.rows},domProps:{value:t.value},on:{input:function(e){return t.$emit("input",e.target.value)}}},"textarea",t.$attrs,!1)),t._v(" "),t.errors.length?n("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[t._v("\n    "+t._s(t.errors[0])+"\n  ")]):t._e(),t._v(" "),t.help?n("p",{staticClass:"f7 mb3 lh-title"},[t._v("\n    "+t._s(t.help)+"\n  ")]):t._e()])}),[],!1,null,"baf4edc2",null);e.a=s.exports},"3kjz":function(t,e,n){"use strict";n.r(e);var a=n("pF+r"),r=n("3QM4"),s=n("rrJu"),i=n("Z84v"),o={components:{Layout:n("+SZM").a,TextInput:a.a,TextArea:r.a,Errors:s.a,LoadingButton:i.a},props:{notifications:{type:Array,default:null},news:{type:Object,default:null}},data:function(){return{form:{title:null,content:null,errors:[]},loadingState:"",errorTemplate:Error}},created:function(){this.form.title=this.news.title,this.form.content=this.news.content},methods:{submit:function(){var t=this;this.loadingState="loading",axios.put("/"+this.$page.auth.company.id+"/account/news/"+this.news.id,this.form).then((function(e){localStorage.success="The news has been updated",t.$inertia.visit("/"+e.data.data.company.id+"/account/news")})).catch((function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))}))}}},l=(n("A2Go"),n("KHd+")),c=Object(l.a)(o,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("layout",{attrs:{title:"Home",notifications:t.notifications}},[n("div",{staticClass:"ph2 ph0-ns"},[n("div",{staticClass:"mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"},[n("ul",{staticClass:"list ph0 tc-l tl"},[n("li",{staticClass:"di"},[n("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/dashboard"}},[t._v(t._s(t.$page.auth.company.name))])],1),t._v(" "),n("li",{staticClass:"di"},[t._v("\n          ...\n        ")]),t._v(" "),n("li",{staticClass:"di"},[n("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/account/news"}},[t._v(t._s(t.$t("app.breadcrumb_account_manage_company_news")))])],1),t._v(" "),n("li",{staticClass:"di"},[t._v("\n          "+t._s(t.$t("app.breadcrumb_account_edit_company_news"))+"\n        ")])])]),t._v(" "),n("div",{staticClass:"mw7 center br3 mb5 bg-white box restricted relative z-1"},[n("div",{staticClass:"pa3 mt5 measure center"},[n("h2",{staticClass:"tc normal mb4"},[t._v("\n          "+t._s(t.$t("account.company_news_edit_headline",{name:t.$page.auth.company.name}))+"\n        ")]),t._v(" "),n("form",{on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[n("errors",{attrs:{errors:t.form.errors}}),t._v(" "),n("text-input",{attrs:{id:"title",name:"title",datacy:"news-title-input",errors:t.$page.errors.title,label:t.$t("account.company_news_new_title"),help:t.$t("account.company_news_new_title_help"),required:!0},model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}}),t._v(" "),n("text-area",{attrs:{label:t.$t("account.company_news_new_content"),datacy:"news-content-textarea",required:!0,rows:10,help:t.$t("account.company_news_new_content_help")},model:{value:t.form.content,callback:function(e){t.$set(t.form,"content",e)},expression:"form.content"}}),t._v(" "),n("div",{staticClass:"mv4"},[n("div",{staticClass:"flex-ns justify-between"},[n("div",[n("inertia-link",{staticClass:"btn btn-secondary dib tc w-auto-ns w-100 mb2 pv2 ph3",attrs:{href:"/"+t.$page.auth.company.id+"/account/news","data-cy":"cancel-button"}},[t._v("\n                  "+t._s(t.$t("app.cancel"))+"\n                ")])],1),t._v(" "),n("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 mb2 pv2 ph3",state:t.loadingState,text:t.$t("app.publish"),"cypress-selector":"submit-update-news-button"}})],1)])],1)])])])])}),[],!1,null,"12edbc87",null);e.default=c.exports},"9cby":function(t,e,n){"use strict";var a=n("Y36v");n.n(a).a},A2Go:function(t,e,n){"use strict";var a=n("c8QF");n.n(a).a},"FMG/":function(t,e,n){"use strict";var a=n("16MF");n.n(a).a},SVIm:function(t,e,n){(t.exports=n("I1BE")(!1)).push([t.i,".error-explanation[data-v-8778c8da] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-8778c8da]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}",""])},Y36v:function(t,e,n){var a=n("1krx");"string"==typeof a&&(a=[[t.i,a,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(a,r);a.locals&&(t.exports=a.locals)},c8QF:function(t,e,n){var a=n("h/XO");"string"==typeof a&&(a=[[t.i,a,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(a,r);a.locals&&(t.exports=a.locals)},"h/XO":function(t,e,n){(t.exports=n("I1BE")(!1)).push([t.i,"\ninput[type=checkbox][data-v-12edbc87] {\n  top: 5px;\n}\ninput[type=radio][data-v-12edbc87] {\n  top: -2px;\n}\n",""])},"pF+r":function(t,e,n){"use strict";var a={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-input-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},customRef:{type:String,default:"input"},name:{type:String,default:"input"},datacy:{type:String,default:""},placeholder:{type:String,default:""},help:{type:String,default:""},label:{type:String,default:""},required:{type:Boolean,default:!1},extraClassUpperDiv:{type:String,default:"mb3"},errors:{type:Array,default:function(){return[]}}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,e){this.$refs.input.setSelectionRange(t,e)},sendEscKey:function(){this.$emit("esc-key-pressed")}}},r=(n("FMG/"),n("KHd+")),s=Object(r.a)(a,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{class:t.extraClassUpperDiv},[t.label?n("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:t.id}},[t._v("\n    "+t._s(t.label)+"\n  ")]):t._e(),t._v(" "),n("input",t._b({ref:t.customRef,staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",class:{error:t.errors.length},attrs:{id:t.id,required:t.required?"required":"",type:t.type,name:t.name,placeholder:t.placeholder,"data-cy":t.datacy},domProps:{value:t.value},on:{input:function(e){return t.$emit("input",e.target.value)},keydown:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"])?null:t.sendEscKey(e)}}},"input",t.$attrs,!1)),t._v(" "),t.errors.length?n("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[t._v("\n    "+t._s(t.errors[0])+"\n  ")]):t._e(),t._v(" "),t.help?n("p",{staticClass:"f7 mb3 lh-title"},[t._v("\n    "+t._s(t.help)+"\n  ")]):t._e()])}),[],!1,null,"8778c8da",null);e.a=s.exports},rrJu:function(t,e,n){"use strict";var a={props:{errors:{type:Object,default:null}}},r=n("KHd+"),s=Object(r.a)(a,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return Object.keys(t.errors).length>0?n("div",[n("p",[t._v("app.error_title")]),t._v(" "),n("br"),t._v(" "),t._l(t.errors,(function(e){return n("ul",{key:e.id},t._l(e,(function(e){return n("li",{key:e.id},[t._v("\n      "+t._s(e)+"\n    ")])})),0)}))],2):t._e()}),[],!1,null,null,null);e.a=s.exports}}]);
//# sourceMappingURL=6.js.map?id=7e6876d57bdf78b3eee7