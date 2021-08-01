<template>
  <div v-if="! loading">
    <base-alert
      v-if="error"
      class="mb-4"
      color="red"
      :content="error"
    />
    <base-alert
      v-else-if="success"
      class="mb-4"
      color="green"
      :content="success"
    />
    <base-alert
      v-else-if="$store.state.customers.length === 0"
      class="mb-4"
      content="There are no customers in the queue. Add some using the form below."
    />

    <div class="bg-white rounded-md shadow mb-6">
      <form @submit.prevent="save">
        <div class="p-4 sm:p-6">
          <div class="mb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              New Customer
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Select the service, customer type and enter their details.
            </p>
          </div>
          <div class="mb-4">
            <select
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              :class="{
                'border-red-300': !! validation('service'),
              }"
              v-model="customer.service"
            >
              <option :value="null">Select Service</option>
              <option
                v-for="(service, index) in $store.state.services"
                :key="service.id"
                :value="service.id"
              >{{ service.name }}</option>
            </select>
            <p
              v-if="!! validation('service')"
              class="mt-2 text-xs text-red-600"
            >{{ validation('service') }}</p>
          </div>

          <fieldset class="mb-4">
            <legend class="sr-only">
              Customer type
            </legend>
            <div class="grid gap-4 grid-cols-3">
              <label
                v-for="(name, index) in types"
                :key="index"
                class="relative block rounded-lg border border-gray-300 bg-white shadow-sm px-6 py-4 cursor-pointer hover:border-gray-400 sm:flex sm:justify-between focus-within:ring-1 focus-within:ring-offset-2 focus-within:ring-indigo-500"
                :class="{
                  'bg-indigo-50': customer.type === name
                }"
              >
                <input type="radio" name="type" :value="name" class="sr-only" v-model="customer.type">
                <div class="flex-grow flex justify-center items-center">
                  <div class="text-sm">
                    <p
                      class="font-medium text-gray-900"
                      :class="{
                        'text-indigo-900': customer.type === name
                      }"
                    >
                      {{ $filters.capitalize(name) }}
                    </p>
                  </div>
                </div>
                <div
                  class="border-transparent absolute -inset-px rounded-lg border pointer-events-none"
                  :class="{
                    'border-indigo-200': customer.type === name
                  }"
                  aria-hidden="true"
                ></div>
              </label>
            </div>
          </fieldset>

          <template v-if="customer.type === 'citizen'">
            <div class="grid gap-4 grid-cols-10 md:grid-cols-12">
              <div class="col-span-3 md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <div class="mt-1">
                  <select
                    id="title"
                    class="mt-1 block w-full pl-3 pr-8 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    :class="{
                      'border-red-300': !! validation('title'),
                    }"
                    v-model="customer.title"
                  >
                    <option
                      v-for="(title, index) in titles"
                      :key="index"
                      :value="title"
                    >{{ title }}</option>
                  </select>
                  <p
                    v-if="!! validation('title')"
                    class="mt-2 text-xs text-red-600"
                  >{{ validation('title') }}</p>
                </div>
              </div>
              <div class="col-span-7 md:col-span-5">
                <label for="first-name" class="block text-sm font-medium text-gray-700">First name</label>
                <div class="mt-1">
                  <input
                    type="text"
                    id="first-name"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    :class="{
                      'border-red-300': !! validation('first_name')
                    }"
                    placeholder="Jane"
                    v-model="customer.first_name"
                  >
                  <p
                    v-if="!! validation('first_name')"
                    class="mt-2 text-xs text-red-600"
                  >{{ validation('first_name') }}</p>
                </div>
              </div>
              <div class="col-span-10 md:col-span-5">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                <div class="mt-1">
                  <input
                    type="text"
                    id="last-name"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    :class="{
                      'border-red-300': !! validation('last_name')
                    }"
                    placeholder="Doe"
                    v-model="customer.last_name"
                  >
                  <p
                    v-if="!! validation('last_name')"
                    class="mt-2 text-xs text-red-600"
                  >{{ validation('last_name') }}</p>
                </div>
              </div>
            </div>
          </template>

          <template v-else-if="customer.type === 'organisation'">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Organisation name</label>
              <div class="mt-1">
                <input
                  type="text"
                  id="name"
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  :class="{
                    'border-red-300': !! validation('name')
                  }"
                  placeholder="Acme Inc."
                  v-model="customer.name"
                >
                <p
                  v-if="!! validation('name')"
                  class="mt-2 text-xs text-red-600"
                >{{ validation('name') }}</p>
              </div>
            </div>
          </template>
        </div>
        <div class="px-4 py-3 sm:px-6 bg-gray-50 text-right rounded-b-md">
          <button
            type="submit"
            class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :disabled="submitting"
          >Save</button>
        </div>
      </form>
    </div>

    <div
      v-if="$store.state.customers.length"
      class="flex flex-col mb-6"
    >
      <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="pl-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    #
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Customer
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Service
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs text-right font-medium text-gray-500 uppercase tracking-wider">
                    Queued At
                  </th>
                  <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Delete</span>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="(customer, index) in $store.state.customers"
                  :key="customer.id"
                >
                  <td class="pl-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">
                      {{ index + 1 }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-full" :src="customer.avatar" alt="">
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                          {{ customer.full_name }}
                        </div>
                        <div class="text-sm text-gray-500">
                          {{ customer.type }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ customer.service.name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-right text-gray-500">
                      {{ customer.queued_at }}
                    </div>
                  </td>
                  <td class="pr-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button
                      type="button"
                      class="inline-flex justify-center items-center w-6 h-6 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      :disabled="submitting"
                      @click="destroy(customer.id)"
                    >
                      &times;
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        loading: true,
        submitting: false,
        success: null,
        error: null,
        errors: {},
        customer: {
          service: null,
          type: 'anonymous',
          title: 'Mr.',
          first_name: null,
          last_name: null,
          name: null
        },
        types: ['anonymous', 'citizen', 'organisation'],
        titles: ['Mr.', 'Mrs.', 'Miss', 'Ms.', 'Dr.', 'Prof.']
      }
    },
    created () {
      this.$watch(
        () => this.$route.params,
        () => {
          this.fetch()
        },
        { immediate: true }
      )
    },
    methods: {
      fetch () {
        this.loading = true

        axios.get('customers')
          .then(response => {
            this.loading = false

            this.$store.commit('customers', response.data.customers)
            this.$store.commit('services', response.data.services)
          })
          .catch(error => {
            this.loading = false
            this.error = error.message
          })
      },
      save () {
        this.submitting = true

        axios.post('customers', this.customer)
          .then(response => {
            this.submitting = false
            this.success = response.data.message
            this.error = null
            this.errors = {}
            this.customer = {
              service: null,
              type: 'anonymous',
              title: 'Mr.',
              first_name: null,
              last_name: null,
              name: null
            }

            this.$store.commit('addCustomer', response.data.customer)

            this.scrollTop()
          })
          .catch(error => {
            this.submitting = false
            this.success = null
            this.error = null
            this.errors = {}

            if (error.response && error.response.data.errors) {
              this.errors = error.response.data.errors
            } else {
              this.error = error.message
            }

            this.scrollTop()
          })
      },
      destroy (id) {
        if (confirm('Are you sure you want to remove this customer from the queue?')) {
          this.submitting = true

          axios.delete(`customers/${id}`)
            .then(response => {
              this.submitting = false
              this.success = response.data.message
              this.error = null

              this.$store.commit('removeCustomer', id)

              this.scrollTop()
            })
            .catch(error => {
              this.submitting = false
              this.success = null
              this.error = error.message

              this.scrollTop()
            })
        }
      },
      validation (field) {
        if (this.errors.hasOwnProperty(field)) {
          return _.first(this.errors[field])
        }

        return null        
      },
      scrollTop () {
        window.scroll({
          top: 0,
          behavior: 'smooth',
        })
      }
    }
  }
</script>