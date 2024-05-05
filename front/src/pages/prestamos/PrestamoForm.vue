<template>
  <q-card>
    <q-card-section>
      <q-form @submit.prevent="submit">
        <div class="row">
          <div class="col-6 col-md-1 q-pa-xs">
            <div class="text-h6 text-red flex flex-center" style="line-height: 1;">
              <b>Id:</b> {{ prestamo.id }}
            </div>
          </div>
          <div class="col-6 col-md-3 q-pa-xs">
            <q-input v-model="prestamo.date" outlined dense label="Fecha" type="date" />
          </div>
          <div class="col-6 col-md-2 q-pa-xs">
            <q-input v-model="ci" outlined dense label="C.I." @update:modelValue="clientSearch" debounce="500"
                     :rules="[val => !!val || 'La cedula de identidad es requerida']" />
          </div>
          <div class="col-6 col-md-6 q-pa-xs">
            <q-input v-model="name" outlined dense label="Nombre"
                     :rules="[val => !!val || 'El nombre es requerido']" />
          </div>
          <div class="col-6 col-md-3 q-pa-xs">
            <q-select v-model="prestamo.currency" outlined dense label="Moneda" :options="['DOLARES', 'BOLIVIANOS']" />
          </div>
          <div class="col-6 col-md-3 q-pa-xs">
            <q-input v-model="prestamo.dolar" outlined dense label="Dolar" type="number" step="0.01" />
          </div>
          <div class="col-6 col-md-3 q-pa-xs flex flex-center">
              <q-chip v-if="prestamo.status === 'PENDIENTE'" color="orange" text-color="white" label="Pendiente" />
              <q-chip v-if="prestamo.status === 'CANCELADO'" color="positive" text-color="white" label="Cancelado" />
              <q-chip v-if="prestamo.status === 'ANULADO'" color="negative" text-color="white" label="Anulado" />
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
                   @click="calculate" v-if="option === 'create'"
            />
          </div>
          <div class="col-6 col-md-2 flex flex-center">
            <q-btn color="green" label="Guardar" type="submit" :loading="loading" no-caps size="12px" class="text-bold" icon="o_save"
                   @click="loan" v-if="option === 'create'"
            />
          </div>
          <div class="col-12">
            <q-markup-table dense wrap-cells flat bordered :separator="'cell'">
              <thead>
              <tr>
                <th><div class="text-bold">#</div></th>
                <th><div class="text-bold">Opcion</div></th>
                <th><div class="text-bold">#Estado</div></th>
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
                <td class="text-right">{{ index + 1 }}</td>
                <td class="text-right">
                  <q-btn color="positive" label="Pagar" dense no-caps size="12px" class="text-bold" icon="o_payment"
                         @click="pay(cuota)" v-if="option === 'show' && cuota.isLast"
                  />
                </td>
                <td class="text-right">
                  <q-chip v-if="cuota.status === 'PENDIENTE'" dense color="orange" text-color="white" label="Pendiente" />
                  <q-chip v-if="cuota.status === 'CANCELADO'" dense color="positive" text-color="white" label="Cancelado" />
                  <q-chip v-if="cuota.status === 'ANULADO'" dense color="negative" text-color="white" label="Anulado" />
                </td>
                <td class="text-right">{{ cuota.amount }}</td>
                <td class="text-right">{{ cuota.interest }}</td>
                <td class="text-right">{{ cuota.custodial_fee }}</td>
                <td class="text-right">{{ cuota.capital }}</td>
                <td class="text-right">{{ ( parseFloat(cuota.interest) + parseFloat(cuota.custodial_fee) + parseFloat(cuota.capital) ).toFixed(2) }}</td>
                <td class="text-right">{{ cuota.saldo }}</td>
                <td class="text-right">{{ cuota.total_bs }}</td>
                <td>{{ formatDateDmy(cuota.date) }}</td>
              </tr>
              </tbody>
            </q-markup-table>
          </div>
        </div>
      </q-form>
    </q-card-section>
  </q-card>
</template>
<script>
import moment from "moment/moment";

export default {
  name: 'PrestamoForm',
  props: {
    prestamoData: {
      type: Object,
      required: true
    },
    cuotasData: {
      type: Array,
      required: true
    },
    ciData: {
      type: String,
      required: true
    },
    nameData: {
      type: String,
      required: true
    },
    option: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      loading: false,
      prestamo: this.prestamoData,
      cuotas: this.cuotasData,
      ci: this.ciData,
      name: this.nameData,
    }
  },
  created() {
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
      this.$axios.post('loans', {
        ...this.prestamo,
        cuotas: this.cuotas,
        ci: this.ci,
        name: this.name
      })
        .then(response => {
          this.$alert.success('Prestamo guardado con exito')
          this.$router.push('/prestamos/' + response.data.id)
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
          total_bs = total * this.prestamo.dolar
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
    submit () {
    }
  }
}
</script>
