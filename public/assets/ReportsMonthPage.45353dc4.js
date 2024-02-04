import{_ as U,M as q,N as k,u as N,aE as P,r as c,o as l,a as u,w as a,f as n,e as h,d as m,ag as w,F as _,J as Y,t as f,g as x,h as y}from"./index.46fdd451.js";import{Q as s}from"./QTd.d26cec80.js";import{Q as b}from"./QTr.6cbeded8.js";import{Q as B}from"./QPage.701dd41d.js";import{M as F,u as v}from"./member-report.bbe9f9ac.js";import{u as L}from"./mixins.eaf0c01c.js";import"./member.7e2676dd.js";import"./QItemLabel.249954d1.js";import"./FileSelector.fa33ffa8.js";import"./FileCard.22a9b7b0.js";import"./QToolbarTitle.cb225f03.js";import"./QToolbar.72090c76.js";import"./QImg.7965eef6.js";import"./QInnerLoading.1459e1ed.js";import"./StateSelect.8267e923.js";import"./index.becf6c0c.js";import"./CitySelect.9ee53a09.js";import"./BaseSectionSkeleton.fd87b72d.js";import"./QSkeleton.a3e15f44.js";const S={components:{MembersTable:F},mixins:[L],data(){return{loaded:!1,visiable:!0,rows:[{id:"1",checked:!1,type:null},{id:"2",checked:!1,type:null},{id:"3",checked:!1,type:null},{id:"4",checked:!1,type:null},{id:"5",checked:!1,type:null},{id:"6",checked:!1,type:null},{id:"7",checked:!1,type:null},{id:"8",checked:!1,type:null},{id:"9",checked:!1,type:null},{id:"10",checked:!1,type:null},{id:"11",checked:!1,type:null},{id:"12",checked:!1,type:null}],year:new Date().getFullYear()}},methods:{...q(v,["reportsMonthly"]),onRequest(e,d){console.func("pages/admins/members/ReportsMonthPage:methods.onRequest()",arguments),Object.assign(d.row,{total:void 0,rolling:void 0,rolling_total:void 0,end_date:void 0,end_date_total:void 0,free:void 0,cancelled_total:void 0,cancelled:void 0,type:void 0,expand:!1}),e&&this.reportsMonthly({year:this.year,month:d.row.id}).then(g=>{Object.assign(d.row,g)})},onLoad(e,d){if(console.func("pages/admins/members/ReportsMonthPage:methods.onLoad()",arguments),e.row.expand&&d===e.row.type){e.row.expand=!1;return}else e.row.expand=!1,e.row.type=null;this.$nextTick(()=>{e.row.type=d,e.row.expand=!0})},onChangeYear(){console.func("pages/admins/members/ReportsMonthPage:methods.onChangeYear()",arguments),this.visiable=!1,this.rows=this.rows.map(e=>({...e,total:void 0,rolling:void 0,rolling_total:void 0,end_date:void 0,end_date_total:void 0,monthly:void 0,yearly:void 0,yearly_total:void 0,free:void 0,cancelled_total:void 0,cancelled:void 0,type:void 0,checked:!1})),this.$nextTick(()=>{this.visiable=!0})}},mounted(){setTimeout(()=>{this.loaded=!0},500)},computed:{...k(N,["stats"]),...k(v,{columns:"monthlyReportColumns"}),years(){return P.exports.range(2015,new Date().getFullYear()+1,1)}}},D={class:"col"},j={class:"row q-col-gutter-md"},A={class:"col-xs-12 col-sm"},E={key:1};function O(e,d,g,J,r,p){const V=c("base-select"),M=c("base-input"),C=c("base-btn"),R=c("members-table"),T=c("base-table"),Q=c("base-section");return l(),u(B,{padding:""},{default:a(()=>[r.visiable?(l(),u(Q,{key:0,flat:"",bordered:"","no-row":""},{default:a(()=>[n(T,{"rows-per-page-options":[0],loaded:r.loaded,rows:r.rows,columns:e.columns,"hide-pagination":"","no-responsive":""},{top:a(()=>[h("div",D,[h("div",j,[h("div",A,[n(V,{"options-dense":"",modelValue:r.year,"onUpdate:modelValue":[d[0]||(d[0]=t=>r.year=t),p.onChangeYear],options:p.years,dense:"",outlined:"",prefix:e.$t("prefix.year")},null,8,["modelValue","options","prefix","onUpdate:modelValue"])]),(l(!0),m(_,null,w(e.columns.filter(t=>t.stats),(t,o)=>(l(),m("div",{key:o,class:"col-xs-6 col-sm"},[n(M,{prefix:`${t.label}:`,readonly:"",modelValue:e.stats[t.name],"onUpdate:modelValue":i=>e.stats[t.name]=i},null,8,["prefix","modelValue","onUpdate:modelValue"])]))),128))])])]),body:a(t=>[n(b,{props:t},{default:a(()=>[(l(!0),m(_,null,w(t.cols,o=>(l(),u(s,{key:o.name,props:t},{default:a(()=>[o.name==="id"?(l(),u(Y,{key:0,dense:"",modelValue:t.row.checked,"onUpdate:modelValue":[i=>t.row.checked=i,i=>p.onRequest(i,t)],label:o.value},null,8,["modelValue","onUpdate:modelValue","label"])):(l(),m(_,{key:1},[o.value&&o.load?(l(),u(C,{key:0,link:"",color:"black",onClick:i=>p.onLoad(t,o.name),flat:"",dense:"",label:o.value},null,8,["onClick","label"])):(l(),m("span",E,f(o.value),1))],64))]),_:2},1032,["props"]))),128))]),_:2},1032,["props"]),t.row.expand?(l(),u(b,{key:0,props:t},{default:a(()=>[n(s,{class:"q-pa-none",colspan:"100%"},{default:a(()=>[n(R,{"no-responsive":"",type:t.row.type,year:r.year,month:t.row.id},null,8,["type","year","month"])]),_:2},1024)]),_:2},1032,["props"])):x("",!0)]),"bottom-row":a(()=>[n(b,null,{default:a(()=>[n(s,{align:"center",class:"text-bold",colspan:"2"}),n(s,{align:"center",class:"text-bold",colspan:"1"},{default:a(()=>[y(f(e.getTotal("rolling_total")),1)]),_:1}),n(s,{align:"center",class:"text-bold",colspan:"1"}),n(s,{align:"center",class:"text-bold",colspan:"1"},{default:a(()=>[y(f(e.getTotal("end_date_total")),1)]),_:1}),n(s,{align:"center",class:"text-bold",colspan:"1"}),n(s,{align:"center",class:"text-bold",colspan:"1"}),n(s,{align:"center",class:"text-bold",colspan:"1"},{default:a(()=>[y(f(e.getTotal("cancelled_total")),1)]),_:1}),n(s,{align:"center",class:"text-bold",colspan:"1"})]),_:1})]),_:1},8,["loaded","rows","columns"])]),_:1})):x("",!0)]),_:1})}var ce=U(S,[["render",O]]);export{ce as default};
