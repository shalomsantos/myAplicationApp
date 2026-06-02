<template>
    <Dialog
        v-model="model"
        width="40vw"
        title="Novo projeto"
        @onCloseDialog="
            ((model = false),
            (inputProjeto = null),
            (tipoProjetosValue = null),
            (inputDescricao = null))
        "
    >
        <v-row>
            <v-col cols="12">
                <v-text-field
                    v-model="inputProjeto"
                    label="Nome*"
                    variant="outlined"
                    density="compact"
                    color="green-darken-3"
                    hide-details
                    clearable
                ></v-text-field>
            </v-col>
            <v-col cols="12" class="d-flex ga-2 align-center">
                {{ tipoProjetosOptions }}
                <v-combobox
                    v-model="tipoProjetosValue"
                    :items="tipoProjetosOptions"
                    item-title="nome"
                    item-value="id"
                    :return-object="false"
                    label="Escolher ou inserir tipo projeto"
                    variant="outlined"
                    density="compact"
                    color="green-darken-3"
                    hide-details
                    clearable
                >
                    <template v-slot:prepend-item>
                        <v-divider></v-divider>
                        <v-list-item
                            title="Não encontrou?"
                            subtitle="Clique para cadastrar um novo"
                            prepend-icon="mdi-plus-circle-outline"
                            color="primary"
                            @click.prevent="dialogNovoTipoProjeto = true"
                        >
                        </v-list-item>
                        <v-divider></v-divider>
                    </template>

                    <template v-slot:no-data>
                        <v-list-item
                            title="Nenhum item encontrado"
                            subtitle="Clique aqui para criar um novo"
                            @click.prevent="dialogNovoTipoProjeto = true"
                        ></v-list-item>
                    </template>
                </v-combobox>
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
            <v-col cols="12" class="pt-3">
                <v-btn
                    class="text-none"
                    color="green-darken-1"
                    prepend-icon="mdi-clipboard-check"
                    text="Salvar"
                    :disabled="carregando"
                    @click.prevent="insert"
                />
            </v-col>
        </v-row>

        <NovoTipoProjeto
            v-model="dialogNovoTipoProjeto"
            width="40vw"
            @insertProcess="insertTipoProjeto"
        />
    </Dialog>
</template>

<script setup>
import NovoTipoProjeto from "./NovoTipoProjeto.vue";
import { useProjeto } from "@/Composables/useProjeto";
import { useTipoProjeto } from "@/Composables/useTipoProjeto";
import { useFeedback } from "@/Composables/useFeedback";
import { ref } from "vue";
import Dialog from "../Dialog.vue";

const model = defineModel();

const props = defineProps({
    tiposProjetos: Object
});
const emit = defineEmits(["end"]);

const { carregando, store } = useProjeto();
const { trigger } = useFeedback();
const { index } = useTipoProjeto();

const inputProjeto = ref(null);
const inputDescricao = ref(null);

const tipoProjetosValue = ref(null);
const tipoProjetosOptions = ref(props.tiposProjetos ?? []);

// Dialog
const dialogNovoTipoProjeto = ref(false);

// Functions
async function carregandoTodosTiposProjetos() {
    const res = await index();
    tipoProjetosOptions.value = res
}
async function insert() {

    if (inputProjeto.value == null) { trigger('O nome deve ser preenchido.', 'warning'); return; }
    if (tipoProjetosValue.value == null) { trigger('O tipo do projeto deve ser preenchido.', 'warning'); return; }
    try {
        const payload = {
            nome: inputProjeto.value,
            tipo_projeto_id: tipoProjetosValue.value,
            descricao: inputDescricao.value,
        };
        
        const res = await store(payload)
        if (res.success) {
            emit("end", res.message);
        } else {
            trigger(res.message || res, "error");
        }
    } catch (error) {
        emit("end", err.response?.data);
    }
}
async function insertTipoProjeto(tipoProjeto) {
    try {
        const res = await axios.post(route("tipoprojeto.store"), { nome: tipoProjeto.nome })
            if (res.data.success) {
                trigger(res.data.message, 'success');
            } else {
                trigger(res.data.message, 'error');
            }
    } catch (err) {
        trigger("Axios: " + err + ". Data: " + res.data.message + ".", 'error')
    } finally{
        tipoProjetosOptions.value = [];
        await carregandoTodosTiposProjetos();
    }
}
</script>

<style scoped></style>
