import{Q as m,a as u}from"./QLayout.56fe95cf.js";import{L as p}from"./LayoutHeader.07f95736.js";import{_ as d,r as t,o as s,a as c,w as r,f as e}from"./index.46fdd451.js";import"./QScrollObserver.debb7ba9.js";import"./QResizeObserver.b7431853.js";import"./QToolbarTitle.cb225f03.js";import"./QToolbar.72090c76.js";import"./QBadge.942c3fbf.js";import"./shop.2753827a.js";import"./logo.d17bc7ed.js";const f={components:{LayoutHeader:p},methods:{onFullScreen(){this.$q.fullscreen.toggle().then(()=>{}).catch(o=>{console.log(o)})}},computed:{fullscreenIcon(){return this.$q.fullscreen.isActive?"fad fa-compress-wide":"fad fa-expand-wide"}}};function _(o,h,g,w,v,n){const a=t("base-btn"),l=t("layout-header"),i=t("router-view");return s(),c(u,{view:"hHh LpR lFr",class:"bg-main"},{default:r(()=>[e(l,{"no-menu":""},{menus:r(()=>[e(a,{class:"q-mr-sm items-stretch",icon:n.fullscreenIcon,rounded:"",flat:"",dense:"",size:"16px",color:"grey",onClick:n.onFullScreen},null,8,["icon","onClick"]),e(a,{class:"q-mr-sm items-stretch",icon:"fad fa-square-arrow-up-right",to:{name:"Orders"},rounded:"",flat:"",dense:"",size:"16px",color:"negative"})]),_:1}),e(m,null,{default:r(()=>[(s(),c(i,{key:o.$router.fullPath}))]),_:1})]),_:1})}var Q=d(f,[["render",_]]);export{Q as default};
