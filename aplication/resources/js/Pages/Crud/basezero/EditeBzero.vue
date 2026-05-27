<template>
    <DefaultLayout
        v-model="viewOption"
        title="Edição da base"
        :location="location"
    >
        <v-row dense>
            <v-col cols="12">
                <v-card :title="dados.projeto.nome" class="border-s-lg">
                    <template #item>
                        <p class="text-body-2 text-disabled">
                            {{ dados.descricao }}
                        </p>
                    </template>
                    <template #text>
                        <v-sheet class="d-flex ga-6">
                            <v-sheet>
                                <p class="text-body-2">Status</p>
                                <div>
                                    <p class="text-body-2 text-disabled">
                                        {{ dados.status.nome }}
                                    </p>
                                </div>
                            </v-sheet>
                            <v-sheet>
                                <p class="text-body-2">Ano</p>
                                <div>
                                    <p class="text-body-2 text-disabled">
                                        {{ dados.ano }}
                                    </p>
                                </div>
                            </v-sheet>
                            <v-sheet>
                                <p class="text-body-2">Criado em</p>
                                <div>
                                    <p class="text-body-2 text-disabled">
                                        {{ isDate(dados.created_at) }}
                                    </p>
                                </div>
                            </v-sheet>
                            <v-sheet>
                                <p class="text-body-2">Por</p>
                                <div>
                                    <p class="text-body-2 text-disabled">
                                        {{ dados.created_by.name }}
                                    </p>
                                </div>
                            </v-sheet>
                        </v-sheet>
                    </template>
                </v-card>
                <v-row class="py-3">
                    <v-col cols="4">
                        <v-select
                            v-model="valuePlataforma"
                            clearable
                            variant="outlined"
                            label="Selecione uma plataforma"
                            color="green-darken-3"
                            :items="plataformas"
                            density="compact"
                            item-title="nome"
                            item-value="id"
                            hide-details="auto"
                        >
                            <template #append>
                                <v-btn
                                    variant="flat"
                                    color="green-darken-3"
                                    icon="mdi-plus"
                                    class="rounded"
                                    density="comfortable"
                                    :disabled="
                                        valuePlataforma == null ? true : false
                                    "
                                    @click.prevent="associarPlataforma"
                                />
                            </template>
                        </v-select>
                    </v-col>
                </v-row>
            </v-col>
            <v-col
                cols="12"
                v-if="dados && viewOption"
                v-for="(item, id) in dados.plataformas"
                :key="id"
            >
                <v-card v-bind="props" class="border-s-lg">
                    <template #title>
                        {{ item.nome }}
                        <v-divider></v-divider>
                    </template>
                    <template #item>
                        <v-sheet
                            class="d-flex flex-wrap ga-2 mt-2"
                            color="transparent"
                        >
                            <v-btn
                                icon="mdi-plus"
                                class="h-auto rounded"
                                min-height="160"
                                color="green-lighten-5"
                                @click.prevent="dialogAdicionarItem=true"
                            ></v-btn>
                            <v-card
                                v-for="itemPivot in item.itens_pivot"
                                :key="id"
                                min-width="300px"
                                color="green-lighten-5"
                                elevation="0"
                                border
                            >
                                <template #title>
                                    <v-sheet
                                        class="d-flex justify-space-between"
                                        color="transparent"
                                    >
                                        {{ itemPivot.subitem.nome }}
                                        <v-menu location="top">
                                            <template
                                                v-slot:activator="{ props }"
                                            >
                                                <v-btn
                                                    v-bind="props"
                                                    icon="mdi-dots-vertical"
                                                    variant="text"
                                                    density="compact"
                                                ></v-btn>
                                            </template>
                                            <v-list
                                                :items="obterItensMenu(itemPivot)"
                                                density="compact"
                                            />
                                        </v-menu>
                                    </v-sheet>
                                </template>
                                <template #subtitle>
                                    {{ itemPivot.item.nome }}
                                </template>
                                <template #text>
                                    {{
                                        itemPivot.fornecedor?.razao_social ??
                                        "Sem"
                                    }}
                                </template>
                                <template #actions>
                                    <v-sheet
                                        class="d-flex justify-space-between w-100"
                                        color="transparent"
                                    >
                                        <div class="d-flex ga-1">
                                            <p
                                                class="text-caption text-disabled"
                                            >
                                                R$
                                            </p>
                                            <p class="">
                                                {{
                                                    itemPivot.vl_unit_cot
                                                        .toString()
                                                        .replace(".", ",")
                                                }}
                                            </p>
                                        </div>
                                        <p>{{ itemPivot.qt_unidade_cot }}</p>
                                        <p>{{ itemPivot.qt_multip_uni_cot }}</p>
                                        <div class="d-flex ga-1">
                                            <p
                                                class="text-caption text-disabled"
                                            >
                                                R$
                                            </p>
                                            <p class="">
                                                {{
                                                    (
                                                        itemPivot.vl_unit_cot *
                                                        itemPivot.qt_unidade_cot *
                                                        itemPivot.qt_multip_uni_cot
                                                    ).toLocaleString("pt-BR", {
                                                        minimumFractionDigits: 2,
                                                    })
                                                }}
                                            </p>
                                        </div>
                                    </v-sheet>
                                </template>
                            </v-card>
                        </v-sheet>
                    </template>
                </v-card>
            </v-col>
            <v-col cols="12" v-else-if="dados && !viewOption">
                <v-row
                    no-gutters
                    class="bg-grey-lighten-4 pa-2 d-none d-md-flex"
                >
                    <v-col cols="2">Item</v-col>
                    <v-col cols="2">Subitem</v-col>
                    <v-col cols="2">Fornecedor</v-col>
                    <v-col cols="1" class="text-right">Vl. unit.</v-col>
                    <v-col cols="1" class="text-center">Qtd.</v-col>
                    <v-col cols="1" class="text-center">Mult.</v-col>
                    <v-col cols="1" class="text-right">Total</v-col>
                    <v-col class="text-right"></v-col>
                </v-row>

                <v-sheet
                    v-for="plataforma in dados.plataformas"
                    :key="plataforma.id"
                    class="border mb-3"
                >
                    <v-row no-gutters>
                        <v-col cols="12" class="d-flex align-center ga-3 bg-green-lighten-5">
                            <v-btn
                                icon="mdi-plus"
                                class="rounded"
                                density="comfortable"
                                color="green-lighten-5"
                                @click.prevent="dialogAdicionarItem=true"
                            ></v-btn>
                            <h4 class="text-green-darken-3">
                                <v-icon
                                    icon="mdi-layers-outline"
                                    start
                                ></v-icon>
                                {{ plataforma.nome }}
                            </h4>
                        </v-col>
                    </v-row>

                    <v-row
                        v-for="itemPivot in plataforma.itens_pivot"
                        :key="itemPivot.id"
                        no-gutters
                        class="bg-grey-lighten-4 align-center pa-2"
                    >
                        <v-col cols="12" md="2" class="text-truncate">
                            {{ itemPivot.item.nome }}
                        </v-col>
                        <v-col cols="12" md="2" class="text-truncate">
                            {{ itemPivot.subitem.nome }}
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                            class="text-truncate text-grey-darken-1"
                        >
                            {{ itemPivot.fornecedor?.razao_social }}
                        </v-col>
                        <v-col cols="3" md="1" class="text-right">
                            R$
                            {{
                                itemPivot.vl_unit_cot
                                    .toString()
                                    .replace(".", ",")
                            }}
                        </v-col>
                        <v-col cols="3" md="1" class="text-center">
                            {{ itemPivot.qt_unidade_cot }}
                        </v-col>
                        <v-col cols="3" md="1" class="text-center">
                            {{ itemPivot.qt_multip_uni_cot }}
                        </v-col>
                        <v-col cols="3" md="1" class="text-right">
                            R$
                            {{
                                (
                                    itemPivot.vl_unit_cot *
                                    itemPivot.qt_unidade_cot *
                                    itemPivot.qt_multip_uni_cot
                                ).toLocaleString("pt-BR", {
                                    minimumFractionDigits: 2,
                                })
                            }}
                        </v-col>
                        <v-col class="ps-4">
                            <v-menu location="top">
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        v-bind="props"
                                        icon="mdi-dots-vertical"
                                        variant="text"
                                        density="compact"
                                    ></v-btn>
                                </template>
                                <v-list 
                                    :items="obterItensMenu(itemPivot)"
                                    density="compact" 
                                />
                            </v-menu>
                        </v-col>
                    </v-row>
                </v-sheet>
            </v-col>
            <v-col cols="12" v-else>
                <EmptyData />
            </v-col>
        </v-row>
        <IncluirItem
            v-model="dialogAdicionarItem" 
            @incluirProcess="((dialogAdicionarItem=false))"
            @onCloseDialog="dialogAdicionarItem=false"
        />
        <EditarItem
            v-model="dialogEditarItem"
            :itemEdited="itemEdited"
            @incluirProcess="((dialogEditarItem=false))"
            @onCloseDialog="dialogEditarItem=false"
        />
    </DefaultLayout>
</template>

<script setup>
import IncluirItem from "@/Components/Dialogs/Bzero/IncluirItem.vue";
import EditarItem from "@/Components/Dialogs/Bzero/EditarItem.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { useFeedback } from "@/Composables/useFeedback";
import EmptyData from "@/Components/EmptyData.vue";
import { ref, computed } from "vue";
import axios from "axios";

const props = defineProps({
    bzero: Object,
    preferencias: Object,
    plataformas: Object,
});

const { trigger } = useFeedback();

const location = [
    { title: "Kronos", disabled: false, href: "/" },
    { title: "Base", disabled: true },
    { title: "Edição", disabled: true },
];

const viewOption          = ref(props.preferencias?.listagem_menu ?? 0);
const dados               = ref(props.bzero);
const valuePlataforma     = ref(null);
const plataformas         = computed(() => props.plataformas);
const itemEdited          = ref(null)
const dialogAdicionarItem = ref(null);
const dialogEditarItem    = ref(null);

const obterItensMenu = (item) => [
    {
        title: "Anexar",
        value: 1,
        props: {
            prependIcon: "mdi-tag",
            onClick: () => {
                console.log("Anexar no ID:", item); 
            }
        },
    },
    {
        title: "Editar",
        value: 2,
        props: {
            prependIcon: "mdi-pencil",
            onClick: () => {
                openEditeItem(item);
            }
        },
    },
];
function openEditeItem(item) {
    itemEdited.value = item;
    dialogEditarItem.value = true;
}

async function associarPlataforma() {
    await axios
        .post(route("associar.plataforma", { id: dados.value.id }), {
            plataforma_id: valuePlataforma.value,
        })
        .then((res) => {
            if (res.data.success) {
                dados.value = res.data.data;
                valuePlataforma.value = null;
                trigger(res.data.message, 'success')
                return;
            }
            trigger(res.data.message, 'error')
        })
        .catch((err) => {
            trigger(err.data.message, 'error')
        });
}
</script>

<style scoped>
.border-s-lg {
    border-color: #81c784 !important;
}
.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
