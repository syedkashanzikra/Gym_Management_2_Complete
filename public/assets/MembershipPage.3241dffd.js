import{Q as C}from"./QBanner.bf86e8e3.js";import{Q as x}from"./QPopupProxy.e4ea2ef4.js";import{cX as M,c as d,A as b,_ as A,M as N,N as Y,r as p,o as v,a as k,w as n,f as s,e as _,n as Q,a5 as m,h as f,t as g,l as R,g as D,J as c}from"./index.46fdd451.js";import{Q as V}from"./QPage.701dd41d.js";import{M as B}from"./MemberDialog.30db186f.js";import"./QMenu.84416a21.js";import"./selection.e9a8e664.js";import"./QSelect.37e8c679.js";import"./QField.98c52be8.js";import"./QChip.2eda5f32.js";import"./QItemLabel.249954d1.js";import"./rtl.b51694b1.js";import"./QExpansionItem.fdf07114.js";import"./QTimeline.b6d0ce4e.js";import"./QInnerLoading.1459e1ed.js";import"./member.7e2676dd.js";import"./ParqForm.480a0115.js";import"./QToolbarTitle.cb225f03.js";import"./QToolbar.72090c76.js";import"./AddressFields.58941de5.js";import"./StateSelect.8267e923.js";import"./index.becf6c0c.js";import"./CitySelect.9ee53a09.js";const S=e=>[{label:e.$t("label.all"),value:null},{label:e.$t("label.checked"),value:"checked"},{label:e.$t("label.notChecked"),value:"notchecked"},{label:e.$t("label.pARQYes"),value:"parq"},{label:e.$t("label.pARQNo"),value:"notparq"},{label:e.$t("label.yesPIC"),value:"pic"},{label:e.$t("label.noPIC"),value:"notpic"}],h=M("finance-membership",{getters:{module:e=>({name:e.$t("modules.memberships"),singular:e.$t("modules.singular.membership"),plural:e.$t("modules.plural.memberships")}),columns:e=>[{name:"id",align:"left",label:e.$t("label.memberID"),field:"member_id_formated",style:"width: 40px",sortable:!0},{name:"name",align:"left",label:e.$t("label.name"),field:"name",style:"width: 40px",sortable:!0},{name:"starts_at",align:"left",label:e.$t("label.startsAt"),style:"width: 40px",field:"starts_at",format:a=>d.formatDate(a,"DD MMM, YYYY"),sortable:!0},{name:"ends_at",align:"left",label:e.$t("label.endsAt"),style:"width: 40px",field:"ends_at",format:a=>d.formatDate(a,"DD MMM, YYYY"),sortable:!0},{name:"renewal_fee",align:"left",label:e.$t("label.renewalFee"),field:"price",classes:a=>a.ends_at?"text-negative text-bold":"",format:a=>d.money(a==null?void 0:a.amount),style:"width: 40px",sortable:!1},{name:"price",align:"left",label:e.$t("label.plan"),field:a=>{var o;return(o=a==null?void 0:a.price)==null?void 0:o.label},style:"width: 250px; white-space: normal",sortable:!0},{name:"checked",align:"left",label:e.$t("label.checked"),field:"checked",format:a=>a?"Yes":"No",style:"width: 40px",sortable:!0},{name:"has_parq",align:"left",label:e.$t("label.pARQ"),field:"has_parq",format:a=>a?"Yes":"No",style:"width: 40px",sortable:!0},{name:"request_parq",align:"left",label:e.$t("label.m-PARQ"),field:"request_parq",format:a=>a?"Yes":"No",sortable:!0,style:"width: 40px"},{name:"has_avatar",align:"left",label:e.$t("label.pic"),field:"has_avatar",format:a=>a?"Yes":"No",sortable:!0,style:"width: 40px"},{name:"request_avatar",align:"left",label:e.$t("label.m-Pic"),field:"request_avatar",format:a=>a?"Yes":"No",sortable:!0}],toolbar:e=>[{title:e.$t("label.type"),action:"request",component:"base-select",dense:!0,outlined:!0,key:"type",placeholder:e.$t("placeholder.select"),optionsDense:!0,style:"width: 180px",mapOptions:!0,emitValue:!0,options:S(e),deleted:"all",prefix:e.$t("prefix.type")},{tooltip:e.$t("tooltip.exportAsCsv"),icon:"fas fa-file-csv",action:"export",color:"primary",deleted:"all",type:"csv"}]},actions:{get(e){return new Promise((a,o)=>{b.get("finance/memberships",e).then(l=>{this.setRows(l.data),a(l)}).catch(l=>{o(l)})})},checked(e){return e.loading=!0,new Promise((a,o)=>{b.post(`users/${e.id}/checked`).then(l=>{e.loading=!1,e.checked=!e.checked;const{message:i}=l;d.success(i),a(l)}).catch(l=>{e.loading=!1,d.error(l),o(l)})})},requestAvatar(e){return e.loading=!0,new Promise((a,o)=>{b.post(`users/${e.id}/request-avatar`).then(l=>{e.loading=!1,e.request_avatar=!e.request_avatar;const{message:i}=l;d.success(i),a(l)}).catch(l=>{e.loading=!1,d.error(l),o(l)})})},requestParq(e){return e.loading=!0,new Promise((a,o)=>{b.post(`users/${e.id}/request-parq`).then(l=>{e.loading=!1,e.request_parq=!e.request_parq;const{message:i}=l;d.success(i),a(l)}).catch(l=>{e.loading=!1,d.error(l),o(l)})})}}}),U={data(){return{loading:!1,loaded:!1,stats:{},pagination:{sortBy:"id",descending:!0,page:1,filter:"",type:null,advancedFilter:{},deleted:!1,rowsPerPage:15,rowsNumber:15,loaded:!1,includes:["price"],override:["includes"]},useFinanceMembershipStore:h()}},methods:{...N(h,["checked","get","requestAvatar","requestParq"]),onRequest(e){console.func("pages/admins/finance/MembershipPage:methods.onRequest()",arguments);const{page:a,rowsPerPage:o,sortBy:l,descending:i}=e.pagination;this.pagination=e.pagination,this.loading=!0,this.get({...e.pagination,direction:i?"desc":"asc"}).then(({meta:r})=>{this.pagination.rowsNumber=r.total,this.stats=r,this.pagination.page=a,this.pagination.rowsPerPage=o,this.pagination.sortBy=l,this.pagination.descending=i,this.pagination.loaded=!0,this.pagination.from=r.from,this.pagination.to=r.to,this.loading=!1}).catch(r=>{this.$core.error(r,{title:this.$t("dialog.title.error")})})},rowClicked(e,a){console.func("pages/admins/finance/AdminPage:methods.rowClicked()",arguments),this.$q.dialog({component:B,componentProps:{title:a.name,id:a.id}})}},computed:{...Y(h,["columns","toolbar","rows","module"]),permissions(){return[]}}},z=["innerHTML"];function F(e,a,o,l,i,r){const w=p("base-btn"),$=p("base-label"),P=p("base-table"),y=p("base-section");return v(),k(V,{padding:""},{default:n(()=>[s(y,{flat:"",bordered:"","no-row":""},{default:n(()=>[s(P,{columns:e.columns,module:e.module,store:i.useFinanceMembershipStore,rows:e.rows,toolbar:e.toolbar,loading:i.loading,pagination:i.pagination,onRequest:r.onRequest,onRowClicked:r.rowClicked,"no-data-label":e.$t("membership.noData"),"no-permissions":"","table-key":"finance/membership"},{"body-cell-name":n(t=>[_("i",{class:Q(`q-mr-sm q-icon fas fa-circle rag-${t.row.rag||"white"}`),style:{"font-size":"8px"},"aria-hidden":"true",role:"presentation"},null,2),s(w,{onClick:a[0]||(a[0]=m(()=>{},["stop"])),link:"",size:"12px",tag:"a",to:{name:"Single Member",params:{id:t.row.id},query:{action:"edit"}}},{default:n(()=>[f(g(t.value),1)]),_:2},1032,["to"]),t.row.note?(v(),k(R,{key:0,class:"q-ml-md",size:"9px",dense:"",round:"",flat:"",icon:"fad fa-memo-pad",onClick:a[1]||(a[1]=m(()=>{},["stop"]))},{default:n(()=>[s(x,null,{default:n(()=>[s(C,{style:{width:"350px"}},{default:n(()=>[s($,null,{default:n(()=>[f(g(e.$t("specialNote")),1)]),_:1}),_("span",{innerHTML:t.row.note.replace(/\r?\n/g,"<br />")},null,8,z)]),_:2},1024)]),_:2},1024)]),_:2},1024)):D("",!0)]),"body-cell-price":n(t=>{var u,q;return[s(w,{onClick:a[2]||(a[2]=m(()=>{},["stop"])),link:"",size:"12px",tag:"a",to:{name:"Single Plan",params:{id:(q=(u=t.row)==null?void 0:u.price)==null?void 0:q.plan_id},query:{action:"edit"}}},{default:n(()=>[f(g(t.value),1)]),_:2},1032,["to"])]}),"body-cell-checked":n(t=>[s(c,{onClick:a[3]||(a[3]=m(()=>{},["stop"])),dense:"",disable:t.row.loading,"model-value":t.row.checked,"onUpdate:modelValue":u=>e.checked(t.row),label:t.value},null,8,["disable","model-value","onUpdate:modelValue","label"])]),"body-cell-has_parq":n(t=>[s(c,{onClick:a[4]||(a[4]=m(()=>{},["stop"])),dense:"","model-value":t.row.has_parq,label:t.value},null,8,["model-value","label"])]),"body-cell-has_avatar":n(t=>[s(c,{onClick:a[5]||(a[5]=m(()=>{},["stop"])),dense:"","model-value":t.row.has_avatar,label:t.value},null,8,["model-value","label"])]),"body-cell-request_parq":n(t=>[s(c,{onClick:a[6]||(a[6]=m(()=>{},["stop"])),dense:"",disable:t.row.loading,"model-value":t.row.request_parq,"onUpdate:modelValue":u=>e.requestParq(t.row),label:t.value},null,8,["disable","model-value","onUpdate:modelValue","label"])]),"body-cell-request_avatar":n(t=>[s(c,{onClick:a[7]||(a[7]=m(()=>{},["stop"])),dense:"",disable:t.row.loading,"model-value":t.row.request_avatar,"onUpdate:modelValue":u=>e.requestAvatar(t.row),label:t.value},null,8,["disable","model-value","onUpdate:modelValue","label"])]),_:1},8,["columns","module","store","rows","toolbar","loading","pagination","onRequest","onRowClicked","no-data-label"])]),_:1})]),_:1})}var me=A(U,[["render",F]]);export{me as default};
