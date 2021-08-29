<template>
   <div v-if="loading">Loading... </div>
  <div v-else>
      <div v-if="contacts.length === 0">
          <p>No Contact yet. <router-link to="/contacts/create">Get Started</router-link></p>
      </div>

      <div v-for="contact in contacts">
          <router-link :to="'/contacts/' + contact.data.contact_id"  class="flex items-center border-b border-gray-400 p-4 hover:bg-gray-100">
            <UserCircle :name="contact.data.name" />

            <div class="pl-4">
                <p class="text-blue-400 font-bold">{{ contact.data.name }} </p>
                <p class="text-gray-600 font-bold">{{ contact.data.company }} </p>
            </div>
          </router-link>
      </div>
  </div>
</template>

<script>
import UserCircle from './UserCircle';

export default {
    components: {
        UserCircle,
    },

    props: [
        'endpoint',
    ],

    mounted() {
        axios.get(this.endpoint)
        .then(response => {
            this.contacts =response.data.data;

            this.loading= false
        }).catch(error => {
            this.loading =false;

            alert('Unable to  fetch contacts')
        })
    }, 

    data() {
        return {
            loading: true,

        }
    }
}
</script>

<style>

</style>