<template>
    <div class="p-4">
        <!-- Titre + bouton ajouter -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                Liste des utilisateurs
            </h2>
            <button @click="showForm = true" class="btn inline">Ajouter</button>
        </div>

        <!-- Overlay -->
        <div
            v-if="showForm"
            class="fixed inset-0 bg-black/60 z-40"
            @click="toggleForm"
        >
            <div class="flex items-center justify-center min-h-screen">
                <button
                    @click.stop="toggleForm"
                    class="absolute top-4 right-4 bg-white text-gray-700 hover:text-red-500 transition rounded-full p-2 shadow-lg z-50"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Formulaire -->
        <div
            v-if="showForm"
            class="bg-white p-6 rounded shadow-lg z-50 max-w-lg mx-auto fixed top-20 left-1/2 -translate-x-1/2"
        >
            <UtilisateurForm @created="handleCreated" />
        </div>

        <!-- Liste des utilisateurs -->
        <ul class="space-y-3 mt-6">
            <li
                v-for="utilisateur in utilisateurs"
                :key="utilisateur.id"
                class="bg-white p-4 rounded shadow flex justify-between items-start"
            >
                <div>
                    <h3 class="font-semibold text-blue-700">
                        {{ utilisateur.nom_utilisateur }} ({{
                            utilisateur.role
                        }})
                    </h3>
                    <p class="text-sm text-gray-600">{{ utilisateur.email }}</p>
                    <p class="text-xs text-gray-500">
                        Téléphone : {{ utilisateur.telephone }}
                    </p>
                </div>
                <div class="flex space-x-2">
                    <!-- Voir -->
                    <button
                        @click="viewUtilisateur(utilisateur)"
                        class="btn-icon text-green-600"
                    >
                        👁️
                    </button>

                    <!-- Modifier -->
                    <button
                        @click="editUtilisateur(utilisateur)"
                        class="btn-icon text-yellow-500"
                    >
                        ✏️
                    </button>

                    <!-- Supprimer -->
                    <button
                        @click="deleteUtilisateur(utilisateur.id)"
                        class="btn-icon text-red-600"
                    >
                        🗑️
                    </button>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
//import UtilisateurForm from "@/components/UtilisateurForm.vue";

const utilisateurs = ref([]);
const showForm = ref(false);

const toggleForm = () => {
    showForm.value = !showForm.value;
};

// const fetchUtilisateurs = async () => {
//     const { data } = await axios.get("/api/utilisateurs");
//     utilisateurs.value = data;
// };

// const handleCreated = () => {
//     fetchUtilisateurs();
//     showForm.value = false;
// };

// const viewUtilisateur = (utilisateur) => {
//     alert(`Nom : ${utilisateur.nom_utilisateur}\nEmail : ${utilisateur.email}`);
// };

// const editUtilisateur = (utilisateur) => {
//     alert(`Rediriger vers édition utilisateur #${utilisateur.id}`);
//     // À implémenter : formulaire de mise à jour prérempli
// };

// const deleteUtilisateur = async (id) => {
//     if (confirm("Voulez-vous supprimer cet utilisateur ?")) {
//         await axios.delete(`/api/utilisateurs/${id}`);
//         fetchUtilisateurs();
//     }
// };

// onMounted(fetchUtilisateurs);
</script>

<style scoped>
.btn {
    @apply bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition;
}
.btn-icon {
    @apply p-2 bg-gray-100 rounded hover:bg-gray-200 transition;
}
</style>
