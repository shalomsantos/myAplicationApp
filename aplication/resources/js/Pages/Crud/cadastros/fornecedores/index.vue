<template>
    <DefaultLayout
        v-model="viewOption"
        title="Fornecedores"
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
                            @click:clear="carregandoTodasFornecedores('')"
                        />
                    </v-col>
                    <v-col align="end">
                        <v-btn
                            @click.prevent="dialogNovoFornecedor = true"
                            class="text-none"
                            color="green-darken-1"
                            prepend-icon="mdi-store-plus"
                            text="Novo fornecedor"
                        />
                    </v-col>
                </v-row>
            </v-col>
            <v-col
                cols="6"
                v-if="dados.data.length > 0 && viewOption"
                v-for="(item, id) in dados.data"
                :key="id"
            >
                <v-hover>
                    <template v-slot:default="{ isHovering, props }">
                        <v-card
                            v-bind="props"
                            :title="item.razao_social"
                            prepend-icon="mdi-store"
                            class="border-s-lg"
                            :elevation="isHovering ? 3 : 1"
                            @click.prevent="
                                ((fornecedorSelecionado = item),
                                (dialogEditeFornecedor = true))
                            "
                        >
                            <template #subtitle>
                                <v-sheet class="d-flex justify-space-between align-end" color="transparent">
                                    <Avatar :nomeCompleto="item.created_by.name"/>
                                    <p class="text-body-2 text-disabled">
                                        {{ isDate(item.created_at) }}
                                    </p>
                                </v-sheet>
                            </template>
                        </v-card>
                    </template>
                </v-hover>
            </v-col>
            <v-col cols="12" v-else-if="dados.data.length > 0 && !viewOption">
                <v-table
                    class="rounded-lg elevation-3"
                    density="compact"
                    striped="even"
                >
                    <thead>
                        <tr>
                            <th class="text-left">Nome</th>
                            <th class="text-left">Criado em</th>
                            <th class="text-left">Por</th>
                            <th class="text-left">***</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, id) in dados.data"
                            :key="id"
                            @click.prevent="
                                ((fornecedorSelecionado = item),
                                (dialogEditeFornecedor = true))
                            "
                        >
                            <td>{{ item.razao_social }}</td>
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
                                ></v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-col>
            <v-col cols="12" v-else>
                <EmptyData />
            </v-col>
            <v-col cols="12">
                <v-pagination
                    v-model="dados.current_page"
                    :length="dados.last_page"
                    :total-visible="4"
                    @update:model-value="updatePage"
                    class="position-absolute bottom-0 mb-3"
                    style="left: 50%; transform: translateX(-50%); z-index: 15"
                    active-color="green-darken-4"
                    color="green-lighten-1"
                    density="comfortable"
                    variant="flat"
                ></v-pagination>
            </v-col>
        </v-row>

        <EditeFornecedor
            v-model="dialogEditeFornecedor"
            :fornecedor="fornecedorSelecionado"
            @onCloseDialog="
                ((dialogEditeFornecedor = false),
                (fornecedorSelecionado = null))
            "
        />
        
        <NovoFornecedor
            v-model="dialogNovoFornecedor"
            @onCloseDialog="dialogNovoFornecedor = false"
        />

        <NormalFeedback v-model="feedback"></NormalFeedback>
    </DefaultLayout>
</template>

<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EditeFornecedor from "@/Components/Dialogs/Fornecedores/EditeFornecedor.vue";
import NovoFornecedor from "@/Components/Dialogs/Fornecedores/NovoFornecedor.vue";
import EmptyData from "@/Components/EmptyData.vue";
import NormalFeedback from "@/Components/Feedback/NormalFeedback.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import Avatar from "@/Components/Bases/Avatar.vue";

const props = defineProps({
    fornecedores: Object,
    user: Object,
    preferencias: Object,
});

const location = [
    { title: "Kronos", disabled: false, href: "/" },
    { title: "Fornecedores", disabled: true },
    { title: "Lista", disabled: true },
];

const viewOption = ref(props.preferencias?.listagem_menu ?? 0);
const dados = ref(props.fornecedores ?? []);
const fornecedorSelecionado = ref(null);
const search = ref("");

const dialogEditeFornecedor = ref(false);
const dialogNovoFornecedor = ref(false);

const updatePage = (page) => {
    router.get(
        route("fornecedor.index"),
        {
            page: page,
            search: search.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                dados.value = page.props.fornecedores;
            },
        },
    );
};

// Feedback
const feedback = ref({
    show: false,
    timeout: 2000,
    color: "success",
    text: "",
});

function executarBusca() {
    carregandoTodasFornecedores(search.value);
}
async function carregandoTodasFornecedores(termo = "") {
    await axios
        .get(route("fornecedor.index"), {
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
