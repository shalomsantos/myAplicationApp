<template>
    <DefaultLayout v-model="viewOption" :title="'Usuários Lista'" :location="location">
        <v-sheet class="bg-transparent">
            <v-row
                class="bg-transparent"
                v-if="dados.length > 0 && viewOption"
            >
                <v-col cols="4" v-for="(item, id) in dados" :key="id" @click.prevent="redirect" class="cursor-pointer">
                    <v-hover>
                        <template v-slot:default="{ isHovering, props }">
                            <v-card
                                v-bind="props"
                                :color="
                                    isHovering ? 'teal-lighten-5' : undefined
                                "
                            >
                                <template v-slot:title>
                                    <p class="text-h6 font-weight-bold text-green-darken-1">
                                        #{{ item.id }} {{ item.name }}
                                    </p>
                                </template>
                                <template v-slot:item>
                                    <v-row no-gutters class="pt-2">
                                        <v-col cols="6">
                                            <p class="text-body-2">E-mail</p>
                                            <div>
                                                <p class="text-body-2 text-disabled">{{ item.email }}</p>
                                            </div>
                                        </v-col>
                                        <v-col cols="6">
                                            <p class="text-body-2">Cargo</p>
                                            <div>
                                                <p class="text-body-2 text-disabled">{{ item.cargo.nome }}</p>
                                            </div>
                                        </v-col>
                                        <v-col cols="6">
                                            <p class="text-body-2">Criado em</p>
                                            <div>
                                                <p class="text-body-2 text-disabled">{{ isDate(item.created_at ?? false) }}</p>
                                            </div>
                                        </v-col>
                                        <v-col cols="6">
                                            <p class="text-body-2">Preferência(Listas)</p>
                                            <div>
                                                <p class="text-body-2 text-disabled">{{ item.preferencia?.listagem_menu?'Card':'Tabela' }}</p>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </template>
                            </v-card>
                        </template>
                    </v-hover>
                </v-col>
            </v-row>
            <v-table
                density="compact"
                class="bg-green-lighten-5"
                v-else-if="dados.length > 0 && !viewOption"
                striped="even"
            >
                <thead>
                    <tr>
                        <th class="text-left">Id</th>
                        <th class="text-left">Nome</th>
                        <th class="text-left">E-mail</th>
                        <th class="text-left">Cargo</th>
                        <th class="text-left">Criado em</th>
                        <th class="text-left">Preferência(Listas)</th>
                        <th class="text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, id) in dados" :key="id" @click.prevent="redirect">
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.email }}</td>
                        <td>{{ item.cargo.nome }}</td>
                        <td>{{ isDate(item.created_at ?? false) }}</td>
                        <td>{{ item.preferencia?.listagem_menu?'Card':'Tabela' }}</td>
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
    </DefaultLayout>
</template>

<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { ref, watch } from "vue";
import axios from 'axios';

const props = defineProps({
    usuarios: Object,
    preferencias: Object
})

const viewOption = ref(props.preferencias?.listagem_menu ?? 0);
const dados = ref(props.usuarios ?? [])

watch(viewOption, () => {
    carregandoTodosUsuarios()
})

const location = [
    { title: "Kronos", disabled: false, href: "/" },
    { title: "Usuários", disabled: true },
    { title: "Lista", disabled: true },
];
async function carregandoTodosUsuarios() {
    await axios.get("/usuarios", {
        headers: {
            Accept: "application/json",
        }
    })
    .then((res) => {
        dados.value = res.data
    }).catch((err) => {
        console.log(err)
    })
}
function redirect(){
    window.location.replace(route("profile.edit"))
}
</script>

<style scoped>

</style>
