<template>
  <div :class="`p-4 border rounded-md ${backgroundColor} ${borderColor}`">
    <div class="flex">
      <div class="ml-3 flex-1 md:flex md:justify-between">
        <p :class="`text-sm ${textColor}`">
          {{ content }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      content: {
        type: String,
        required: true
      },
      color: {
        type: String,
        default: 'blue',
      }
    },
    computed: {
      backgroundColor () {
        let colors = {
          blue: 'bg-blue-50',
          green: 'bg-green-50',
          red: 'bg-red-50',
        }

        return colors[this.color]
      },
      borderColor () {
        let colors = {
          blue: 'border-blue-100',
          green: 'border-green-100',
          red: 'border-red-100',
        }

        return colors[this.color]
      },
      textColor () {
        let colors = {
          blue: 'text-blue-700',
          green: 'text-green-700',
          red: 'text-red-700',
        }

        return colors[this.color]
      }
    }
  }
</script>