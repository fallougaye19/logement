<template>
    <div class="p-4">
        <!-- Titre -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Mon tableau de bord</h2>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <StatCard label="Chambre louée" :value="contratEnCours ? 'Oui' : 'Non'" color="blue" />
            <StatCard label="Contrat actif" :value="contratEnCours?.date_fin ? formatDate(contratEnCours.date_fin) : 'Aucun'" color="green" />
            <StatCard label="Problèmes signalés" :value="problemes.length" color="red" />
            <StatCard label="Rendez-vous à venir" :value="rendezVous.length" color="indigo" />
        </div>

        <!-- Chambre louée -->
        <div v-if="chambreLouee" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow mb-8">
            <div class="relative h-48 bg-gray-200">
                <img :src="chambreLouee.image_principale" alt="Image de la chambre" class="w-full h-full object-cover" />
            </div>
            <div class="p-4">
                <h3 class="text-lg font-semibold truncate">{{ chambreLouee.titre }}</h3>
                <p class="text-sm text-gray-600 mt-1">
                    {{ formatPrice(chambreLouee.prix) }} / mois
                </p>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">Type : {{ chambreLouee.type }}</span>
                    <span :class="{
                        'bg-green-100 text-green-800': contratEnCours,
                        'bg-red-100 text-red-800': !contratEnCours
                    }" class="px-2 py-1 rounded-full text-xs font-medium">
                        {{ contratEnCours ? "En location" : "Aucun contrat" }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Contrat -->
        <div v-if="contratEnCours" class="mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Détails de votre contrat</h3>
            <div class="bg-white shadow-md rounded p-6">
                <div class="space-y-3">
                    <div><label class="block text-sm font-medium text-gray-500">Date de début</label><p>{{ formatDate(contratEnCours.date_debut) }}</p></div>
                    <div><label class="block text-sm font-medium text-gray-500">Date de fin</label><p>{{ formatDate(contratEnCours.date_fin) }}</p></div>
                    <div><label class="block text-sm font-medium text-gray-500">Montant de caution</label><p>{{ formatPrice(contratEnCours.montant_caution) }}</p></div>
                    <div><label class="block text-sm font-medium text-gray-500">Périodicité</label><p>{{ contratEnCours.periodicite }}</p></div>
                </div>
            </div>
        </div>

        <!-- Problèmes -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Problèmes signalés</h3>
            <div v-if="problemes.length" class="space-y-4">
                <div v-for="probleme in problemes" :key="probleme.id" class="bg-white p-4 rounded shadow hover:shadow-md transition">
                    <div class="flex justify-between">
                        <div>
                            <h4 class="font-medium">{{ probleme.type }}</h4>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ probleme.description }}</p>
                        </div>
                        <span :class="{
                            'bg-green-100 text-green-800': probleme.resolu,
                            'bg-yellow-100 text-yellow-800': !probleme.resolu
                        }" class="inline-block px-2 py-1 rounded text-xs font-medium">
                            {{ probleme.resolu ? "Résolu" : "Non résolu" }}
                        </span>
                    </div>
                    <div class="mt-3 text-xs text-gray-500">
                        Signalé le {{ formatDate(probleme.created_at) }}
                    </div>
                </div>
            </div>
            <div v-else class="bg-gray-100 p-4 rounded text-gray-600 italic">
                Aucun problème signalé.
            </div>
        </div>

        <!-- Rendez-vous -->
        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Mes rendez-vous</h3>
            <div v-if="rendezVous.length" class="space-y-4">
                <div v-for="r in rendezVous" :key="r.id" class="bg-white p-4 rounded shadow hover:shadow-md transition">
                    <h4 class="font-medium">{{ r.type }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ r.description || "Aucune description" }}</p>
                    <div class="mt-3 flex justify-between items-center">
                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">Date : {{ formatDate(r.date_heure) }}</span>
                        <span :class="{
                            'text-green-600': r.statut === 'confirmé',
                            'text-yellow-600': r.statut === 'en attente',
                            'text-red-600': r.statut === 'annulé'
                        }" class="text-xs px-2 py-1 rounded-full font-medium capitalize">{{ r.statut }}</span>
                    </div>
                </div>
            </div>
            <div v-else class="bg-gray-100 p-4 rounded text-gray-600 italic">
                Aucun rendez-vous programmé.
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

// Données utilisateur connecté
const user = JSON.parse(localStorage.getItem("user"));

// Chambre louée (via contrat)
const chambreLouee = ref(null);

// Contrat en cours
const contratEnCours = ref(null);

// Liste des problèmes
const problemes = ref([]);

// Liste des rendez-vous
const rendezVous = ref([]);

// Charger les données du dashboard locataire
const fetchDashboardData = async () => {
    try {
        const [contratRes, problemeRes, rdvRes] = await Promise.all([
            axios.get(`/api/contrats?locataire_id=${user.id}`),
            axios.get(`/api/problemes?signale_par=${user.id}`),
            axios.get(`/api/rendez-vous?locataire_id=${user.id}`)
        ]);

        // Contrat actif
        contratEnCours.value = contratRes.data.find(c => c.statut === 'actif');

        if (contratEnCours.value) {
            const chambreRes = await axios.get(`/api/chambres/${contratEnCours.value.chambre_id}`);
            chambreLouee.value = chambreRes.data;
        }

        // Problèmes
        problemes.value = problemeRes.data;

        // Rendez-vous
        rendezVous.value = rdvRes.data;

    } catch (e) {
        console.error("Erreur lors du chargement des données", e);
    }
};

onMounted(fetchDashboardData);

// Méthodes utilitaires
const formatDate = (date) => {
    return new Date(date).toLocaleDateString("fr-FR");
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(price);
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
