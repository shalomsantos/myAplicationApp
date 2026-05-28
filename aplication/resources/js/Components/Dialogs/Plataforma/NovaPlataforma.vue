<template>
    <Dialog
        v-model="model"
        width="40vw"
        title="Nova Plataforma"
        @onCloseDialog="((inputPlataforma=null), (inputDescricao=null), (model = false))"
    >
        <v-row>
            <v-col cols="12">
                <v-text-field
                    v-model="inputPlataforma"
                    label="Nome da plataforma*"
                    variant="outlined"
                    density="compact"
                    color="green-darken-3"
                    hide-details
                    clearable
                ></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-textarea
                    v-model="inputDescricao"
                    label="Descrição"
                    variant="outlined"
                    density="compact"
                    color="green-darken-3"
                    :counter="255"
                    maxlength="255"
                    rows="3"
                    max-rows="3"
                    auto-grow
                ></v-textarea>
            </v-col>
            <v-col cols="12">
                <v-btn
                    class="text-none"
                    color="green-darken-1"
                    prepend-icon="mdi-layers-plus"
                    text="Salvar"
                    @click.prevent="insertEvent"
                />
            </v-col>
        </v-row>
    </Dialog>
</template>

<script setup>
import Dialog from "../Dialog.vue";
import { ref } from "vue";

const model = defineModel();

const inputPlataforma = ref(null);
const inputDescricao = ref(null);

const emit = defineEmits(["insertProcess"]);

function insertEvent() {
    let plataforma = {
        nome: inputPlataforma.value,
        descricao: inputDescricao.value,
    };
    inputPlataforma.value = null;
    inputDescricao.value = null;
    model.value = false;
    emit("insertProcess", plataforma);
}
</script>

<style scoped></style>
