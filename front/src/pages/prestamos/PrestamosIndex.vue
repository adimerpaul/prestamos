<template>
  <q-page class="q-pa-xs bg-grey-3">
    <q-card>
      <q-card-section>
      <div class="row">
        <div class="col-6 col-md-2" >
          <q-input v-model="fechaInit" label="Fecha Inicio" dense outlined type="date" />
        </div>
        <div class="col-6 col-md-2" >
          <q-input v-model="fechaFin" label="Fecha Fin" dense outlined type="date" />
        </div>
        <div class="col-6 col-md-3" >
          <q-input v-model="filter" label="Buscar" dense outlined />
        </div>
        <div class="col-6 col-md-2 flex flex-center">
          <q-btn color="primary" label="Buscar" @click="getPrestamos" no-caps icon="search" :loading="loading" />
        </div>
        <div class="col-6 col-md-3 text-right flex-center">
          <q-btn color="green" label="Nuevo" @click="create" no-caps icon="add_circle_outline" :loading="loading" />
        </div>
        <div class="col-12 flex flex-center">
          <q-pagination v-model="pagination.page" :max="pagination.last_page" @update:model-value="getPrestamos" boundary-numbers :max-pages="7" />
        </div>
        <div class="col-12">
          <q-markup-table dense wrap-cells>
            <thead>
              <tr>
                <th>Acciones</th>
                <th>Id</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Monto</th>
                <th>Interes</th>
                <th>Tipo</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in prestamos" :key="row.id">
                <td>
                  <q-btn color="primary" icon="visibility" @click="show(row)" dense size="10px" label="Ver Detalle" no-caps />
<!--                  <q-btn color="negative" icon="delete" @click="eliminar(row)" />-->
                </td>
                <td>{{ row.id }}</td>
                <td>{{ row.date }}</td>
                <td>{{ row.client?.name }}</td>
                <td>{{ row.amount }}</td>
                <td>{{ row.interest_rate }}</td>
                <td>
                  <q-chip label="Dolares" text-color="white" dense color="green-9"  v-if="row.currency == 'DOLARES'"/>
                  <q-chip label="Bolivianos" text-color="white" dense color="indigo-9"  v-if="row.currency == 'BOLIVIANOS'"/>
                </td>
              </tr>
            </tbody>
          </q-markup-table>
        </div>
      </div>
      </q-card-section>
<!--      <pre>{{prestamos}}</pre>-->
    </q-card>
  </q-page>
</template>
<script>
import moment from "moment";
export default {
  name: 'PrestamosIndex',
  data () {
    return {
      fechaInit: moment().startOf('isoWeek').format('YYYY-MM-DD'),
      fechaFin: moment().endOf('isoWeek').format('YYYY-MM-DD'),
      prestamos: [],
      loading: false,
      filter: '',
      pagination: {
        page: 1,
        last_page: 1
      }
    }
  },
  mounted() {
    this.getPrestamos();
  },
  methods: {
    getPrestamos() {
      this.loading = true;
      this.$axios.get('loans', {
        params: {
          fechaInit: this.fechaInit,
          fechaFin: this.fechaFin,
          filter: this.filter,
          page: this.pagination.page
        }
      })
        .then(response => {
          this.prestamos = response.data.data
          this.pagination.page = response.data.current_page;
          this.pagination.last_page = response.data.last_page;
        })
        .catch(error => {
          this.$alert.error(error.response.data.message);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    show(row) {
      this.$router.push({ name: 'prestamos-show', params: { id: row.id } });
    },
    create() {
      this.$router.push({ name: 'prestamos-create' });
    },
    eliminar(row) {
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Está seguro de eliminar el registro?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.$axios.delete('prestamos/' + row.id)
          .then(response => {
            this.getPrestamos();
          })
          .catch(error => {
            console.log(error);
          });
      });
    }
  }
};
</script>
