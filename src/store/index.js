import { createStore } from 'vuex'

export default createStore({
  state: {
    sidebarVisible: '',
    sidebarUnfoldable: false,
    token: localStorage.getItem('token'),
    user: null,
  },
  mutations: {
    toggleSidebar(state) {
      state.sidebarVisible = !state.sidebarVisible
    },
    toggleUnfoldable(state) {
      state.sidebarUnfoldable = !state.sidebarUnfoldable
    },
    updateSidebarVisible(state, payload) {
      state.sidebarVisible = payload.value
    },
    updateToken(state, payload) {
      state.token = payload.value
    },
    updateUser(state, payload) {
      state.user = payload.value
    },
  },
  actions: {},
  modules: {},
})
