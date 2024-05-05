
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue'), meta: { requiresAuth: true } },
      { path: 'prestamos', component: () => import('pages/prestamos/PrestamosIndex.vue'), meta: { requiresAuth: true } },
      { path: 'prestamos/create', component: () => import('pages/prestamos/PrestamosCreate.vue'), meta: { requiresAuth: true } },
      { path: 'prestamos/:id', component: () => import('pages/prestamos/PrestamosShow.vue'), meta: { requiresAuth: true } },
      { path: 'clients', component: () => import('pages/clients/ClientsIndex.vue'), meta: { requiresAuth: true } },
      { path: 'debtors', component: () => import('pages/debtors/DebtorsIndex.vue'), meta: { requiresAuth: true } },
      { path: 'payments', component: () => import('pages/payments/PaymentsIndex.vue'), meta: { requiresAuth: true } },
      { path: 'finished-loans', component: () => import('pages/finished-loans/FinishedLoansIndex.vue'), meta: { requiresAuth: true } },
    ]
  },
  {
    path: '/login',
    component: () => import('pages/LoginPage.vue')
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
