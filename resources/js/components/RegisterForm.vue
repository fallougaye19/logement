<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
        <form
            @submit.prevent="register"
            class="bg-white shadow-lg rounded p-8 w-full max-w-md space-y-4"
        >
            <h2 class="text-2xl font-bold text-blue-700 text-center mb-6">
                Inscription
            </h2>

            <div v-if="error" class="bg-red-100 text-red-700 p-3 rounded">
                {{ error }}
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Nom</label
                >
                <input v-model="form.nom" type="text" required class="w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
            </div>
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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Téléphone</label
                >
                <input v-model="form.telephone" type="tel" class="w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"
                    >CNI</label
                >
                <input v-model="form.cni" type="text" class="w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Rôle</label
                >
                <select v-model="form.role" class="w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="locataire">Locataire</option>
                    <option value="proprietaire">Propriétaire</option>
                </select>
            </div>
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

            <button
                type="submit"
                class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition-colors"
            >
                S’inscrire
            </button>

            <p class="text-center mt-4 text-sm">
                Déjà inscrit ?
                <router-link to="/login" class="text-blue-600 hover:underline"
                    >Se connecter</router-link
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
const form = ref({
    nom: "",
    email: "",
    telephone: "",
    cni: "",
    role: "locataire",
    password: "",
});
const error = ref("");

const register = async () => {
    try {
        const res = await axios.post("/api/register", form.value);
        const { token, user } = res.data;

        localStorage.setItem("token", token);
        localStorage.setItem("user", JSON.stringify(user));
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        router.push("/login");
    } catch (e) {
        error.value =
            e.response?.data?.message || "Erreur lors de l'inscription";
    }
};
</script>
