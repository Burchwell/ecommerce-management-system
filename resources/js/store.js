import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const state = {
    sidebar: {
        Left: {
            Show: true,
            Fixed: true
        },
        Right: {
            Show: false
        },
    },
    darkMode: true,
    loading: false,
    error: []
}

const mutations = {
    toggleLoading() {
        this.toggle(state.loading)
    },
    set(state, [variable, value]) {
        state[variable] = value
    },
    toggle(state, variable) {
        state[variable] = !state[variable]
    }
}

export default new Vuex.Store({
    state,
    mutations
})
