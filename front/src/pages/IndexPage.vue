<template>
  <q-page class="q-pa-md">
    <div class="row">
      <div class="col-12 col-md-4">
        <CardComponent title="Prestamos" icon="o_account_balance" to="/prestamos" color="indigo" :amount="2000" />
      </div>
      <div class="col-12 col-md-4">
        <CardComponent title="Clientes" icon="o_people" to="/clients" color="green" :amount="100" />
      </div>
      <div class="col-12 col-md-4">
        <CardComponent title="Deudores" icon="o_people" to="/debtors" color="red" :amount="50" />
      </div>
      <div class="col-12 col-md-4">
        <CardComponent title="Pagos" icon="o_payment" to="/payments" color="blue" :amount="500" />
      </div>
      <div class="col-12 col-md-4">
        <CardComponent title="Prestamos Finalizados" icon="o_done_all" to="/finished-loans" color="purple" :amount="100" />
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import CardComponent from "components/CardComponent.vue";

export default defineComponent({
  name: 'IndexPage',
  components: {CardComponent}
})
</script>
