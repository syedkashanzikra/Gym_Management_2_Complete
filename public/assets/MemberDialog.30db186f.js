import{Q as S}from"./QSelect.37e8c679.js";import{Q as A}from"./QExpansionItem.fdf07114.js";import{Q as N}from"./QTimeline.b6d0ce4e.js";import{M as x,aE as y,N as P,u as Q,_ as U,r as i,o as r,a as h,w as n,f as t,e as a,h as d,t as u,g as f,d as D,F as L,ag as E,aF as F,aD as H}from"./index.46fdd451.js";import{Q as B}from"./QInnerLoading.1459e1ed.js";import{u as I}from"./member.7e2676dd.js";import{P as O}from"./ParqForm.480a0115.js";import{A as J}from"./AddressFields.58941de5.js";const T={components:{ParqForm:O,AddressFields:J},data(){return{member:{address:{}},default:{address:{}},loading:!0,parq:!1,paymentLoading:!1}},props:{title:{type:String,default:"memberInfo"},id:[Number,String],isEnquiry:Boolean},emits:["ok","hide"],methods:{...x(I,{showMember:"show",update:"update",notes:"notes",markAsPaid:"markAsPaid"}),async show(){console.func("components/MemberDialog:methods.show()",arguments),this.loading=!0,this.$refs.dialog.show(),this.showMember(this.id).then(e=>{this.member=e,this.default=y.exports.cloneDeep(e),this.loading=!1}).catch(e=>{this.$core.error(e,{title:this.$t("dialog.title.error")}),this.hide(),this.loading=!1})},hide(){console.func("components/MemberDialog:methods.close()",arguments),this.$refs.dialog.hide()},onDialogHide(){console.func("components/MemberDialog:methods.onDialogHide()",arguments),this.$emit("hide")},onDone(){console.func("components/MemberDialog:methods.onDone()",arguments),this.$emit("ok"),this.hide()},onSubmit(e){console.func("components/MemberDialog:methods.onSubmit()",arguments),this.loading=!0,this.update(this.member).then(({message:o})=>{this.default=y.exports.cloneDeep(this.member),this.loading=!1,this.$q.notify(o)}).catch(o=>{this.loading=!1,this.$core.error(o,{title:this.$t("dialog.title.error")})})},onSaveAsMember(){console.func("components/MemberDialog:methods.onSaveAsMember()",arguments),this.loading=!0,this.update({...this.member,status:"Active",is_enquiry:!1}).then(({message:e})=>{this.loading=!1,this.$core.success(e),this.onDone()}).catch(e=>{this.loading=!1,this.$core.error(e,{title:this.$t("dialog.title.error")})})},onCreateNote(e){console.func("components/MemberDialog:methods.onCreateNote()",arguments),e?this.member.notes.splice(0,0,e):this.showMember(this.id).then(o=>{this.member=o,this.default=y.exports.cloneDeep(o),this.loading=!1})},async onCollectPayment(e){console.func("components/MemberDialog:methods.onCollectPayment()",arguments),this.paymentLoading=!0,await this.markAsPaid(this.member).then(()=>{this.show()}).catch(()=>{}),this.paymentLoading=!1}},computed:{...P(Q,["user"]),disable(){return this.default&&JSON.stringify(this.member)===JSON.stringify(this.default)}}},z={class:"col-xs-12 col-sm-2"},j={class:"col-xs-12 col-sm-3"},G={class:"col-xs-12 col-sm-3"},K={class:"col-xs-12 col-sm-4"},R={class:"col-xs-12 col-sm-3"},W={class:"col-xs-12 col-sm-3"},X={class:"col-xs-12"},Y={class:"col-xs-12 col-sm-12"},Z={class:"text-right q-gutter-x-sm col-xs-12"},$={key:0,class:"col-xs-12"};function ee(e,o,g,oe,s,c){const m=i("base-label"),k=i("base-select"),p=i("base-input"),q=i("address-fields"),_=i("base-btn"),w=i("parq-form"),V=i("base-section"),v=i("base-note-card"),C=i("base-dialog");return r(),h(C,{title:e.$t(g.title),"body-class":"q-pa-none scroll","content-style":"width: 950px; max-width: 95vw",ref:"dialog",onHide:c.onDialogHide,"use-separator":"","hide-footer":"",persistent:""},{body:n(()=>[t(V,{flat:""},{default:n(()=>{var b,M;return[a("div",z,[t(m,null,{default:n(()=>[d(u(e.$t("title")),1)]),_:1}),t(k,{modelValue:s.member.title,"onUpdate:modelValue":o[0]||(o[0]=l=>s.member.title=l),dense:"",outlined:"",options:["Mr","Mrs","Ms","Miss","Mx","Dr","Fr","Prof"]},null,8,["modelValue"])]),a("div",j,[t(m,null,{default:n(()=>[d(u(e.$t("firstName")),1)]),_:1}),t(p,{modelValue:s.member.first_name,"onUpdate:modelValue":o[1]||(o[1]=l=>s.member.first_name=l)},null,8,["modelValue"])]),a("div",G,[t(m,null,{default:n(()=>[d(u(e.$t("surname")),1)]),_:1}),t(p,{modelValue:s.member.last_name,"onUpdate:modelValue":o[2]||(o[2]=l=>s.member.last_name=l)},null,8,["modelValue"])]),a("div",K,[t(m,null,{default:n(()=>[d(u(e.$t("emailAddress")),1)]),_:1}),t(p,{modelValue:s.member.email,"onUpdate:modelValue":o[3]||(o[3]=l=>s.member.email=l)},null,8,["modelValue"])]),a("div",R,[t(m,null,{default:n(()=>[d(u(e.$t("phoneNumber")),1)]),_:1}),t(p,{modelValue:s.member.phone_number,"onUpdate:modelValue":o[4]||(o[4]=l=>s.member.phone_number=l)},null,8,["modelValue"])]),a("div",W,[t(m,null,{default:n(()=>[d(u(e.$t("label.status")),1)]),_:1}),t(S,{dense:"",outlined:"",modelValue:s.member.status,"onUpdate:modelValue":o[5]||(o[5]=l=>s.member.status=l),options:["Active","Pending","Deactive","Lost"]},null,8,["modelValue"])]),a("div",X,[t(q,{modelValue:s.member.address,"onUpdate:modelValue":o[6]||(o[6]=l=>s.member.address=l)},null,8,["modelValue"])]),a("div",Y,[t(m,null,{default:n(()=>[d(u(e.$t("specialNote")),1)]),_:1}),t(p,{modelValue:s.member.special_note,"onUpdate:modelValue":o[7]||(o[7]=l=>s.member.special_note=l),type:"textarea"},null,8,["modelValue"])]),a("div",Z,[((M=(b=s.member)==null?void 0:b.latest_invoice)==null?void 0:M.status)==="open"?(r(),h(_,{key:0,class:"main-btn",color:"positive",icon:"fas fa-money-bill-1",label:e.$t("collectPayment"),onClick:c.onCollectPayment,loading:s.paymentLoading},null,8,["label","onClick","loading"])):f("",!0),g.isEnquiry?(r(),h(_,{key:1,class:"main-btn",color:"secondary",label:e.$t("saveAsMember"),onClick:c.onSaveAsMember},null,8,["label","onClick"])):f("",!0),t(_,{class:"main-btn",color:"primary",label:e.$t("update"),disable:c.disable,onClick:c.onSubmit},null,8,["label","disable","onClick"])]),g.isEnquiry?(r(),D("div",$,[t(A,{"header-class":"bg-grey-3",icon:"fas fa-clipboard-list-check",label:e.$t("parq"),onShow:o[9]||(o[9]=l=>s.parq=!0),onHide:o[10]||(o[10]=l=>s.parq=!1)},{default:n(()=>[s.parq?(r(),h(w,{key:0,class:"q-pa-md border-all",member:s.member,"onUpdate:member":o[8]||(o[8]=l=>s.member=l)},null,8,["member"])):f("",!0)]),_:1},8,["label"])])):f("",!0)]}),_:1}),t(V,{flat:"",title:e.$t("notes"),description:e.$t("members.activityDesc"),"no-row":"","body-class":"q-px-lg","head-class":"q-pt-none"},{default:n(()=>[t(N,{class:"comments",color:"secondary"},{default:n(()=>[t(v,{"module-action":e.notes,"module-id":s.member.id,class:"comment",creating:"",onCreate:c.onCreateNote},null,8,["module-action","module-id","onCreate"]),(r(!0),D(L,null,E(s.member.notes,b=>(r(),h(v,F({class:"comment",key:b.id},b,{"module-id":s.member.id,user:b.admin}),null,16,["module-id","user"]))),128))]),_:1})]),_:1},8,["title","description"])]),loading:n(()=>[t(B,{showing:s.loading},{default:n(()=>[t(H,{size:"50px",color:"primary"})]),_:1},8,["showing"])]),_:1},8,["title","onHide"])}var de=U(T,[["render",ee]]);export{de as M};
