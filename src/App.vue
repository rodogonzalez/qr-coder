<script setup>
//import { useRoute } from 'vue-router'

</script>

<template> 
  <main class="container p-0">         
    <router-view :telefono="$route.params.telefono" :mensaje="$route.params.mensaje"  />
  </main>
</template>

