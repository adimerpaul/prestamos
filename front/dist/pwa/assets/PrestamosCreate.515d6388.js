import{Q as s}from"./QPage.b1885bf6.js";import{h as m}from"./moment.40bc58bf.js";import{P as n}from"./PrestamoForm.83800ed3.js";import{_ as i,P as c,Q as e,R as o,S as p,aD as l}from"./index.2980ed33.js";import"./QForm.850f9777.js";import"./QMarkupTable.6951bb2c.js";import"./QItem.fe6e203b.js";import"./QMenu.7e92266f.js";import"./position-engine.23904abf.js";import"./format.2cae61da.js";import"./moment.19647928.js";const d={name:"PrestamosCreate",components:{PrestamoForm:n},data(){return{loading:!1,prestamo:{id:null,client_id:"",date:m().format("YYYY-MM-DD"),amount:"",payments:"",interest_rate:5,custodial_fee:1,description:"",currency:"DOLARES",dolar:6.96},cuotas:[],ci:"",name:""}},created(){this.getId()},methods:{getId(){this.loading=!0,this.$axios.get("loans/nextId").then(a=>{this.prestamo.id=a.data}).catch(a=>{console.log(a)}).finally(()=>{this.loading=!1})}}};function u(a,f,_,h,t,D){const r=c("PrestamoForm");return e(),o(s,{class:"bg-grey-3 q-pa-xs"},{default:p(()=>[t.prestamo.id?(e(),o(r,{key:0,prestamoData:t.prestamo,cuotasData:t.cuotas,ciData:t.ci,nameData:t.name,option:"create"},null,8,["prestamoData","cuotasData","ciData","nameData"])):l("",!0)]),_:1})}var B=i(d,[["render",u]]);export{B as default};