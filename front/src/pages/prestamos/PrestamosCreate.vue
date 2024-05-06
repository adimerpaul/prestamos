<template>
  <q-page class="bg-grey-3 q-pa-xs">
    <PrestamoForm :prestamoData="prestamo" :cuotasData="cuotas" :ciData="ci" :nameData="name" :option="'create'" v-if="prestamo.id" />
  </q-page>
</template>
<script>
import moment from "moment";
import PrestamoForm from "pages/prestamos/PrestamoForm.vue";

export default {
  name: 'PrestamosCreate',
  components: {PrestamoForm},
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
        description: '',
        currency: 'DOLARES',
        dolar: 6.96
      },
      cuotas: [],
      ci: '',
      name: ''
    }
  },
  created() {
    this.getId()
  },
  methods: {
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
  }
}
</script>
