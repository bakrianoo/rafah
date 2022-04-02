<template>
  <CRow>
    <h2>New Project</h2>
  </CRow>
</template>

<script>
import { ref } from 'vue'
export default {
  name: 'ProjectsNew',
  setup() {
    const activeKey = ref(1)
    const flushActiveKey = ref(1)

    return {
      activeKey,
      flushActiveKey,
    }
  },
}
</script>
