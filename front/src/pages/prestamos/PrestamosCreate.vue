<template>
  <q-page class="bg-grey-3 q-pa-xs">
    <q-card>
      <q-card-section>
        <q-form @submit="submit">
          <div class="row">
            <div class="col-6 col-md-4 col-sm-12">
              <div>
                <b>Id:</b> {{ prestamo.id }}
              </div>
            </div>
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>
<script>
export default {
  name: 'PrestamosCreate',
  data () {
    return {
      loading: false,
      prestamo: {
        fecha: '',
        monto: 0,
        tasa: 0,
        plazo: 0,
        cuota: 0,
        pagos: []
      }
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
    submit () {
      // this.$store.dispatch('prestamos/create', this.prestamo)
    }
  }
}
</script>
