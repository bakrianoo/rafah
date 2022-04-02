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
    to: '/projects/list',
    icon: 'cil-puzzle',
    items: [
      {
        component: 'CNavItem',
        name: 'new',
        to: '/projects/list',
        icon: 'cil-puzzle',
      },
    ],
  },
]
