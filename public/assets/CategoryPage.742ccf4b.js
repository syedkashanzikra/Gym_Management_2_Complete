import{_ as T,aE as p,M as _,N as S,r as i,o as h,a as b,w as n,e as l,f as o,h as u,t as c,K as I,I as V,a5 as J,g as Q}from"./index.46fdd451.js";import{Q as F}from"./QPage.701dd41d.js";import{C as z}from"./CategorySelect.d1b1d0fd.js";import{S as E}from"./SeoSection.c1301ced.js";import{F as M}from"./FileSelector.fa33ffa8.js";import{S as G}from"./SkeletonSinglePage.f05b1d90.js";import{u as v}from"./category.8d269d68.js";import{u as w}from"./index.dbe12f27.js";import"./QSelect.37e8c679.js";import"./QField.98c52be8.js";import"./QChip.2eda5f32.js";import"./QItemLabel.249954d1.js";import"./QMenu.84416a21.js";import"./selection.e9a8e664.js";import"./rtl.b51694b1.js";import"./FileCard.22a9b7b0.js";import"./QToolbarTitle.cb225f03.js";import"./QToolbar.72090c76.js";import"./QImg.7965eef6.js";import"./QInnerLoading.1459e1ed.js";import"./QSkeleton.a3e15f44.js";import"./tag.19d681e9.js";import"./collection.a5cbc931.js";const q={name:"",description:"",status:"Draft"},K={components:{CategorySelect:z,SeoSection:E,SkeletonSinglePage:G,ThumbnailSelector:M},data(){return{loaded:!1,submited:!1,default:p.exports.cloneDeep(q),category:p.exports.cloneDeep(q),options:[],pagination:{sortBy:"TITLE_ASC",descending:!1,page:1,filter:"",rowsPerPage:15,rowsNumber:15,loaded:!1,loading:!1}}},methods:{..._(v,["show","store","update"]),..._(w,["get"]),onSubmit(e){console.func("pages/categories/category:methods.onSubmit()",arguments),this.submited=!0,(this.creating?this.store:this.update)(this.category).then(({data:d,message:g})=>{this.submited=!1,this.$q.notify(g),this.category=d,this.default=p.exports.cloneDeep(d),this.pagination.loading=!1,this.pagination.loaded=!0,this.$router.push({name:"Single Category",params:{id:d.id},query:{action:"edit"}})}).catch(d=>{this.submited=!1,this.$core.error(d,{title:this.$t("dialog.title.error")})})},onReset(e){console.func("pages/categories/category:methods.onReset()",arguments),this.loaded=!1,setTimeout(()=>{this.category=p.exports.cloneDeep(this.default),this.loaded=!0},1)},onCancel(e){console.func("pages/categories/category:methods.onCancel()",arguments),this.$router.back()},onRequestProduct(e){console.func("pages/categories/category:methods.onRequest()",arguments);const{page:a,rowsPerPage:d,sortBy:g,descending:t}=e.pagination;this.pagination=e.pagination,this.pagination.loading=!0,this.get({...e.pagination,descending:t?"desc":"asc",category:this.category.id}).then(({meta:r})=>{this.pagination.rowsNumber=r.total,this.pagination.page=a,this.pagination.rowsPerPage=d,this.pagination.sortBy=g,this.pagination.descending=t,this.pagination.loaded=!0,this.pagination.from=r.from,this.pagination.to=r.to,this.pagination.loading=!1}).catch(()=>{this.pagination.loading=!1})},onUpdateSortBy(){console.func("pages/categories/category:methods.onUpdateSortBy()",arguments),this.onRequestProduct({pagination:this.pagination})}},mounted(){this.creating?this.loaded=!0:this.show(this.id).then(e=>{this.category=e,this.default=p.exports.cloneDeep(e),this.loaded=!0}).catch(e=>{this.$core.error(e,{title:this.$t("dialog.title.error")})})},computed:{...S(w,["rows","sortOptions"]),...S(v,{columns:"productColumns"}),edit(){return["add","edit"].includes(this.action)||this.creating},creating(){return this.id==="add"},id(){return this.$route.params.id},action(){return this.$route.query.action},disable(){return this.default&&JSON.stringify(this.category)===JSON.stringify(this.default)},resetable(){return this.default&&JSON.stringify(this.category)!==JSON.stringify(this.default)}}},L={class:"row q-col-gutter-md"},Y={class:"col-xs-12 col-sm-9"},j={class:"q-gutter-md"},H={class:"col-xs-12"},W={class:"col-xs-12"},X={class:"q-mb-md row q-col-gutter-md"},Z={class:"col"},$={class:"col-xs-12 col-sm-3"},ee={class:"q-gutter-md"};function te(e,a,d,g,t,r){const m=i("base-label"),C=i("base-input"),P=i("base-editor"),f=i("base-section"),y=i("base-select"),k=i("base-thumbnail"),B=i("base-btn"),N=i("base-status"),R=i("base-table"),U=i("seo-section"),D=i("category-select"),x=i("thumbnail-selector"),O=i("base-form"),A=i("skeleton-single-page");return h(),b(F,{padding:""},{default:n(()=>[t.loaded?(h(),b(O,{key:0,onSubmit:r.onSubmit,onCancel:r.onCancel,onReset:r.onReset,resetable:r.resetable,disable:r.disable,submited:t.submited},{default:n(()=>[l("div",L,[l("div",Y,[l("div",j,[o(f,{title:"General information",description:"A great category title and description has the power to turn a casual shopper into a revenue-generating buyer."},{default:n(()=>[l("div",H,[o(m,null,{default:n(()=>[u(c(e.$t("label.name")),1)]),_:1}),o(C,{modelValue:t.category.name,"onUpdate:modelValue":a[0]||(a[0]=s=>t.category.name=s)},null,8,["modelValue"])]),l("div",W,[o(m,null,{default:n(()=>[u(c(e.$t("label.descriptionOptional")),1)]),_:1}),o(P,{modelValue:t.category.description,"onUpdate:modelValue":a[1]||(a[1]=s=>t.category.description=s),"min-height":"10rem"},null,8,["modelValue"])])]),_:1}),this.creating?Q("",!0):(h(),b(f,{key:0,title:"Products","no-row":""},{default:n(()=>[l("div",X,[l("div",Z,[o(y,{prefix:"Sort: ","map-options":"","emit-value":"",modelValue:t.pagination.sortBy,"onUpdate:modelValue":[a[2]||(a[2]=s=>t.pagination.sortBy=s),r.onUpdateSortBy],options:e.sortOptions,dense:"",outlined:""},null,8,["modelValue","options","onUpdate:modelValue"])])]),l("div",null,[o(R,{columns:e.columns,rows:e.rows,loading:t.pagination.loading,pagination:t.pagination,"hide-top":"",onRequest:r.onRequestProduct,"no-data-label":e.$t("label.noProductAvaialble")},{"body-cell-title":n(s=>[o(I,{class:"q-pa-none",dense:""},{default:n(()=>[o(V,{avatar:""},{default:n(()=>[o(k,{size:40,media:s.row.thumbnail},null,8,["media"])]),_:2},1024),o(V,{avatar:""},{default:n(()=>[o(B,{onClick:a[3]||(a[3]=J(()=>{},["stop"])),link:"",size:"12px",tag:"a",to:{name:"Single Product",params:{id:s.row.id},query:{action:"edit"}}},{default:n(()=>[u(c(s.value),1)]),_:2},1032,["to"])]),_:2},1024)]),_:2},1024)]),"body-cell-status":n(s=>[o(N,{type:s.value},null,8,["type"])]),_:1},8,["columns","rows","loading","pagination","onRequest","no-data-label"])])]),_:1})),o(U,{prefix:"https://yourshop.com/categories/",modelValue:t.category,"onUpdate:modelValue":a[4]||(a[4]=s=>t.category=s),message:"Add a name and description to see how this category might appear in a search engine listing"},null,8,["modelValue"])])]),l("div",$,[o(f,{"no-row":""},{default:n(()=>[l("div",ee,[l("div",null,[o(m,null,{default:n(()=>[u(c(e.$t("label.categoryStatus")),1)]),_:1}),o(y,{modelValue:t.category.status,"onUpdate:modelValue":a[5]||(a[5]=s=>t.category.status=s),options:["Active","Draft"],dense:"",outlined:""},null,8,["modelValue"])]),l("div",null,[o(m,null,{default:n(()=>[u(c(e.$t("label.parent")),1)]),_:1}),o(D,{modelValue:t.category.parent,"onUpdate:modelValue":a[6]||(a[6]=s=>t.category.parent=s),hint:""},null,8,["modelValue"])]),l("div",null,[o(m,null,{default:n(()=>[u(c(e.$t("label.thumbnail")),1)]),_:1}),o(x,{loadFromServer:!0,"dialog-label":e.$t("label.uploadthumbnail"),icon:"fad fa-image",hint:"You can only choose images as category thumbnail.",modelValue:t.category.thumbnail,"onUpdate:modelValue":a[7]||(a[7]=s=>t.category.thumbnail=s)},null,8,["dialog-label","modelValue"])])])]),_:1})])])]),_:1},8,["onSubmit","onCancel","onReset","resetable","disable","submited"])):(h(),b(A,{key:1}))]),_:1})}var Ce=T(K,[["render",te]]);export{Ce as default};
