<template>
  <div class="sidebar">
    <SidebarHeader/>
    <SidebarForm/>
    <nav class="sidebar-nav">
      <div slot="header"></div>
      <ul class="nav">
       <li v-for="project of projects" class="nav-item">
          <div>
             <router-link :to="{ name: 'Project', params: { id: project.project_name }}"
             class="router-link-exact-active open active nav-link" replace>
              <i class="icon-plus"></i>
                {{project.project_name}}
             </router-link>

          </div>
      </li>
      <li class="nav-item">
          <div>
             <router-link :to="{ name: 'exportData'}"
             class="router-link-exact-active open active nav-link" replace>
              <i class="icon-cloud-download"></i>
                Export Data
             </router-link>

          </div>
      </li>

      </ul>
      <slot></slot>
    </nav>
    <SidebarFooter/>
    <SidebarMinimizer/>
  </div>
</template>
<script>
import SidebarNavDivider from './SidebarNavDivider'
import SidebarFooter from './SidebarFooter'
import SidebarMinimizer from './SidebarMinimizer'
import SidebarHeader from './SidebarHeader'
import SidebarForm from './SidebarForm'
import projects  from '../../firebase';

export default {
  name: 'sidebar',
  props: {
    navItems: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  components: {
    SidebarNavDivider,
    SidebarFooter,
    SidebarMinimizer,
    SidebarHeader,
    SidebarForm
  },
  methods: {
    handleClick (e) {
      e.preventDefault()
      e.target.parentElement.classList.toggle('open')
    }
  },
  data () {
    return {
      projects : projects
    }
  },
}
</script>

<style lang="css">
  .nav-link {
    cursor:pointer;
  }
</style>
