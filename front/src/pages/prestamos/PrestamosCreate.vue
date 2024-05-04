<template>
  <q-page class="bg-grey-3 q-pa-xs">
    <q-card>
      <q-card-section>
        <q-form @submit.prevent="submit">
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
              <q-input v-model="ci" outlined dense label="C.I." @update:modelValue="clientSearch" debounce="500"
                        :rules="[val => !!val || 'La cedula de identidad es requerida']" />
            </div>
            <div class="col-6 col-md-3 q-pa-xs">
              <q-input v-model="name" outlined dense label="Nombre"
                        :rules="[val => !!val || 'El nombre es requerido']" />
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-select v-model="prestamo.currency" outlined dense label="Moneda" :options="['DOLARES', 'BOLIVIANOS']" />
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-input v-model="dolar" outlined dense label="Dolar" type="number" step="0.01" />
            </div>
            <div class="col-12 col-md-12 q-pa-xs">
              <q-input type="textarea" v-model="prestamo.description" outlined dense label="Descripcion" />
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-input v-model="prestamo.amount" outlined dense label="Monto" type="number"
                       :rules="[val => !!val || 'El monto es requerido', val => val > 0 || 'El monto debe ser mayor a 0']" />
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-input v-model="prestamo.interest_rate" outlined dense label="Interes %" type="number" />
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-input v-model="prestamo.custodial_fee" outlined dense label="Custodia %" type="number" />
            </div>
            <div class="col-6 col-md-2 q-pa-xs">
              <q-input v-model="prestamo.payments" outlined dense label="Pagos" type="number"
                        :rules="[val => !!val || 'El numero de pagos es requerido', val => val > 0 || 'El numero de pagos debe ser mayor a 0']" />
            </div>
            <div class="col-6 col-md-2 flex flex-center">
              <q-btn color="primary" label="Calcular" type="submit" :loading="loading" no-caps size="12px" class="text-bold" icon="o_calculate"
                @click="calculate" />
            </div>
            <div class="col-6 col-md-2 flex flex-center">
              <q-btn color="green" label="Guardar" type="submit" :loading="loading" no-caps size="12px" class="text-bold" icon="o_save"
                     @click="loan"
              />
            </div>
            <div class="col-12">
              <q-markup-table dense wrap-cells flat bordered :separator="'cell'">
                <thead>
                  <tr>
                    <th><div class="text-bold">Nro</div></th>
                    <th><div class="text-bold">Saldo Inicial</div></th>
                    <th><div class="text-bold">Interes</div></th>
                    <th><div class="text-bold">Custodia</div></th>
                    <th><div class="text-bold">Capital</div></th>
                    <th><div class="text-bold">Total</div></th>
                    <th><div class="text-bold">Saldo Final</div></th>
                    <th><div class="text-bold">Total A Pagar</div></th>
                    <th><div class="text-bold">Fecha</div></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(cuota, index) in cuotas" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ cuota.amount }}</td>
                    <td>{{ cuota.interest }}</td>
                    <td>{{ cuota.custodial_fee }}</td>
                    <td>{{ cuota.capital }}</td>
                    <td>{{ ( parseFloat(cuota.interest) + parseFloat(cuota.custodial_fee) + parseFloat(cuota.capital) ).toFixed(2) }}</td>
                    <td>{{ cuota.saldo }}</td>
                    <td>{{ cuota.total_bs }}</td>
                    <td>{{ formatDateDmy(cuota.date) }}</td>
                  </tr>
                </tbody>
              </q-markup-table>
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
        amount: '25',
        payments: '3',
        interest_rate: 5,
        custodial_fee: 1,
        currency: 'DOLARES'
      },
      cuotas: [],
      ci: '',
      name: '',
      dolar: 6.96,
    }
  },
  created() {
    this.getId()
  },
  methods: {
    formatDateDmy (date) {
      const meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic']
      const dia = date.split('-')[2]
      const mes = meses[parseInt(date.split('-')[1]) - 1]
      const anio = date.split('-')[0]
      return `${dia}-${mes}-${anio}`
    },
    loan () {
      if (this.cuotas.length === 0) {
        this.$alert.error('Primero debe calcular las cuotas')
        return false
      }
      if (this.ci === '') {
        this.$alert.error('Primero debe buscar el cliente')
        return false
      }
      if (this.name === '') {
        this.$alert.error('Primero debe buscar el cliente')
        return false
      }
      this.loading = true
      this.$axios.post('loans', this.prestamo)
        .then(response => {
          this.$alert.success(response.data.message)
          this.$router.push('/prestamos')
        })
        .catch(error => {
          this.$alert.error(error.response.data.message)
        }).finally(() => {
          this.loading = false
        })
    },
    calculate () {
      let amount = this.prestamo.amount
      const payments = this.prestamo.payments
      const interest_rate = this.prestamo.interest_rate
      const custodial_fee = this.prestamo.custodial_fee
      const currency = this.prestamo.currency
      this.cuotas = []
      let total_bs = 0
      let total = 0
      let interest = 0
      let custodial = 0
      let saldo = 0
      const capital = amount / payments
      let date = moment(this.prestamo.date).format('YYYY-MM-DD')

      for (let i = 0; i < payments; i++) {

        interest = amount * interest_rate / 100
        custodial = amount * custodial_fee / 100
        total = interest + custodial + capital
        if (currency === 'DOLARES') {
          total_bs = total * this.dolar
        }else{
          total_bs = total
        }
        saldo = Math.round((amount - capital) * 100) / 100
        date = moment(date).add(1, 'months').format('YYYY-MM-DD')
        this.cuotas.push({
          amount: parseFloat(amount).toFixed(2),
          interest: interest.toFixed(2),
          custodial_fee: custodial.toFixed(2),
          capital: capital.toFixed(2),
          saldo: saldo.toFixed(2),
          total_bs: total_bs.toFixed(2),
          date: date
        })
        amount -= capital
      }
    },
    clientSearch () {
      this.loading = true
      this.$axios.get('clients/search', { params: { ci: this.ci } })
        .then(response => {
          if (response.data !== '') {
            this.name = response.data[0].name
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
    }
  }
}
</script>
