<template>
    <v-sheet class="bg-transparent">
        <!-- Modo card -->
        <v-row class="bg-transparent" v-if="dados.length > 0 && viewOption">
            <v-col cols="4" v-for="(item, id) in dados" :key="id">
                <v-hover>
                    <template v-slot:default="{ isHovering, props }">
                        <v-card
                            v-bind="props"
                            :color="isHovering ? 'teal-lighten-5' : undefined"
                        >
                            <template v-slot:title>
                                <v-btn
                                    variant="outlined"
                                    color="green-darken-1"
                                >
                                    <p class="text-h6 text-green-darken-1">
                                        #{{ item.id }}
                                    </p>
                                    <v-icon
                                        icon="mdi-text-box"
                                        color="green-darken-1"
                                    ></v-icon>
                                </v-btn>
                                {{ item.projeto.nome }}
                            </template>
                            <template v-slot:item>
                                <v-row no-gutters class="pt-2">
                                    <v-col cols="4">
                                        <p class="text-body-2">Status</p>
                                        <div>
                                            <p
                                                class="text-body-2 text-disabled"
                                            >
                                                {{ item.status.nome }}
                                            </p>
                                        </div>
                                    </v-col>
                                    <v-col cols="4">
                                        <p class="text-body-2">Ano</p>
                                        <div>
                                            <p
                                                class="text-body-2 text-disabled"
                                            >
                                                {{ item.ano }}
                                            </p>
                                        </div>
                                    </v-col>
                                    <v-col cols="4">
                                        <p class="text-body-2">Criado em</p>
                                        <div>
                                            <p
                                                class="text-body-2 text-disabled"
                                            >
                                                {{ isDate(item.created_at) }}
                                            </p>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-chip
                                            size="x-small"
                                            color="green"
                                            variant="flat"
                                        >
                                            {{ item.created_by.name }}
                                        </v-chip>
                                    </v-col>
                                </v-row>
                            </template>
                        </v-card>
                    </template>
                </v-hover>
            </v-col>
        </v-row>
        <!-- Modo tabela -->

        <v-table
            density="compact"
            class="bg-green-lighten-5"
            v-else-if="dados.length > 0 && !viewOption"
            striped="even"
        >
            <thead>
                <tr>
                    <th class="text-left">Id</th>
                    <th class="text-left">Projeto</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Ano</th>
                    <th class="text-left">Criado em</th>
                    <th class="text-left">Criador por</th>
                    <th class="text-left"></th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(item, id) in dados"
                    :key="id"
                >
                    <td>{{ item.id }}</td>
                    <td>{{ item.projeto.nome }}</td>
                    <td>{{ item.status.nome }}</td>
                    <td>{{ item.ano }}</td>
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
                            density="compact"
                            color="red-lighten-2"
                        ></v-btn>
                    </td>
                </tr>
            </tbody>
        </v-table>

        <EmptyData v-else />
    </v-sheet>
</template>

<script setup>
import EmptyData from "@/Components/EmptyData.vue";
import { ref } from "vue";

const viewOption = defineModel();

const props = defineProps({
    dados: {
        type: Array,
        required: true,
    },
});
</script>

<style scoped>
</style>
