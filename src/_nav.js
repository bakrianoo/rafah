export default [
  {
    component: 'CNavItem',
    name: 'Dashboard',
    to: '/dashboard',
    icon: 'cil-speedometer',
    badge: {
      color: 'primary',
      text: 'NEW',
    },
  },
  {
    component: 'CNavGroup',
    name: 'Projects',
    icon: 'cil-puzzle',
    items: [
      {
        component: 'CNavItem',
        name: 'list',
        to: '/projects/list',
        icon: 'cil-puzzle',
      },
      {
        component: 'CNavItem',
        name: 'new',
        to: '/projects/new',
        icon: 'cil-puzzle',
      },
    ],
  },
]
