import { h, resolveComponent } from 'vue'
import axios from 'axios'
import config from '@/env.config.js'

import { createRouter, createWebHashHistory } from 'vue-router'
import DefaultLayout from '@/layouts/DefaultLayout'
import store from '@/store'

window.axios = axios.create({
  withCredentials: true,
  baseURL: config.API_BASE_URL
});

const routes = [
  {
    path: '/',
    name: 'Home',
    component: DefaultLayout,
    meta: {requiresAuth: true},
    redirect: '/dashboard',
    children: [
      {
        path: '/dashboard',
        name: 'Dashboard',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () =>
          import('@/views/Dashboard.vue'),
      },
      {
        path: '/projects',
        name: 'Projects',
        component: {
          render() {
            return h(resolveComponent('router-view'))
          },
        },
        redirect: '/projects/list',
        children: [
          {
            path: '/projects/list',
            name: 'ProjectsList',
            component: () => import('@/views/projects/list.vue'),
          },
          {
            path: '/projects/new',
            name: 'ProjectsNew',
            component: () => import('@/views/projects/new.vue'),
          },
        ],
      },
    ],
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/pages/Login'),
    meta: {guest: true},
  },
  {
    path: '/pages',
    redirect: '/pages/404',
    name: 'Pages',
    component: {
      render() {
        return h(resolveComponent('router-view'))
      },
    },
    children: [
      {
        path: '404',
        name: 'Page404',
        component: () => import('@/views/pages/Page404'),
      },
      {
        path: '500',
        name: 'Page500',
        component: () => import('@/views/pages/Page500'),
      },
      {
        path: 'register',
        name: 'Register',
        component: () => import('@/views/pages/Register'),
      },
    ],
  },
]

async function isLoggedIn(){
  
  if(store.state.token && store.state.token.length){
    // update axios to use the Bearer token
    let token = store.state.token;
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

    // validate the user token
    return await window.axios.get('/api/user').then((response)=>{
      if(response.data.status && response.data.user){
        // update the user store data
        store.commit({
          type: 'updateUser',
          value: response.data.user,
        })
        return true;
      }
      return false;
    }).catch((errors)=>{
      return false;
    });
  }

  return false;
}

const router = createRouter({
  history: createWebHashHistory(process.env.BASE_URL),
  routes,
})

router.beforeEach(async (to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
      // this route requires authorized, check if logged in
      // if not, redirect to login page.
      const logged = await isLoggedIn()
      if (!logged) {
          next({
          path: '/login',
          query: { redirect: to.fullPath }
          })
      } else {
          next()
      }
  }
  else if(to.matched.some(record => record.meta.guest)) {
      const logged = await isLoggedIn()
      if (logged) {
          next({
          path: '/',
          query: { redirect: to.fullPath }
          })
      } else {
          next()
      }
  } else {
      next() // always call next()!
  }
})

export default router
