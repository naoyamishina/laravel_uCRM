<script setup>
const props = defineProps({ 
  'data' : Object 
})

</script>

<template>
  <div v-if="data.type === 'perDay' || data.type === 'perMonth' || data.type === 'perYear'">
    <tr v-for="item in data.data" :key="item.date">
      <thead>
        <th>日付</th>
        <th>合計</th> 
      </thead>
      <tbody>
        <td>{{ item.date }} </td>
        <td>{{ item.total }} </td> 
      </tbody>
    </tr>
  </div>

  <div v-if="data.type === 'decile' ">
    <tr v-for="item in data.data" :key="item.date">
      <thead>
        <th>グループ</th>
        <th>平均</th>
        <th>金額</th> 
        <th>構成比</th> 
      </thead>
      <tbody>
        <td>{{ item.decile }}</td>
        <td>{{ item.average }}</td>
        <td>{{ item.totalPerGroup }}</td> 
        <td>{{ item.totalRatio }}</td>
      </tbody>
    </tr>
  </div>
</template>
