import{A as S}from"./ApplicationLogo-NaXoMRh0.js";import{s as B,x as E,m as v,y as L,o as l,f as p,b as e,p as c,j as $,z as k,a as r,w as a,n as u,A as D,c as _,u as m,i as y,d,t as x,g as M,F as N,Z as V}from"./app-CyrrLprq.js";import j from"./DeleteUserForm-BSgyIcxl.js";import z from"./UpdatePasswordForm-lLrwidmn.js";import A from"./UpdateProfileInformationForm-BoiuUk--.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";import"./TextInput-DqBMP9oU.js";import"./Modal-DX_abC3j.js";import"./PrimaryButton-CpdXtkQm.js";const P={class:"relative"},q={__name:"Dropdown",props:{align:{type:String,default:"right"},width:{type:String,default:"48"},contentClasses:{type:String,default:"py-1 bg-white"}},setup(n){const o=n,t=h=>{i.value&&h.key==="Escape"&&(i.value=!1)};B(()=>document.addEventListener("keydown",t)),E(()=>document.removeEventListener("keydown",t));const s=v(()=>({48:"w-48"})[o.width.toString()]),g=v(()=>o.align==="left"?"ltr:origin-top-left rtl:origin-top-right start-0":o.align==="right"?"ltr:origin-top-right rtl:origin-top-left end-0":"origin-top"),i=L(!1);return(h,f)=>(l(),p("div",P,[e("div",{onClick:f[0]||(f[0]=w=>i.value=!i.value)},[c(h.$slots,"trigger")]),$(e("div",{class:"fixed inset-0 z-40",onClick:f[1]||(f[1]=w=>i.value=!1)},null,512),[[k,i.value]]),r(D,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"opacity-0 scale-95","enter-to-class":"opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"opacity-100 scale-100","leave-to-class":"opacity-0 scale-95"},{default:a(()=>[$(e("div",{class:u(["absolute z-50 mt-2 rounded-md shadow-lg",[s.value,g.value]]),style:{display:"none"},onClick:f[2]||(f[2]=w=>i.value=!1)},[e("div",{class:u(["rounded-md ring-1 ring-black ring-opacity-5",n.contentClasses])},[c(h.$slots,"content")],2)],2),[[k,i.value]])]),_:3})]))}},C={__name:"DropdownLink",props:{href:{type:String,required:!0}},setup(n){return(o,t)=>(l(),_(m(y),{href:n.href,class:"block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"},{default:a(()=>[c(o.$slots,"default")]),_:3},8,["href"]))}},O={__name:"NavLink",props:{href:{type:String,required:!0},active:{type:Boolean}},setup(n){const o=n,t=v(()=>o.active?"inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out":"inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out");return(s,g)=>(l(),_(m(y),{href:n.href,class:u(t.value)},{default:a(()=>[c(s.$slots,"default")]),_:3},8,["href","class"]))}},b={__name:"ResponsiveNavLink",props:{href:{type:String,required:!0},active:{type:Boolean}},setup(n){const o=n,t=v(()=>o.active?"block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out":"block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out");return(s,g)=>(l(),_(m(y),{href:n.href,class:u(t.value)},{default:a(()=>[c(s.$slots,"default")]),_:3},8,["href","class"]))}},F={class:"min-h-screen bg-gray-100"},T={class:"border-b border-gray-100 bg-white"},R={class:"mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"},U={class:"flex h-16 justify-between"},Z={class:"flex"},G={class:"flex shrink-0 items-center"},H={class:"hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"},I={class:"hidden sm:ms-6 sm:flex sm:items-center"},J={class:"relative ms-3"},K={class:"inline-flex rounded-md"},Q={type:"button",class:"inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"},W={class:"-me-2 flex items-center sm:hidden"},X={class:"h-6 w-6",stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"},Y={class:"space-y-1 pb-3 pt-2"},ee={class:"border-t border-gray-200 pb-1 pt-4"},te={class:"px-4"},se={class:"text-base font-medium text-gray-800"},oe={class:"text-sm font-medium text-gray-500"},re={class:"mt-3 space-y-1"},ae={key:0,class:"bg-white shadow"},ne={class:"mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8"},ie={__name:"AuthenticatedLayout",setup(n){const o=L(!1);return(t,s)=>(l(),p("div",null,[e("div",F,[e("nav",T,[e("div",R,[e("div",U,[e("div",Z,[e("div",G,[r(m(y),{href:t.route("dashboard")},{default:a(()=>[r(S,{class:"block h-9 w-auto fill-current text-gray-800"})]),_:1},8,["href"])]),e("div",H,[r(O,{href:t.route("dashboard"),active:t.route().current("dashboard")},{default:a(()=>s[1]||(s[1]=[d(" Dashboard ")])),_:1},8,["href","active"])])]),e("div",I,[e("div",J,[r(q,{align:"right",width:"48"},{trigger:a(()=>[e("span",K,[e("button",Q,[d(x(t.$page.props.auth.user.name)+" ",1),s[2]||(s[2]=e("svg",{class:"-me-0.5 ms-2 h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor"},[e("path",{"fill-rule":"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z","clip-rule":"evenodd"})],-1))])])]),content:a(()=>[r(C,{href:t.route("profile.edit")},{default:a(()=>s[3]||(s[3]=[d(" Profile ")])),_:1},8,["href"]),r(C,{href:t.route("logout"),method:"post",as:"button"},{default:a(()=>s[4]||(s[4]=[d(" Log Out ")])),_:1},8,["href"])]),_:1})])]),e("div",W,[e("button",{onClick:s[0]||(s[0]=g=>o.value=!o.value),class:"inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"},[(l(),p("svg",X,[e("path",{class:u({hidden:o.value,"inline-flex":!o.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M4 6h16M4 12h16M4 18h16"},null,2),e("path",{class:u({hidden:!o.value,"inline-flex":o.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"},null,2)]))])])])]),e("div",{class:u([{block:o.value,hidden:!o.value},"sm:hidden"])},[e("div",Y,[r(b,{href:t.route("dashboard"),active:t.route().current("dashboard")},{default:a(()=>s[5]||(s[5]=[d(" Dashboard ")])),_:1},8,["href","active"])]),e("div",ee,[e("div",te,[e("div",se,x(t.$page.props.auth.user.name),1),e("div",oe,x(t.$page.props.auth.user.email),1)]),e("div",re,[r(b,{href:t.route("profile.edit")},{default:a(()=>s[6]||(s[6]=[d(" Profile ")])),_:1},8,["href"]),r(b,{href:t.route("logout"),method:"post",as:"button"},{default:a(()=>s[7]||(s[7]=[d(" Log Out ")])),_:1},8,["href"])])])],2)]),t.$slots.header?(l(),p("header",ae,[e("div",ne,[c(t.$slots,"header")])])):M("",!0),e("main",null,[c(t.$slots,"default")])])]))}},le={class:"py-12"},de={class:"mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8"},ue={class:"bg-white p-4 shadow sm:rounded-lg sm:p-8"},ce={class:"bg-white p-4 shadow sm:rounded-lg sm:p-8"},fe={class:"bg-white p-4 shadow sm:rounded-lg sm:p-8"},we={__name:"Edit",props:{mustVerifyEmail:{type:Boolean},status:{type:String}},setup(n){return(o,t)=>(l(),p(N,null,[r(m(V),{title:"Profile"}),r(ie,null,{header:a(()=>t[0]||(t[0]=[e("h2",{class:"text-xl font-semibold leading-tight text-gray-800"}," Profile ",-1)])),default:a(()=>[e("div",le,[e("div",de,[e("div",ue,[r(A,{"must-verify-email":n.mustVerifyEmail,status:n.status,class:"max-w-xl"},null,8,["must-verify-email","status"])]),e("div",ce,[r(z,{class:"max-w-xl"})]),e("div",fe,[r(j,{class:"max-w-xl"})])])])]),_:1})],64))}};export{we as default};
