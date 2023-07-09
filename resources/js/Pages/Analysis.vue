<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { reactive, onMounted, toRef } from 'vue' 
import { getToday } from '@/common'
import Chart from '@/Components/Chart.vue'


onMounted(() => {
    form.startDate = getToday() 
    form.endDate = getToday()
})

const form = reactive({ 
    startDate: null, 
    endDate: null,
    type: 'perDay'
})

const data = reactive({})

const getData = async () => { try{
    await axios.get('/api/analysis/', { 
        params: {
        startDate: form.startDate, 
        endDate: form.endDate, 
        type: form.type
        } 
    })
    .then( res => {
        data.data = res.data.data
        data.labels = res.data.labels 
        data.totals = res.data.totals
        console.log(data.totals)
    })
    } catch (e){
    console.log(e.message) 
    }
}
</script>

<template>
    <Head title="データ分析" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">データ分析</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="getData">
                        From: <input type="date" name="startDate" v-model="form.startDate"> 
                        To: <input type="date" name="endDate" v-model="form.endDate"> 
                        <button>分析する</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="data.data">
            <Chart :data="data" />
        </div>

        <div v-show="data.data">
        <tr v-for="item in data.data" :key="item.date">
            <thead>
            <td>日付</td>
            <td>合計</td> 
            </thead>
            <tbody>
            <td>{{ item.date }} </td>
            <td>{{ item.total }} </td> 
            </tbody>
        </tr>
        </div>
    </AuthenticatedLayout>
</template>
