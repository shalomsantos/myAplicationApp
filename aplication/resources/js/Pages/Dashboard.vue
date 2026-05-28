<template>
    <DefaultLayout
        v-model="viewOption"
        title="Bases Lista"
        :location="location"
    >
        <v-row dense class="pb-16">
            <v-col cols="12" class="d-flex justify-end ga-3 mb-1">
                <v-btn
                    class="text-none"
                    prepend-icon="mdi-filter"
                    color="green-darken-1"
                    text="Filtro"
                    @click="dialogFilter = true"
                />
                <v-btn
                    class="text-none"
                    prepend-icon="mdi-plus"
                    color="green-darken-1"
                    text="Nova base"
                    @click="dialogNewBasezero = true"
                />
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
                    <v-col
                        cols="6"
                        v-if="dados.data.length > 0 && viewOption"
                        v-for="item in dados.data"
                        :key="item.id"
                    >
                        <v-hover>
                            <template v-slot:default="{ isHovering, props }">
                                <v-card
                                    v-bind="props"
                                    :elevation="isHovering ? 3 : 1"
                                    :disabled="
                                        item.status.id == 5 ||
                                        item.status.id == 6
                                    "
                                    class="position-relative border-s-lg"
                                >
                                    <template v-slot:title>
                                        {{ item.projeto.nome }}
                                    </template>
                                    <template v-slot:item>
                                        <v-row no-gutters class="pt-2">
                                            <v-col cols="2">
                                                <p class="text-body-2">
                                                    Status
                                                </p>
                                                <div>
                                                    <p
                                                        class="text-body-2 text-disabled"
                                                    >
                                                        {{ item.status.nome }}
                                                    </p>
                                                </div>
                                            </v-col>
                                            <v-col cols="2">
                                                <p class="text-body-2">Ano</p>
                                                <div>
                                                    <p
                                                        class="text-body-2 text-disabled"
                                                    >
                                                        {{ item.ano }}
                                                    </p>
                                                </div>
                                            </v-col>
                                            <v-col cols="2">
                                                <p class="text-body-2">
                                                    Criado em
                                                </p>
                                                <div>
                                                    <p
                                                        class="text-body-2 text-disabled"
                                                    >
                                                        {{
                                                            isDate(
                                                                item.created_at,
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </v-col>
                                            <v-col cols="2">
                                                <Avatar :nomeCompleto="item.created_by.name"/>
                                            </v-col>
                                            <v-col cols="6" class="d-flex ga-3">
                                                <v-btn
                                                    icon="mdi-pencil"
                                                    color="primary"
                                                    text="teste"
                                                    density="comfortable"
                                                    variant="tonal"
                                                    rounded
                                                ></v-btn>
                                                <v-btn
                                                    icon="mdi-currency-usd"
                                                    color="success"
                                                    text="teste"
                                                    density="comfortable"
                                                    variant="tonal"
                                                    rounded
                                                    @click="() => router.get(route('bzero.show', item.id))"
                                                ></v-btn>
                                                <v-btn
                                                    icon="mdi-folder"
                                                    color="warning"
                                                    text="teste"
                                                    density="comfortable"
                                                    variant="tonal"
                                                    rounded
                                                ></v-btn>
                                                <v-btn
                                                    icon="mdi-information"
                                                    color="info"
                                                    text="teste"
                                                    density="comfortable"
                                                    variant="tonal"
                                                    rounded
                                                ></v-btn>
                                                <v-btn
                                                    icon="mdi-delete"
                                                    color="red"
                                                    text="teste"
                                                    density="comfortable"
                                                    variant="tonal"
                                                    @click.prevent="
                                                        ((idDelete = item.id),
                                                        (dialogConfirmation = true))
                                                    "
                                                    rounded
                                                ></v-btn>
                                            </v-col>
                                            <v-col
                                                cols="6"
                                                class="d-flex ga-3 justify-end"
                                            >
                                                <v-btn
                                                    icon="mdi-arrow-left"
                                                    color="warning"
                                                    text="teste"
                                                    variant="tonal"
                                                    density="comfortable"
                                                ></v-btn>
                                                <v-btn
                                                    icon="mdi-arrow-right"
                                                    color="warning"
                                                    text="teste"
                                                    variant="tonal"
                                                    density="comfortable"
                                                ></v-btn>
                                            </v-col>
                                            <v-sheet
                                                class="d-flex flex-wrap ga-2 bg-transparent pt-3"
                                            >
                                                <v-chip
                                                    v-if="
                                                        item.plataformas
                                                            ?.length > 0
                                                    "
                                                    v-for="plataforma in item.plataformas"
                                                    size="x-small"
                                                    color="green"
                                                    variant="flat"
                                                    :key="plataforma.id"
                                                >
                                                    {{ plataforma.nome }}
                                                </v-chip>
                                                <v-chip
                                                    v-else
                                                    size="x-small"
                                                    color="green"
                                                    style="width: 10rem"
                                                >
                                                </v-chip>
                                            </v-sheet>
                                        </v-row>
                                    </template>
                                </v-card>
                            </template>
                        </v-hover>
                    </v-col>
                    <v-col
                        cols="12"
                        v-else-if="dados.data.length > 0 && !viewOption"
                    >
                        <v-table
                            class="rounded-lg elevation-3"
                            density="compact"
                            striped="even"
                        >
                            <thead>
                                <tr>
                                    <th class="text-left">Projeto</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-left">Ano</th>
                                    <th class="text-left">Criado em</th>
                                    <th class="text-left">Por</th>
                                    <th class="text-left"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in dados.data"
                                    :key="item.id"
                                >
                                    <td>
                                        <v-chip color="green" size="x-small">{{
                                            item.id
                                        }}</v-chip>
                                        {{ item.projeto.nome }}
                                    </td>
                                    <td>{{ item.status.nome }}</td>
                                    <td>{{ item.ano }}</td>
                                    <td>{{ isDate(item.created_at) }}</td>
                                    <td>
                                        <Avatar :nomeCompleto="item.created_by.name"/>
                                    </td>
                                    <td>
                                        <!-- <v-btn
                                            class="text-none me-1"
                                            icon="mdi-delete"
                                            density="compact"
                                            color="red-lighten-2"
                                        ></v-btn> -->
                                        <v-menu location="top">
                                            <template v-slot:activator="{ props }">
                                                <v-btn
                                                    v-bind="props"
                                                    icon="mdi-dots-vertical"
                                                    variant="text"
                                                    density="compact"
                                                ></v-btn>
                                            </template>
                                            <v-list :items="items" density="compact" @click:select="(acao) => gerenciarAcoesMenu(acao, item.id)"/>
                                        </v-menu>
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
        <FiltroBase
            v-model="dialogFilter" 
            @onFilter="filtro"
        />
        <NovaBase
            v-model="dialogNewBasezero"
            :projetos="projetos"
            @end="endInsert"
        />
        <Confirmation
            title="Para continuar confirme a ação!"
            subtitle="Deseja realmente detelar a base."
            v-model="dialogConfirmation"
            @confirmed="deleteItem()"
            @canceled="dialogConfirmation = false"
        />
    </DefaultLayout>
</template>

<script setup>
import FiltroBase from "@/Components/Dialogs/Bzero/FiltroBase.vue";
import NovaBase from "@/Components/Dialogs/Bzero/NovaBase.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EmptyData from "@/Components/EmptyData.vue";
import Confirmation from "@/Components/Dialogs/Confirmation.vue";
import Avatar from "@/Components/Bases/Avatar.vue";
import { useFeedback } from "@/Composables/useFeedback";
import { useBzero } from "@/Composables/useBzero";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    bzeros: Object,
    projetos: Object,
    preferencias: Object,
});
const location = [
    { title: "Kronos", disabled: false, href: "/" },
    { title: "Lista", disabled: true },
];
const items = [
    { title: "Editar", value: "editar", props: { prependIcon: "mdi-pencil" } },
    { title: "Anexar Moeda", value: "anexar_moeda", props: { prependIcon: "mdi-currency-usd" } },
    { title: "Anexar Pasta", value: "anexar_pasta", props: { prependIcon: "mdi-folder" } },
    { title: "Voltar Fase", value: "voltar", props: { prependIcon: "mdi-arrow-left" } },
    { title: "Avançar Fase", value: "avancar", props: { prependIcon: "mdi-arrow-right" } },
];
const { trigger } = useFeedback();
const { carregando, carregarDados, filtrarBases, deletarBzero } = useBzero();
// Variables
const dados = ref(props.bzeros);
const viewOption = ref(props.preferencias?.listagem_menu ?? 0);
const idDelete = ref(null);
// Dialogs
const dialogFilter = ref(false);
const dialogNewBasezero = ref(false);
const dialogConfirmation = ref(false);
// Functions
async function filtro(filtros) {
    dialogFilter.value = false;
    const res = await filtrarBases(filtros);
    if (res.sucesso) {
        dados.value = res.data;
    }
}
async function endInsert(message) {
    dialogNewBasezero.value = false;
    trigger(message, "success");
    const res = await carregarDados();
    dados.value = res;
}
async function deleteItem() {
    try {
        const res = await deletarBzero(idDelete.value);
        if (res.success) {
            trigger(res.message, "success");
            return;
        }
        trigger(res.message || res, "error");
    } finally {
        dialogConfirmation.value = false;
        const res = await carregarDados();
        dados.value = res;
    }
}
function gerenciarAcoesMenu(acao, idProjeto) {
    const tipoAcao = acao.id;

    switch (tipoAcao) {
        case 'anexar_moeda':
            router.get(route("bzero.show", idProjeto));
            break;
        case 'editar':
            console.log("Editando projeto:", idProjeto);
            break;
    }
}
const updatePage = (page) => {
    router.get(
        route("bzero.index"),
        { page: page },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                dados.value = page.props.bzeros;
            },
        },
    );
};
</script>

<style scoped></style>
