<template>
  <NavView />

  <section class="min-h-screen bg-[#050816] text-white px-4 py-10">
    <div class="max-w-2xl mx-auto">
      <!-- TITLE -->
      <h1 class="text-3xl sm:text-4xl font-black tracking-wider mb-10 text-cyan-400 text-center">
        MODIFIER MON PROFIL
      </h1>

      <!-- CARD -->
      <div
        class="bg-[#0B1020] border border-[#1E2A52] rounded-3xl p-6 sm:p-8 shadow-[0_0_20px_rgba(34,211,238,0.15)]"
      >
        <!-- AVATAR -->
        <div class="flex flex-col items-center mb-8">
          <div
            class="w-28 h-28 sm:w-32 sm:h-32 rounded-full border border-cyan-400 bg-cyan-500/10 overflow-hidden flex items-center justify-center mb-4"
          >
            <img
              v-if="avatarPreview || currentAvatar"
              :src="avatarPreview || currentAvatar"
              class="w-full h-full object-cover"
            />
            <span v-else class="text-cyan-300 text-4xl sm:text-5xl font-black">
              {{ form.name?.charAt(0).toUpperCase() }}
            </span>
          </div>

          <label
            class="cursor-pointer px-4 py-2 rounded-xl border border-cyan-400 text-cyan-400 hover:bg-cyan-400/10 transition text-sm sm:text-base"
          >
            Changer d’avatar
            <input type="file" @change="handleAvatar" class="hidden" />
          </label>
        </div>

        <!-- FORM -->
        <form @submit.prevent="updateProfile" class="space-y-5 sm:space-y-6">
          <!-- NAME -->
          <div>
            <label class="block mb-1 text-cyan-300 font-semibold text-sm sm:text-base">Nom</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full bg-[#050816] border border-[#1E2A52] rounded-xl px-4 py-3 text-white focus:border-cyan-400 outline-none"
            />
          </div>

          <!-- EMAIL -->
          <div>
            <label class="block mb-1 text-cyan-300 font-semibold text-sm sm:text-base">Email</label>
            <input
              v-model="form.email"
              type="email"
              class="w-full bg-[#050816] border border-[#1E2A52] rounded-xl px-4 py-3 text-white focus:border-cyan-400 outline-none"
            />
          </div>

          <!-- PASSWORD -->
          <div>
            <label class="block mb-1 text-cyan-300 font-semibold text-sm sm:text-base"
              >Nouveau mot de passe</label
            >
            <input
              v-model="form.password"
              type="password"
              class="w-full bg-[#050816] border border-[#1E2A52] rounded-xl px-4 py-3 text-white focus:border-cyan-400 outline-none"
            />
          </div>

          <!-- BUTTONS -->
          <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
            <button
              type="submit"
              class="w-full sm:w-auto px-6 py-3 rounded-xl border border-green-400 text-green-400 hover:bg-green-400/10 transition uppercase tracking-wider text-sm sm:text-base"
            >
              Enregistrer
            </button>

            <router-link
              to="/profile"
              class="w-full sm:w-auto px-6 py-3 rounded-xl border border-gray-500 text-gray-400 hover:bg-gray-500/10 transition uppercase tracking-wider text-sm sm:text-base text-center"
            >
              Annuler
            </router-link>
          </div>
        </form>
      </div>

      <!-- DANGER ZONE -->
      <div
        class="mt-10 bg-[#0B1020] border border-red-500/40 rounded-3xl p-6 text-center shadow-[0_0_20px_rgba(239,68,68,0.15)]"
      >
        <button
          @click="confirmDelete"
          class="px-6 py-3 rounded-xl border border-red-500 text-red-400 hover:bg-red-500/10 transition uppercase tracking-wider text-sm sm:text-base"
        >
          Supprimer mon compte
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
import NavView from './NavView.vue'

const auth = useAuthStore()
const router = useRouter()

const API_URL = import.meta.env.VITE_API_URL
const BASE_URL = API_URL.replace('/api', '')

const form = ref({
  name: '',
  email: '',
  password: '',
  avatar: null,
})

const currentAvatar = ref(null)
const avatarPreview = ref(null)

const handleAvatar = (e) => {
  const file = e.target.files[0]
  form.value.avatar = file
  avatarPreview.value = URL.createObjectURL(file)
}

onMounted(async () => {
  const res = await axios.get(`${API_URL}/profile`, {
    headers: { Authorization: `Bearer ${auth.token}` },
  })

  form.value.name = res.data.user.name
  form.value.email = res.data.user.email

  if (res.data.user.avatar) {
    currentAvatar.value = `${BASE_URL}/storage/${res.data.user.avatar}`
  }
})

const updateProfile = async () => {
  const data = new FormData()
  data.append('name', form.value.name)
  data.append('email', form.value.email)
  if (form.value.password) data.append('password', form.value.password)
  if (form.value.avatar) data.append('avatar', form.value.avatar)

  try {
    const res = await axios.post(`${API_URL}/profile/update`, data, {
      headers: {
        Authorization: `Bearer ${auth.token}`,
        'Content-Type': 'multipart/form-data',
      },
    })

    if (res.data.user.avatar) {
      currentAvatar.value = `${BASE_URL}/storage/${res.data.user.avatar}`
    }

    avatarPreview.value = null
    form.value.avatar = null

    router.push('/profile')
  } catch (error) {
    console.error(error)
  }
}

const confirmDelete = () => {
  if (confirm('Es-tu sûr de vouloir supprimer ton compte ? Cette action est irréversible.')) {
    deleteAccount()
  }
}

const deleteAccount = async () => {
  try {
    await axios.delete(`${API_URL}/user/delete-self`, {
      headers: { Authorization: `Bearer ${auth.token}` },
    })

    auth.logout()
    router.push('/home')
  } catch (error) {
    console.error(error)
  }
}
</script>
