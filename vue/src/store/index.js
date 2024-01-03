import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
    getters: {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
    },
    state: {
        status: '',
        token: sessionStorage.getItem('token_user') || '',
        user: JSON.parse(sessionStorage.getItem('user')) || {},
        ruta: sessionStorage.getItem('ruta') || '',
        zona: sessionStorage.getItem('ZonaHoraria') || ''
    },
    mutations: {
        auth_request(state) {
            state.status = 'loading'
        },
        auth_success(state, { token, user, ruta, zona }) {
            state.status = 'success'
            state.token = token
            state.user = user
            state.ruta = ruta
            state.zona = zona
        },
        auth_error(state) {
            state.status = 'error'
        },
        logout(state) {
            state.status = ''
            state.token = ''
            state.ruta = ''
            state.zona = ''
        },
    },
    actions: {
        login({ commit }, usuario) {
            let token = usuario.token;
            const user = usuario.usuario;
            const ruta = usuario.ruta;
            const rutaE = usuario.rutaE;
            const cliente = usuario.cliente;
            const empresa = usuario.empresa;
            const zona = usuario.Zona
            Vue.prototype.$http.defaults.headers.common['Authorization'] = token;

            sessionStorage.setItem('token_user', token);
            sessionStorage.setItem('user', JSON.stringify(user));
            sessionStorage.setItem('clientelog', JSON.stringify(cliente));
            sessionStorage.setItem('ruta', ruta);
            sessionStorage.setItem('rutaE', rutaE);
            sessionStorage.setItem('empresa', JSON.stringify(empresa));
            sessionStorage.setItem('ZonaHoraria', zona);

            commit('auth_success', { token, user, ruta, zona});
        },

        logout({ commit })
        {
            return new Promise((resolve, reject) => {
                commit('logout')
                sessionStorage.removeItem('token_user');
                sessionStorage.removeItem('user');
                resolve();
            });
        }
    },
    modules: {}
})