<template>
    <v-card>
        <v-layout>
            <v-navigation-drawer expand-on-hover rail class="bg-blue-grey-darken-3">

                <v-divider></v-divider>

                <v-list density="compact" nav>
                    <v-list-item prepend-icon="mdi-folder" title="My Files" value="myfiles"></v-list-item>
                    <v-list-item prepend-icon="mdi-account-multiple" title="Shared with me"
                        value="shared"></v-list-item>
                    <v-list-item prepend-icon="mdi-star" title="Starred" value="starred"></v-list-item>
                </v-list>
            </v-navigation-drawer>

            <v-main class="overflow-y-auto bg-grey-lighten-3" style="min-height: 100vh;">
                <v-sheet class="bg-transparent pa-3">
                    <p class="text-h6 fit-content hyper-b" style="width: min-content;">Header</p>
                    <v-container>
                        <v-card title="Card title" subtitle="Subtitle" text="Texto do card exibido em tela para testar espaço e valores de consulta para exibir também em tela.">
                            <v-card-actions>
                                <v-btn>Click me</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-container>
                </v-sheet>
            </v-main>
        </v-layout>
    </v-card>
</template>

<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({});
const variable = ref(0);
</script>

<style></style>
