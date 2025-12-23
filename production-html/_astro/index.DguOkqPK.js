/*
 * @version: 3.2.3
 * @author: Preline Labs Ltd.
 * @license: Licensed under MIT and Preline UI Fair Use License (https://preline.co/docs/license.html)
 * Copyright 2024 Preline Labs Ltd.
 */const a=s=>s==="true",l=(s,t,e="")=>(window.getComputedStyle(s).getPropertyValue(t)||e).replace(" ",""),c=(s,t,e="")=>{let n="";return s.classList.forEach(r=>{r.includes(t)&&(n=r)}),n.match(/:(.*)]/)?n.match(/:(.*)]/)[1]:e},u=(s,t)=>{const e=s.children;for(let n=0;n<e.length;n++)if(e[n]===t)return!0;return!1},h=()=>/iPad|iPhone|iPod/.test(navigator.platform)?!0:navigator.maxTouchPoints&&navigator.maxTouchPoints>2&&/MacIntel/.test(navigator.platform),d=()=>navigator.maxTouchPoints&&navigator.maxTouchPoints>2&&/MacIntel/.test(navigator.platform),p=(s,t,e=null)=>{const n=new CustomEvent(s,{detail:{payload:e},bubbles:!0,cancelable:!0,composed:!1});t.dispatchEvent(n)},g=(s,t)=>{const e=()=>{t(),s.removeEventListener("transitionend",e,!0)},n=window.getComputedStyle(s),r=n.getPropertyValue("transition-duration");n.getPropertyValue("transition-property")!=="none"&&parseFloat(r)>0?s.addEventListener("transitionend",e,!0):t()},y={historyIndex:-1,addHistory(s){this.historyIndex=s},existsInHistory(s){return s>this.historyIndex},clearHistory(){this.historyIndex=-1}};/*
 * HSBasePlugin
 * @version: 3.2.3
 * @author: Preline Labs Ltd.
 * @license: Licensed under MIT and Preline UI Fair Use License (https://preline.co/docs/license.html)
 * Copyright 2024 Preline Labs Ltd.
 */class v{constructor(t,e,n){this.el=t,this.options=e,this.events=n,this.el=t,this.options=e,this.events={}}createCollection(t,e){t.push({id:e?.el?.id||t.length+1,element:e})}fireEvent(t,e=null){if(this.events.hasOwnProperty(t))return this.events[t](e)}on(t,e){this.events[t]=e}}export{v as H,g as a,h as b,d as c,p as d,c as e,l as g,u as i,y as m,a as s};
