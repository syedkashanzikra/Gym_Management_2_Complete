import{_ as C,M as h,N as w,r as l,o as y,a as _,w as s,f as n,K as k,I as g,a5 as P,h as q,t as v}from"./index.46fdd451.js";import{Q as S}from"./QPage.701dd41d.js";import{u as r}from"./category.8d269d68.js";const R={data(){return{loading:!1,loaded:!1,selected:[],pagination:{sortBy:"name",descending:!1,page:1,filter:"",deleted:!1,rowsPerPage:15,rowsNumber:15,loaded:!1},useCategoryStore:r()}},methods:{...h(r,["get"]),onRequest(e){console.func("pages/admins/categories/CategoriesPage:methods.onRequest()",arguments);const{page:a,rowsPerPage:d,sortBy:c,descending:t}=e.pagination;this.pagination=e.pagination,this.loading=!0,this.get({...e.pagination,descending:t?"desc":"asc"}).then(({meta:o})=>{this.pagination.rowsNumber=o.total,this.pagination.page=a,this.pagination.rowsPerPage=d,this.pagination.sortBy=c,this.pagination.descending=t,this.pagination.loaded=!0,this.pagination.from=o.from,this.pagination.to=o.to,this.loading=!1}).catch(o=>{this.$core.error(o,{title:this.$t("dialog.title.error")})})},async actionClicked(e,a){console.func("pages/admins/categories/CategoriesPage:methods.actionClicked()",arguments)},async toolbarClicked(e,a){console.func("pages/admins/categories/CategoriesPage:methods.toolbarClicked()",arguments)},async rowClicked(e,a){console.func("pages/admins/categories/CategoriesPage:methods.rowClicked()",arguments),this.$router.push({name:"Single Category",params:{id:a.id},query:{action:"edit"}})}},computed:{...w(r,["actions","rows","columns","module","toolbar","filters"])}};function B(e,a,d,c,t,o){const u=l("base-thumbnail"),m=l("base-btn"),p=l("base-status"),b=l("base-table"),f=l("base-section");return y(),_(S,{padding:""},{default:s(()=>[n(f,{flat:"",bordered:"","no-row":""},{default:s(()=>[n(b,{store:t.useCategoryStore,module:e.module,columns:e.columns,rows:e.rows,actions:e.actions,toolbar:e.toolbar,loading:t.loading,pagination:t.pagination,onRequest:o.onRequest,selection:"multiple",onActionClicked:o.actionClicked,onToolbarClicked:o.toolbarClicked,onRowClicked:o.rowClicked,"no-data-label":e.$t("label.noCategoryAvaialble"),"table-key":"category",selected:t.selected,"onUpdate:selected":a[1]||(a[1]=i=>t.selected=i)},{"body-cell-name":s(i=>[n(k,{class:"q-pa-none",dense:""},{default:s(()=>[n(g,{avatar:""},{default:s(()=>[n(u,{size:40,media:i.row.thumbnail},null,8,["media"])]),_:2},1024),n(g,{avatar:""},{default:s(()=>[n(m,{onClick:a[0]||(a[0]=P(()=>{},["stop"])),link:"",size:"12px",tag:"a",to:{name:"Single Category",params:{id:i.row.id},query:{action:"edit"}}},{default:s(()=>[q(v(i.value),1)]),_:2},1032,["to"])]),_:2},1024)]),_:2},1024)]),"body-cell-status":s(i=>[n(p,{type:i.value},null,8,["type"])]),_:1},8,["store","module","columns","rows","actions","toolbar","loading","pagination","onRequest","onActionClicked","onToolbarClicked","onRowClicked","no-data-label","selected"])]),_:1})]),_:1})}var I=C(R,[["render",B]]);export{I as default};
