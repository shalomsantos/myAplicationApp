<template>
    <DefaultLayout
        v-model="viewOption"
        title="Projetos"
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
                            @keydown.enter.prevent="executarBusca"
                            @click:clear="carregarDados('')" 
                        />
                    </v-col>
                    <v-col align="end">
                        <v-btn
                            @click.prevent="dialogNewProjeto = true"
                            class="text-none"
                            color="green-darken-1"
                            prepend-icon="mdi-clipboard-plus"
                            text="Novo projeto"
                        />
                    </v-col>
                </v-row>
            </v-col>
            <v-row dense>
                <v-col
                    v-if="carregando"
                    cols="12"
                    class="d-flex justify-center align-center py-10"
                >
                    <v-progress-circular
                        indeterminate
                        color="green-darken-1"
                        size="64"
                    />
                </v-col>
                <template v-else>
                    <v-col cols="4" v-if="dados.data.length > 0 && viewOption" v-for="(item, id) in dados.data" :key="id">
                        <v-hover>
                            <template v-slot:default="{ isHovering, props }">
                                <v-card
                                    v-bind="props"
                                    :title="item.nome"
                                    prepend-icon="mdi-clipboard"
                                    class="border-s-lg"
                                    @click.prevent="
                                        ((projetoSelecionado = item),
                                        (dialogEditProjeto = true))
                                    "
                                    :elevation="
                                        isHovering ? 3 : 1
                                    "
                                >
                                    <template #subtitle>
                                        <v-chip
                                            size="small"
                                            color="green"
                                        >
                                            {{ item.tipo_projeto.nome }}
                                        </v-chip>
                                    </template>
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
                                    <th class="text-left">Nome</th>
                                    <th class="text-left">Tipo</th>
                                    <th class="text-center">Por</th>
                                    <th class="text-left"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, id) in dados.data"
                                    :key="id"
                                    @click.prevent="
                                        ((projetoSelecionado = item),
                                        (dialogEditProjeto = true))
                                    "
                                >
                                    <td>{{ item.nome }}</td>
                                    <td>{{ item.tipo_projeto.nome }}</td>
                                    <td style="width: 200px;">
                                        <Avatar :avatar="item"/>
                                    </td>
                                    <td>
                                        <v-btn
                                            disabled
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
                </template>
            </v-row> 
            <v-col cols="12" class="d-flex justify-center">
                <v-pagination
                    v-model="dados.current_page"
                    :length="dados.last_page"
                    :total-visible="4"
                    @update:model-value="updatePage"
                    active-color="green-darken-4"
                    color="green-lighten-1"
                    class="position-absolute bottom-0 mb-3"
                    style="left: 50%; transform: translateX(-50%); z-index: 15"
                    density="comfortable"
                    variant="flat"
                ></v-pagination>
            </v-col>            
        </v-row>

        <EditeProjeto
            v-model="dialogEditProjeto"
            :projeto="projetoSelecionado"
            :tipos="tiposProjetos"
            @closeEditProjeto="(projetoSelecionado = null)"
            @editeProcess="editProjeto"
        />
        <NovoProjeto
            v-model="dialogNewProjeto"
            :tiposProjetos="tiposProjetos"
            @end="endInsert"
        />
    </DefaultLayout>
</template>

<script setup>
import EditeProjeto from "@/Components/Dialogs/Projeto/EditeProjeto.vue";
import Avatar from "@/Components/Bases/Avatar.vue";
import NovoProjeto from "@/Components/Dialogs/Projeto/NovoProjeto.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { useFeedback } from "@/Composables/useFeedback";
import { useProjeto } from "@/Composables/useProjeto";
import EmptyData from "@/Components/EmptyData.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";

const props = defineProps({
    projetos: Object,
    tiposProjetos: Object,
    user: Object,
    preferencias: Object
});
const location = [
    { title: "Kronos", href: "/" },
    { title: "Projetos" },
    { title: "Lista", disabled: false },
];
const { trigger } = useFeedback();

const dados = ref(props.projetos);
const viewOption = ref(props.preferencias?.listagem_menu ?? 0);
const { carregando, index } = useProjeto();

const projetoSelecionado = ref(null);
const search = ref("");

// dialogs
const dialogNewProjeto = ref(false);
const dialogEditProjeto = ref(false);
// functions
async function endInsert(message) {
    dialogNewProjeto.value = false
    trigger(message, 'success')
    const res = await index();
    dados.value = res.data || res.data.data;
}
function executarBusca() {
    carregarDados(search.value);
}
async function carregarDados(termo = "") {
   try {
        const res = await index(termo);
        dados.value = res.data;
    } catch (err) {
        trigger(err.response?.data?.message || "Erro ao carregar", 'error');
    }
}
const updatePage = (page) => {
    router.get(
        route("projeto.index"),
        { page: page },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                dados.value = page.props.projetos;
            },
        },
    );
};
async function editProjeto(projeto) {
    const projectId = projeto.id;

    await axios
        .patch(route("projeto.update", projectId), projeto)
        .then((res) => {
            if (!res.data.success) {
                trigger(res.data.message, "error")
                return;
            }
            carregarDados();
            trigger(res.data.message, "success")
        })
        .catch((err) => {
            trigger(err, "error")
        });
}
</script>

<style scoped></style>
