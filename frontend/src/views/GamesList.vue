<template>
  <section class="min-h-screen bg-[#050816] text-white px-4 py-10 select-none">
    <div class="max-w-7xl mx-auto text-center">
      <h1 class="text-2xl sm:text-4xl font-black tracking-wider text-cyan-400 mb-10">
        CHOISIS UN JEU
      </h1>

      <div v-if="games.length > 0">
        <!-- CAROUSEL -->
        <div
          class="relative w-full overflow-hidden py-6 sm:py-10"
          @touchstart="startDrag"
          @touchmove="onDrag"
          @touchend="endDrag"
        >
          <div
            class="flex justify-center items-center gap-6 sm:gap-10 transition-transform duration-300"
          >
            <!-- LEFT PREVIEW -->
            <div class="cursor-pointer hidden sm:block -mr-20" @click="prev">
              <div
                class="w-40 h-24 sm:w-52 sm:h-32 opacity-50 scale-90 bg-[#0B1020] border border-[#1E2A52] rounded-2xl overflow-hidden hover:opacity-80 transition"
              >
                <img v-if="leftGame" :src="leftGame.gif" class="w-full h-full object-cover" />
              </div>
            </div>

            <!-- CENTER GAME -->
            <div class="cursor-pointer z-10" @click="openGame(centerGame.slug)">
              <p v-if="centerGame" class="mb-3 sm:mb-4 text-cyan-300 font-bold text-xl sm:text-3xl">
                {{ centerGame.name }}
              </p>

              <div
                class="w-64 h-40 sm:w-96 sm:h-60 md:w-105 md:h-65 bg-[#0B1020] border border-cyan-400 rounded-2xl shadow-[0_0_55px_rgba(34,211,238,0.7)] overflow-hidden transition-transform duration-200 hover:scale-105 mx-auto"
              >
                <img v-if="centerGame" :src="centerGame.gif" class="w-full h-full object-cover" />
              </div>
            </div>

            <!-- RIGHT PREVIEW -->
            <div class="cursor-pointer hidden sm:block -ml-20" @click="next">
              <div
                class="w-40 h-24 sm:w-52 sm:h-32 opacity-50 scale-90 bg-[#0B1020] border border-[#1E2A52] rounded-2xl overflow-hidden hover:opacity-80 transition"
              >
                <img v-if="rightGame" :src="rightGame.gif" class="w-full h-full object-cover" />
              </div>
            </div>
          </div>
        </div>

        <!-- DOTS -->
        <div class="flex justify-center gap-2 sm:gap-3 mt-4 sm:mt-6">
          <div
            v-for="(g, i) in games"
            :key="i"
            @click="goTo(i)"
            class="w-3 h-3 sm:w-4 sm:h-4 rounded-full cursor-pointer transition"
            :class="i === index ? 'bg-cyan-400 scale-125' : 'bg-gray-600 hover:bg-gray-500'"
          ></div>
        </div>
      </div>

      <p v-else class="text-gray-500 mt-10">Chargement des jeux...</p>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const games = ref([])
const index = ref(0)

const API_URL = import.meta.env.VITE_API_URL

/* DRAG MOBILE ONLY */
let startX = 0
let dragging = false

function startDrag(e) {
  dragging = true
  startX = e.touches[0].clientX
}

function onDrag(e) {
  if (!dragging) return

  const diff = e.touches[0].clientX - startX

  if (diff > 80) {
    prev()
    dragging = false
  } else if (diff < -80) {
    next()
    dragging = false
  }
}

function endDrag() {
  dragging = false
}

/* FETCH GAMES */
onMounted(async () => {
  const response = await axios.get(`${API_URL}/games`)
  games.value = response.data
})

/* GAME ACCESSORS */
const centerGame = computed(() => (games.value.length ? games.value[index.value] : null))

const leftGame = computed(() =>
  games.value.length
    ? games.value[(index.value - 1 + games.value.length) % games.value.length]
    : null,
)

const rightGame = computed(() =>
  games.value.length ? games.value[(index.value + 1) % games.value.length] : null,
)

/* NAVIGATION */
function next() {
  if (!games.value.length) return
  index.value = (index.value + 1) % games.value.length
}

function prev() {
  if (!games.value.length) return
  index.value = (index.value - 1 + games.value.length) % games.value.length
}

function goTo(i) {
  if (!games.value.length) return
  index.value = i
}

function openGame(slug) {
  router.push(`/game/${slug}`)
}
</script>
