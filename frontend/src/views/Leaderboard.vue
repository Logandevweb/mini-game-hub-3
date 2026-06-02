<template>
  <NavView />

  <section class="min-h-screen bg-[#050816] text-white px-4 py-10">
    <div class="max-w-6xl mx-auto">
      <!-- TITLE -->
      <div class="text-center mb-10">
        <h1 class="text-3xl sm:text-5xl font-black tracking-wider mb-3 text-cyan-400">
          LEADERBOARD
        </h1>
      </div>

      <!-- GAMES FILTER -->
      <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-10">
        <button
          v-for="game in games"
          :key="game.slug"
          @click="selectGame(game.slug)"
          class="px-4 sm:px-5 py-2 rounded-xl border transition-all duration-300 uppercase tracking-wider text-xs sm:text-sm"
          :class="
            game.slug === selectedGame
              ? 'border-cyan-400 text-cyan-400 bg-cyan-400/10 shadow-[0_0_20px_rgba(34,211,238,0.4)]'
              : 'border-[#1E2A52] text-gray-400 hover:border-cyan-400 hover:text-cyan-400'
          "
        >
          {{ game.name }}
        </button>
      </div>

      <!-- TOP 3 -->
      <div
        v-if="topThree.length"
        class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-end mb-10"
      >
        <!-- FIRST -->
        <div
          v-if="topThree[0]"
          class="order-1 sm:order-2 bg-[#0B1020] border border-yellow-400/40 rounded-2xl p-4 sm:p-6 text-center h-auto sm:h-80 flex flex-col justify-center shadow-[0_0_30px_rgba(250,204,21,0.3)]"
        >
          <div class="text-xl sm:text-4xl mb-2">👑</div>

          <div
            class="w-20 sm:w-28 aspect-square rounded-full overflow-hidden mx-auto mb-4 flex items-center justify-center bg-cyan-500/10 border border-cyan-400"
          >
            <img
              v-if="topThree[0].user.avatar"
              :src="`${BASE_URL}/storage/${topThree[0].user.avatar}`"
              class="w-full h-full object-cover"
            />
            <span v-else class="text-cyan-300 text-4xl sm:text-6xl font-black">
              {{ topThree[0].user.name.charAt(0).toUpperCase() }}
            </span>
          </div>

          <h2 class="text-2xl sm:text-5xl font-black text-yellow-400 mb-1">#1</h2>
          <p class="text-lg sm:text-2xl font-black mb-1">{{ topThree[0].user.name }}</p>
          <p class="text-yellow-400 text-xl sm:text-3xl font-black">{{ topThree[0].score }}</p>
        </div>

        <!-- SECOND -->
        <div
          v-if="topThree[1]"
          class="order-2 sm:order-1 bg-[#0B1020] border border-pink-500/40 rounded-2xl p-4 sm:p-6 text-center h-auto sm:h-72 flex flex-col justify-center"
        >
          <div
            class="w-16 sm:w-24 aspect-square rounded-full overflow-hidden mx-auto mb-4 flex items-center justify-center bg-pink-500/10 border border-pink-400"
          >
            <img
              v-if="topThree[1].user.avatar"
              :src="`${BASE_URL}/storage/${topThree[1].user.avatar}`"
              class="w-full h-full object-cover"
            />
            <span v-else class="text-pink-300 text-3xl sm:text-5xl font-black">
              {{ topThree[1].user.name.charAt(0).toUpperCase() }}
            </span>
          </div>

          <h2 class="text-2xl sm:text-4xl font-black text-gray-300 mb-1">#2</h2>
          <p class="text-lg sm:text-xl font-bold mb-1">{{ topThree[1].user.name }}</p>
          <p class="text-pink-400 text-xl sm:text-2xl font-black">{{ topThree[1].score }}</p>
        </div>

        <!-- THIRD -->
        <div
          v-if="topThree[2]"
          class="order-3 sm:order-3 bg-[#0B1020] border border-cyan-500/40 rounded-2xl p-4 sm:p-6 text-center h-auto sm:h-64 flex flex-col justify-center"
        >
          <div
            class="w-16 sm:w-24 aspect-square rounded-full overflow-hidden mx-auto mb-4 flex items-center justify-center bg-cyan-500/10 border border-cyan-400"
          >
            <img
              v-if="topThree[2].user.avatar"
              :src="`${BASE_URL}/storage/${topThree[2].user.avatar}`"
              class="w-full h-full object-cover"
            />
            <span v-else class="text-cyan-300 text-3xl sm:text-5xl font-black">
              {{ topThree[2].user.name.charAt(0).toUpperCase() }}
            </span>
          </div>

          <h2 class="text-2xl sm:text-4xl font-black text-orange-400 mb-1">#3</h2>
          <p class="text-lg sm:text-xl font-bold mb-1">{{ topThree[2].user.name }}</p>
          <p class="text-cyan-400 text-xl sm:text-2xl font-black">{{ topThree[2].score }}</p>
        </div>
      </div>

      <!-- FULL RANKING -->
      <div
        class="bg-[#0B1020] border border-[#1E2A52] rounded-3xl overflow-hidden max-w-5xl mx-auto"
      >
        <!-- HEADER -->
        <div
          class="flex flex-col sm:flex-row gap-3 sm:gap-0 justify-between items-center p-4 border-b border-[#1E2A52]"
        >
          <h2 class="text-lg sm:text-xl uppercase tracking-widest text-cyan-400">Full Rankings</h2>

          <input
            v-model="search"
            type="text"
            placeholder="Search player..."
            class="bg-[#050816] border border-[#1E2A52] rounded-xl px-4 py-2 outline-none focus:border-cyan-400 text-sm w-full sm:w-auto"
          />
        </div>

        <!-- TABLE WRAPPER -->
        <div class="overflow-x-auto">
          <table class="w-full min-w-150">
            <thead class="bg-[#101935] text-[#7AA2FF] text-xs sm:text-sm">
              <tr class="text-left">
                <th class="p-3 sm:p-4">RANK</th>
                <th class="p-3 sm:p-4">PLAYER</th>
                <th class="p-3 sm:p-4 text-right">SCORE</th>
                <th class="p-3 sm:p-4 text-right">GAME</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="(score, index) in paginatedScores"
                :key="score.id"
                class="border-t border-[#16213D] hover:bg-white/5 transition"
              >
                <!-- RANK -->
                <td
                  class="p-3 sm:p-4 font-black text-lg sm:text-xl"
                  :class="{
                    'text-yellow-400': index + startIndex === 0,
                    'text-gray-300': index + startIndex === 1,
                    'text-orange-400': index + startIndex === 2,
                  }"
                >
                  {{ index + 1 + startIndex }}
                </td>

                <!-- PLAYER -->
                <td class="p-3 sm:p-4">
                  <div class="flex items-center gap-2 sm:gap-3">
                    <div
                      class="w-10 h-10 sm:w-12 sm:h-12 rounded-full overflow-hidden flex items-center justify-center bg-cyan-500/10 border border-cyan-400"
                    >
                      <img
                        v-if="score.user.avatar"
                        :src="`${BASE_URL}/storage/${score.user.avatar}`"
                        class="w-full h-full object-cover"
                      />
                      <span v-else class="text-cyan-300 font-black text-base sm:text-lg">
                        {{ score.user.name.charAt(0).toUpperCase() }}
                      </span>
                    </div>

                    <div>
                      <p class="font-bold text-base sm:text-lg">
                        {{ score.user.name }}
                      </p>

                      <p class="text-gray-400 text-xs">
                        {{ currentGameName }}
                      </p>
                    </div>
                  </div>
                </td>

                <!-- SCORE -->
                <td class="p-3 sm:p-4 text-right text-cyan-400 font-black text-xl sm:text-2xl">
                  {{ score.score }}
                </td>

                <!-- GAME -->
                <td class="p-3 sm:p-4 text-right text-gray-400 text-xs sm:text-sm">
                  {{ currentGameName }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- EMPTY -->
        <div v-if="!filteredScores.length" class="p-10 text-center text-gray-500">
          Aucun score trouvé
        </div>

        <!-- PAGINATION -->
        <div class="flex flex-wrap justify-center items-center gap-3 sm:gap-4 py-6">
          <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="px-3 sm:px-4 py-2 rounded-lg border border-[#1E2A52] text-gray-400 hover:text-cyan-400 hover:border-cyan-400 disabled:opacity-30 disabled:cursor-not-allowed text-sm"
          >
            Prev
          </button>

          <div class="flex flex-wrap justify-center gap-2">
            <button
              v-for="page in totalPages"
              :key="page"
              @click="goToPage(page)"
              class="px-3 py-1 rounded-lg border text-xs sm:text-sm transition"
              :class="
                page === currentPage
                  ? 'border-cyan-400 text-cyan-400 bg-cyan-400/10'
                  : 'border-[#1E2A52] text-gray-400 hover:text-cyan-400 hover:border-cyan-400'
              "
            >
              {{ page }}
            </button>
          </div>

          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-3 sm:px-4 py-2 rounded-lg border border-[#1E2A52] text-gray-400 hover:text-cyan-400 hover:border-cyan-400 disabled:opacity-30 disabled:cursor-not-allowed text-sm"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import axios from 'axios'
import NavView from './NavView.vue'

const API_URL = import.meta.env.VITE_API_URL
const BASE_URL = API_URL.replace('/api', '')

const games = ref([])
const selectedGame = ref(null)

const currentScores = ref([])
const currentGameName = ref('')

const search = ref('')

/* LOAD SCORES */
const loadAllScores = async () => {
  const res = await axios.get(`${API_URL}/scores/all`)
  games.value = res.data

  if (games.value.length > 0) {
    selectGame(games.value[0].slug)
  }
}

/* SELECT GAME */
const selectGame = (slug) => {
  selectedGame.value = slug

  const game = games.value.find((game) => game.slug === slug)

  if (game) {
    currentScores.value = game.scores
    currentGameName.value = game.name
    currentPage.value = 1
  }
}

/* TOP 3 */
const topThree = computed(() => currentScores.value.slice(0, 3))

/* SEARCH */
const filteredScores = computed(() => {
  if (!search.value.trim()) return currentScores.value

  const value = search.value.toLowerCase()
  return currentScores.value.filter((score) => score.user.name.toLowerCase().includes(value))
})

/* PAGINATION */
const currentPage = ref(1)
const itemsPerPage = 20

const totalPages = computed(() => Math.ceil(filteredScores.value.length / itemsPerPage))

const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage)

const paginatedScores = computed(() =>
  filteredScores.value.slice(startIndex.value, startIndex.value + itemsPerPage),
)

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const goToPage = (page) => {
  currentPage.value = page
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(() => loadAllScores())
</script>
