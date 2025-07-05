<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <form @submit.prevent="login" class="bg-white shadow-lg p-8 rounded-xl w-full max-w-sm space-y-6">
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

const login = async () => {
    error.value = ''  // Clear any previous error
    try {
        const { data } = await axios.post('/api/auth/login', form.value) // Use /api/auth/login to match your API route
        localStorage.setItem('token', data.token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`

        // Vérification si l'utilisateur a un rôle valide avant redirection
        const userRole = data.user.role;
        if (userRole === 'locataire') {
            router.push('/dashboardLoc')  // Redirection vers le dashboardLoc pour les locataires
        } else if (userRole === 'proprietaire') {
            router.push('/dashboard')  // Redirection vers le dashboard pour les propriétaires
        } else {
            error.value = 'Rôle utilisateur non valide'; // Si aucun rôle valide n'est trouvé
        }
    } catch (e) {
        error.value = e.response?.data?.message || "Erreur lors de la connexion"
    }
}
</script>
