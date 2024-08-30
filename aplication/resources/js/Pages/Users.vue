<template>
    <DefaultLayout>
        <v-sheet class="text-right pa-3">
            <v-btn icon="mdi-arrow-left-thick" link :href="route('home')" class="ml-auto">
            </v-btn>
        </v-sheet>
        <v-table>
            <thead>
                <tr>
                    <th class="text-left">id</th>
                    <th class="text-left">nome</th>
                    <th class="text-left">e-mail</th>
                    <th class="text-left">*</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, id) in user" :key="id">
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.email }}</td>
                    <td class="d-flex ga-2">
                        <v-btn icon="mdi-trash-can" size="x-small"></v-btn>
                        <v-btn icon="mdi-pen" size="x-small"></v-btn>
                    </td>
                </tr>
            </tbody>
        </v-table>
    </DefaultLayout>
</template>
<script setup>
import { Link } from "@inertiajs/vue3";
import DefaultLayout from '../Layouts/DefaultLayout.vue'

const props = defineProps({
    user: { default: null },
});
</script>

<style scoped>
.relative:hover {
    background-color: #eaeaea;
}
.relative:hover .absolute {
    display: block !important;
}
.absolute {
    display: none !important;
    width: max-content;
    background-color: #6e67fd;
    padding: 0.4rem 1rem;
    border-radius: 0.4rem 0.4rem 0.4rem 0;
    color: white;
    top: -150%;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}
</style>
