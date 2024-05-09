<template>
  <q-layout>
    <q-page-container>
      <q-page class="q-pa-lg dark-overlay">
        <div class="row">
          <div class="col-12 col-md-1"></div>
          <div class="col-12 col-md-10">
            <q-card>
              <q-card-section>
                <div class="row">
                  <div class="col-12 col-md-8 flex flex-center" v-if="!$q.screen.lt.md">
                    <q-img src="~assets/login.jpg" />
                  </div>
                  <div class="col-12 col-md-4 q-pa-md">
                    <div class="text-h6 text-center text-bold">Iniciar Sesión</div>
                    <div class="text-caption text-center">Ingresa tus credenciales para acceder al sistema</div>
                    <q-input v-model="username" label="Usuario" outlined :rules="[val => !!val || 'Este campo es requerido']"
                             rounded
                    >
                      <template v-slot:prepend>
                        <q-icon name="account_circle" />
                      </template>
                    </q-input>
                    <q-input v-model="password" label="Contraseña" :type="passwordVisible ? 'text' : 'password'" outlined :rules="[val => !!val || 'Este campo es requerido']"
                             rounded
                    >
                      <template v-slot:prepend>
                        <q-icon name="lock" />
                      </template>
                      <template v-slot:append>
                        <q-icon :name="passwordVisible ? 'visibility' : 'visibility_off'" @click="passwordVisible = !passwordVisible" />
                      </template>
                    </q-input>
                    <q-btn color="primary" class="full-width" rounded label="Iniciar Sesión" @click="login" no-caps :loading="loading" />
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </div>
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>
<script>
export default {
  data() {
    return {
      username: '',
      password: '',
      passwordVisible: false,
      loading: false
    }
  },
  methods: {
    login() {
      this.loading = true
      this.$axios.post('login', {
        username: this.username,
        password: this.password
      }).then(response => {
        this.$store.user = response.data.user
        this.$store.isLogged = true
        localStorage.setItem('tokenPrestamos', response.data.token)
        this.$axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
        this.$router.push('/')
      }).catch(error => {
        this.$alert.error(error.response.data.message)
      }).finally(() => {
        this.loading = false
      })
    }
  }
}
</script>
<style>
.dark-overlay {
  position: relative;
}

.dark-overlay::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.3); /* Color negro con 50% de opacidad */
}

.dark-overlay {
  /* Agrega las propiedades de la imagen de fondo */
  background-image: url('./../fondoLogin.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}
</style>

