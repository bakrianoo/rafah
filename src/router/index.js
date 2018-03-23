import Vue from 'vue'
import Router from 'vue-router'

// Containers
import Full from '@/containers/Full'

// Views
import Dashboard from '@/views/Dashboard'
import Project from '@/views/Project'
import loadData from '@/views/loadData'
import exportData from '@/views/exportData'

Vue.use(Router)

export default new Router({
  mode: 'hash',
  linkActiveClass: 'open active',
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: '/',
      redirect: '/dashboard',
      name: 'Home',
      component: Full,
      children: [
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard
        },
        {
          path: 'project/:id',
          name: 'Project',
          component: Project
        },
        {
          path: 'project/:id/:page',
          name: 'ProjectPage',
          component: Project
        },
        {
          path: 'load/:id',
          name: 'loadData',
          component: loadData
        },
        {
          path: 'export',
          name: 'exportData',
          component: exportData
        }
      ]
    }
  ]
})
