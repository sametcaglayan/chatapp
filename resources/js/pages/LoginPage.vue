<template>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow" style="min-width: 320px; max-width: 400px; width: 100%;">
      <h3 class="text-center mb-4">Giriş Yap</h3>

      <div class="form-group mb-3">
        <label for="username">Kullanıcı Adı</label>
        <input v-model="username" type="text" id="username" class="form-control" placeholder="admin" />
      </div>

      <div class="form-group mb-4">
        <label for="password">Şifre</label>
        <div class="input-group">
          <input v-model="password" :type="passwordType" id="password" class="form-control" placeholder="Şifrenizi girin" />
          <button @click="togglePasswordVisibility" type="button" class="btn btn-outline-secondary">
            <span v-if="passwordType === 'password'">Göster</span>
            <span v-else>Gizle</span>
          </button>
        </div>
      </div>

      <button @click="login" class="btn btn-primary w-100">Giriş</button>

      <div v-if="error" class="alert alert-danger mt-3 text-center">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { isLoggedIn } from '../auth'
isLoggedIn.value = false
localStorage.removeItem('token')
const username = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

// Şifre görünürlüğünü kontrol eden ref
const passwordType = ref('password')

// Şifreyi göster/gizle işlevi
function togglePasswordVisibility() {
  passwordType.value = passwordType.value === 'password' ? 'text' : 'password'
}

const login = async () => {
  error.value = ''
  try {
    const response = await fetch('http://localhost:9000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        username: username.value,
        password: password.value
      })
    }) 
    const data = await response.json()
    if (data.status === 'success') {
      localStorage.setItem('token', 'udascasdsad33')
      isLoggedIn.value = true
      location.href = '/home';
    } else {
      error.value = data.message || 'Giriş başarısız'
    }
  } catch (e) {
    error.value = e.data || 'Giriş başarısız'  
  }
}
</script>

<style scoped>
body {
  background-color: #f8f9fa;
}
</style>
