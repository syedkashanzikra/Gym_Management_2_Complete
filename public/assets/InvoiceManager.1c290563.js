import{M as b,dD as h,N as f,_ as w,r as s,o as v,a as _,w as l,f as c}from"./index.46fdd451.js";import{u as d}from"./stripe.esm.e1002465.js";const I={data(){return{loading:!1,columns:[{name:"date",label:this.$t("label.date"),field:"date",align:"left"},{name:"amount",label:this.$t("label.amount"),field:"amount",align:"left"},{name:"status",label:this.$t("label.status"),field:"status",align:"left"},{name:"actions",label:"",field:"actions",align:"right"}],pagination:{page:1,rowsPerPage:10,rowsNumber:10,loaded:!1}}},methods:{...b(d,["getInvoices","downloadInvoice"]),onRequest(e){console.func("components/payment-methods/InvoiceManager:methods.onRequest()",arguments),this.loading=!0;const{page:o,rowsPerPage:a}=e.pagination;this.getInvoices({page:o,rowsPerPage:a}).then(t=>{this.pagination.rowsNumber=t.total,this.pagination.page=o,this.pagination.rowsPerPage=a,this.pagination.from=t.from,this.pagination.to=t.to,this.pagination.loaded=!0,this.loading=!1}).catch(t=>{this.$emit("error",t)})},onViewInvoice(e){console.func("components/payment-methods/InvoiceManager:methods.onViewInvoice()",arguments),e.row.loading=!0,this.downloadInvoice(e==null?void 0:e.row.stripe_id).then(o=>{e.row.loading=!1;const a=new Blob([o],{type:"application/pdf"});this.$q.dialog({component:h,componentProps:{title:`Invoice-${e==null?void 0:e.row.number}.pdf`,url:URL.createObjectURL(a)}}).onOk(t=>{URL.revokeObjectURL(t)})}).catch(o=>{e.row.loading=!1,this.$core.error(o,{title:this.$t("dialog.title.error")})})},onLoad(){console.func("components/payment-methods/InvoiceManager:methods.onLoad()",arguments),this.onRequest({pagination:this.pagination})}},computed:{...f(d,["invoices"])}};function $(e,o,a,t,i,r){const p=s("base-status"),m=s("base-btn"),u=s("base-table"),g=s("base-section");return v(),_(g,{flat:"",bordered:"","no-row":"",title:e.$t("invoices.historyTitle"),description:e.$t("invoices.historyDesc"),class:"invoice-manager"},{default:l(()=>[c(u,{rowsPerPageOptions:[0],rows:e.invoices,columns:i.columns,"no-data-label":e.$t("invoices.noData"),pagination:i.pagination,"onUpdate:pagination":o[0]||(o[0]=n=>i.pagination=n),onRequest:r.onRequest,loading:i.loading,"hide-top":"","has-actions":""},{"body-cell-status":l(n=>[c(p,{type:n.row.status},null,8,["type"])]),actions:l(n=>[c(m,{link:"",color:"grey",loading:n.row.loading,onClick:y=>r.onViewInvoice(n),label:e.$t("invoices.viewLabel")},null,8,["loading","onClick","label"])]),_:1},8,["rows","columns","no-data-label","pagination","onRequest","loading"])]),_:1},8,["title","description"])}var k=w(I,[["render",$]]);export{k as I};
