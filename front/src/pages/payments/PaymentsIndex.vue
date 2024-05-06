<template>
  <q-page class="bg-grey-3 q-pa-xs">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 col-md-2">
            <q-input v-model="fechaInit" label="Fecha Inicio" dense outlined type="date" />
          </div>
          <div class="col-6 col-md-2">
            <q-input v-model="fechaFin" label="Fecha Fin" dense outlined type="date" />
          </div>
          <div class="col-6 col-md-3">
            <q-input v-model="filter" label="Buscar" dense outlined />
          </div>
          <div class="col-6 col-md-3 flex flex-center">
            <q-btn color="primary" label="Buscar" @click="debtorsGet" no-caps icon="search" :loading="loading" />
          </div>
          <div class="col-6 col-md-2">
          </div>
          <div class="col-4">
            <CardShowComponent :monto="total" color="green" title="Deuda Total" icono="o_trending_up" />
          </div>
          <div class="col-12">
            <q-markup-table dense :separator="'cell'" wrap-cells>
              <thead>
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Deuda</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Id</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(debtor, index) in debtors" :key="debtor.id">
                <td>{{index + 1}}</td>
                <td>{{debtor.loan?.client?.name}}</td>
                <td>{{debtor.total_bs}}</td>
                <td>{{debtor.date}}</td>
                <td>
                  <q-chip class="q-ma-none" size="10px" label="Pendiente" text-color="white" dense color="orange" v-if="debtor.status == 'PENDIENTE'"/>
                  <q-chip class="q-ma-none" size="10px" label="Pagado" text-color="white" dense color="green" v-if="debtor.status == 'PAGADO'"/>
                  <q-chip class="q-ma-none" size="10px" label="Anulado" text-color="white" dense color="red" v-if="debtor.status == 'ANULADO'"/>
                </td>
                <td>{{debtor.loan?.id}}</td>
              </tr>
              </tbody>
            </q-markup-table>
<!--            <pre>{{debtors}}</pre>-->
          </div>
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>
<script>
import moment from "moment/moment";
import CardShowComponent from "components/CardShowComponent.vue";

export default {
  name: 'PaymentsIndex',
  components: {CardShowComponent},
  data () {
    return {
      loading: false,
      fechaInit: moment().startOf('month').format('YYYY-MM-DD'),
      fechaFin: moment().endOf('month').format('YYYY-MM-DD'),
      filter: '',
      debtors: []
    }
  },
  mounted() {
    this.debtorsGet()
  },
  methods: {
    debtorsGet() {
      this.loading = true
      this.$axios.get('payments', {
        params: {
          fechaInit: this.fechaInit,
          fechaFin: this.fechaFin,
          filter: this.filter
        }
      })
        .then(response => {
          this.debtors = response.data
        })
        .catch(error => {
          console.log(error)
        }).finally(() => {
        this.loading = false
      })
    }
  },
  computed: {
    total() {
      let total = 0
      this.debtors.forEach(debtor => {
        total += parseFloat(debtor.total_bs)
      })
      return total
    }
  }
};
</script>
