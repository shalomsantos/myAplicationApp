<template>
    <Dialog
        v-model="model"
        title="Novo fornecedor"
        width="60vw"
        @onCloseDialog="$emit('onCloseDialog')"
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
                            label="CPF"
                            density="compact"
                            color="green-darken-3"
                            variant="outlined"
                            hide-details="auto"
                            clearable
                        ></v-text-field>
        
                        <v-text-field
                            v-else
                            v-model="cnpj"
                            label="CNPJ"
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
import { ref } from "vue";

const model = defineModel();

const opcaoSelecionada = ref('1')
const inputFornecedorRazaoSocial = ref("");
const razao_social = ref("Anthony e Danilo Consultoria Financeira Ltda");
const nome_fantasia = ref("Sônia e Isaac Pizzaria Ltda");
const cpf = ref("999.999.999-99");
const cnpj = ref("99.999.999/9999-99");
const endereco = ref("Rua julio silva, 261 - Praia do futuro - CE.");
const contato = ref("85 9 5241-5241");
const email = ref("shalomsantos1234@gmail.com");
const inputDescricao = ref("lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec auctor, nisl eget ultricies lacinia, nunc nisl aliquam nisl, eget aliquam nunc nisl eget nunc. Donec auctor, nisl eget ultricies lacinia, nunc nisl aliquam nisl, eget aliquam nunc nisl eget nunc.");


function insertProcess(){

}
</script>

<style lang="scss" scoped></style>
