<template>
  <q-page class="bg-grey-3 q-pa-md">
    <q-table :rows="clients" :columns="columns" title="Clientes" :rows-per-page-options="[0]" row-key="id" dense :filter="filter" :loading="loading">
      <template v-slot:body-cell-option="props">
          <q-td auto-width>
            <q-btn flat dense icon="edit" @click="clientEdit(props.row.id)" >
              <q-tooltip>Editar</q-tooltip>
            </q-btn>
            <q-btn flat dense icon="delete" @click="clientDelete(props.row.id)" >
              <q-tooltip>Eliminar</q-tooltip>
            </q-btn>
<!--            historial de prestamos-->
            <q-btn flat dense icon="history" @click="clientHistory(props.row)" >
              <q-tooltip>Historial</q-tooltip>
            </q-btn>
          </q-td>
      </template>
      <template v-slot:top-right>
        <q-btn outline dense icon="add_circle" @click="clientAdd" label="Agregar" no-caps :loading="loading">
          <q-tooltip>Agregar</q-tooltip>
        </q-btn>
        <q-input v-model="filter" dense class="q-ml-md" debounce="300" placeholder="Buscar" outlined >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
    </q-table>
    <q-dialog v-model="clientDialog" persistent>
      <q-card style="width: 250px;max-width: 90vw;">
        <q-card-section class="row items-center">
          <div class="text-h6">{{ client.id ? 'Editar' : 'Agregar' }} Cliente</div>
          <q-space />
          <q-btn flat dense icon="close" @click="clientDialog = false" />
        </q-card-section>
        <q-form @submit="clientSave">
        <q-card-section>
          <div class="row">
            <div class="col-12">
              <q-input v-model="client.name" label="Nombre" outlined dense :rules="[val => !!val || 'Campo requerido']" />
            </div>
            <div class="col-12">
              <q-input v-model="client.ci" label="CI" outlined dense :rules="[val => !!val || 'Campo requerido']" />
            </div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancelar" v-close-popup :loading="loading" />
          <q-btn color="primary" label="Guardar" type="submit" :loading="loading" />
        </q-card-actions>
        </q-form>
      </q-card>
    </q-dialog>
    <q-dialog v-model="clienDialogHistory" persistent>
      <q-card style="width: 500px;max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Historial de Prestamos</div>
          <q-space />
          <q-btn flat dense icon="close" @click="clienDialogHistory = false" />
        </q-card-section>
        <q-card-section>
          <div class="row">
            <div class="col-6">
              <div><b>Nombre:</b> {{ client.name }}</div>
            </div>
            <div class="col-6">
              <div><b>CI:</b> {{ client.ci }}</div>
            </div>
            <div class="col-12">
              <q-markup-table dense wrap-cells>
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Cuotas</th>
                    <th>Interes</th>
<!--                    <th>Total</th>-->
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="loan in client.loans" :key="loan.id">
                    <td>{{ loan.date }}</td>
                    <td>{{ loan.amount }}</td>
                    <td>{{ loan.payments }}</td>
                    <td>{{ loan.interest_rate }}</td>
<!--                    <td>{{ loan.total }}</td>-->
                  </tr>
                </tbody>
              </q-markup-table>
<!--              <pre>{{ client }}</pre>-->
            </div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cerrar" v-close-popup :loading="loading" />
<!--          <q-btn color="primary" label="Guardar" type="submit" :loading="loading" />-->
        </q-card-actions>
      </q-card>
    </q-dialog>
<!--    <pre>{{ clients }}</pre>-->
  </q-page>
</template>
<script>
export default {
  name: 'ClientsIndex',
  data () {
    return {
      columns: [
        { name: 'option', label: 'Opciones', align: 'left', field: row => row.option },
        { name: 'id', label: 'ID', align: 'left', field: row => row.id },
        { name: 'name', label: 'Nombre', align: 'left', field: row => row.name },
        { name: 'ci', label: 'CI', align: 'left', field: row => row.ci }
      ],
      loading: false,
      clients: [],
      client: {},
      clientDialog: false,
      clienDialogHistory: false,
      filter: ''
    }
  },
  mounted() {
    this.getClients()
  },
  methods: {
    clientSave () {
      this.loading = true
      if (this.client.id) {
        this.$axios.put(`clients/${this.client.id}`, this.client).then(response => {
          this.clientDialog = false
          const index = this.clients.findIndex(client => client.id === this.client.id)
          this.clients.splice(index, 1, response.data)
        }).catch(error => {
          this.$alert.error(error.response.data.message)
        }).finally(() => {
          this.loading = false
        })
      } else {
        this.$axios.post('clients', this.client).then(response => {
          this.clientDialog = false
          this.clients.push(response.data)
        }).catch(error => {
          this.$alert.error(error.response.data.message)
        }).finally(() => {
          this.loading = false
        })
      }
    },
    clientDelete (id) {
      this.$alert.confirm('¿Está seguro de eliminar este cliente?').onOk(() => {
        this.loading = true
        this.$axios.delete(`clients/${id}`).then(response => {
          const index = this.clients.findIndex(client => client.id === id)
          this.clients.splice(index, 1)
        }).catch(error => {
          this.$alert.error(error.response.data.message)
        }).finally(() => {
          this.loading = false
        })
      })
    },
    clientHistory (client) {
      this.clienDialogHistory = true
      this.client = {...client}
    },
    clientEdit (id) {
      this.clientDialog = true
      this.clientFind = this.clients.find(client => client.id === id)
      this.client = {
        id: this.clientFind.id,
        name: this.clientFind.name,
        ci: this.clientFind.ci
      }
    },
    clientAdd () {
      this.clientDialog = true
      this.client = {
        name: '',
        ci: ''
      }
    },
    getClients () {
      this.loading = true
      this.$axios.get('clients').then(response => {
          this.clients = response.data
      }).catch(error => {
          this.$alert.error(error.response.data.message)
      }).finally(() => {
          this.loading = false
      })
    }
  },
};
</script>
