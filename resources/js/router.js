import { createRouter, createWebHistory } from 'vue-router'
import HomePage from './pages/HomePage.vue'
import ChatPage from './pages/ChatPage.vue'
import LoginPage from './pages/LoginPage.vue'
import MessPage from './pages/MessPage.vue'

function isLoggedIn() {
  return localStorage.getItem('token') !== null
}

const routes = [
  { path: '/home', name: 'Home', component: HomePage },
  { path: '/chat', name: 'Chat', component: ChatPage },           
  { path: '/chat/:deviceId', name: 'ChatWithId', component: ChatPage }, 
  { path: '/chatid/:chatId', name: 'MessWithId', component: MessPage }, 
  { path: '/login', name: 'Login', component: LoginPage },
  { path: '/:pathMatch(.*)*', redirect: '/login' }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})


router.beforeEach((to, from, next) => {
 
  if ((to.name === 'Home' || to.name === 'Chat' || to.name === 'ChatWithId' || to.name === 'MessWithId') && !isLoggedIn()) {
    next('/login')
  } else {
    next() 
  }
})

export default router
