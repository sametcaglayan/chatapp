<template>
  <div>
    <div v-if="isLoggedIn" class="p-3 bg-light">
      <router-link to="/home" class="btn btn-outline-primary me-2">Anasayfa</router-link>
      <button class="btn btn-danger ms-3" @click="logout">Çıkış</button>
    </div>
    <router-view :key="$route.fullPath" />
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'


const isLoggedIn = ref(false)


onMounted(() => {
  const token = localStorage.getItem('token')
  if (token) {
    isLoggedIn.value = true
  }
})

const router = useRouter()


function logout() {
  localStorage.removeItem('token')
  isLoggedIn.value = false
  router.push('/login')
}
</script>
