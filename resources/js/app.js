require('./bootstrap')

import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createStore } from 'vuex'

// Components
import Alert from './components/Alert'

// Views
import App from './views/App'
import Home from './views/Home'

const routes = [
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: {
      template: '<p>Page not found</p>'
    }
  },
  {
    path: '/',
    name: 'Home',
    component: Home
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes: routes
})

const store = createStore({
  state () {
    return {
      customers: [],
      services: []
    }
  },
  mutations: {
    customers (state, array) {
      state.customers = array
    },
    addCustomer (state, object) {
      state.customers.push(object)
    },
    removeCustomer (state, id) {
      let index = _.findIndex(state.customers, { id: id })

      state.customers.splice(index, 1)
    },
    services (state, array) {
      state.services = array
    }
  }
})

const app = createApp(App)

app.use(router)
app.use(store)

app.component('base-alert', Alert)

app.config.globalProperties.$filters = {
  capitalize (value) {
    return _.capitalize(value)
  }
}

app.mount('#app')