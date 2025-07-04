<!-- LoginPage.vue -->

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
        <form
            @submit.prevent="login"
            class="bg-white shadow-lg rounded p-8 w-full max-w-md space-y-4"
        >
            <h2 class="text-2xl font-bold text-blue-700 text-center mb-6">
                Connexion
            </h2>

            <!-- Message d'erreur -->
            <div v-if="error" class="bg-red-100 text-red-700 p-3 rounded">
                {{ error }}
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Email</label
                >
                <input
                    v-model="form.email"
                    type="email"
                    required
                    class="w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <!-- Mot de passe -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Mot de passe</label
                >
                <input
                    v-model="form.password"
                    type="password"
                    required
                    class="w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <!-- Bouton -->
            <button
                type="submit"
                class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition-colors"
            >
                Se connecter
            </button>

            <!-- Lien vers inscription -->
            <p class="text-center mt-4 text-sm">
                Pas encore inscrit ?
                <router-link
                    to="/register"
                    class="text-blue-600 hover:underline"
                    >S'inscrire</router-link
                >
            </p>
        </form>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();
const form = ref({ email: "", password: "" });
const error = ref("");

const login = async () => {
    try {
        const res = await axios.post("/api/login", form.value);
        const { token, user } = res.data;

        // Stockage local
        localStorage.setItem("token", token);
        localStorage.setItem("user", JSON.stringify(user));
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

        // Redirection selon le rôle
        if (user.role === "admin") {
            router.push("/dashboardLoc");
        } else if (user.role === "proprietaire") {
            router.push("/dashboard");
        } else {
            router.push("/");
        }
    } catch (e) {
        error.value =
            e.response?.data?.message || "Erreur lors de la connexion";
    }
};
</script>
