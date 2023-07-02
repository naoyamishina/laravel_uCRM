<script setup>
  import InputLabel from '@/components/InputLabel.vue'
  import TextInput from '@/components/TextInput.vue'
  import GuestLayout from '@/Layouts/GuestLayout.vue'

  const emitTest = e => console.log(e)
</script> 

<template>
  <GuestLayout>
    <InputLabel>タイトル</InputLabel>
    <TextInput 
      modelValue="初期値です"
      @update:modelValue="emitTest">
    </TextInput>
  </GuestLayout> 
</template>
