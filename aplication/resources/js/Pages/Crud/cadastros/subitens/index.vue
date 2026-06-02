<template>
    <DefaultLayout 
        v-model="viewOption" 
        title="Subitens" 
        :location="location"
    >
        <v-row dense>
            <v-col cols="12">
                <v-row>
                    <v-col cols="4">
                        <v-text-field
                            v-model="search"
                            placeholder="Aperte a tecla enter para buscar..."
                            variant="outlined"
                            density="compact"
                            hide-details="auto"
                            color="green-darken-3"
                            clearable
                            append-inner-icon="mdi-magnify"
                            @keydown.enter="executarBusca"
                            @click:clear="carregandoTodasSubitens('')"
                        />
                    </v-col>
                    <v-col align="end">
                        <v-btn
                            class="text-none"
                            color="green-darken-1"
                            prepend-icon="mdi-plus"
                            text="Novo subitem"
                            @click.prevent="dialogNovoSubitem = true"
                        />
                    </v-col>
                </v-row>
            </v-col>
            <v-col
                cols="6"
                v-if="dados.length > 0 && viewOption"
                v-for="(item, id) in dados"
                :key="id"
            >
                <v-hover>
                    <template v-slot:default="{ isHovering, props }">
                        <v-card
                            v-bind="props"
                            :title="item.nome"
                            prepend-icon="mdi-sitemap"
                            class="border-s-lg"
                            :color="isHovering ? 'green-lighten-5' : undefined"
                            @click.prevent="
                                ((subitemSelecionado = item),
                                (dialogEditSubitem = true))
                            "
                        >
                            <template #subtitle>
                                <v-sheet class="d-flex justify-space-between align-end" color="transparent">
                                    <Avatar :nomeCompleto="item.created_by.name" />
                                    <p class="text-body-2 text-disabled">
                                        {{ isDate(item.created_at) }}
                                    </p>
                                </v-sheet>
                            </template>
                            <template #item>
                                <v-sheet
                                    class="d-flex flex-wrap ga-2 bg-transparent pt-3"
                                >
                                    <v-chip
                                        size="x-small"
                                        color="green"
                                        variant="flat"
                                        v-for="(
                                            fornecedor, id
                                        ) in item.fornecedores"
                                        :key="id"
                                    >
                                        {{ fornecedor.razao_social }}
                                    </v-chip>
                                </v-sheet>
                            </template>
                        </v-card>
                    </template>
                </v-hover>
            </v-col>
            <v-col cols="12" v-else-if="dados.length > 0 && !viewOption">
                <v-table
                    class="rounded-lg elevation-3"
                    density="compact"
                    striped="even"
                >
                    <thead>
                        <tr>
                            <th class="text-left">Nome</th>
                            <th class="text-left">Subitens</th>
                            <th class="text-left">Criado em</th>
                            <th class="text-left">Por</th>
                            <th class="text-left">***</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, id) in dados"
                            :key="id"
                            @click.prevent="
                                ((subitemSelecionado = item),
                                (dialogEditSubitem = true))
                            "
                        >
                            <td>{{ item.nome }}</td>
                            <td>
                                <v-chip
                                    v-for="(
                                        fornecedor, idx
                                    ) in item.fornecedores.slice(0, 2)"
                                    :key="idx"
                                    size="x-small"
                                    color="green-darken-1"
                                    variant="tonal"
                                    class="font-weight-bold"
                                >
                                    {{ fornecedor.razao_social }}
                                </v-chip>
                                <a
                                    v-if="item.fornecedores.length > 2"
                                    size="x-small"
                                    variant="text"
                                    class="text-grey-darken-1"
                                >
                                    +{{ item.fornecedores.length - 2 }} itens
                                </a>
                            </td>
                            <td>{{ isDate(item.created_at) }}</td>
                            <td>
                                <Avatar :nomeCompleto="item.created_by.name"/>
                            </td>
                            <td>
                                <v-btn
                                    class="text-none me-1"
                                    icon="mdi-delete"
                                    density="comfortable"
                                    color="red-lighten-2"
                                    @click.prevent="confirmation = true"
                                ></v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-col>
            <v-col cols="12" v-else>
                <EmptyData />
            </v-col>
        </v-row>

        <EditeSubitem
            v-model="dialogEditSubitem"
            :subitem="subitemSelecionado"
            @onCloseDialog="
                ((subitemSelecionado = null), (dialogEditSubitem = false))
            "
        />
        <NovoSubitem 
            v-model="dialogNovoSubitem" 
            @insertProcess="insertSubitem"
        />
    </DefaultLayout>
</template>

<script setup>
import EditeSubitem from "@/Components/Dialogs/Subitens/EditeSubitem.vue";
import { useFeedback } from "@/Composables/useFeedback";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EmptyData from "@/Components/EmptyData.vue";
import axios from "axios";
import { ref } from "vue";
import Avatar from "@/Components/Bases/Avatar.vue";
import NovoSubitem from "@/Components/Dialogs/Subitens/NovoSubitem.vue";
import { useSubitem } from "@/Composables/useSubitem";

const props = defineProps({
    subitens: Object,
    user: Object,
    preferencias: Object,
});
const location = [
    { title: "Kronos", disabled: false, href: "/" },
    { title: "Subitem", disabled: true },
    { title: "Lista", disabled: true },
];
const { trigger } = useFeedback();
const { store } = useSubitem();

const viewOption = ref(props.preferencias?.listagem_menu ?? 0);
const dados = ref(props.subitens ?? []);
const subitemSelecionado = ref(null);
const search = ref("");
// dialog
const dialogEditSubitem = ref(false);
const dialogNovoSubitem = ref(false);

async function insertSubitem(item) {
    try{
        const res = await store(item);
        if(res.success) {
            trigger(res.message, "success");
            return;
        }
        trigger(res.message || 'Erro sem idenficação.', "error");
    } catch(err) {
        trigger(err, "error");
    } finally{
        carregandoTodasSubitens();
    }
}
const executarBusca = async () => {
    await carregandoTodasSubitens(search.value);
};
async function carregandoTodasSubitens(termo = "") {
    await axios
        .get(route("subitem.index"), {
            params: { search: termo },
            headers: {
                Accept: "application/json",
            },
        })
        .then((res) => {
            dados.value = res.data;
        })
        .catch((err) => console.log(err));
}
</script>

<style scoped></style>
