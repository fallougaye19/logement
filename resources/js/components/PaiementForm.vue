<template>
    <div class="p-8 bg-gray-100 min-h-screen flex justify-center items-start">
        <form
            @submit.prevent="submit"
            class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-xl space-y-6"
        >
            <h2 class="text-2xl font-bold text-blue-800 text-center">
                {{ editing ? "Modifier le paiement" : "Nouveau paiement" }}
            </h2>

            <!-- Contrat -->
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1"
                    >Contrat</label
                >
                <select v-model="form.contrat_id" class="input" required>
                    <option disabled value="">-- Sélectionner --</option>
                    <option
                        v-for="contrat in contrats"
                        :key="contrat.id"
                        :value="contrat.id"
                    >
                        Contrat #{{ contrat.id }} -
                        {{ formatePrice(contrat.montant_caution) }}
                    </option>
                </select>
            </div>

            <!-- Montant -->
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1"
                    >Montant</label
                >
                <input
                    v-model.number="form.montant"
                    type="number"
                    step="any"
                    class="input"
                    placeholder="Ex : 75000"
                    required
                />
            </div>

            <!-- Statut -->
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1"
                    >Statut</label
                >
                <select v-model="form.statut" class="input" required>
                    <option disabled value="">-- Sélectionner --</option>
                    <option value="payé">Payé</option>
                    <option value="en attente">En attente</option>
                    <option value="retard">Retard</option>
                </select>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1"
                        >Date d'échéance</label
                    >
                    <input
                        v-model="form.date_echeance"
                        type="date"
                        class="input"
                        required
                    />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1"
                        >Date de paiement</label
                    >
                    <input
                        v-model="form.date_paiement"
                        type="date"
                        class="input"
                    />
                </div>
            </div>

            <!-- Bouton -->
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-semibold py-2 rounded-lg shadow"
            >
                {{
                    editing
                        ? "Enregistrer les modifications"
                        : "Ajouter le paiement"
                }}
            </button>

            <!-- Message -->
            <p
                v-if="message"
                class="text-sm text-center"
                :class="{ 'text-green-600': success, 'text-red-600': !success }"
            >
                {{ message }}
            </p>
        </form>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from "vue";
import axios from "axios";

const props = defineProps({
    paiement: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["created", "updated"]);

// État du formulaire
const initialForm = {
    contrat_id: "",
    montant: "",
    statut: "",
    date_echeance: "",
    date_paiement: "",
};

const form = ref({ ...initialForm });

if (props.paiement) {
    form.value = { ...props.paiement };
}

const contrats = ref([]);

// Chargement des contrats
const fetchContrats = async () => {
    try {
        const res = await axios.get("/api/contrats");
        contrats.value = res.data;
    } catch (err) {
        console.error("Erreur lors du chargement des contrats", err);
    }
};

fetchContrats();

// Mode édition
const editing = ref(!!props.paiement);

const message = ref("");
const success = ref(false);

const submit = async () => {
    try {
        let response;
        if (editing.value) {
            response = await axios.put(
                `/api/paiements/${props.paiement.id}`,
                form.value
            );
            emit("updated", response.data);
        } else {
            response = await axios.post("/api/paiements", form.value);
            emit("created", response.data);
        }

        message.value = editing.value
            ? "Paiement mis à jour."
            : "Paiement ajouté.";
        success.value = true;
    } catch (e) {
        message.value = "Erreur lors de l'enregistrement.";
        success.value = false;
        console.error(e);
    }
};
</script>

<style scoped>
.input {
    @apply w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500;
}
</style>
