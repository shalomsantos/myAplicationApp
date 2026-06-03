<template>
    <DefaultLayout
        v-model="viewOption"
        title="Plataformas"
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
                            @click:clear="carregandoTodasPlataformas('')"
                        />
                    </v-col>
                    <v-col align="end">
                        <v-btn
                            @click.prevent="dialogNovaPlataforma = true"
                            class="text-none"
                            color="green-darken-1"
                            prepend-icon="mdi-layers-plus"
                            text="Nova plataforma"
                        />
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="4" v-if="dados.length > 0 && viewOption" v-for="(item, id) in dados" :key="id">
                <v-hover class="h-100">
                    <template v-slot:default="{ isHovering, props }">
                        <v-card
                            v-bind="props"
                            :title="item.nome"
                            prepend-icon="mdi-layers"
                            @click.prevent="
                                ((plataformaSelecionada = item),
                                (dialogEditePlataforma = true))
                            "
                            :elevation="isHovering ? 3 : 1"
                            class="h-100 d-flex flex-column border-s-lg" 
                        >
                            <template #item>
                                <v-sheet color="transparent" class="d-flex flex-column ga-3">
                                    <Avatar :avatar="item"/>
                                    <MultItens :item="item" entity="itens"/>
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
                            <th class="text-left">Itens</th>
                            <th class="text-center">Por</th>
                            <th class="text-left"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, id) in dados"
                            :key="id"
                            @click.prevent="
                                ((plataformaSelecionada = item),
                                (dialogEditePlataforma = true))
                            "
                        >
                            <td>{{ item.nome }}</td>
                            <td>
                                <MultItens :item="item" entity="itens"/>
                            </td>
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
        </v-row>
        <!-- Dialogs -->
        <EditePlataforma
            v-model="dialogEditePlataforma"
            :plataforma="plataformaSelecionada"
            @editProcess="carregandoTodasPlataformas"
            @onCloseDialog="
                ((plataformaSelecionada = null),
                (dialogEditePlataforma = false))
            "
        />
        <NovaPlataforma
            v-model="dialogNovaPlataforma"
            @onCloseDialog="((dialogNovaPlataforma = false))"
            @insertProcess="insertPlataforma"
        />
    </DefaultLayout>
</template>

<script setup>
import EditePlataforma from "@/Components/Dialogs/Plataforma/EditePlataforma.vue";
import NovaPlataforma from "@/Components/Dialogs/Plataforma/NovaPlataforma.vue";
import { useFeedback } from "@/Composables/useFeedback";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EmptyData from "@/Components/EmptyData.vue";
import Avatar from "@/Components/Bases/Avatar.vue";
import axios from "axios";
import { ref } from "vue";
import MultItens from "@/Components/Bases/MultItens.vue";

const props = defineProps({
    plataformas: Object,
    user: Object,
    preferencias: Object,
});
const location = [
    { title: "Kronos", disabled: false, href: "/" },
    { title: "Plataformas", disabled: true },
    { title: "Lista", disabled: true },
];
const { trigger } = useFeedback();

const viewOption = ref(props.preferencias?.listagem_menu ?? 0);
const dados = ref(props.plataformas ?? []);
const plataformaSelecionada = ref(null);
const search = ref("");
// dialogs
const dialogEditePlataforma = ref(false);
const dialogNovaPlataforma = ref(false);
// functions
async function insertPlataforma(plataforma) {
    await axios
        .post(route("plataforma.store"), plataforma)
        .then((res) => {
            if (res.data.success) {
                carregandoTodasPlataformas();
                trigger(res.data.message, 'success');
                return;
            }
            trigger(res.data.message, 'error');
        })
        .catch((err) => trigger(err, 'error'));
}
function executarBusca() {
    carregandoTodasPlataformas(search.value);
}
async function carregandoTodasPlataformas(termo = "") {
    await axios
        .get(route("plataforma.index"), {
            params: { search: termo },
            headers: {
                Accept: "application/json",
            },
        }).then((res) => {
            dados.value = res.data;
        })
        .catch((err) => trigger(err, 'error'));
}
</script>

<style scoped></style>
