(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[12],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Shared_Errors__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/Shared/Errors */ "./resources/js/Shared/Errors.vue");
/* harmony import */ var _Shared_LoadingButton__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/Shared/LoadingButton */ "./resources/js/Shared/LoadingButton.vue");
/* harmony import */ var _Shared_Layout__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/Shared/Layout */ "./resources/js/Shared/Layout.vue");
/* harmony import */ var _Pages_Adminland_Flow_Actions__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @/Pages/Adminland/Flow/Actions */ "./resources/js/Pages/Adminland/Flow/Actions.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    Layout: _Shared_Layout__WEBPACK_IMPORTED_MODULE_2__["default"],
    Errors: _Shared_Errors__WEBPACK_IMPORTED_MODULE_0__["default"],
    LoadingButton: _Shared_LoadingButton__WEBPACK_IMPORTED_MODULE_1__["default"],
    Actions: _Pages_Adminland_Flow_Actions__WEBPACK_IMPORTED_MODULE_3__["default"]
  },
  props: {
    notifications: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {
      numberOfSteps: 1,
      isComplete: false,
      numberOfBeforeSteps: 0,
      numberOfAfterSteps: 0,
      oldestStep: 0,
      newestStep: 0,
      form: {
        name: null,
        type: null,
        steps: [],
        errors: []
      },
      loadingState: '',
      errorTemplate: Error
    };
  },
  computed: {
    orderedSteps: function orderedSteps() {
      return _.orderBy(this.form.steps, 'id');
    }
  },
  mounted: function mounted() {
    this.form.steps.push({
      id: 0,
      type: 'same_day',
      frequency: 'days',
      number: 1,
      actions: []
    });
  },
  methods: {
    // Check whether the given step is not the last and not the first
    // Useful to determine if we need to put a separator between steps
    notFirstAndLastStep: function notFirstAndLastStep(id) {
      if (this.oldestStep == id && this.numberOfSteps == 1) {
        return false;
      }

      if (this.newestStep == id) {
        return false;
      }

      return true;
    },
    addStepBefore: function addStepBefore() {
      this.oldestStep = this.oldestStep + 1 * -1;
      this.form.steps.push({
        id: this.oldestStep,
        type: 'before',
        frequency: 'days',
        number: 1,
        actions: []
      });
      this.numberOfSteps = this.numberOfSteps + 1;
      this.numberOfBeforeSteps = this.numberOfBeforeSteps + 1;
    },
    addStepAfter: function addStepAfter() {
      this.newestStep = this.newestStep + 1;
      this.form.steps.push({
        id: this.newestStep,
        type: 'after',
        frequency: 'days',
        number: 1,
        actions: []
      });
      this.numberOfSteps = this.numberOfSteps + 1;
      this.numberOfAfterSteps = this.numberOfAfterSteps + 1;
    },
    removeStep: function removeStep(step) {
      var idToRemove = step.id;
      this.form.steps.splice(this.form.steps.findIndex(function (i) {
        return i.id === step.id;
      }), 1);

      if (step.type == 'before') {
        this.numberOfSteps = this.numberOfSteps - 1;
        this.numberOfBeforeSteps = this.numberOfBeforeSteps - 1;

        if (step.id == this.oldestStep) {
          // this basically calculates what is the mininum number that we should
          // assign to the step
          this.oldestStep = Math.min.apply(Math, this.form.steps.map(function (o) {
            return o.id;
          }));
        }
      }

      if (step.type == 'after') {
        this.numberOfSteps = this.numberOfSteps - 1;
        this.numberOfAfterSteps = this.numberOfAfterSteps - 1;

        if (step.id == this.newestStep) {
          this.newestStep = Math.max.apply(Math, this.form.steps.map(function (o) {
            return o.id;
          }));
        }
      }
    },
    submit: function submit() {
      var _this = this;

      this.loadingState = 'loading';
      axios.post('/' + this.$page.auth.company.id + '/account/flows', this.form).then(function (response) {
        localStorage.success = 'The flow has been added';
        Turbolinks.visit('/' + response.data.company_id + '/account/flows');
      })["catch"](function (error) {
        _this.loadingState = null;
        _this.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    checkComplete: function checkComplete(event) {
      var isCompleteYet = true; // check if the event is selected

      if (this.form.type == null) {
        isCompleteYet = false;
      } // check if a name has been set for the flow


      if (!this.form.name) {
        isCompleteYet = false;
      } // check if all the steps have the all actions they need


      for (var index = 0; index < this.form.steps.length; index++) {
        var actions = this.form.steps[index]['actions'];

        for (var otherIndex = 0; otherIndex < actions.length; otherIndex++) {
          if (actions[otherIndex]['complete'] == false || !actions[otherIndex]['complete']) {
            isCompleteYet = false;
          }
        }
      }

      this.isComplete = isCompleteYet;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Errors.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/Errors.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    errors: {
      type: Object,
      "default": null
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.flow[data-v-21c11e4f] {\n  background-color: #f4f6fa;\n  box-shadow: inset 1px 2px 2px rgba(0, 0, 0, 0.14);\n  border-radius: 8px;\n}\n.box-plus-button[data-v-21c11e4f] {\n  top: -19px;\n}\n.green-box[data-v-21c11e4f] {\n  border: 2px solid #1cbb70;\n}\n.lh0[data-v-21c11e4f] {\n  line-height: 0;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=template&id=21c11e4f&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=template&id=21c11e4f&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "layout",
    { attrs: { title: "Home", notifications: _vm.notifications } },
    [
      _c("div", { staticClass: "ph2 ph0-ns" }, [
        _c(
          "div",
          {
            staticClass:
              "mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
          },
          [
            _c("ul", { staticClass: "list ph0 tc-l tl" }, [
              _c(
                "li",
                { staticClass: "di" },
                [
                  _c(
                    "inertia-link",
                    {
                      attrs: {
                        href: "/" + _vm.$page.auth.company.id + "/dashboard"
                      }
                    },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(_vm.$page.auth.company.name) +
                          "\n          "
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c("li", { staticClass: "di" }, [
                _vm._v("\n          ...\n        ")
              ]),
              _vm._v(" "),
              _c(
                "li",
                { staticClass: "di" },
                [
                  _c(
                    "inertia-link",
                    {
                      attrs: {
                        href: "/" + _vm.$page.auth.company.id + "/account/flows"
                      }
                    },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(
                            _vm.$t("app.breadcrumb_account_manage_flows")
                          ) +
                          "\n          "
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c("li", { staticClass: "di" }, [
                _vm._v(
                  "\n          " +
                    _vm._s(_vm.$t("app.breadcrumb_account_add_employee")) +
                    "\n        "
                )
              ])
            ])
          ]
        ),
        _vm._v(" "),
        _c(
          "div",
          {
            staticClass:
              "mw7 center br3 mb5 bg-white box restricted relative z-1"
          },
          [
            _c(
              "div",
              { staticClass: "pa3 mt5 center" },
              [
                _c("h2", { staticClass: "tc normal mb4" }, [
                  _vm._v(
                    "\n          " +
                      _vm._s(_vm.$t("account.flows_cta")) +
                      "\n        "
                  )
                ]),
                _vm._v(" "),
                _c("errors", { attrs: { errors: _vm.form.errors } }),
                _vm._v(" "),
                _c(
                  "form",
                  {
                    on: {
                      submit: function($event) {
                        $event.preventDefault()
                        return _vm.submit($event)
                      }
                    }
                  },
                  [
                    _c("div", { staticClass: "mb3" }, [
                      _c(
                        "label",
                        {
                          staticClass: "db fw4 lh-copy f6",
                          attrs: { for: "name" }
                        },
                        [_vm._v(_vm._s(_vm.$t("account.flow_new_flow")))]
                      ),
                      _vm._v(" "),
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.form.name,
                            expression: "form.name"
                          }
                        ],
                        staticClass:
                          "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                        attrs: {
                          id: "name",
                          type: "text",
                          name: "name",
                          required: ""
                        },
                        domProps: { value: _vm.form.name },
                        on: {
                          change: _vm.checkComplete,
                          input: function($event) {
                            if ($event.target.composing) {
                              return
                            }
                            _vm.$set(_vm.form, "name", $event.target.value)
                          }
                        }
                      }),
                      _vm._v(" "),
                      _c("p", { staticClass: "f7 mb4 lh-title" }, [
                        _vm._v(
                          "\n              " +
                            _vm._s(_vm.$t("account.flow_new_help")) +
                            "\n            "
                        )
                      ])
                    ]),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "mb3 flow pv4" },
                      _vm._l(_vm.orderedSteps, function(step) {
                        return _c("div", { key: step.id }, [
                          _c(
                            "div",
                            {
                              directives: [
                                {
                                  name: "show",
                                  rawName: "v-show",
                                  value: _vm.oldestStep == step.id,
                                  expression: "oldestStep == step.id"
                                }
                              ],
                              staticClass: "tc lh0"
                            },
                            [
                              _c("img", {
                                staticClass: "center pointer",
                                attrs: {
                                  src: "/img/company/account/flow_plus_top.svg"
                                },
                                on: {
                                  click: function($event) {
                                    return _vm.addStepBefore()
                                  }
                                }
                              })
                            ]
                          ),
                          _vm._v(" "),
                          _c(
                            "div",
                            {
                              staticClass:
                                "step tc measure center bg-white br3 ma3 mt0 mb0 relative",
                              class: {
                                "green-box":
                                  _vm.numberOfSteps > 1 &&
                                  step.type == "same_day"
                              }
                            },
                            [
                              _c("img", {
                                directives: [
                                  {
                                    name: "show",
                                    rawName: "v-show",
                                    value: step.type != "same_day",
                                    expression: "step.type != 'same_day'"
                                  }
                                ],
                                staticClass:
                                  "box-plus-button absolute br-100 pa2 bg-white pointer",
                                attrs: { src: "/img/trash_button.svg" },
                                on: {
                                  click: function($event) {
                                    $event.preventDefault()
                                    return _vm.removeStep(step)
                                  }
                                }
                              }),
                              _vm._v(" "),
                              _c(
                                "div",
                                {
                                  directives: [
                                    {
                                      name: "show",
                                      rawName: "v-show",
                                      value: step.type == "before",
                                      expression: "step.type == 'before'"
                                    }
                                  ],
                                  staticClass: "condition pa3 bb bb-gray"
                                },
                                [
                                  _c(
                                    "ul",
                                    { staticClass: "list ma0 pa0 mb2" },
                                    [
                                      _c("li", { staticClass: "di mr2" }, [
                                        _c("input", {
                                          directives: [
                                            {
                                              name: "model",
                                              rawName: "v-model",
                                              value: step.number,
                                              expression: "step.number"
                                            }
                                          ],
                                          staticClass:
                                            "tc br2 f5 ba b--black-40 pa2 outline-0",
                                          attrs: {
                                            id: "",
                                            type: "number",
                                            min: "1",
                                            max: "100",
                                            name: "",
                                            required: ""
                                          },
                                          domProps: { value: step.number },
                                          on: {
                                            input: function($event) {
                                              if ($event.target.composing) {
                                                return
                                              }
                                              _vm.$set(
                                                step,
                                                "number",
                                                $event.target.value
                                              )
                                            }
                                          }
                                        })
                                      ]),
                                      _vm._v(" "),
                                      _c("li", { staticClass: "di mr2" }, [
                                        _c("input", {
                                          directives: [
                                            {
                                              name: "model",
                                              rawName: "v-model",
                                              value: step.frequency,
                                              expression: "step.frequency"
                                            }
                                          ],
                                          staticClass: "mr1",
                                          attrs: {
                                            id: "frequency_days_" + step.id,
                                            type: "radio",
                                            name: "frequency_before_" + step.id,
                                            value: "days"
                                          },
                                          domProps: {
                                            checked: _vm._q(
                                              step.frequency,
                                              "days"
                                            )
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.$set(
                                                step,
                                                "frequency",
                                                "days"
                                              )
                                            }
                                          }
                                        }),
                                        _vm._v(" "),
                                        _c(
                                          "label",
                                          {
                                            attrs: {
                                              for: "frequency_days_" + step.id
                                            }
                                          },
                                          [
                                            _vm._v(
                                              _vm._s(
                                                _vm.$t("account.flow_new_days")
                                              )
                                            )
                                          ]
                                        )
                                      ]),
                                      _vm._v(" "),
                                      _c("li", { staticClass: "di mr2" }, [
                                        _c("input", {
                                          directives: [
                                            {
                                              name: "model",
                                              rawName: "v-model",
                                              value: step.frequency,
                                              expression: "step.frequency"
                                            }
                                          ],
                                          staticClass: "mr1",
                                          attrs: {
                                            id: "frequency_weeks_" + step.id,
                                            type: "radio",
                                            name: "frequency_before_" + step.id,
                                            value: "weeks"
                                          },
                                          domProps: {
                                            checked: _vm._q(
                                              step.frequency,
                                              "weeks"
                                            )
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.$set(
                                                step,
                                                "frequency",
                                                "weeks"
                                              )
                                            }
                                          }
                                        }),
                                        _vm._v(" "),
                                        _c(
                                          "label",
                                          {
                                            attrs: {
                                              for: "frequency_weeks_" + step.id
                                            }
                                          },
                                          [
                                            _vm._v(
                                              _vm._s(
                                                _vm.$t("account.flow_new_weeks")
                                              )
                                            )
                                          ]
                                        )
                                      ]),
                                      _vm._v(" "),
                                      _c("li", { staticClass: "di" }, [
                                        _c("input", {
                                          directives: [
                                            {
                                              name: "model",
                                              rawName: "v-model",
                                              value: step.frequency,
                                              expression: "step.frequency"
                                            }
                                          ],
                                          staticClass: "mr1",
                                          attrs: {
                                            id: "frequency_months_" + step.id,
                                            type: "radio",
                                            name: "frequency_before_" + step.id,
                                            value: "months"
                                          },
                                          domProps: {
                                            checked: _vm._q(
                                              step.frequency,
                                              "months"
                                            )
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.$set(
                                                step,
                                                "frequency",
                                                "months"
                                              )
                                            }
                                          }
                                        }),
                                        _vm._v(" "),
                                        _c(
                                          "label",
                                          {
                                            attrs: {
                                              for: "frequency_months_" + step.id
                                            }
                                          },
                                          [
                                            _vm._v(
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_months"
                                                )
                                              )
                                            )
                                          ]
                                        )
                                      ])
                                    ]
                                  ),
                                  _vm._v(" "),
                                  _c("p", { staticClass: "ma0 pa0" }, [
                                    _vm._v(
                                      "\n                    " +
                                        _vm._s(
                                          _vm.$t("account.flow_new_before")
                                        ) +
                                        " "
                                    ),
                                    _c("span", { staticClass: "brush-blue" }, [
                                      _vm._v(
                                        _vm._s(
                                          _vm.$t(
                                            "account.flow_new_type_" +
                                              _vm.form.type
                                          )
                                        )
                                      )
                                    ])
                                  ])
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "div",
                                {
                                  directives: [
                                    {
                                      name: "show",
                                      rawName: "v-show",
                                      value: step.type == "same_day",
                                      expression: "step.type == 'same_day'"
                                    }
                                  ],
                                  staticClass: "condition pa3 bb bb-gray"
                                },
                                [
                                  _c("p", { staticClass: "ma0 pa0 mb2" }, [
                                    _vm._v(
                                      "\n                    " +
                                        _vm._s(
                                          _vm.$t(
                                            "account.flow_new_the_day_event_happens"
                                          )
                                        ) +
                                        "\n                  "
                                    )
                                  ]),
                                  _vm._v(" "),
                                  _c(
                                    "select",
                                    {
                                      directives: [
                                        {
                                          name: "model",
                                          rawName: "v-model",
                                          value: _vm.form.type,
                                          expression: "form.type"
                                        }
                                      ],
                                      on: {
                                        change: [
                                          function($event) {
                                            var $$selectedVal = Array.prototype.filter
                                              .call(
                                                $event.target.options,
                                                function(o) {
                                                  return o.selected
                                                }
                                              )
                                              .map(function(o) {
                                                var val =
                                                  "_value" in o
                                                    ? o._value
                                                    : o.value
                                                return val
                                              })
                                            _vm.$set(
                                              _vm.form,
                                              "type",
                                              $event.target.multiple
                                                ? $$selectedVal
                                                : $$selectedVal[0]
                                            )
                                          },
                                          _vm.checkComplete
                                        ]
                                      }
                                    },
                                    [
                                      _c(
                                        "option",
                                        {
                                          attrs: {
                                            value: "employee_joins_company"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            "\n                      " +
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_type_employee_joins_company"
                                                )
                                              ) +
                                              "\n                    "
                                          )
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "option",
                                        {
                                          attrs: {
                                            value: "employee_leaves_company"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            "\n                      " +
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_type_employee_leaves_company"
                                                )
                                              ) +
                                              "\n                    "
                                          )
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "option",
                                        {
                                          attrs: { value: "employee_birthday" }
                                        },
                                        [
                                          _vm._v(
                                            "\n                      " +
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_type_employee_birthday"
                                                )
                                              ) +
                                              "\n                    "
                                          )
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "option",
                                        {
                                          attrs: {
                                            value: "employee_joins_team"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            "\n                      " +
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_type_employee_joins_team"
                                                )
                                              ) +
                                              "\n                    "
                                          )
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "option",
                                        {
                                          attrs: {
                                            value: "employee_leaves_team"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            "\n                      " +
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_type_employee_leaves_team"
                                                )
                                              ) +
                                              "\n                    "
                                          )
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "option",
                                        {
                                          attrs: {
                                            value: "employee_becomes_manager"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            "\n                      " +
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_type_employee_becomes_manager"
                                                )
                                              ) +
                                              "\n                    "
                                          )
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "option",
                                        {
                                          attrs: {
                                            value: "employee_new_position"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            "\n                      " +
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_type_employee_new_position"
                                                )
                                              ) +
                                              "\n                    "
                                          )
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "option",
                                        {
                                          attrs: {
                                            value: "employee_leaves_holidays"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            "\n                      " +
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_type_employee_leaves_holidays"
                                                )
                                              ) +
                                              "\n                    "
                                          )
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "option",
                                        {
                                          attrs: {
                                            value: "employee_returns_holidays"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            "\n                      " +
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_type_employee_returns_holidays"
                                                )
                                              ) +
                                              "\n                    "
                                          )
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "option",
                                        {
                                          attrs: {
                                            value: "employee_returns_leave"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            "\n                      " +
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_type_employee_returns_leave"
                                                )
                                              ) +
                                              "\n                    "
                                          )
                                        ]
                                      )
                                    ]
                                  )
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "div",
                                {
                                  directives: [
                                    {
                                      name: "show",
                                      rawName: "v-show",
                                      value: step.type == "after",
                                      expression: "step.type == 'after'"
                                    }
                                  ],
                                  staticClass: "condition pa3 bb bb-gray"
                                },
                                [
                                  _c(
                                    "ul",
                                    { staticClass: "list ma0 pa0 mb2" },
                                    [
                                      _c("li", { staticClass: "di mr2" }, [
                                        _c("input", {
                                          directives: [
                                            {
                                              name: "model",
                                              rawName: "v-model",
                                              value: step.number,
                                              expression: "step.number"
                                            }
                                          ],
                                          staticClass:
                                            "tc br2 f5 ba b--black-40 pa2 outline-0",
                                          attrs: {
                                            id: "",
                                            type: "number",
                                            min: "1",
                                            max: "100",
                                            name: "",
                                            required: ""
                                          },
                                          domProps: { value: step.number },
                                          on: {
                                            input: function($event) {
                                              if ($event.target.composing) {
                                                return
                                              }
                                              _vm.$set(
                                                step,
                                                "number",
                                                $event.target.value
                                              )
                                            }
                                          }
                                        })
                                      ]),
                                      _vm._v(" "),
                                      _c("li", { staticClass: "di mr2" }, [
                                        _c("input", {
                                          directives: [
                                            {
                                              name: "model",
                                              rawName: "v-model",
                                              value: step.frequency,
                                              expression: "step.frequency"
                                            }
                                          ],
                                          staticClass: "mr1",
                                          attrs: {
                                            id: "frequency_days_" + step.id,
                                            type: "radio",
                                            name: "frequency_after_" + step.id,
                                            value: "days"
                                          },
                                          domProps: {
                                            checked: _vm._q(
                                              step.frequency,
                                              "days"
                                            )
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.$set(
                                                step,
                                                "frequency",
                                                "days"
                                              )
                                            }
                                          }
                                        }),
                                        _vm._v(" "),
                                        _c(
                                          "label",
                                          {
                                            attrs: {
                                              for: "frequency_days_" + step.id
                                            }
                                          },
                                          [
                                            _vm._v(
                                              _vm._s(
                                                _vm.$t("account.flow_new_days")
                                              )
                                            )
                                          ]
                                        )
                                      ]),
                                      _vm._v(" "),
                                      _c("li", { staticClass: "di mr2" }, [
                                        _c("input", {
                                          directives: [
                                            {
                                              name: "model",
                                              rawName: "v-model",
                                              value: step.frequency,
                                              expression: "step.frequency"
                                            }
                                          ],
                                          staticClass: "mr1",
                                          attrs: {
                                            id: "frequency_weeks_" + step.id,
                                            type: "radio",
                                            name: "frequency_after_" + step.id,
                                            value: "weeks"
                                          },
                                          domProps: {
                                            checked: _vm._q(
                                              step.frequency,
                                              "weeks"
                                            )
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.$set(
                                                step,
                                                "frequency",
                                                "weeks"
                                              )
                                            }
                                          }
                                        }),
                                        _vm._v(" "),
                                        _c(
                                          "label",
                                          {
                                            attrs: {
                                              for: "frequency_weeks_" + step.id
                                            }
                                          },
                                          [
                                            _vm._v(
                                              _vm._s(
                                                _vm.$t("account.flow_new_weeks")
                                              )
                                            )
                                          ]
                                        )
                                      ]),
                                      _vm._v(" "),
                                      _c("li", { staticClass: "di" }, [
                                        _c("input", {
                                          directives: [
                                            {
                                              name: "model",
                                              rawName: "v-model",
                                              value: step.frequency,
                                              expression: "step.frequency"
                                            }
                                          ],
                                          staticClass: "mr1",
                                          attrs: {
                                            id: "frequency_months_" + step.id,
                                            type: "radio",
                                            name: "frequency_after_" + step.id,
                                            value: "months"
                                          },
                                          domProps: {
                                            checked: _vm._q(
                                              step.frequency,
                                              "months"
                                            )
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.$set(
                                                step,
                                                "frequency",
                                                "months"
                                              )
                                            }
                                          }
                                        }),
                                        _vm._v(" "),
                                        _c(
                                          "label",
                                          {
                                            attrs: {
                                              for: "frequency_months_" + step.id
                                            }
                                          },
                                          [
                                            _vm._v(
                                              _vm._s(
                                                _vm.$t(
                                                  "account.flow_new_months"
                                                )
                                              )
                                            )
                                          ]
                                        )
                                      ])
                                    ]
                                  ),
                                  _vm._v(" "),
                                  _c("p", { staticClass: "ma0 pa0" }, [
                                    _vm._v(
                                      "\n                    " +
                                        _vm._s(
                                          _vm.$t("account.flow_new_after")
                                        ) +
                                        " "
                                    ),
                                    _c("span", { staticClass: "brush-blue" }, [
                                      _vm._v(
                                        _vm._s(
                                          _vm.$t(
                                            "account.flow_new_type_" +
                                              _vm.form.type
                                          )
                                        )
                                      )
                                    ])
                                  ])
                                ]
                              ),
                              _vm._v(" "),
                              _c("actions", {
                                on: {
                                  completed: function($event) {
                                    return _vm.checkComplete($event)
                                  }
                                },
                                model: {
                                  value: step.actions,
                                  callback: function($$v) {
                                    _vm.$set(step, "actions", $$v)
                                  },
                                  expression: "step.actions"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _vm.notFirstAndLastStep(step.id)
                            ? _c("div", { staticClass: "tc lh0" }, [
                                _c("img", {
                                  staticClass: "center pointer",
                                  attrs: {
                                    src: "/img/company/account/flow_line.svg"
                                  }
                                })
                              ])
                            : _vm._e(),
                          _vm._v(" "),
                          _c(
                            "div",
                            {
                              directives: [
                                {
                                  name: "show",
                                  rawName: "v-show",
                                  value: _vm.newestStep == step.id,
                                  expression: "newestStep == step.id"
                                }
                              ],
                              staticClass: "tc"
                            },
                            [
                              _c("img", {
                                staticClass: "center pointer",
                                attrs: {
                                  src:
                                    "/img/company/account/flow_plus_bottom.svg"
                                },
                                on: {
                                  click: function($event) {
                                    return _vm.addStepAfter()
                                  }
                                }
                              })
                            ]
                          )
                        ])
                      }),
                      0
                    ),
                    _vm._v(" "),
                    _c("div", { staticClass: "mv4" }, [
                      _c(
                        "div",
                        { staticClass: "flex-ns justify-between" },
                        [
                          _c("div", [
                            _c(
                              "a",
                              {
                                staticClass:
                                  "btn btn-secondary dib tc w-auto-ns w-100 mb2 pv2 ph3",
                                attrs: {
                                  href:
                                    "/" +
                                    _vm.$page.auth.company.id +
                                    "/account/employees"
                                }
                              },
                              [_vm._v(_vm._s(_vm.$t("app.cancel")))]
                            )
                          ]),
                          _vm._v(" "),
                          _c("loading-button", {
                            attrs: {
                              classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                              state: _vm.loadingState,
                              text: _vm.$t("app.save"),
                              "cypress-selector": "submit-add-employee-button"
                            }
                          })
                        ],
                        1
                      )
                    ])
                  ]
                )
              ],
              1
            )
          ]
        )
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Errors.vue?vue&type=template&id=8bb12772&":
/*!*****************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/Errors.vue?vue&type=template&id=8bb12772& ***!
  \*****************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return Object.keys(_vm.errors).length > 0
    ? _c(
        "div",
        [
          _c("p", [_vm._v("app.error_title")]),
          _vm._v(" "),
          _c("br"),
          _vm._v(" "),
          _vm._l(_vm.errors, function(errorsList) {
            return _c(
              "ul",
              { key: errorsList.id },
              _vm._l(errorsList, function(error) {
                return _c("li", { key: error.id }, [
                  _vm._v("\n      " + _vm._s(error) + "\n    ")
                ])
              }),
              0
            )
          })
        ],
        2
      )
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Create.vue":
/*!******************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Create.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Create_vue_vue_type_template_id_21c11e4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Create.vue?vue&type=template&id=21c11e4f&scoped=true& */ "./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=template&id=21c11e4f&scoped=true&");
/* harmony import */ var _Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Create.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Create_vue_vue_type_style_index_0_id_21c11e4f_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css& */ "./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Create_vue_vue_type_template_id_21c11e4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Create_vue_vue_type_template_id_21c11e4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "21c11e4f",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Adminland/Flow/Create.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Create.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css& ***!
  \***************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_style_index_0_id_21c11e4f_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=style&index=0&id=21c11e4f&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_style_index_0_id_21c11e4f_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_style_index_0_id_21c11e4f_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_style_index_0_id_21c11e4f_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_style_index_0_id_21c11e4f_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_style_index_0_id_21c11e4f_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=template&id=21c11e4f&scoped=true&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=template&id=21c11e4f&scoped=true& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_template_id_21c11e4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Create.vue?vue&type=template&id=21c11e4f&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Create.vue?vue&type=template&id=21c11e4f&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_template_id_21c11e4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_template_id_21c11e4f_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/Shared/Errors.vue":
/*!****************************************!*\
  !*** ./resources/js/Shared/Errors.vue ***!
  \****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Errors_vue_vue_type_template_id_8bb12772___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Errors.vue?vue&type=template&id=8bb12772& */ "./resources/js/Shared/Errors.vue?vue&type=template&id=8bb12772&");
/* harmony import */ var _Errors_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Errors.vue?vue&type=script&lang=js& */ "./resources/js/Shared/Errors.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Errors_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Errors_vue_vue_type_template_id_8bb12772___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Errors_vue_vue_type_template_id_8bb12772___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Shared/Errors.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Shared/Errors.vue?vue&type=script&lang=js&":
/*!*****************************************************************!*\
  !*** ./resources/js/Shared/Errors.vue?vue&type=script&lang=js& ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Errors_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Errors.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Errors.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Errors_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Shared/Errors.vue?vue&type=template&id=8bb12772&":
/*!***********************************************************************!*\
  !*** ./resources/js/Shared/Errors.vue?vue&type=template&id=8bb12772& ***!
  \***********************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Errors_vue_vue_type_template_id_8bb12772___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Errors.vue?vue&type=template&id=8bb12772& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Errors.vue?vue&type=template&id=8bb12772&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Errors_vue_vue_type_template_id_8bb12772___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Errors_vue_vue_type_template_id_8bb12772___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);