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
                cols="4"
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
                            <template #item>
                                <Avatar :avatar="item"/>
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
                            <th class="text-left">Nome fantasia</th>
                            <th class="text-center">Por</th>
                            <th class="text-left"></th>
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
                            <td>{{ item.nome_fantasia }}</td>
                            <td style="width: 200px;">
                                <Avatar :avatar="item"/>
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
            @end="endEditeFornecedor"
        />
        
        <NovoFornecedor
            v-model="dialogNovoFornecedor"
            @end="endNovoFornecedor"
        />
    </DefaultLayout>
</template>

<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EditeFornecedor from "@/Components/Dialogs/Fornecedores/EditeFornecedor.vue";
import NovoFornecedor from "@/Components/Dialogs/Fornecedores/NovoFornecedor.vue";
import EmptyData from "@/Components/EmptyData.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import Avatar from "@/Components/Bases/Avatar.vue";
import { useFeedback } from "@/Composables/useFeedback";
import { useFornecedor } from "@/Composables/useFornecedor";

const props = defineProps({
    fornecedores: Object,
    user: Object,
    preferencias: Object,
});

const { trigger } = useFeedback();
const { index } = useFornecedor();

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

async function endEditeFornecedor(response) {
    if (response.success) {
        trigger(response.message, "success");
        await carregandoTodasFornecedores("");
        return;
    } else {
        trigger(response.message || "Erro desconhecido.", "error");
        return;
    }
}
async function endNovoFornecedor(response) {
    if (response.success) {
        trigger(response.message, "success");
        await carregandoTodasFornecedores("");
        return;
    } else {
        trigger(response.message || "Erro desconhecido.", "error");
        return;
    }
}

function executarBusca() {
    carregandoTodasFornecedores(search.value);
}
async function carregandoTodasFornecedores(termo = "") {
    try{
        const res = await index(termo);
        dados.value = res;
    } catch (error) {
        trigger(error, "error");
    }
}
</script>

<style scoped></style>
