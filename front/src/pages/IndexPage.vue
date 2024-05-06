<template>
  <q-page class="q-pa-md">
    <div class="row">
      <div class="col-12 col-md-4">
        <CardComponent title="Prestamos" icon="o_account_balance" to="/prestamos" color="indigo" :amount="prestamos" />
      </div>
      <div class="col-12 col-md-4">
        <CardComponent title="Clientes" icon="o_people" to="/clients" color="green" :amount="clientes" />
      </div>
      <div class="col-12 col-md-4">
        <CardComponent title="Deudores" icon="o_people" to="/debtors" color="red" :amount="deudores" />
      </div>
      <div class="col-12 col-md-4">
        <CardComponent title="Pagos" icon="o_payment" to="/payments" color="blue" :amount="pagos" />
      </div>
      <div class="col-12 col-md-4">
        <CardComponent title="Prestamos Finalizados" icon="o_done_all" to="/finished-loans" color="purple" :amount="prestamosFinalizados" />
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import CardComponent from "components/CardComponent.vue";

export default defineComponent({
  name: 'IndexPage',
  components: {CardComponent},
  data() {
    return {
      prestamos: 0,
      clientes: 0,
      deudores: 0,
      pagos: 0,
      prestamosFinalizados: 0
    }
  },
  mounted() {
    this.getDashboard();
  },
  methods: {
    getDashboard() {
      this.$axios.get('dashboard')
        .then(response => {
          this.prestamos = response.data.prestamos;
          this.clientes = response.data.clientes;
          this.deudores = response.data.deudores;
          this.pagos = response.data.pagos;
          this.prestamosFinalizados = response.data.prestamosFinalizados;
        })
    }
  }
})
</script>
