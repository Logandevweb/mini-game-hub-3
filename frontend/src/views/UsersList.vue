<template>
  <NavView />

  <section class="min-h-screen bg-[#050816] text-white p-4 sm:p-10">
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <!-- TITLE + COUNT -->
      <div class="flex items-center gap-4">
        <h1 class="text-2xl sm:text-3xl font-bold tracking-wide">ALL USERS</h1>

        <span class="px-3 py-1 rounded-lg bg-purple-500/20 text-purple-400 text-sm">
          {{ filteredUsers.length }}
        </span>
      </div>

      <!-- SEARCH -->
      <input
        v-model="search"
        type="text"
        placeholder="Search users..."
        class="bg-[#0B1020] border border-[#1E2A52] rounded-xl px-4 py-3 outline-none focus:border-purple-500 w-full sm:w-auto"
      />
    </div>

    <!-- TABLE WRAPPER (scroll on mobile) -->
    <div class="bg-[#0B1020] border border-[#1E2A52] rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full min-w-175">
          <!-- HEAD -->
          <thead class="bg-[#101935] text-[#7AA2FF]">
            <tr class="text-left">
              <th class="p-4 sm:p-5">USER</th>
              <th class="p-4 sm:p-5">EMAIL</th>
              <th class="p-4 sm:p-5">ROLE</th>
              <th class="p-4 sm:p-5">JOINED</th>
              <th class="p-4 sm:p-5 text-right">ACTIONS</th>
            </tr>
          </thead>

          <!-- BODY -->
          <tbody>
            <tr
              v-for="user in filteredUsers"
              :key="user.id"
              class="border-t border-[#16213D] hover:bg-white/5 transition"
            >
              <td class="p-4 sm:p-5 font-semibold">{{ user.name }}</td>

              <td class="p-4 sm:p-5 text-gray-300">{{ user.email }}</td>

              <td class="p-4 sm:p-5">
                <span
                  class="px-3 py-1 rounded-full text-xs border uppercase"
                  :class="{
                    'border-pink-500 text-pink-500': user.role === 'admin',
                    'border-blue-500 text-blue-400': user.role === 'user',
                  }"
                >
                  {{ user.role }}
                </span>
              </td>

              <td class="p-4 sm:p-5 text-gray-400">
                {{ new Date(user.created_at).toLocaleDateString() }}
              </td>

              <td class="p-4 sm:p-5">
                <div class="flex justify-end">
                  <button
                    @click="deleteUser(user.id)"
                    class="px-4 py-2 rounded-lg border border-pink-500 text-pink-500 hover:bg-pink-500/10 transition"
                  >
                    DELETE
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- EMPTY -->
      <div v-if="!filteredUsers.length && !loading" class="p-10 text-center text-gray-400">
        Aucun utilisateur trouvé
      </div>

      <!-- LOADING -->
      <div v-if="loading" class="p-10 text-center text-gray-400">Chargement...</div>
    </div>
  </section>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import axios from 'axios'
import NavView from './NavView.vue'

const API_URL = import.meta.env.VITE_API_URL

const users = ref([])
const loading = ref(true)

const search = ref('')

/*
|--------------------------------------------------------------------------
| FILTER USERS
|--------------------------------------------------------------------------
*/

const filteredUsers = computed(() => {
  if (!search.value.trim()) {
    return users.value
  }

  const searchValue = search.value.toLowerCase()

  return users.value.filter((user) => {
    return (
      user.name.toLowerCase().includes(searchValue) ||
      user.email.toLowerCase().includes(searchValue) ||
      user.role.toLowerCase().includes(searchValue)
    )
  })
})

/*
|--------------------------------------------------------------------------
| FETCH USERS
|--------------------------------------------------------------------------
*/

const fetchUsers = async () => {
  try {
    const token = localStorage.getItem('token')

    const response = await axios.get(`${API_URL}/admin/users`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    users.value = response.data
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

const deleteUser = async (id) => {
  const confirmDelete = confirm('Voulez-vous vraiment supprimer cet utilisateur ?')

  if (!confirmDelete) {
    return
  }

  try {
    const token = localStorage.getItem('token')

    await axios.delete(`${API_URL}/admin/users/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    users.value = users.value.filter((user) => user.id !== id)
  } catch (error) {
    console.error(error)
  }
}

onMounted(() => {
  fetchUsers()
})
</script>
