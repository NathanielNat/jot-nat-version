<template>
    <div>
        <div class="flex justify-between">
            <div class="text-blue-400">
                < Back </div> 
                <div>
                    <router-link :to="'/contact/'+ contact.id + '/edit'" class="px-4 py-2 rounded text-green-500 border border-green-500">Edit</router-link>
                    <a href="#" class="px-4 py-2"> Delete</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ContactsShow',

        mounted() {
            axios.get('/api/contacts/' + this.$route.params.id)
                .then(response => {
                    this.contact = response.data.data;
                })
                .catch(errors => {
                    // this.errors = errors.response.errors
                })
        },
        data: function () {
            return {
                contact: null
            }
        }
    }

</script>

<style>

</style>
