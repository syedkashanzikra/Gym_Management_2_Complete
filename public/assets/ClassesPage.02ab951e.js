import{_ as b,M as C,N as w,r as l,o as k,a as v,w as t,f as i,a5 as _,h as y,t as g,a6 as u,e as P}from"./index.46fdd451.js";import{Q as q}from"./QPage.701dd41d.js";import{u as r}from"./class-list.763448d9.js";const R={data(){return{loading:!1,loaded:!1,pagination:{sortBy:"name",descending:!1,page:1,filter:"",advancedFilter:{},deleted:!1,rowsPerPage:15,rowsNumber:15,active:!0,loaded:!1},useClassListStore:r()}},methods:{...C(r,["get","changeActive","changeHasDescription"]),onRequest(e){console.func("admins/class-lists/ClassesPage:methods.onRequest()",arguments);const{page:s,rowsPerPage:d,sortBy:c,descending:n}=e.pagination;this.pagination=e.pagination,this.loading=!0,this.get({...e.pagination,direction:n?"desc":"asc"}).then(({meta:a})=>{this.pagination.rowsNumber=a.total,this.pagination.page=s,this.pagination.rowsPerPage=d,this.pagination.sortBy=c,this.pagination.descending=n,this.pagination.loaded=!0,this.pagination.from=a.from,this.pagination.to=a.to,this.loading=!1}).catch(a=>{this.$core.error(a,{title:this.$t("dialog.title.error")})})},async actionClicked(e,s){console.func("admins/class-lists/ClassesPage:methods.actionClicked()",arguments)},async toolbarClicked(e,s){console.func("admins/class-lists/ClassesPage:methods.toolbarClicked()",arguments)},async rowClicked(e,s){console.func("admins/class-lists/ClassesPage:methods.rowClicked()",arguments),this.$router.push({name:"Single Class",params:{id:s.id},query:{action:"edit"}})}},computed:{...w(r,["actions","rows","columns","module","toolbar","filters"])}},S={class:"ellipsis-2-lines"};function V(e,s,d,c,n,a){const m=l("base-btn"),p=l("base-table"),h=l("base-section");return k(),v(q,{padding:""},{default:t(()=>[i(h,{flat:"",bordered:"","no-row":""},{default:t(()=>[i(p,{store:n.useClassListStore,module:e.module,columns:e.columns,rows:e.rows,actions:e.actions,toolbar:e.toolbar,filters:e.filters,loading:n.loading,pagination:n.pagination,onRequest:a.onRequest,onActionClicked:a.actionClicked,onToolbarClicked:a.toolbarClicked,onRowClicked:a.rowClicked,"no-data-label":e.$t("classes.noData")},{"body-cell-name":t(o=>[i(m,{onClick:s[0]||(s[0]=_(()=>{},["stop"])),link:"",size:"12px",tag:"a",to:{name:"Single Class",params:{id:o.row.id},query:{action:"edit"}}},{default:t(()=>[y(g(o.value),1)]),_:2},1032,["to"])]),"body-cell-is_active":t(o=>[i(u,{"onUpdate:modelValue":f=>e.changeActive(o.row),size:"sm",dense:"","model-value":o.row.is_active,color:"green"},null,8,["onUpdate:modelValue","model-value"])]),"body-cell-has_description":t(o=>[i(u,{"onUpdate:modelValue":f=>e.changeHasDescription(o.row),size:"sm",dense:"","model-value":o.row.has_description,color:"green"},null,8,["onUpdate:modelValue","model-value"])]),"body-cell-description":t(o=>[P("div",S,g(o.value),1)]),_:1},8,["store","module","columns","rows","actions","toolbar","filters","loading","pagination","onRequest","onActionClicked","onToolbarClicked","onRowClicked","no-data-label"])]),_:1})]),_:1})}var D=b(R,[["render",V]]);export{D as default};
