<template>
    <Dialog
        v-model="model"
        title="Novo fornecedor"
        width="60vw"
        @onCloseDialog="(
            (razao_social=null),
            (nome_fantasia=null),
            (opcaoSelecionada=null),
            (cpf=null),
            (cnpj=null),
            (endereco=null),
            (contato=null),
            (email=null),
            (descricao=null),
            (model=false))"
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
                    @click.prevent="insertProcess"
                />
            </v-col>
        </v-row>
    </Dialog>
</template>

<script setup>
import Dialog from "../Dialog.vue";
import { useFeedback } from "@/Composables/useFeedback.js";
import { useFornecedor } from "@/Composables/useFornecedor.js";
import { ref } from "vue";

const model = defineModel();
const { store } = useFornecedor();
const { trigger } = useFeedback();
const emit = defineEmits(["end"]);

const razao_social = ref(null);
const nome_fantasia = ref(null);
const opcaoSelecionada = ref("1");
const cpf = ref(null);
const cnpj = ref(null);
const endereco = ref(null);
const contato = ref(null);
const email = ref(null);
const descricao = ref(null);

async function insertProcess() {
    let validNomeFantasia = nome_fantasia.value == null || nome_fantasia.value.trim() == "";
    let validRazaoSocial = razao_social.value == null || razao_social.value.trim() == "";
    if (validNomeFantasia || validRazaoSocial) {
        trigger(
            "Por favor, informe a razão social ou apenas o nome fantasia.",
            "warning",
        );
        return;
    }
    if (opcaoSelecionada.value === "1") {
        if (cpf.value == null || cpf.value.trim() == "") {
            trigger("Por favor, informe um CPF.", "warning");
            return;
        }
    } else if (opcaoSelecionada.value === "2") {
        if (cnpj.value == null || cnpj.value.trim() == "") {
            trigger("Por favor, informe um CNPJ.", "warning");
            return;
        }
    }
    if (endereco.value == null || endereco.value.trim() == "") {
        trigger("Por favor, informe um endereço.", "warning");
        return;
    }
    let validContato = contato.value == null || contato.value.trim() == "";
    let validEmail = email.value == null || email.value.trim() == "";
    if (validContato || validEmail) {
        trigger("Por favor, informe ao menos um meio de contato.", "warning");
        return;
    }
    let data = {
        nome_fantasia: nome_fantasia.value,
        razao_social: razao_social.value,
        descricao: descricao.value,
        cpf: opcaoSelecionada.value === "1" ? cpf.value : null,
        cnpj: opcaoSelecionada.value === "2" ? cnpj.value : null,
        endereco: endereco.value,
        contato: contato.value,
        email: email.value,
    };
    let res;

    try {
        res = await store(data);
    } catch (error) {
        trigger(error, "erro");
    } finally {
        model.value = false
        emit("end", res);
    }
}
</script>

<style scoped></style>
