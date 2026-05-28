<template>
    <Dialog 
        v-model="model" 
        title="Novo item" 
        width="30vw"
        @onCloseDialog="model=false"
    >
        <v-row>
            <v-col cols="12">
                <v-text-field
                    v-model="nome"
                    label="Nome do item*"
                    variant="outlined"
                    density="compact"
                    hide-details="auto"
                    clearable
                ></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-btn
                    class="text-none"
                    color="green-darken-1"
                    prepend-icon="mdi-invoice-edit"
                    text="Adicionar"
                    :loading="carregando"
                    :disabled="carregando"
                    @click.prevent="insertEvent"
                />
            </v-col>
        </v-row>
    </Dialog>
</template>

<script setup>
import Dialog from "@/Components/Dialogs/Dialog.vue";
import { useFeedback } from "@/Composables/useFeedback";
import { useItem } from "@/Composables/useItem";
import { ref } from "vue";

const model = defineModel();
const { store, carregando } = useItem();
const { trigger } = useFeedback();

const emit = defineEmits(['insertProcess']);

const nome = ref(null);

async function insertEvent() {
    if (nome.value == null) { trigger('O nome deve ser preenchido.', 'warning'); return; }
    const res = await store(nome.value);
    try {
        if(res.success){
            trigger(res.message, 'success');
            return;   
        }
        trigger(res.message, 'erro');
    } catch (err) {
        trigger(err, 'erro');
    } finally {
        emit('insertProcess');
    }
}
</script>

<style scoped></style>
