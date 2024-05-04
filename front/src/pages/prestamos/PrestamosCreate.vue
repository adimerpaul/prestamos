<template>
  <q-page class="bg-grey-3 q-pa-xs">
    <q-card>
      <q-card-section>
        <q-form @submit="submit">
          <div class="row">
            <div class="col-6 col-md-1 q-pa-xs">
              <div class="text-h6 text-red flex flex-center" style="line-height: 1;">
                <b>Id:</b> {{ prestamo.id }}
              </div>
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-input v-model="prestamo.date" outlined dense label="Fecha" type="date" />
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-input v-model="ci" outlined dense label="C.I." @update:modelValue="clientSearch" debounce="300" />
            </div>
            <div class="col-6 col-md-4 q-pa-xs">
              <q-input v-model="name" outlined dense label="Nombre" />
            </div>
            <div class="col-6 col-md-3 q-pa-xs">
              <q-select v-model="prestamo.currency" outlined dense label="Moneda" :options="['DOLARES', 'BOLIVIANOS']" />
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-input v-model="prestamo.amount" outlined dense label="Monto" type="number" />
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-input v-model="prestamo.interest_rate" outlined dense label="Interes %" type="number" />
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-input v-model="prestamo.custodial_fee" outlined dense label="Custodia %" type="number" />
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-input v-model="prestamo.payments" outlined dense label="Pagos" type="number" />
            </div>
            <div class="col-6 col-md-2 flex flex-center">
              <q-btn color="primary" label="Calcular" type="submit" :loading="loading" no-caps size="12px" class="text-bold" icon="o_calculate" />
            </div>
            <div class="col-6 col-md-2 flex flex-center">
              <q-btn color="green" label="Guardar" type="submit" :loading="loading" no-caps size="12px" class="text-bold" icon="o_save" />
            </div>
            <div class="col-12">

            </div>
          </div>
        </q-form>
      </q-card-section>
    </q-card>
<!--    <pre>{{client}}</pre>-->
  </q-page>
</template>
<script>
import moment from "moment";

export default {
  name: 'PrestamosCreate',
  data () {
    return {
      loading: false,
      prestamo: {
        id: null,
        client_id: '',
        date: moment().format('YYYY-MM-DD'),
        amount: '',
        payments: '',
        interest_rate: 5,
        custodial_fee: 1,
        currency: 'DOLARES'
      },
      ci: '',
      name: ''
    }
  },
  created() {
    this.getId()
  },
  methods: {
    clientSearch () {
      this.loading = true
      this.$axios.get('clients/search', { params: { ci: this.client.ci } })
        .then(response => {
          console.log(response.data)
          if (response.data !== '') {
            console.log(response.data)
            this.name = response.data.name
          }
        })
        .catch(error => {
          this.$alert.error(error.response.data.message)
        }).finally(() => {
          this.loading = false
        })
    },
    getId () {
      this.loading = true
      this.$axios.get('loans/nextId')
        .then(response => {
          this.prestamo.id = response.data
        })
        .catch(error => {
          console.log(error)
        }).finally(() => {
          this.loading = false
        })
    },
    submit () {
      // this.$store.dispatch('prestamos/create', this.prestamo)
    }
  }
}
</script>
