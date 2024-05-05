<template>
  <q-page class="bg-grey-3 q-pa-xs">
    <PrestamoForm :prestamoData="prestamo" :cuotasData="cuotas" :ciData="ci" :nameData="name" v-if="prestamo.id" :option="'show'" />
<!--    <pre>{{cuotas}}</pre>-->
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
      id: null,
      prestamo: {},
      cuotas: [],
      ci: '',
      name: ''
    }
  },
  created() {
    this.id = this.$route.params.id
    this.prestamosShow()
  },
  methods: {
    prestamosShow () {
      this.loading = true
      this.$axios.get('loans/show/' + this.id)
        .then(response => {
          this.ci = response.data.client.ci
          this.name = response.data.client.name
          this.cuotas = response.data.quotas
          this.prestamo = response.data
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
