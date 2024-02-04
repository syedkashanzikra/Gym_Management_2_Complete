import{_ as q,aE as C,M as A,N as h,u as B,r as c,o as n,a as i,w as o,f as a,e as k,d as u,ag as x,F as _,J as M,t as m,g as v,l as N,h as g}from"./index.46fdd451.js";import{Q as s}from"./QTd.d26cec80.js";import{Q as w}from"./QTr.6cbeded8.js";import{Q as P}from"./QPage.701dd41d.js";import{M as F,u as V}from"./member-report.bbe9f9ac.js";import{u as L}from"./mixins.eaf0c01c.js";import"./member.7e2676dd.js";import"./QItemLabel.249954d1.js";import"./FileSelector.fa33ffa8.js";import"./FileCard.22a9b7b0.js";import"./QToolbarTitle.cb225f03.js";import"./QToolbar.72090c76.js";import"./QImg.7965eef6.js";import"./QInnerLoading.1459e1ed.js";import"./StateSelect.8267e923.js";import"./index.becf6c0c.js";import"./CitySelect.9ee53a09.js";import"./BaseSectionSkeleton.fd87b72d.js";import"./QSkeleton.a3e15f44.js";const p=new Date().getFullYear(),U=C.exports.range(p,p-5).map(e=>({id:e.toString(),label:e===p?"Current Year":e.toString(),checked:!1,type:null})),D={components:{MembersTable:F},mixins:[L],data(){return{loaded:!1,visiable:!0,rows:U,year:p}},methods:{...A(V,["reportsYearly"]),onRequest(e,r){console.func("pages/admins/members/ReportsPage:methods.onRequest()",arguments),Object.assign(r.row,{total:void 0,rolling:void 0,rolling_total:void 0,end_date:void 0,end_date_total:void 0,free:void 0,cancelled_total:void 0,cancelled:void 0,type:void 0,expand:!1}),e&&this.reportsYearly({year:r.row.id}).then(y=>{Object.assign(r.row,y)})},onLoad(e,r){if(console.func("pages/admins/members/ReportsPage:methods.onLoad()",arguments),e.row.expand&&r===e.row.type){e.row.expand=!1;return}else e.row.expand=!1,e.row.type=null;this.$nextTick(()=>{e.row.type=r,e.row.expand=!0})},onAdd(){console.func("pages/admins/members/ReportsPage:methods.onAdd()",arguments);const e=this.rows[this.rows.length-1].id-1;this.rows.push({id:e.toString(),label:e.toString(),checked:!1,type:null})}},mounted(){setTimeout(()=>{this.loaded=!0},500)},computed:{...h(B,["stats"]),...h(V,{columns:"yearlyReportColumns"}),years(){return C.exports.range(2015,new Date().getFullYear()+1,1)}}},j={class:"col"},E={class:"row q-col-gutter-md"},O={key:1};function J(e,r,y,$,f,b){const R=c("base-input"),T=c("base-btn"),Q=c("members-table"),S=c("base-table"),Y=c("base-section");return n(),i(P,{padding:""},{default:o(()=>[f.visiable?(n(),i(Y,{key:0,flat:"",bordered:"","no-row":""},{default:o(()=>[a(S,{"rows-per-page-options":[0],loaded:f.loaded,rows:f.rows,columns:e.columns,"hide-pagination":"","no-responsive":""},{top:o(()=>[k("div",j,[k("div",E,[(n(!0),u(_,null,x(e.columns.filter(t=>t.stats),(t,l)=>(n(),u("div",{key:l,class:"col-xs-6 col-sm"},[a(R,{prefix:`${t.label}:`,readonly:"",modelValue:e.stats[t.name],"onUpdate:modelValue":d=>e.stats[t.name]=d},null,8,["prefix","modelValue","onUpdate:modelValue"])]))),128))])])]),body:o(t=>[a(w,{props:t},{default:o(()=>[(n(!0),u(_,null,x(t.cols,l=>(n(),i(s,{key:l.name,props:t},{default:o(()=>[l.name==="id"?(n(),i(M,{key:0,dense:"",modelValue:t.row.checked,"onUpdate:modelValue":[d=>t.row.checked=d,d=>b.onRequest(d,t)],label:l.value},null,8,["modelValue","onUpdate:modelValue","label"])):(n(),u(_,{key:1},[l.value&&l.load?(n(),i(T,{key:0,link:"",color:"black",onClick:d=>b.onLoad(t,l.name),flat:"",dense:"",label:l.value},null,8,["onClick","label"])):(n(),u("span",O,m(l.value),1))],64))]),_:2},1032,["props"]))),128))]),_:2},1032,["props"]),t.row.expand?(n(),i(w,{key:0,props:t},{default:o(()=>[a(s,{class:"q-pa-none",colspan:"100%"},{default:o(()=>[a(Q,{"no-responsive":"",type:t.row.type,year:t.row.id},null,8,["type","year"])]),_:2},1024)]),_:2},1032,["props"])):v("",!0)]),"bottom-row":o(()=>[a(w,{"no-hover":""},{default:o(()=>[a(s,{class:"text-bold",colspan:"2"},{default:o(()=>[a(N,{flat:"",color:"primary",icon:"fad fa-circle-plus",dense:"",round:"",onClick:b.onAdd},null,8,["onClick"])]),_:1}),a(s,{align:"center",class:"text-bold",colspan:"1"},{default:o(()=>[g(m(e.getTotal("rolling_total")),1)]),_:1}),a(s,{align:"center",class:"text-bold",colspan:"1"}),a(s,{align:"center",class:"text-bold",colspan:"1"},{default:o(()=>[g(m(e.getTotal("end_date_total")),1)]),_:1}),a(s,{align:"center",class:"text-bold",colspan:"1"}),a(s,{align:"center",class:"text-bold",colspan:"1"}),a(s,{align:"center",class:"text-bold",colspan:"1"},{default:o(()=>[g(m(e.getTotal("cancelled_total")),1)]),_:1}),a(s,{align:"center",class:"text-bold",colspan:"1"})]),_:1})]),_:1},8,["loaded","rows","columns"])]),_:1})):v("",!0)]),_:1})}var ue=q(D,[["render",J]]);export{ue as default};
