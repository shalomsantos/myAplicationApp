<template>
    <v-layout class="rounded rounded-md">
        <v-navigation-drawer v-model="drawer" :width="200" color="blue-grey-lighten-5">
            <v-list density="compact">
                <v-list-item title="Base Zero" link :href="route('home')"/>
                <v-list-group>
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props">Cadastro</v-list-item>
                    </template>

                    <v-list-item v-for="(item, id) in menuCadastros" :key="id" :href="item.to">
                        <p class="text-decoration-none text-grey-darken-1">{{ item.text }}</p>
                    </v-list-item>
                </v-list-group>
                <v-list-item title="Usuários" link href="users"/>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar color="blue-grey-darken-2">
            <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>
                <v-breadcrumbs :items="breadcrumbs"></v-breadcrumbs>
            </v-toolbar-title>
            <v-spacer></v-spacer>

            <v-menu>
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" icon="mdi-account-arrow-right"/>
                </template>
                <v-card min-width="300">
                    <v-list density="compact">
                        <v-list-item prepend-icon="mdi-account"
                            subtitle="shalom.santos@gmail.com" title="Shalom Santos">
                        </v-list-item>
                    </v-list>

                    <v-divider></v-divider>

                    <v-list density="compact">
                        <v-list-item>
                            <v-icon icon="mdi-account-cog"></v-icon>
                            <Link :href="route('profile.edit')" class="text-decoration-none text-black pl-2 py-16" style="padding-right: 50%;">Perfil</Link>
                        </v-list-item>
                        <v-list-item>
                            <v-icon icon="mdi-exit-to-app"></v-icon>
                            <Link :href="route('logout')" method="post" class="text-decoration-none text-black pl-2 py-16" style="padding-right: 50%;">Sair</Link>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-menu>
        </v-app-bar>

        <v-main color="grey-lighten-5" style="min-height: 100%">
            <slot/>
        </v-main>
    </v-layout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const drawer = ref(false);
const breadcrumbs = [
    { 
        title:'Base Zero',
        disabled: false
    },
    { 
        title:'Visualização',
        disabled: false
    }
]
const menuCadastros = [
    {
        to: '/projetos',
        text: 'Projeto',
    },
    {
        to: '/plataformas',
        text: 'Plataforma - Modelos BZ',
    },
    {
        to: '/itens',
        text: 'Cotação - Item',
    },
    {
        to: '/subitens',
        text: 'Cotação - Subitem',
    },
    {
        to: '/fornecedores',
        text: 'Fornecedor',
    },
    {
        to: '/colaboradores',
        text: 'Colaborador',
    },
    {
        to: '/intermediarios',
        text: 'Intermediario',
    },
    {
        to: '/setores',
        text: 'Setores',
    },
    {
        to: '/equipes',
        text: 'Equipes',
    },
    {
        to: '/setups',
        text: 'Precificacao - Setups',
    },
]
</script>

<style scoped></style>
