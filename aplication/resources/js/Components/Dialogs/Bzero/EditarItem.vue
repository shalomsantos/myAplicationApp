<template>
    <Dialog
        v-model="model"
        :title="'Editar Item da plataforma: '+plataformaNome"
        width="50vw"
        @onCloseDialog="$emit('onCloseDialog')"
    >
        <v-row>
            <v-col cols="4">
                <v-select
                    v-model="valueItens"
                    label="Item"
                    :items="itens"
                    item-title="nome"
                    item-value="id"
                    variant="outlined"
                    density="compact"
                    hide-details="auto"
                    clearable
                ></v-select>
            </v-col>
            <v-col cols="4">
                <v-select
                    v-model="valueSubItens"
                    label="Subitem"
                    :items="subItens"
                    item-value="id"
                    item-title="nome"
                    variant="outlined"
                    density="compact"
                    hide-details="auto"
                    clearable
                ></v-select>
            </v-col>
            <v-col cols="4">
                <v-select
                    v-model="valueFornecedores"
                    label="Fornecedor"
                    :items="fornecedores"
                    item-value="id"
                    item-title="razao_social"
                    variant="outlined"
                    density="compact"
                    hide-details="auto"
                    clearable
                ></v-select>
            </v-col>
            <v-col cols="4">
                <v-text-field
                    v-model="valorUnitario"
                    label="Valor Unitário"
                    variant="outlined"
                    density="compact"
                    hide-details="auto"
                    @keypress="onlyNumbers"
                    prefix="R$"
                    clearable
                ></v-text-field>
            </v-col>
            <v-col cols="2">
                <v-text-field
                    type="number"
                    v-model.number="qt_unidade_cot"
                    label="Quantidade"
                    variant="outlined"
                    density="compact"
                    hide-details="auto"
                ></v-text-field>
            </v-col>
            <v-col cols="2">
                <v-text-field
                    type="number"
                    v-model.number="qt_multip_uni_cot"
                    label="Unidade"
                    variant="outlined"
                    density="compact"
                    hide-details="auto"
                ></v-text-field>
            </v-col>
            <v-col>
                <v-btn
                    class="text-none w-100"
                    color="green-darken-1"
                    size="large"
                    prepend-icon="mdi-update"
                    @click.prevent="updatePlataforma()"
                    >Atualizar</v-btn
                >
            </v-col>
        </v-row>
    </Dialog>
</template>

<script setup>
import Dialog from "@/Components/Dialogs/Dialog.vue";
import { ref, computed, watch } from "vue";

const model = defineModel();

const props = defineProps({
    itemEdited: Object,
})

const plataformaNome = ref('')
const valueItens = ref(null);
const itens = ref([]);

const valueSubItens = ref(null);
const subItens = ref([]);

const valueFornecedores = ref(null);
const fornecedores = ref([]);

const vl_unit_cot = ref(0);
const qt_unidade_cot = ref(1);
const qt_multip_uni_cot = ref(1);

watch(
    () => props.itemEdited,
    (novo) => {
        if (novo) {
            loadCombos()
            plataformaNome.value = novo.plataforma.nome ?? "";
            valueItens.value = novo.item.nome ?? "";
            valueSubItens.value = novo.subitem.nome ?? "";
            valueFornecedores.value = novo.fornecedor.nome_fantasia ?? "";
            vl_unit_cot.value = novo.vl_unit_cot
            qt_unidade_cot.value = novo.qt_unidade_cot
            qt_multip_uni_cot.value = novo.qt_multip_uni_cot
        }
    },
    { immediate: true },
);

async function loadCombos(){
    await carregandoTodosItens()
    await carregandoTodosSubitens()
    await carregandoTodosFornecedores()
}
const carregandoTodosItens = async () => {
    await axios
        .get(route("item.index"), { headers: { Accept: "application/json" } })
        .then((res) => {
            itens.value = res.data;
        })
        .catch((err) => {
            console.log(err)
        });
};
const carregandoTodosSubitens = async () => {
    await axios
        .get(route("subitem.index"), { headers: { Accept: "application/json" } })
        .then((res) => {
            subItens.value = res.data;
        })
        .catch((err) => {
            console.log(err)
        });
};
const carregandoTodosFornecedores = async () => {
    await axios
        .get(route("fornecedor.index"), { headers: { Accept: "application/json" } })
        .then((res) => {
            fornecedores.value = res.data.data || res.data;
        })
        .catch((err) => {
            console.log(err)
        });
};

const valorUnitario = computed({
    get() {
        return vl_unit_cot.value.toLocaleString("pt-BR", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    },
    set(newValue) {
        if (!newValue) {
            vl_unit_cot.value = 0;
            return;
        }
        let value = String(newValue).replace(/\D/g, "");
        vl_unit_cot.value = parseFloat(value) / 100;
    },
});
</script>

<style scoped></style>
