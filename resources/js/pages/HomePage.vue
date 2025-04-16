<template>
  <div class="container mt-5">
    <h3 class="mb-4">Cihaz Listesi</h3>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status"></div>
    </div>

    <div v-else>
      <table class="table table-striped table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Device ID</th>
            <th>İsim</th>
            <th>Kredi</th>
            <th>Detay</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="device in devices" :key="device.id">
            <td>{{ device.uuid}}</td>
            <td>{{ device.name }}</td>
            <td>{{ device.chat_credit }}</td>
            <td>
              <router-link :to="`/chat/${device.uuid}`" class="btn btn-sm btn-outline-primary">
                Chat
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="devices.length === 0" class="alert alert-warning text-center">
        Kayıtlı cihaz bulunamadı.
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
const devices = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const response = await fetch('http://localhost:9000/api/admin/devices')
    const json = await response.json()

    if (json.ok) {
      devices.value = json.data
    }
  } catch (err) {
    console.error('Hata:', err)
  } finally {
    loading.value = false
  }
})
</script>
