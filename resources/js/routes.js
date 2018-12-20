import Dashboard from './components/Dashboard'
import Login from './components/Login'

import VueRouter from 'vue-router';

const routes = [
 { name:'Dashboard', path: '/', component: Dashboard, meta: { requiresAuth: true} },
 { path: '/login', component: Login },
]

export const router = new VueRouter({
  mode: 'history',
  routes // short for `routes: routes`
})


router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (localStorage.getItem("user") === null) {
      window.location = "/login";
    } else {
      next();
    }
  } else {
    next();
  }
})
