import{b as s,u as n,c}from"./index.46fdd451.js";var f=s(async({store:i,router:t})=>{const e=n(),a="Do you really want to leave? you have unsaved changes!";window.addEventListener("beforeunload",function(r){return e.isDirt?(r.returnValue=a,a):!0}),t.beforeEach((r,u,o)=>{e.isDirt?c.confirm(a,{title:"Discard unsaved changes?",ok:"Leave page",cancel:"Cancel",okColor:"negative"}).then(()=>{e.setIsDirt(!1),o()}).catch(()=>{o(!1)}):o()})});export{f as default};
