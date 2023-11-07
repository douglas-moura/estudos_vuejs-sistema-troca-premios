import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue')
    },
    {
      path: '/premios',
      name: 'premios',
      component: () => import('../views/CatalogoView.vue')
    },
    {
      path: '/solicitado',
      name: 'solicitado',
      component: () => import('../views/PedidoFeitoView.vue')
    },
    {
      path: '/pedidos',
      name: 'pedidos',
      component: () => import('../views/PedidosView.vue')
    },
    {
      path: '/pontos',
      name: 'pontos',
      component: () => import('../views/PontosUserView.vue')
    },
    {
      path: '/desempenho',
      name: 'desempenho',
      component: () => import('../views/DesempenhoView.vue')
    },
    {
      path: '/comunicados',
      name: 'comunicados',
      component: () => import('../views/ComunicadosView.vue')
    },
    {
      path: '/perfil',
      name: 'perfil',
      component: () => import('../views/UserView.vue')
    }
  ]
})

router.beforeEach((to, from, next) => {
  if(to.name !== 'login' && !sessionStorage.getItem('authUser')) {
    next({name: 'login'})
  } else {
    next()
  }
})

export default router
