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
<!--            :readonly="option === 'show'"-->
            <q-input v-model="prestamo.date" outlined dense label="Fecha" type="date"
                      @update:modelValue="changeDate" />
          </div>
          <div class="col-6 col-md-2 q-pa-xs">
            <q-input v-model="ci" outlined dense label="C.I." @update:modelValue="clientSearch" debounce="500"
                     :rules="[val => !!val || 'La cedula de identidad es requerida']" :readonly="option === 'show'" />
          </div>
          <div class="col-6 col-md-6 q-pa-xs">
            <q-input v-model="name" outlined dense label="Nombre"
                     :rules="[val => !!val || 'El nombre es requerido']" :readonly="option === 'show'" />
          </div>
          <div class="col-6 col-md-3 q-pa-xs">
            <q-select v-model="prestamo.currency" outlined dense label="Moneda" :options="['DOLARES', 'BOLIVIANOS']"
                      :readonly="option === 'show'" />
          </div>
          <div class="col-6 col-md-2 q-pa-xs">
            <q-input v-model="prestamo.dolar" outlined dense label="Dolar" type="number" step="0.01"
                      :rules="[val => !!val || 'El dolar es requerido', val => val > 0 || 'El dolar debe ser mayor a 0']" :readonly="option === 'show'" />
          </div>
          <div class="col-6 col-md-2 q-pa-xs text-center">
              <q-chip v-if="prestamo.status === 'PENDIENTE'" color="orange" text-color="white" label="Pendiente" />
              <q-chip v-if="prestamo.status === 'PAGADO'" color="positive" text-color="white" label="Pagado" />
              <q-chip v-if="prestamo.status === 'ANULADO'" color="negative" text-color="white" label="Anulado" />
          </div>
          <div class="col-6 col-md-2 q-pa-xs">
            <q-input v-model="prestamo.interest_rate" outlined dense label="Interes %" type="number"
                     :rules="[val => !!val || 'El interes es requerido', val => val > 0 || 'El interes debe ser mayor a 0']" :readonly="option === 'show'" />
          </div>
          <div class="col-6 col-md-2 q-pa-xs">
            <q-input v-model="prestamo.custodial_fee" outlined dense label="Custodia %" type="number"
                     :rules="[val => !!val || 'La custodia es requerida', val => val > 0 || 'La custodia debe ser mayor a 0']" :readonly="option === 'show'" />
          </div>
          <div class="col-12 col-md-12 q-pa-xs">
            <q-input type="textarea" v-model="prestamo.description" outlined dense label="Descripcion" @update:modelValue="descriptionUpdate" :debounce="1000" />
          </div>
          <div class="col-6 col-md-2 q-pa-xs">
            <q-input v-model="prestamo.amount" outlined dense label="Monto" type="number"
                     :rules="[val => !!val || 'El monto es requerido', val => val > 0 || 'El monto debe ser mayor a 0']"
                      :readonly="option === 'show'" bg-color="orange" label-color="white" />
          </div>
          <div class="col-6 col-md-2 q-pa-xs">
            <q-input v-model="prestamo.saldo" outlined dense label="Saldo" type="number"
                     :readonly="true" bg-color="red-5" label-color="white" />
          </div>
          <div class="col-6 col-md-2 q-pa-xs">
            <q-input v-model="prestamo.amortization" outlined dense label="Amortizacion" type="number"
                     :readonly="true" bg-color="green-5" label-color="white" />
          </div>

          <div class="col-6 col-md-2 q-pa-xs">
            <q-input v-model="prestamo.payments" outlined dense label="Pagos" type="number"
                     :rules="[val => !!val || 'El numero de pagos es requerido', val => val > 0 || 'El numero de pagos debe ser mayor a 0']"
                      :readonly="option === 'show'" />
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
            <q-btn color="indigo" label="Opciones" :loading="loading" no-caps size="12px" class="text-bold" icon="o_settings"
                   v-if="option === 'show'">
              <q-menu>
                <q-list>
                  <q-item clickable v-ripple @click="printCompromiso">
                    <q-item-section avatar>
                      <q-icon name="o_print" />
                    </q-item-section>
                    <q-item-section>Compromiso de pago</q-item-section>
                  </q-item>
                  <q-item clickable v-ripple @click="printPlan">
                    <q-item-section avatar>
                      <q-icon name="o_print" />
                    </q-item-section>
                    <q-item-section>Plan de pago</q-item-section>
                  </q-item>
                  <q-item clickable v-ripple @click="anularPrestamo" v-if="prestamo.status === 'PENDIENTE'">
                    <q-item-section avatar>
                      <q-icon name="o_block" />
                    </q-item-section>
                    <q-item-section>Anular prestamo</q-item-section>
                  </q-item>
                </q-list>
              </q-menu>
            </q-btn>
          </div>
          <div class="col-12">
            <q-markup-table dense wrap-cells flat bordered :separator="'cell'" class="bg-grey-3">
              <thead class="bg-primary text-white">
              <tr>
                <th><div class="text-bold">#</div></th>
                <th><div class="text-bold">Opcion</div></th>
                <th><div class="text-bold">Estado</div></th>
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
                <td class="text-subtitle2 text-right">{{ index + 1 }}</td>
                <td class="text-subtitle2 text-right">
                  <q-btn color="red" label="Anular" dense no-caps size="12px" class="text-bold" icon="o_block"
                         @click="payAnular(cuota)" v-if="option === 'show' && cuota.status === 'PAGADO'"
                         :loading="loading"></q-btn>
                  <q-btn color="positive" label="Pagar" dense no-caps size="12px" class="text-bold" icon="o_payment"
                         @click="payDialogShow(cuota)" v-if="option === 'show' && cuota.isLast && prestamo.status === 'PENDIENTE'"
                         :loading="loading"
                  />
                </td>
                <td class="text-subtitle2 text-right">
                  <q-chip v-if="cuota.status === 'PENDIENTE'" dense color="orange" text-color="white" label="Pendiente" />
                  <q-btn v-if="cuota.status === 'PAGADO'" dense flat color="indigo" round size="12px" class="text-bold"
                         icon="o_print" @click="printPago(cuota)" />
                  <q-chip v-if="cuota.status === 'PAGADO'" dense color="positive" text-color="white" label="Pagado" />
                  <q-chip v-if="cuota.status === 'ANULADO'" dense color="negative" text-color="white" label="Anulado" />
                </td>
                <td class="text-subtitle2 text-right">{{ cuota.amount }} <b class="text-red" v-if="cuota.status=='PAGADO'">{{ cuota.days}}d</b></td>
                <td class="text-subtitle2 text-right">{{ cuota.interest }} <b class="text-red" v-if="cuota.status=='PAGADO'">{{ cuota.interest_paid}}</b></td>
                <td class="text-subtitle2 text-right">{{ cuota.custodial_fee }} <b class="text-red" v-if="cuota.status=='PAGADO'">{{ cuota.custodial_fee_paid}}</b></td>
                <td class="text-subtitle2 text-right text-bold">{{ cuota.capital }} <b class="text-red" v-if="cuota.status=='PAGADO'">{{ cuota.capital_paid}}</b></td>
                <td class="text-subtitle2 text-right">{{ ( parseFloat(cuota.interest) + parseFloat(cuota.custodial_fee) + parseFloat(cuota.capital) ).toFixed(2) }} <b class="text-red" v-if="cuota.status=='PAGADO'">{{ cuota.total_bs_paid}}</b></td>
                <td class="text-subtitle2 text-right">{{ cuota.saldo }}</td>
                <td class="text-subtitle2 text-right">{{ cuota.total_bs }}
                  <b class="text-red" v-if="cuota.status=='PAGADO'">{{ cuota.total_bs_paid }}</b>
                </td>
                <td class="text-subtitle2" @click="changeDatePay(cuota)">
                  {{ formatDateDmy(cuota.date) }}
                  <br>
                  <b class="text-red" v-if="cuota.status=='PAGADO'">{{ formatDateDmy(cuota.date_paid) }}</b>
                </td>
              </tr>
              </tbody>
            </q-markup-table>
<!--            <pre>{{ cuotas }}</pre>-->
          </div>
        </div>
      </q-form>
    </q-card-section>
    <q-dialog v-model="payDialog" persistent>
      <q-card style="width: 750px;max-width: 100vw;">
        <q-card-section>
          <q-form @submit.prevent="submit">
            <div class="row">
              <div class="col-12 col-md-12 q-pa-xs">
                <div class="text-h6 text-red flex flex-center" style="line-height: 1;">
                  <b>Pagar cuota</b>
                </div>
              </div>
              <div class="col-12 col-md-2 q-pa-xs">
                <q-input v-model="cuota.capital" outlined dense label="Capital" type="number"/>
              </div>
              <div class="col-12 col-md-2 q-pa-xs">
                <q-input v-model="days" outlined dense label="Dias de atraso" type="number"/>
              </div>
              <div class="col-12 col-md-2 q-pa-xs">
                <div class="text-red flex flex-center" style="line-height: 1;">
                  <b>Interes</b>
                </div>
                {{interest}}
              </div>
              <div class="col-12 col-md-2 q-pa-xs">
                <div class="text-red flex flex-center" style="line-height: 1;">
                  <b>Custodia</b>
                </div>
                {{ custodial }}
              </div>
              <div class="col-12 col-md-2 q-pa-xs">
                <div class="text-red flex flex-center" style="line-height: 1;">
                  <b>Total</b>
                </div>
                <div class="text-bold">
                  {{ totalPay }}
                </div>
              </div>
              <div class="col-12 col-md-2 q-pa-xs">
                <div class="text-red flex flex-center" style="line-height: 1;">
                  <b>Total Bs</b>
                </div>
                {{totalBs}}
              </div>
            </div>
          </q-form>
<!--          <pre>{{cuota}}</pre>-->
        </q-card-section>
        <q-card-actions align="right">
          <q-btn color="primary" label="Pagar" type="submit" :loading="loading" no-caps size="12px" class="text-bold" icon="o_payment"
                 @click="pay" />
          <q-btn color="negative" label="Cancelar" type="submit" :loading="loading" no-caps size="12px" class="text-bold" icon="o_block"
                 @click="payDialog = false" />
        </q-card-actions>
      </q-card>
    </q-dialog>
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
      payDialog: false,
      cuota: {},
      days: 0,
      interDay: 0
    }
  },
  created() {
  },
  methods: {
    changeDatePay (cuota) {
      this.$q.dialog({
        title: 'Cambiar fecha de pago',
        message: 'Seleccione la nueva fecha de pago',
        prompt: {
          model: cuota.date,
          type: 'date'
        },
        cancel: true,
        persistent: true
      }).onOk(data => {
        this.$axios.put('quotaDateUpdate/' + cuota.id, { date: data })
          .then(response => {
            // this.$alert.success('Fecha actualizada con exito')
            this.cuotas = response.data.quotas
          })
      })
    },
    changeDate (value) {
      if (this.option === 'show') {
        this.$axios.put('loanDateUpdate/' + this.prestamo.id, { date: value })
          .then(response => {
            // this.$alert.success('Fecha actualizada con exito')
          })
      }
    },
    printPago (cuota) {
      const url = import.meta.env.VITE_API_BACK + 'printPago/' + cuota.id
      window.open(url, '_blank')
    },
    payAnular (cuota) {
      this.loading = true
      this.$axios.put('quotaAnular/' + cuota.id)
        .then(response => {
          this.$alert.success('Cuota anulada con exito')
          this.cuotas = response.data.quotas
          this.prestamo.status = response.data.status
          this.prestamo.saldo = response.data.saldo
          this.prestamo.amortization = response.data.amortization
        })
        .catch(error => {
          this.$alert.error(error.response.data.message)
        }).finally(() => {
        this.loading = false
      })
    },
    payDialogShow (cuota) {
      this.loading = true
      this.$axios.get('daysCuota/' + this.prestamo.id).then(response => {
        this.days = response.data.days
        this.interDay = response.data.interDay
        this.payDialog = true
        this.cuota = {...cuota}
      }).finally(() => {
        this.loading = false
      })
    },
    pay () {
      this.loading = true
      this.$axios.put('quotaPay/' + this.cuota.id,{
        capital: this.cuota.capital,
        days: this.days,
        interest: this.interest,
        custodial: this.custodial,
        total: this.totalPay,
        total_bs: this.totalBs
      })
        .then(response => {
          this.$alert.success('Cuota pagada con exito')
          this.cuotas = response.data.quotas
          this.prestamo.status = response.data.status
          this.prestamo.saldo = response.data.saldo
          this.prestamo.amortization = response.data.amortization
          this.payDialog = false
        })
        .catch(error => {
          this.$alert.error(error.response.data.message)
        }).finally(() => {
        this.loading = false
      })
    },
    descriptionUpdate (value) {
      if (this.option === 'show') {
        this.$axios.put('loanDescriptionUpdate/' + this.prestamo.id, { description: value })
          .then(response => {
            // this.$alert.success('Descripcion actualizada con exito')
          })
      }
    },
    printCompromiso () {
      const url = import.meta.env.VITE_API_BACK + 'compromiso/' + this.prestamo.id
      window.open(url, '_blank')
    },
    printPlan () {
      const url = import.meta.env.VITE_API_BACK + 'plan/' + this.prestamo.id
      window.open(url, '_blank')
    },
    anularPrestamo () {
      this.$alert.confirm('Esta seguro de anular el prestamo?').onOk(() => {
        this.loading = true
        this.$axios.put('loansAnular/' + this.prestamo.id, { status: 'ANULADO' })
          .then(response => {
            this.$alert.success('Prestamo anulado con exito')
            this.$router.push('/prestamos')
          })
          .catch(error => {
            this.$alert.error(error.response.data.message)
          }).finally(() => {
          this.loading = false
        })
      })
    },
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
  },
  computed: {
    interest () {
      return parseFloat(this.interDay*this.days*this.prestamo.saldo/100).toFixed(2)
    },
    custodial () {
      return parseFloat(this.prestamo.saldo * this.prestamo.custodial_fee / 100).toFixed(2)
    },
    totalPay () {
      return (parseFloat(this.cuota.capital) + this.interDay*this.days*this.prestamo.saldo/100 + this.prestamo.saldo * this.prestamo.custodial_fee / 100).toFixed(2)
    },
    totalBs () {
      if (this.prestamo.currency === 'DOLARES') {
        return ((parseFloat(this.cuota.capital) + this.interDay*this.days*this.prestamo.saldo/100 + this.prestamo.saldo * this.prestamo.custodial_fee / 100) * this.prestamo.dolar).toFixed(2)
      }else{
        return (parseFloat(this.cuota.capital) + this.interDay*this.days*this.prestamo.saldo/100 + this.prestamo.saldo * this.prestamo.custodial_fee / 100).toFixed(2)
      }
    }
  }
}
</script>
