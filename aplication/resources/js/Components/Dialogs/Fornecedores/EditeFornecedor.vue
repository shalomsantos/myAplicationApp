<template>
    <Dialog
        v-model="model"
        title="Editar fornecedor"
        width="60vw"
        @onCloseDialog="(model = false)"
    >
         <v-row>
            <v-col cols="6">
                <v-text-field
                    v-model="razao_social"
                    label="Razão social"
                    density="compact"
                    color="green-darken-3"
                    variant="outlined"
                    hide-details="auto"
                    clearable
                ></v-text-field>
            </v-col>
            <v-col cols="6">
                <v-text-field
                    v-model="nome_fantasia"
                    label="Nome fantasia"
                    density="compact"
                    color="green-darken-3"
                    variant="outlined"
                    hide-details="auto"
                    clearable
                ></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-row align="end">
                    <v-col cols="4">
                        <v-radio-group v-model="opcaoSelecionada" inline>
                            <v-radio label="CPF" value="1"></v-radio>
                            <v-radio label="CNPJ" value="2"></v-radio>
                        </v-radio-group>

                        <v-text-field
                            v-if="opcaoSelecionada == 1"
                            v-model="cpf"
                            label="000.000.000-00"
                            density="compact"
                            color="green-darken-3"
                            variant="outlined"
                            hide-details="auto"
                            clearable
                        ></v-text-field>

                        <v-text-field
                            v-else
                            v-model="cnpj"
                            label="00.000.000/0000-00"
                            density="compact"
                            color="green-darken-3"
                            variant="outlined"
                            hide-details="auto"
                            clearable
                        ></v-text-field>
                    </v-col>
                    <v-col cols="8">
                        <v-text-field
                            v-model="endereco"
                            label="Endereço"
                            density="compact"
                            color="green-darken-3"
                            variant="outlined"
                            placeholder="Rua, número - bairro - cidade"
                            hide-details="auto"
                            clearable
                        ></v-text-field>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="4">
                <v-text-field
                    v-model="contato"
                    label="Contato"
                    density="compact"
                    color="green-darken-3"
                    variant="outlined"
                    hide-details="auto"
                    clearable
                ></v-text-field>
            </v-col>
            <v-col cols="8">
                <v-text-field
                    v-model="email"
                    label="E-mail"
                    density="compact"
                    color="green-darken-3"
                    variant="outlined"
                    hide-details="auto"
                    clearable
                ></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-textarea
                    v-model="descricao"
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
                    prepend-icon="mdi-check"
                    text="Salvar"
                    :loading="carregando"
                    @click.prevent="editProcess"
                />
            </v-col>
        </v-row>
    </Dialog>
</template>

<script setup>
import { useFornecedor } from "@/Composables/useFornecedor.js";
import Dialog from "../Dialog.vue";
import { ref, watch } from "vue";
import { useFeedback } from "@/Composables/useFeedback.js";

const model = defineModel();
const emit = defineEmits(["end"]);
const { update, carregando } = useFornecedor();
const { trigger } = useFeedback();

const props = defineProps({
    fornecedor: Object,
});

const fornecedorId = ref(null);
const razao_social = ref("");
const nome_fantasia = ref(null);
const opcaoSelecionada = ref(null);
const cpf = ref(null);
const cnpj = ref(null);
const endereco = ref(null);
const contato = ref(null);
const email = ref(null);
const descricao = ref(null);

watch(
    () => props.fornecedor,
    (novo) => {
        if (novo) {
            fornecedorId.value = props.fornecedor?.id;
            razao_social.value = props.fornecedor?.razao_social;
            nome_fantasia.value = props.fornecedor?.nome_fantasia;
            opcaoSelecionada.value = props.fornecedor?.cpf? "1" : "2";
            cpf.value = props.fornecedor?.cpf;
            cnpj.value = props.fornecedor?.cnpj;
            endereco.value = props.fornecedor?.endereco;
            contato.value = props.fornecedor?.contato;
            email.value = props.fornecedor?.email;
            descricao.value = props.fornecedor?.descricao;
        }
    },
    { immediate: true },
);

async function editProcess() {
    let data = {
        razao_social: razao_social.value,
        nome_fantasia: nome_fantasia.value,
        cpf: opcaoSelecionada.value === '1' ? cpf.value : null,
        cnpj: opcaoSelecionada.value === '2' ? cnpj.value : null,
        endereco: endereco.value,
        contato: contato.value,
        email: email.value,
        descricao: descricao.value,
    };
    let res;
    
    try {
        res = await update(fornecedorId.value, data);
    } catch (error) {
        trigger(error, 'error')
    } finally{
        model.value = false;
        emit('end', res)
    }
}
</script>

<style lang="scss" scoped></style>
