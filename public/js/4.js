(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[4],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-click-outside */ "./node_modules/vue-click-outside/index.js");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_click_outside__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-loaders/dist/vue-loaders.css */ "./node_modules/vue-loaders/dist/vue-loaders.css");
/* harmony import */ var vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var vue_loaders_src_loaders_ball_pulse__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-loaders/src/loaders/ball-pulse */ "./node_modules/vue-loaders/src/loaders/ball-pulse.vue");
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
    BallPulseLoader: vue_loaders_src_loaders_ball_pulse__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  directives: {
    ClickOutside: vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  props: {
    action: {
      type: Object,
      "default": null
    }
  },
  data: function data() {
    return {
      who: '',
      message: '',
      updatedMessage: '',
      notification: {
        id: 0,
        type: '',
        target: '',
        employeeId: 0,
        teamId: 0,
        message: '',
        complete: false
      },
      form: {
        searchTerm: null,
        errors: []
      },
      processingSearch: false,
      searchEmployees: [],
      searchTeams: [],
      displayModal: false,
      actionsModal: false,
      showEveryoneConfirmationModal: false,
      showSeachEmployeeModal: false,
      showSeachTeamModal: false,
      showEditMessage: false,
      deleteActionConfirmation: false
    };
  },
  computed: {
    charactersLeft: function charactersLeft() {
      var _char = this.updatedMessage.length,
          limit = 255;
      return 'Characters remaining: ' + (limit - _char) + ' / ' + limit;
    }
  },
  mounted: function mounted() {
    // prevent click outside event with popupItem.
    this.popupItem = this.$el;
    this.notification = this.action;
    this.who = 'an employee';
    this.setMessage(this.$t('account.flow_new_action_label_unknown_message'));
  },
  methods: {
    displayConfirmationModal: function displayConfirmationModal() {
      this.showEveryoneConfirmationModal = true;
      this.displayModal = false;
    },
    displayEmployeeSearchBox: function displayEmployeeSearchBox() {
      this.displayModal = false;
      this.showSeachEmployeeModal = true;
    },
    displayTeamSearchBox: function displayTeamSearchBox() {
      this.displayModal = false;
      this.showSeachTeamModal = true;
    },
    displayEditMessageTextarea: function displayEditMessageTextarea() {
      if (this.notification.message == this.$t('account.flow_new_action_label_unknown_message')) {
        this.updatedMessage = '';
      } else {
        this.updatedMessage = this.notification.message;
      }

      this.showEditMessage = true;
    },
    toggleModals: function toggleModals() {
      this.showEveryoneConfirmationModal = false;
      this.displayModal = false;
      this.showSeachEmployeeModal = false;
      this.showSeachTeamModal = false;
      this.actionsModal = false;
      this.showEditMessage = false;
    },
    // check if an action is considered "complete". If not, this will prevent
    // the form to be submitted in the parent component.
    checkComplete: function checkComplete() {
      if (this.notification.message != '' && this.notification.message != this.$t('account.flow_new_action_label_unknown_message') && this.notification.target) {
        this.notification.complete = true;
      }
    },
    setTarget: function setTarget(target) {
      this.notification.target = target;
      this.toggleModals();

      switch (target) {
        case 'actualEmployee':
          this.who = this.$t('account.flow_new_action_label_actual_employee');
          break;

        case 'everyone':
          this.who = this.$t('account.flow_new_action_label_everyone');
          break;

        case 'managers':
          this.who = this.$t('account.flow_new_action_label_managers');
          break;

        case 'directReports':
          this.who = this.$t('account.flow_new_action_label_reports');
          break;

        case 'employeeTeam':
          this.who = this.$t('account.flow_new_action_label_team_employee');
          break;

        case 'specificTeam':
          break;

        case 'specificEmployee':
          break;

        default:
          this.who = this.$t('account.flow_new_action_label_employee');
      }

      this.checkComplete();
      this.$emit('update', this.notification);
    },
    searchEmployee: _.debounce(function () {
      var _this = this;

      if (this.form.searchTerm != '') {
        this.processingSearch = true;
        axios.post('/search/employees/', this.form).then(function (response) {
          _this.searchEmployees = response.data.data;
          _this.processingSearch = false;
        })["catch"](function (error) {
          _this.form.errors = _.flatten(_.toArray(error.response.data));
          _this.processingSearch = false;
        });
      }
    }, 500),
    searchTeam: _.debounce(function () {
      var _this2 = this;

      if (this.form.searchTerm != '') {
        this.processingSearch = true;
        axios.post('/search/teams/', this.form).then(function (response) {
          _this2.searchTeams = response.data.data;
          _this2.processingSearch = false;
        })["catch"](function (error) {
          _this2.form.errors = _.flatten(_.toArray(error.response.data));
          _this2.processingSearch = false;
        });
      }
    }, 500),
    assignEmployee: function assignEmployee(employee) {
      this.notification.employeeId = employee.id;
      this.who = employee.name;
      this.setTarget('specificEmployee');
      this.toggleModals();
    },
    assignTeam: function assignTeam(team) {
      this.notification.teamId = team.id;
      this.who = team.name;
      this.setTarget('specificTeam');
      this.toggleModals();
    },
    destroyAction: function destroyAction() {
      this.$emit('destroy');
    },
    setMessage: function setMessage(message) {
      if (message == '') {
        this.notification.message = this.$t('account.flow_new_action_label_unknown_message');
      } else {
        this.notification.message = message;
      }

      this.message = this.notification.message;
      this.toggleModals();
      this.checkComplete();
      this.$emit('update', this.notification);
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/lib/loader.js??ref--7-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".actions-dots[data-v-ce8e66d8] {\n  top: 15px;\n}\n.employee-modal[data-v-ce8e66d8] {\n  top: 30px;\n  left: -120px;\n  right: 290px;\n}\n.confirmation-menu[data-v-ce8e66d8] {\n  top: 30px;\n  left: -160px;\n  right: initial;\n  width: 310px;\n}\n.action-menu[data-v-ce8e66d8] {\n  right: -6px;\n  top: 31px;\n}\n.icon-delete[data-v-ce8e66d8] {\n  top: 2px;\n}\n.ball-pulse[data-v-ce8e66d8] {\n  right: 8px;\n  top: 10px;\n  position: absolute;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/lib/loader.js??ref--7-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../node_modules/sass-loader/lib/loader.js??ref--7-3!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=template&id=ce8e66d8&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=template&id=ce8e66d8&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "relative pr3 lh-copy" }, [
    _vm._v("\n  Notify "),
    _c(
      "span",
      {
        staticClass: "bb b--dotted bt-0 bl-0 br-0 pointer",
        on: {
          click: function($event) {
            _vm.displayModal = true
          }
        }
      },
      [_vm._v(_vm._s(_vm.who))]
    ),
    _vm._v(" with "),
    _c(
      "span",
      {
        staticClass: "bb b--dotted bt-0 bl-0 br-0 pointer",
        on: { click: _vm.displayEditMessageTextarea }
      },
      [_vm._v(_vm._s(_vm.message))]
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: _vm.displayModal,
            expression: "displayModal"
          },
          {
            name: "click-outside",
            rawName: "v-click-outside",
            value: _vm.toggleModals,
            expression: "toggleModals"
          }
        ],
        staticClass:
          "popupmenu employee-modal absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
      },
      [
        _c("ul", { staticClass: "list ma0 pa0" }, [
          _c("li", { staticClass: "pv1" }, [
            _c(
              "a",
              {
                staticClass: "pointer",
                on: {
                  click: function($event) {
                    $event.preventDefault()
                    return _vm.setTarget("actualEmployee")
                  }
                }
              },
              [
                _vm._v(
                  _vm._s(
                    _vm.$t(
                      "account.flow_new_action_notification_actual_employee"
                    )
                  )
                )
              ]
            )
          ]),
          _vm._v(" "),
          _c("li", { staticClass: "pv1" }, [
            _c(
              "a",
              {
                staticClass: "pointer",
                on: {
                  click: function($event) {
                    $event.preventDefault()
                    return _vm.displayEmployeeSearchBox($event)
                  }
                }
              },
              [
                _vm._v(
                  _vm._s(
                    _vm.$t(
                      "account.flow_new_action_notification_specific_employee"
                    )
                  )
                )
              ]
            )
          ]),
          _vm._v(" "),
          _c("li", { staticClass: "pv1" }, [
            _c(
              "a",
              {
                staticClass: "pointer",
                on: {
                  click: function($event) {
                    $event.preventDefault()
                    return _vm.setTarget("managers")
                  }
                }
              },
              [
                _vm._v(
                  _vm._s(_vm.$t("account.flow_new_action_notification_manager"))
                )
              ]
            )
          ]),
          _vm._v(" "),
          _c("li", { staticClass: "pv1" }, [
            _c(
              "a",
              {
                staticClass: "pointer",
                on: {
                  click: function($event) {
                    $event.preventDefault()
                    return _vm.setTarget("directReports")
                  }
                }
              },
              [
                _vm._v(
                  _vm._s(_vm.$t("account.flow_new_action_notification_report"))
                )
              ]
            )
          ]),
          _vm._v(" "),
          _c("li", { staticClass: "pv1" }, [
            _c(
              "a",
              {
                staticClass: "pointer",
                on: {
                  click: function($event) {
                    $event.preventDefault()
                    return _vm.setTarget("employeeTeam")
                  }
                }
              },
              [
                _vm._v(
                  _vm._s(
                    _vm.$t("account.flow_new_action_notification_team_members")
                  )
                )
              ]
            )
          ]),
          _vm._v(" "),
          _c("li", { staticClass: "pv1" }, [
            _c(
              "a",
              {
                staticClass: "pointer",
                on: {
                  click: function($event) {
                    $event.preventDefault()
                    return _vm.displayTeamSearchBox($event)
                  }
                }
              },
              [
                _vm._v(
                  _vm._s(
                    _vm.$t("account.flow_new_action_notification_specific_team")
                  )
                )
              ]
            )
          ]),
          _vm._v(" "),
          _c("li", { staticClass: "pv1" }, [
            _c(
              "a",
              {
                staticClass: "pointer",
                on: {
                  click: function($event) {
                    $event.preventDefault()
                    return _vm.displayConfirmationModal($event)
                  }
                }
              },
              [
                _vm._v(
                  _vm._s(
                    _vm.$t("account.flow_new_action_notification_everyone")
                  )
                )
              ]
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
            value: _vm.showEveryoneConfirmationModal,
            expression: "showEveryoneConfirmationModal"
          },
          {
            name: "click-outside",
            rawName: "v-click-outside",
            value: _vm.toggleModals,
            expression: "toggleModals"
          }
        ],
        staticClass:
          "popupmenu confirmation-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
      },
      [
        _c("p", { staticClass: "lh-copy" }, [
          _vm._v(
            "\n      " +
              _vm._s(
                _vm.$t("account.flow_new_action_notification_confirmation")
              ) +
              "\n    "
          )
        ]),
        _vm._v(" "),
        _c("ul", { staticClass: "list ma0 pa0 pb2" }, [
          _c("li", { staticClass: "pv2 di relative mr2" }, [
            _c(
              "a",
              {
                staticClass: "pointer ml1",
                on: {
                  click: function($event) {
                    $event.preventDefault()
                    return _vm.setTarget("everyone")
                  }
                }
              },
              [_vm._v(_vm._s(_vm.$t("app.yes_sure")))]
            )
          ]),
          _vm._v(" "),
          _c("li", { staticClass: "pv2 di" }, [
            _c(
              "a",
              {
                staticClass: "pointer",
                on: {
                  click: function($event) {
                    $event.preventDefault()
                    _vm.showEveryoneConfirmationModal = false
                    _vm.displayModal = true
                  }
                }
              },
              [_vm._v(_vm._s(_vm.$t("app.no")))]
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
            value: _vm.showSeachEmployeeModal,
            expression: "showSeachEmployeeModal"
          },
          {
            name: "click-outside",
            rawName: "v-click-outside",
            value: _vm.toggleModals,
            expression: "toggleModals"
          }
        ],
        staticClass:
          "popupmenu confirmation-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
      },
      [
        _c(
          "form",
          {
            on: {
              submit: function($event) {
                $event.preventDefault()
                return _vm.searchEmployee($event)
              }
            }
          },
          [
            _c("div", { staticClass: "mb3 relative" }, [
              _c("p", [
                _vm._v(
                  _vm._s(
                    _vm.$t(
                      "account.flow_new_action_notification_search_employees"
                    )
                  )
                )
              ]),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "relative" },
                [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.searchTerm,
                        expression: "form.searchTerm"
                      }
                    ],
                    ref: "search",
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: {
                      id: "search",
                      type: "text",
                      name: "search",
                      placeholder: _vm.$t(
                        "account.flow_new_action_notification_search_hint"
                      ),
                      required: ""
                    },
                    domProps: { value: _vm.form.searchTerm },
                    on: {
                      keyup: _vm.searchEmployee,
                      keydown: function($event) {
                        if (
                          !$event.type.indexOf("key") &&
                          _vm._k($event.keyCode, "esc", 27, $event.key, [
                            "Esc",
                            "Escape"
                          ])
                        ) {
                          return null
                        }
                        return _vm.toggleModals()
                      },
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "searchTerm", $event.target.value)
                      }
                    }
                  }),
                  _vm._v(" "),
                  _vm.processingSearch
                    ? _c("ball-pulse-loader", {
                        attrs: { color: "#5c7575", size: "7px" }
                      })
                    : _vm._e()
                ],
                1
              )
            ])
          ]
        ),
        _vm._v(" "),
        _c("ul", { staticClass: "pl0 list ma0" }, [
          _c("li", { staticClass: "fw5 mb3" }, [
            _c("span", { staticClass: "f6 mb2 dib" }, [
              _vm._v(_vm._s(_vm.$t("employee.hierarchy_search_results")))
            ]),
            _vm._v(" "),
            _vm.searchEmployees.length > 0
              ? _c(
                  "ul",
                  { staticClass: "list ma0 pl0" },
                  _vm._l(_vm.searchEmployees, function(employee) {
                    return _c(
                      "li",
                      {
                        key: employee.id,
                        staticClass: "bb relative pv2 ph1 bb-gray bb-gray-hover"
                      },
                      [
                        _vm._v(
                          "\n            " +
                            _vm._s(employee.name) +
                            "\n            "
                        ),
                        _c(
                          "a",
                          {
                            staticClass: "absolute right-1 pointer",
                            attrs: { "data-cy": "potential-manager-button" },
                            on: {
                              click: function($event) {
                                $event.preventDefault()
                                return _vm.assignEmployee(employee)
                              }
                            }
                          },
                          [_vm._v(_vm._s(_vm.$t("app.choose")))]
                        )
                      ]
                    )
                  }),
                  0
                )
              : _c("div", { staticClass: "silver" }, [
                  _vm._v(
                    "\n          " +
                      _vm._s(_vm.$t("app.no_results")) +
                      "\n        "
                  )
                ])
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
            value: _vm.showSeachTeamModal,
            expression: "showSeachTeamModal"
          },
          {
            name: "click-outside",
            rawName: "v-click-outside",
            value: _vm.toggleModals,
            expression: "toggleModals"
          }
        ],
        staticClass:
          "popupmenu confirmation-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
      },
      [
        _c(
          "form",
          {
            on: {
              submit: function($event) {
                $event.preventDefault()
                return _vm.searchTeam($event)
              }
            }
          },
          [
            _c("div", { staticClass: "mb3 relative" }, [
              _c("p", [
                _vm._v(
                  _vm._s(
                    _vm.$t("account.flow_new_action_notification_search_teams")
                  )
                )
              ]),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "relative" },
                [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.searchTerm,
                        expression: "form.searchTerm"
                      }
                    ],
                    ref: "search",
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: {
                      id: "search",
                      type: "text",
                      name: "search",
                      placeholder: _vm.$t(
                        "account.flow_new_action_notification_search_hint"
                      ),
                      required: ""
                    },
                    domProps: { value: _vm.form.searchTerm },
                    on: {
                      keyup: _vm.searchTeam,
                      keydown: function($event) {
                        if (
                          !$event.type.indexOf("key") &&
                          _vm._k($event.keyCode, "esc", 27, $event.key, [
                            "Esc",
                            "Escape"
                          ])
                        ) {
                          return null
                        }
                        return _vm.toggleModals()
                      },
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "searchTerm", $event.target.value)
                      }
                    }
                  }),
                  _vm._v(" "),
                  _vm.processingSearch
                    ? _c("ball-pulse-loader", {
                        attrs: { color: "#5c7575", size: "7px" }
                      })
                    : _vm._e()
                ],
                1
              )
            ])
          ]
        ),
        _vm._v(" "),
        _c("ul", { staticClass: "pl0 list ma0" }, [
          _c("li", { staticClass: "fw5 mb3" }, [
            _c("span", { staticClass: "f6 mb2 dib" }, [
              _vm._v(_vm._s(_vm.$t("employee.hierarchy_search_results")))
            ]),
            _vm._v(" "),
            _vm.searchTeams.length > 0
              ? _c(
                  "ul",
                  { staticClass: "list ma0 pl0" },
                  _vm._l(_vm.searchTeams, function(team) {
                    return _c(
                      "li",
                      {
                        key: team.id,
                        staticClass: "bb relative pv2 ph1 bb-gray bb-gray-hover"
                      },
                      [
                        _vm._v(
                          "\n            " +
                            _vm._s(team.name) +
                            "\n            "
                        ),
                        _c(
                          "a",
                          {
                            staticClass: "absolute right-1 pointer",
                            attrs: { "data-cy": "potential-manager-button" },
                            on: {
                              click: function($event) {
                                $event.preventDefault()
                                return _vm.assignTeam(team)
                              }
                            }
                          },
                          [_vm._v(_vm._s(_vm.$t("app.choose")))]
                        )
                      ]
                    )
                  }),
                  0
                )
              : _c("div", { staticClass: "silver" }, [
                  _vm._v(
                    "\n          " +
                      _vm._s(_vm.$t("app.no_results")) +
                      "\n        "
                  )
                ])
          ])
        ])
      ]
    ),
    _vm._v(" "),
    _c("img", {
      staticClass: "absolute right-0 pointer actions-dots",
      attrs: { src: "/img/common/triple-dots.svg" },
      on: {
        click: function($event) {
          _vm.actionsModal = true
        }
      }
    }),
    _vm._v(" "),
    _vm.actionsModal
      ? _c(
          "div",
          {
            directives: [
              {
                name: "click-outside",
                rawName: "v-click-outside",
                value: _vm.toggleModals,
                expression: "toggleModals"
              }
            ],
            staticClass:
              "popupmenu action-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal"
          },
          [
            _c("ul", { staticClass: "list ma0 pa0" }, [
              _c(
                "li",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: !_vm.deleteActionConfirmation,
                      expression: "!deleteActionConfirmation"
                    }
                  ],
                  staticClass: "pv2 relative"
                },
                [
                  _c("icon-delete", {
                    attrs: {
                      classes: "icon-delete relative",
                      width: 15,
                      height: 15
                    }
                  }),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "pointer ml1 c-delete",
                      on: {
                        click: function($event) {
                          $event.preventDefault()
                          _vm.deleteActionConfirmation = true
                        }
                      }
                    },
                    [_vm._v(_vm._s(_vm.$t("account.flow_new_action_remove")))]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "li",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: _vm.deleteActionConfirmation,
                      expression: "deleteActionConfirmation"
                    }
                  ],
                  staticClass: "pv2"
                },
                [
                  _vm._v(
                    "\n        " + _vm._s(_vm.$t("app.sure")) + "\n        "
                  ),
                  _c(
                    "a",
                    {
                      staticClass: "c-delete mr1 pointer",
                      on: {
                        click: function($event) {
                          $event.preventDefault()
                          return _vm.destroyAction($event)
                        }
                      }
                    },
                    [_vm._v(_vm._s(_vm.$t("app.yes")))]
                  ),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "pointer",
                      on: {
                        click: function($event) {
                          $event.preventDefault()
                          _vm.deleteActionConfirmation = false
                        }
                      }
                    },
                    [_vm._v(_vm._s(_vm.$t("app.no")))]
                  )
                ]
              )
            ])
          ]
        )
      : _vm._e(),
    _vm._v(" "),
    _c(
      "div",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: _vm.showEditMessage,
            expression: "showEditMessage"
          }
        ],
        staticClass: "mt2"
      },
      [
        _c("p", { staticClass: "mb1 f6" }, [
          _vm._v("\n      " + _vm._s(_vm.charactersLeft) + "\n    ")
        ]),
        _vm._v(" "),
        _c("textarea", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.updatedMessage,
              expression: "updatedMessage"
            }
          ],
          staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
          attrs: { cols: "30", rows: "3", maxlength: "255" },
          domProps: { value: _vm.updatedMessage },
          on: {
            input: function($event) {
              if ($event.target.composing) {
                return
              }
              _vm.updatedMessage = $event.target.value
            }
          }
        }),
        _vm._v(" "),
        _c("div", { staticClass: "mv1" }, [
          _c("div", { staticClass: "flex-ns justify-between" }, [
            _c(
              "a",
              {
                staticClass:
                  "btn btn-secondary dib tc w-auto-ns w-100 mb2 pv2 ph3",
                on: {
                  click: function($event) {
                    _vm.showEditMessage = false
                  }
                }
              },
              [_vm._v(_vm._s(_vm.$t("app.cancel")))]
            ),
            _vm._v(" "),
            _c(
              "a",
              {
                staticClass: "btn dib tc w-auto-ns w-100 mb2 pv2 ph3",
                on: {
                  click: function($event) {
                    return _vm.setMessage(_vm.updatedMessage)
                  }
                }
              },
              [_vm._v(_vm._s(_vm.$t("app.save")))]
            )
          ])
        ])
      ]
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/ActionNotification.vue":
/*!******************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/ActionNotification.vue ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ActionNotification_vue_vue_type_template_id_ce8e66d8_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ActionNotification.vue?vue&type=template&id=ce8e66d8&scoped=true& */ "./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=template&id=ce8e66d8&scoped=true&");
/* harmony import */ var _ActionNotification_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ActionNotification.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _ActionNotification_vue_vue_type_style_index_0_id_ce8e66d8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true& */ "./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ActionNotification_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ActionNotification_vue_vue_type_template_id_ce8e66d8_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ActionNotification_vue_vue_type_template_id_ce8e66d8_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "ce8e66d8",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Adminland/Flow/ActionNotification.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActionNotification_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ActionNotification.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActionNotification_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true&":
/*!****************************************************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true& ***!
  \****************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ActionNotification_vue_vue_type_style_index_0_id_ce8e66d8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../node_modules/sass-loader/lib/loader.js??ref--7-3!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=style&index=0&id=ce8e66d8&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ActionNotification_vue_vue_type_style_index_0_id_ce8e66d8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ActionNotification_vue_vue_type_style_index_0_id_ce8e66d8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ActionNotification_vue_vue_type_style_index_0_id_ce8e66d8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ActionNotification_vue_vue_type_style_index_0_id_ce8e66d8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_ActionNotification_vue_vue_type_style_index_0_id_ce8e66d8_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=template&id=ce8e66d8&scoped=true&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=template&id=ce8e66d8&scoped=true& ***!
  \*************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ActionNotification_vue_vue_type_template_id_ce8e66d8_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ActionNotification.vue?vue&type=template&id=ce8e66d8&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/ActionNotification.vue?vue&type=template&id=ce8e66d8&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ActionNotification_vue_vue_type_template_id_ce8e66d8_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ActionNotification_vue_vue_type_template_id_ce8e66d8_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);