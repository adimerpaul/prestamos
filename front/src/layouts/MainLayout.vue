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
          <div class="text-primary text-bold">
            {{title}}
          </div>
        </q-toolbar-title>
        <div>
          <q-btn flat dense round icon="o_account_circle" aria-label="Menu">
            <q-menu>
              <q-list>
                <q-item clickable v-ripple>
                  <q-item-section avatar>
                    <q-avatar icon="account_circle" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Perfil</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item clickable v-ripple>
                  <q-item-section avatar>
                    <q-avatar icon="settings" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Configuración</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item clickable v-ripple @click="logout">
                  <q-item-section avatar>
                    <q-avatar icon="logout" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Cerrar Sesión</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      :width="220"
      class="bg-primary text-white"
    >
      <q-layout>
        <q-header>
          <q-list>
            <q-item-label class="q-pa-xs">
              <q-item dense>
                <q-item-section avatar>
                  <q-img src="/logo.png" />
                </q-item-section>
                <q-item-section>
                  <q-item-label class="text-white text-bold">Bienvenido</q-item-label>
                  <q-item-label caption class="text-white">{{ $store.user.name }}</q-item-label>
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
        </q-header>
        <q-footer>
          <q-item clickable dense v-ripple @click="logout">
            <q-item-section avatar>
              <q-avatar text-color="red" icon="logout" size="38px" />
            </q-item-section>
            <q-item-section>
              <q-item-label class="text-red text-bold">Cerrar Sesión</q-item-label>
            </q-item-section>
          </q-item>
        </q-footer>
      </q-layout>
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
        { title: 'Prestamos', icon: 'account_balance', to: '/prestamos' },
        { title: 'Clientes', icon: 'person_search', to: '/clients' },
        { title: 'Deudores', icon: 'people', to: '/debtors' },
        { title: 'Pagos', icon: 'payment', to: '/payments' },
        { title: 'Prestamos Finalizados', icon: 'done_all', to: '/finished-loans' },
      ],
    };
  },
  methods: {
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen;
    },
    logout() {
      this.$alert.dialog('¿Está seguro que desea cerrar sesión?').onOk(() => {
        this.$axios.post('/logout').then(() => {
          this.$store.isLogeed = false;
          this.$store.user = {};
          localStorage.removeItem('tokenPrestamos');
          this.$router.push('/login');
        })
      })
    },
  },
  computed: {
    rutaActual() {
      return this.$route.path
    },
    title() {
      const route = this.$route.path
      if (route === '/') return 'Inicio'
      if (route === '/prestamos') return 'Prestamos'
      else if (route === '/clients') return 'Clientes'
      else if (route === '/debtors') return 'Deudores'
      else if (route === '/payments') return 'Pagos'
      else if (route === '/finished-loans') return 'Prestamos Finalizados'
      return 'Inicio'
    },
  },
};
</script>
