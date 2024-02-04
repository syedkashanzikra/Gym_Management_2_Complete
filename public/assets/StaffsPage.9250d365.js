import{_ as k,M as v,N as C,r,o as d,a as g,w as t,f as n,K as S,I as m,a5 as y,h as P,t as q,a6 as p,d as A,ag as Q,F as R}from"./index.46fdd451.js";import{Q as B}from"./QChip.2eda5f32.js";import{Q as V}from"./QPage.701dd41d.js";import{u as c}from"./staff.43951c10.js";const z={data(){return{loading:!1,loaded:!1,pagination:{sortBy:"name",descending:!1,page:1,filter:"",advancedFilter:{},deleted:!1,rowsPerPage:15,rowsNumber:15,loaded:!1,active:null},useStaffStore:c()}},methods:{...v(c,["get","changeActive","changeAdmin"]),onRequest(e){console.func("pages/core/staffs/StaffsPage:methods.onRequest()",arguments);const{page:s,rowsPerPage:u,sortBy:f,descending:i}=e.pagination;this.pagination=e.pagination,this.loading=!0,this.get({...e.pagination,direction:i?"desc":"asc"}).then(({meta:o})=>{this.pagination.rowsNumber=o.total,this.pagination.page=s,this.pagination.rowsPerPage=u,this.pagination.sortBy=f,this.pagination.descending=i,this.pagination.loaded=!0,this.pagination.from=o.from,this.pagination.to=o.to,this.loading=!1}).catch(o=>{this.$core.error(o,{title:this.$t("dialog.title.error")})})},async actionClicked(e,s){console.func("pages/core/staffs/StaffsPage:methods.actionClicked()",arguments)},async toolbarClicked(e,s){console.func("pages/core/staffs/StaffsPage:methods.toolbarClicked()",arguments)},async rowClicked(e,s){console.func("pages/core/staffs/StaffsPage:methods.rowClicked()",arguments),this.$router.push({name:"Single Staff",params:{id:s.id},query:{action:"edit"}})}},computed:{...C(c,["actions","rows","columns","module","toolbar","filters"]),permissions(){return[]}}};function N(e,s,u,f,i,o){const b=r("base-avatar"),h=r("base-btn"),w=r("base-table"),_=r("base-section");return d(),g(V,{padding:""},{default:t(()=>[n(_,{flat:"",bordered:"","no-row":""},{default:t(()=>[n(w,{store:i.useStaffStore,module:e.module,columns:e.columns,rows:e.rows,actions:e.actions,toolbar:e.toolbar,filters:e.filters,loading:i.loading,pagination:i.pagination,onRequest:o.onRequest,onActionClicked:o.actionClicked,onToolbarClicked:o.toolbarClicked,onRowClicked:o.rowClicked,"no-data-label":e.$t("staff.noData"),"table-key":"staffs"},{"body-cell-name":t(a=>[n(S,{class:"q-pa-none",dense:""},{default:t(()=>[n(m,{avatar:""},{default:t(()=>[n(b,{rounded:"",class:"cursor-pointer",user:a.row,size:"40px"},null,8,["user"])]),_:2},1024),n(m,{avatar:""},{default:t(()=>[n(h,{onClick:s[0]||(s[0]=y(()=>{},["stop"])),link:"",size:"12px",tag:"a",to:{name:"Single Staff",params:{id:a.row.id},query:{action:"edit"}}},{default:t(()=>[P(q(a.value),1)]),_:2},1032,["to"])]),_:2},1024)]),_:2},1024)]),"body-cell-is_active":t(a=>[n(p,{"onUpdate:modelValue":l=>e.changeActive(a.row),size:"sm",dense:"","model-value":a.row.is_active,color:"green"},null,8,["onUpdate:modelValue","model-value"])]),"body-cell-is_supper_admin":t(a=>[n(p,{"onUpdate:modelValue":l=>e.changeAdmin(a.row),size:"sm",dense:"","model-value":a.row.is_supper_admin,color:"green"},null,8,["onUpdate:modelValue","model-value"])]),"body-cell-groups":t(a=>[(d(!0),A(R,null,Q(a.row.groups,l=>(d(),g(B,{size:"12px",key:l.id,label:l.name},null,8,["label"]))),128))]),_:1},8,["store","module","columns","rows","actions","toolbar","filters","loading","pagination","onRequest","onActionClicked","onToolbarClicked","onRowClicked","no-data-label"])]),_:1})]),_:1})}var $=k(z,[["render",N]]);export{$ as default};
