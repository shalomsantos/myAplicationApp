<template>
    <DefaultLayout>
        <v-container>
            <h1 class="text-disabled">Welcome</h1>
        </v-container>
        <v-container>
            <h1 class="text-medium-emphasis">Welcome</h1>
        </v-container>
        <v-container>
            <h1>Welcome</h1>
            <h1 class="text-blue-grey-darken-1">Welcome</h1>
        </v-container>
    </DefaultLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import DefaultLayout from '../Layouts/DefaultLayout.vue'
import { ref } from "vue";
</script>

<style scoped></style>
