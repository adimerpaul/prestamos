<template>
  <q-layout view="lHh Lpr lFf">
    <q-header class="bg-white text-black" bordered>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          Quasar App
        </q-toolbar-title>

        <div>Quasar v{{ $q.version }}</div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      :width="220"
      class="bg-primary text-white"
    >
      <q-list>
        <q-item-label class="q-pa-xs">
          <q-item dense>
            <q-item-section avatar>
              <q-img src="logo.png" />
            </q-item-section>
            <q-item-section>
              <q-item-label class="text-white text-bold">Bienvenido</q-item-label>
              <q-item-label caption class="text-white">Admin</q-item-label>
            </q-item-section>
          </q-item>
        </q-item-label>
        <q-item clickable dense v-ripple v-for="link in essentialLinks" :key="link.title" :to="link.to" exact :class="`text-white ${rutaActual==link.to?'bg-secondary':''}`">
          <q-item-section avatar>
            <q-avatar  text-color="white" :icon="`${rutaActual==link.to?link.icon:'o_'+link.icon}`" :size="`${rutaActual==link.to?'45px':'38px'}`" />
          </q-item-section>
          <q-item-section>
            <q-item-label :class="`text-white ${rutaActual==link.to?'text-bold':''}`">{{ link.title }}</q-item-label>
          </q-item-section>
        </q-item>

      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
export default {
  data() {
    return {
      leftDrawerOpen: false,
      essentialLinks: [
        { title: 'Inicio', icon: 'home', to: '/' },
        { title: 'Prestamos', icon: 'shopping_bag', to: '/prestamos' },
        { title: 'Clientes', icon: 'person_search', to: '/clients' },
        { title: 'Deudores', icon: 'people', to: '/debtors' },
      ],
    };
  },
  methods: {
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen;
    },
  },
  computed: {
    rutaActual() {
      return this.$route.path;
    },
  },
};
</script>
