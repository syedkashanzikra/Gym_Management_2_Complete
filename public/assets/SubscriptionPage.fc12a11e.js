import{M as y,u as v,N as S,_ as F,r,o as c,a as k,w as p,e as l,d as u,g as w,f as o,F as V,h as L,t as B,a4 as D,a8 as $,L as q,a6 as T,ag as U,aF as N}from"./index.46fdd451.js";import{Q as A}from"./QPage.701dd41d.js";import{u as P,P as H}from"./stripe.esm.e1002465.js";import{S as I}from"./AddPaymentMethod.07f1e0b4.js";import{B as Q}from"./BaseSectionSkeleton.fd87b72d.js";import{P as j}from"./PaymentMethods.2430131c.js";import"./QItemLabel.249954d1.js";import"./member.7e2676dd.js";import"./QSkeleton.a3e15f44.js";import"./PaymentMethodCard.43e041d3.js";import"./QBadge.942c3fbf.js";import"./QMenu.84416a21.js";import"./selection.e9a8e664.js";const O=e=>["create","change"].includes(e),x={components:{StripeCard:I},props:{plan:{required:!0,type:Object},mode:{type:String,validator:O},interval:String,paymentMethod:{type:Object,default:()=>({})}},data(){return{visible:!0,processing:!1}},emits:["ok","hide"],methods:{...y(P,["getSubscribed","subscribe","getPaymentMethods","confirm"]),...y(v,["currentUser"]),onSuccess({id:e,card:t}){console.func("components/ManageSubscription:methods.onSuccess()",arguments),this.processing=!0;const{last4:i}=t;this.subscribe({plan:this.plan.id,payment_interval:this.interval,payment_method:e,last_four_digit:i}).then(m=>{this.processing=!0;const{message:s,requiresAction:n,paymentIntent:d}=m;n?this.$refs.stripeCard.confirmPaymentMethod(d):(this.$core.success(s,{title:this.$t("dialog.title.success")}),this.$router.push({name:"Billing"}),this.processing=!1)}).catch(m=>{this.processing=!1,this.$core.error(m,{title:this.$t("dialog.title.error")})})},onFailed(e){console.func("components/ManageSubscription:methods.onFailed()",arguments),this.$core.error(e,{title:this.$t("dialog.title.paymentFailed")}),this.processing=!1},onConfirmed(e){console.func("components/ManageSubscription:methods.onConfirmed()",arguments),this.confirm({plan:this.plan.id,payment_intent:e.id}).then(({message:t})=>{this.$core.success(t,{title:this.$t("dialog.title.success")}),this.$router.push({name:"Billing"}),this.processing=!1}).catch(t=>{this.processing=!1,this.$core.error(t,{title:this.$t("dialog.title.paymentAuthenticationFailure")})})},onConfirm(){console.func("components/ManageSubscription:methods.onConfirm()",arguments),this.onSuccess(this.paymentMethod)}},computed:{...S(v,["billingDetails","isSubscribed"]),...S(P,["upcomingInvoice","currentPlan","subscription"]),price(){var e,t,i;return(e=this.plan)!=null&&e.is_custom?(t=this.plan)==null?void 0:t.prices[0]:(i=this.plan)==null?void 0:i.prices.find(({interval:m})=>m===this.interval)},intervalLabel(){return this.$core.priceToInterval(this.price)},create(){return this.mode==="create"},change(){return this.mode==="change"},label(){return this.$t("proceedPayment")},color(){return"positive"},isDisable(){var e;return!((e=this.paymentMethod)!=null&&e.id)}}},E={class:"plan-details"},z=["innerHTML"],G=["innerHTML"],J={class:"q-mt-sm payment-btn"},K={class:"q-mt-xs text-small"};function R(e,t,i,m,s,n){const d=r("base-btn"),M=r("stripe-card"),C=r("base-section");return c(),k(C,{flat:"",bordered:"",title:e.$t("paymentSummary"),dense:"","no-row":"",class:"subscription-manage"},{default:p(()=>{var h,g,b,a,f,_;return[l("div",E,[n.create?(c(),u("p",{key:0,innerHTML:e.$t("plan.create",{plan:(h=i.plan)==null?void 0:h.label,amount:e.$core.money((g=n.price)==null?void 0:g.amount),interval:n.intervalLabel})},null,8,z)):n.change?(c(),u("p",{key:1,class:"text-body q-mb-sm",innerHTML:e.$t("plan.change",{currentPlan:(b=e.currentPlan)==null?void 0:b.plan.label,plan:(a=i.plan)==null?void 0:a.label,amount:e.$core.money((f=n.price)==null?void 0:f.amount),interval:n.intervalLabel})},null,8,G)):w("",!0)]),l("div",J,[o(d,{loading:s.processing,disabled:n.isDisable,color:n.color,label:n.label,onClick:n.onConfirm,style:{"min-width":"150px"},padding:"10px"},null,8,["loading","disabled","color","label","onClick"]),l("div",K,[n.change?(c(),u(V,{key:0},[L("*"+B(e.$t("hint.priceChange")),1)],64)):w("",!0)]),D(o(M,{amount:(_=n.price)==null?void 0:_.amount,processing:s.processing,onSuccess:n.onSuccess,onConfirmed:n.onConfirmed,onFailed:n.onFailed,billingDetails:e.billingDetails,ref:"stripeCard"},null,8,["amount","processing","onSuccess","onConfirmed","onFailed","billingDetails"]),[[$,!1]])])]}),_:1},8,["title"])}var W=F(x,[["render",R]]);const X={components:{PlanCard:H,BaseSectionSkeleton:Q,PaymentMethods:j,ManageSubscription:W},data(){return{mode:"create",interval:"month",plan:null,paymentMethod:null,loading:!0}},methods:{...y(P,["getSubscribed","getPlans"]),...y(v,["currentUser"]),onSelectPlan(e,t="create"){console.func("pages/SubscriptionPage:methods.onSelectPlan()",arguments),this.mode=t}},computed:{...S(v,["isSubscribed","hasCancelled"]),...S(P,["upcomingInvoice","currentPlan","defaultPaymentMethod","selectedPlan","plans","defaultPlan"]),title(){return this.isSubscribed?this.$t("changePlan"):this.$t("selectPlan")},price(){var e;return(e=this.selectedPlan(this.plan))==null?void 0:e.prices.find(({interval:t})=>t===this.interval)},processPayment(){var t,i;const e=(t=this.currentPlan)==null?void 0:t.stripe_id;return this.plan&&e!==((i=this.price)==null?void 0:i.stripe_id)}},async mounted(){var e,t,i;await this.currentUser(),await this.getSubscribed(),this.interval=((e=this.currentPlan)==null?void 0:e.interval)||"month",this.plan=((t=this.currentPlan)==null?void 0:t.plan.id)||((i=this.defaultPlan)==null?void 0:i.plan.id),this.loading=!1},watch:{defaultPaymentMethod(e){e!=null&&e.id&&(this.paymentMethod={...e})}}},Y={class:"row q-col-gutter-lg"},Z={class:"col-xs-12 col-sm-12"},ee={class:"col-xs-12 col-sm-6"},te={class:"col-xs-12 col-sm-6"},ne={class:"q-gutter-y-lg"};function se(e,t,i,m,s,n){const d=r("base-page-header"),M=r("plan-card"),C=r("base-section-skeleton"),h=r("base-section"),g=r("payment-methods"),b=r("manage-subscription");return c(),k(A,{padding:""},{default:p(()=>[l("div",Y,[l("div",Z,[o(d,{title:e.$t("subscription"),hint:e.$t("hint.subscription"),"no-tabs":""},null,8,["title","hint"])]),l("div",ee,[o(h,{flat:"",bordered:"",title:n.title},q({action:p(()=>[o(T,{dense:"",modelValue:s.interval,"onUpdate:modelValue":t[0]||(t[0]=a=>s.interval=a),"true-value":"year","false-value":"month",label:e.$t("yearly")},null,8,["modelValue","label"])]),default:p(()=>[(c(!0),u(V,null,U(e.plans,(a,f)=>(c(),u("div",{key:f,class:"col-xs-12 col-sm-6"},[o(M,N({flat:""},a,{interval:s.interval,"is-custom":a.is_custom,current:s.plan,"onUpdate:current":[t[1]||(t[1]=_=>s.plan=_),n.onSelectPlan]}),null,16,["interval","is-custom","current","onUpdate:current"])]))),128))]),_:2},[s.loading?{name:"skeleton",fn:p(()=>[o(C,{flat:"",bordered:""})]),key:"0"}:void 0]),1032,["title"])]),l("div",te,[l("div",ne,[o(g,{modelValue:s.paymentMethod,"onUpdate:modelValue":t[2]||(t[2]=a=>s.paymentMethod=a),"pay-mode":"",title:e.$t("payment")},null,8,["modelValue","title"]),n.processPayment?(c(),k(b,{key:0,mode:s.mode,interval:s.interval,"payment-method":s.paymentMethod,plan:e.selectedPlan(s.plan)},null,8,["mode","interval","payment-method","plan"])):w("",!0)])])])]),_:1})}var fe=F(X,[["render",se]]);export{fe as default};
