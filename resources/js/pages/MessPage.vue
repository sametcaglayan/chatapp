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
            <th>Mesaj</th>
            <th>Chat Mesajı</th>
            <th>Tarih</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="message in messages" :key="message.id">
            <td>{{ message.device_id}}</td>
            <td>{{ message.chat_id }}</td>
            <td>{{ message.message }}</td>
            <td>{{ message.response }}</td>
            <td>{{ formatDate(message.created_at) }}</td>
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
const chatId = route.params.chatId || null

const messages = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const url = chatId
      ? `http://localhost:9000/api/admin/chatid/${chatId}`
      : `http://localhost:9000/api/admin/chatid`

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


const formatDate = (dateString) => {
  const date = new Date(dateString);
  const options = {
    timeZone: 'Europe/Istanbul',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false,
  };
  return date.toLocaleString('tr-TR', options);
}
</script>
