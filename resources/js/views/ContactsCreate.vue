<template>
  <div>
      <form @submit.prevent="submitForm">
         <InputField name='name' placeholder = 'Contact Name' label = 'Contact Name'  @update:field="form.name=$event" 
         :errors="errors"/>
         <InputField name='email' placeholder = 'you@example.com' label = 'Contact Email'  @update:field="form.email=$event" 
         :errors="errors"/>
         <InputField name='company' placeholder = 'Company' label = 'Company'  @update:field="form.company=$event" 
         :errors="errors"/>
         <InputField name='birthday' placeholder = 'MM/DD/YYYY' label = 'Birthday'  @update:field="form.birthday=$event" 
         :errors="errors"/>
        
          <div class="flex justify-end">
              <button class="text-red-700 px-4 rounded border mr-5 hover:border-red-700">Cancel</button>
              <button class="text-white bg-blue-500 py-2 px-4 rounded hover:bg-blue-400">Add New Contact</button>
          </div>
      </form>
  </div>
</template>


<script>
import InputField from '../components/InputField'
export default {
   name: 'ContactsCreate',

   components: {
       InputField
   },
   data: function(){
       return{
           form:{
               name:'',
               email:'',
               company:'',
               birthday:'',
           },
           
           errors: null,

       }
   },

   methods:{
       submitForm:function(){
           axios.post('/api/contacts',this.form)
           .then(response => {

           })
           .catch(errors =>{
               this.errors = errors.response.data.errors
           })
       }
   }
}
</script>

<style scoped>

</style>