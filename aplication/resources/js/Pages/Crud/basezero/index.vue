<template>
    <DefaultLayout
        v-model="viewOption"
        :title="'Subitens lista'"
        :location="location"
    >
        <v-sheet class="d-flex ga-3 mb-3" color="transparent">
            <v-btn
                @click.prevent="dialogNovoItem = true"
                class="text-none"
                color="green-darken-1"
                prepend-icon="mdi-plus"
                text="Adicionar"
            />
            <v-text-field
                v-model="search"
                placeholder="Pesquisar aqui..."
                variant="outlined"
                density="compact"
                hide-details="auto"
                clearable
            />
        </v-sheet>
        <v-row>
            <v-col
                cols="12"
                v-for="(item, id) in subitens"
                :key="id"
                v-if="subitens.length > 0 && viewOption"
            >
                <v-hover>
                    <template v-slot:default="{ isHovering, props }">
                        <v-card
                            v-bind="props"
                            :color="isHovering ? 'green-lighten-5' : undefined"
                            @click.prevent="
                                ((itemSelecionado = item),
                                (dialogEditeItem = true))
                            "
                        >
                            <template #title>
                                <v-row no-gutters>
                                    <v-col cols="10">
                                        {{ item.nome }}
                                    </v-col>
                                    <v-col cols="2">
                                        <p class="text-body-2 text-disabled">
                                            Criado em:
                                            {{ isDate(item.created_at) }}
                                        </p>
                                        <p class="text-body-2 text-disabled">
                                            Por: {{ item.created_by.name }}
                                        </p>
                                    </v-col>
                                </v-row>
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
                                        {{ fornecedor.nome_fantasia }}
                                    </v-chip>
                                </v-sheet>
                            </template>
                        </v-card>
                    </template>
                </v-hover>
            </v-col>
            <v-col cols="12" v-else-if="subitens.length > 0 && !viewOption">
                <v-table
                    class="bg-green-lighten-5"
                    density="compact"
                    striped="even"
                >
                    <thead>
                        <tr>
                            <th class="text-left">Nome</th>
                            <th class="text-left">Subitens</th>
                            <th class="text-left">Criado em</th>
                            <th class="text-left">Criado por</th>
                            <th class="text-left"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, id) in subitens"
                            :key="id"
                            @click.prevent="
                                ((projetoSelecionado = item),
                                (dialogEditProjeto = true))
                            "
                        >
                            <td>{{ item.nome }}</td>
                            <td>
                                <v-chip
                                    size="x-small"
                                    color="green"
                                    variant="flat"
                                    v-for="(subitem, id) in item.subitens"
                                    :key="id"
                                >
                                    {{ subitem.nome }}
                                </v-chip>
                            </td>
                            <td>{{ isDate(item.created_at) }}</td>
                            <td>
                                <v-chip
                                    size="x-small"
                                    color="green"
                                    variant="flat"
                                >
                                    {{ item.created_by.name }}
                                </v-chip>
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
        <NormalFeedback v-model="feedback"></NormalFeedback>
    </DefaultLayout>
</template>

<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EmptyData from "@/Components/EmptyData.vue";
import NormalFeedback from "@/Components/Feedback/NormalFeedback.vue";
import { ref } from "vue";

const location = [
    { title: "Kronos", disabled: false, href: "/" },
    { title: "Fornecedores", disabled: true },
    { title: "Lista", disabled: true },
];

// Feedback var
const feedback = ref({
    show: false,
    timeout: 2000,
    color: "success",
    text: "",
});
</script>

<style scoped></style>
