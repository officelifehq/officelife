(window.webpackJsonp=window.webpackJsonp||[]).push([[40],{IQOQ:function(e,n,t){e.exports=function(){var e="undefined"!=typeof window,n="undefined"!=typeof navigator,t=e&&("ontouchstart"in window||n&&navigator.msMaxTouchPoints>0)?["touchstart","click"]:["click"],r=function(e){return e},i={instances:[]};function a(e){var n="function"==typeof e;if(!n&&"object"!=typeof e)throw new Error("v-click-outside: Binding value must be a function or an object");return{handler:n?e:e.handler,middleware:e.middleware||r,events:e.events||t,isActive:!(!1===e.isActive)}}function d(e){var n=e.el,t=e.event,r=e.handler,i=e.middleware;t.target!==n&&!n.contains(t.target)&&i(t,n)&&r(t,n)}function o(e){var n=e.el,t=e.handler,r=e.middleware;return{el:n,eventHandlers:e.events.map((function(e){return{event:e,handler:function(e){return d({event:e,el:n,handler:t,middleware:r})}}}))}}function u(e){var n=i.instances.findIndex((function(n){return n.el===e}));-1!==n&&(i.instances[n].eventHandlers.forEach((function(e){return document.removeEventListener(e.event,e.handler)})),i.instances.splice(n,1))}return i.bind=function(e,n){var t=a(n.value);if(t.isActive){var r=o({el:e,events:t.events,handler:t.handler,middleware:t.middleware});r.eventHandlers.forEach((function(e){var n=e.event,t=e.handler;return setTimeout((function(){return document.addEventListener(n,t)}),0)})),i.instances.push(r)}},i.update=function(e,n){var t=n.value,r=n.oldValue;if(JSON.stringify(t)!==JSON.stringify(r)){var c=a(t),s=c.events,l=c.handler,v=c.middleware;if(c.isActive){var f=i.instances.find((function(n){return n.el===e}));f?(f.eventHandlers.forEach((function(e){return document.removeEventListener(e.event,e.handler)})),f.eventHandlers=s.map((function(n){return{event:n,handler:function(n){return d({event:n,el:e,handler:l,middleware:v})}}}))):(f=o({el:e,events:s,handler:l,middleware:v}),i.instances.push(f)),f.eventHandlers.forEach((function(e){var n=e.event,t=e.handler;return setTimeout((function(){return document.addEventListener(n,t)}),0)}))}else u(e)}},i.unbind=u,{install:function(e){e.directive("click-outside",i)},directive:i}}()}}]);
//# sourceMappingURL=40.js.map?id=b127c68c952cfceef0d8