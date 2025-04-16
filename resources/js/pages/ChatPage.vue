<template>
  <div class="container mt-5">
    <h3 class="mb-4">Chat Mesaj</h3>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status"></div>
    </div>

    <div v-else>
      <table class="table table-striped table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Device ID</th>
            <th>Chat ID</th>
            <th>Son Mesaj Tarihi</th>
            <th>Chat Geçmişi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="message in messages" :key="message.id">
            <td>{{ message.device_id}}</td>
            <td>{{ message.chat_id }}</td>
            <td>{{ message.latest_message_time }}</td>
            <td>
              <router-link :to="`/chatid/${message.chat_id}`" class="btn btn-sm btn-outline-primary">
                Mesajlar
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="messages.length === 0" class="alert alert-warning text-center">
        Kayıtlı mesaj bulunamadı.
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const deviceId = route.params.deviceId || null

const messages = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const url = deviceId
      ? `http://localhost:9000/api/admin/chat/${deviceId}`
      : `http://localhost:9000/api/admin/chat`

    const response = await fetch(url)
    const json = await response.json()

    if (json.ok) {
      messages.value = json.data
    }
  } catch (err) {
    console.error('Hata:', err)
  } finally {
    loading.value = false
  }
})
</script>
