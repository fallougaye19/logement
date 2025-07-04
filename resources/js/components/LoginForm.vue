<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <form @submit.prevent="submit" class="bg-white shadow-lg p-8 rounded-xl w-full max-w-sm space-y-6">
            <h1 class="text-2xl font-bold text-center text-gray-800">Connexion</h1>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input v-model="form.email" type="email" id="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="email@example.com" required />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input v-model="form.password" type="password" id="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="••••••••" required />
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-md font-semibold hover:bg-blue-700 transition">
                Se connecter
            </button>

            <p v-if="error" class="text-sm text-red-500 text-center">{{ error }}</p>

            <p class="text-sm text-center text-gray-600">
                Pas encore de compte ?
                <router-link to="/register" class="text-blue-600 hover:underline font-medium">
                    Créer un compte
                </router-link>
            </p>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = ref({ email: '', password: '' })
const error = ref('')

const submit = async () => {
    error.value = ''
    try {
        const { data } = await axios.post('/api/auth/login', form.value)
        localStorage.setItem('token', data.token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
        router.push('/dashboard')
    } catch {
        error.value = 'Identifiants invalides'
    }
}
</script>
