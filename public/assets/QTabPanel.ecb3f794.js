import{dm as j,cR as C,aj as O,dn as G,at as S,dp as k,aN as I,av as D,as as E,v as L,y as f,z as H,s as F,a3 as J,D as y,W as Z,dq as ee,E as _,bx as te,m as K,T as ne,U as ae,aT as ie}from"./index.46fdd451.js";import{g as Q,s as W}from"./touch.3df10340.js";import{c as re}from"./selection.e9a8e664.js";function oe(t){const n=[.06,6,50];return typeof t=="string"&&t.length&&t.split(":").forEach((s,p)=>{const o=parseFloat(s);o&&(n[p]=o)}),n}var se=j({name:"touch-swipe",beforeMount(t,{value:n,arg:s,modifiers:p}){if(p.mouse!==!0&&C.has.touch!==!0)return;const o=p.mouseCapture===!0?"Capture":"",e={handler:n,sensitivity:oe(s),direction:Q(p),noop:O,mouseStart(i){W(i,e)&&G(i)&&(S(e,"temp",[[document,"mousemove","move",`notPassive${o}`],[document,"mouseup","end","notPassiveCapture"]]),e.start(i,!0))},touchStart(i){if(W(i,e)){const u=i.target;S(e,"temp",[[u,"touchmove","move","notPassiveCapture"],[u,"touchcancel","end","notPassiveCapture"],[u,"touchend","end","notPassiveCapture"]]),e.start(i)}},start(i,u){C.is.firefox===!0&&k(t,!0);const m=I(i);e.event={x:m.left,y:m.top,time:Date.now(),mouse:u===!0,dir:!1}},move(i){if(e.event===void 0)return;if(e.event.dir!==!1){D(i);return}const u=Date.now()-e.event.time;if(u===0)return;const m=I(i),h=m.left-e.event.x,c=Math.abs(h),g=m.top-e.event.y,l=Math.abs(g);if(e.event.mouse!==!0){if(c<e.sensitivity[1]&&l<e.sensitivity[1]){e.end(i);return}}else if(window.getSelection().toString()!==""){e.end(i);return}else if(c<e.sensitivity[2]&&l<e.sensitivity[2])return;const v=c/u,P=l/u;e.direction.vertical===!0&&c<l&&c<100&&P>e.sensitivity[0]&&(e.event.dir=g<0?"up":"down"),e.direction.horizontal===!0&&c>l&&l<100&&v>e.sensitivity[0]&&(e.event.dir=h<0?"left":"right"),e.direction.up===!0&&c<l&&g<0&&c<100&&P>e.sensitivity[0]&&(e.event.dir="up"),e.direction.down===!0&&c<l&&g>0&&c<100&&P>e.sensitivity[0]&&(e.event.dir="down"),e.direction.left===!0&&c>l&&h<0&&l<100&&v>e.sensitivity[0]&&(e.event.dir="left"),e.direction.right===!0&&c>l&&h>0&&l<100&&v>e.sensitivity[0]&&(e.event.dir="right"),e.event.dir!==!1?(D(i),e.event.mouse===!0&&(document.body.classList.add("no-pointer-events--children"),document.body.classList.add("non-selectable"),re(),e.styleCleanup=q=>{e.styleCleanup=void 0,document.body.classList.remove("non-selectable");const b=()=>{document.body.classList.remove("no-pointer-events--children")};q===!0?setTimeout(b,50):b()}),e.handler({evt:i,touch:e.event.mouse!==!0,mouse:e.event.mouse,direction:e.event.dir,duration:u,distance:{x:c,y:l}})):e.end(i)},end(i){e.event!==void 0&&(E(e,"temp"),C.is.firefox===!0&&k(t,!1),e.styleCleanup!==void 0&&e.styleCleanup(!0),i!==void 0&&e.event.dir!==!1&&D(i),e.event=void 0)}};if(t.__qtouchswipe=e,p.mouse===!0){const i=p.mouseCapture===!0||p.mousecapture===!0?"Capture":"";S(e,"main",[[t,"mousedown","mouseStart",`passive${i}`]])}C.has.touch===!0&&S(e,"main",[[t,"touchstart","touchStart",`passive${p.capture===!0?"Capture":""}`],[t,"touchmove","noop","notPassiveCapture"]])},updated(t,n){const s=t.__qtouchswipe;s!==void 0&&(n.oldValue!==n.value&&(typeof n.value!="function"&&s.end(),s.handler=n.value),s.direction=Q(n.modifiers))},beforeUnmount(t){const n=t.__qtouchswipe;n!==void 0&&(E(n,"main"),E(n,"temp"),C.is.firefox===!0&&k(t,!1),n.styleCleanup!==void 0&&n.styleCleanup(),delete t.__qtouchswipe)}});function ue(){const t=new Map;return{getCache:function(n,s){return t[n]===void 0?t[n]=s:t[n]},getCacheWithFn:function(n,s){return t[n]===void 0?t[n]=s():t[n]}}}const le={name:{required:!0},disable:Boolean},z={setup(t,{slots:n}){return()=>y("div",{class:"q-panel scroll",role:"tabpanel"},_(n.default))}},ce={modelValue:{required:!0},animated:Boolean,infinite:Boolean,swipeable:Boolean,vertical:Boolean,transitionPrev:String,transitionNext:String,transitionDuration:{type:[String,Number],default:300},keepAlive:Boolean,keepAliveInclude:[String,Array,RegExp],keepAliveExclude:[String,Array,RegExp],keepAliveMax:Number},pe=["update:modelValue","beforeTransition","transition"];function de(){const{props:t,emit:n,proxy:s}=F(),{getCacheWithFn:p}=ue();let o,e;const i=L(null),u=L(null);function m(a){const r=t.vertical===!0?"up":"left";x((s.$q.lang.rtl===!0?-1:1)*(a.direction===r?1:-1))}const h=f(()=>[[se,m,void 0,{horizontal:t.vertical!==!0,vertical:t.vertical,mouse:!0}]]),c=f(()=>t.transitionPrev||`slide-${t.vertical===!0?"down":"right"}`),g=f(()=>t.transitionNext||`slide-${t.vertical===!0?"up":"left"}`),l=f(()=>`--q-transition-duration: ${t.transitionDuration}ms`),v=f(()=>typeof t.modelValue=="string"||typeof t.modelValue=="number"?t.modelValue:String(t.modelValue)),P=f(()=>({include:t.keepAliveInclude,exclude:t.keepAliveExclude,max:t.keepAliveMax})),q=f(()=>t.keepAliveInclude!==void 0||t.keepAliveExclude!==void 0);H(()=>t.modelValue,(a,r)=>{const d=T(a)===!0?A(a):-1;e!==!0&&$(d===-1?0:d<A(r)?-1:1),i.value!==d&&(i.value=d,n("beforeTransition",a,r),J(()=>{n("transition",a,r)}))});function b(){x(1)}function N(){x(-1)}function V(a){n("update:modelValue",a)}function T(a){return a!=null&&a!==""}function A(a){return o.findIndex(r=>r.props.name===a&&r.props.disable!==""&&r.props.disable!==!0)}function R(){return o.filter(a=>a.props.disable!==""&&a.props.disable!==!0)}function $(a){const r=a!==0&&t.animated===!0&&i.value!==-1?"q-transition--"+(a===-1?c.value:g.value):null;u.value!==r&&(u.value=r)}function x(a,r=i.value){let d=r+a;for(;d>-1&&d<o.length;){const w=o[d];if(w!==void 0&&w.props.disable!==""&&w.props.disable!==!0){$(a),e=!0,n("update:modelValue",w.props.name),setTimeout(()=>{e=!1});return}d+=a}t.infinite===!0&&o.length!==0&&r!==-1&&r!==o.length&&x(a,a===-1?o.length:-1)}function B(){const a=A(t.modelValue);return i.value!==a&&(i.value=a),!0}function M(){const a=T(t.modelValue)===!0&&B()&&o[i.value];return t.keepAlive===!0?[y(te,P.value,[y(q.value===!0?p(v.value,()=>({...z,name:v.value})):z,{key:v.value,style:l.value},()=>a)])]:[y("div",{class:"q-panel scroll",style:l.value,key:v.value,role:"tabpanel"},[a])]}function U(){if(o.length!==0)return t.animated===!0?[y(Z,{name:u.value},M)]:M()}function X(a){return o=ee(_(a.default,[])).filter(r=>r.props!==null&&r.props.slot===void 0&&T(r.props.name)===!0),o.length}function Y(){return o}return Object.assign(s,{next:b,previous:N,goTo:V}),{panelIndex:i,panelDirectives:h,updatePanelsList:X,updatePanelIndex:B,getPanelContent:U,getEnabledPanels:R,getPanels:Y,isValidPanelName:T,keepAliveProps:P,needsUniqueKeepAliveWrapper:q,goToPanelByOffset:x,goToPanel:V,nextPanel:b,previousPanel:N}}var he=K({name:"QTabPanels",props:{...ce,...ne},emits:pe,setup(t,{slots:n}){const s=F(),p=ae(t,s.proxy.$q),{updatePanelsList:o,getPanelContent:e,panelDirectives:i}=de(),u=f(()=>"q-tab-panels q-panel-parent"+(p.value===!0?" q-tab-panels--dark q-dark":""));return()=>(o(n),ie("div",{class:u.value},e(),"pan",t.swipeable,()=>i.value))}}),ge=K({name:"QTabPanel",props:le,setup(t,{slots:n}){return()=>y("div",{class:"q-tab-panel",role:"tabpanel"},_(n.default))}});export{ge as Q,he as a,ue as u};
