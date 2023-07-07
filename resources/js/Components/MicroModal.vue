<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios'
import { onMounted } from 'vue'

const search = ref('')
const customers = reactive({})

// onMounted(() => {
//   console.log(customers) 
// })

const isShow = ref(false)

const toggleStatus = () => { 
  isShow.value = !isShow.value
}

const searchCustomers = async () => {
  try{
  await axios.get(`/api/searchCustomers/?search=${search.value}`)
    .then( res => { 
      console.log(customers) 
      console.log(res.data) 
      customers.value = res.data
      console.log(customers) 
    }) 
    toggleStatus() 
  } catch (e){
    console.log(e.message)
  }
}
</script> 

<template>
  <div v-show="isShow" class="modal" id="modal-1" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container w-2/3" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-1-title">
            Micromodal
          </h2>
          <button type="button" @click="toggleStatus" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="modal-1-content">
          <div v-if="customers.value" class="lg:w-2/3 w-full mx-auto overflow-auto">
          <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
              <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">Id</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">氏名</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">カナ</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">電話番号</th>
                
              </tr>
            </thead>
            <tbody>
              <tr v-for="customer in customers.value.data" :key="customer.id">
                <td class="border-b-2 border-gray-200 px-4 py-3">
                  <button @click="setCustomer({ id: customer.id, kana: customer.kana })" type="button" class="text-blue-400">
                    {{ customer.id }}
                  </button>
                  </td>
                <td class="border-b-2 border-gray-200 px-4 py-3">{{ customer.name }}</td>
                <td class="border-b-2 border-gray-200 px-4 py-3">{{ customer.kana }}</td>
                <td class="border-b-2 border-gray-200 px-4 py-3">{{ customer.tel }}</td>
              </tr>
            </tbody>
          </table>
          </div>
        </main>
        <footer class="modal__footer">
          <button type="button" @click="toggleStatus" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close</button>
        </footer>
      </div>
    </div>
  </div>
  <input name="customer" v-model="search">
  <button type="button" @click="searchCustomers" data-micromodal-trigger="modal-1" href='javascript:;'>Open Modal Dialog</button >
</template>
