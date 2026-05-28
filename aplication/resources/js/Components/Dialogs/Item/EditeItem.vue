<template>
    <Dialog
        v-model="model"
        width="60vw"
        title="Editar ou vincular subitens a este item?"
        @onCloseDialog="((isEditingName = false), ($emit('closeEvent')))"
    >
        <v-row>
            <v-col cols="5">
                <v-row>
                    <v-col v-if="isEditingName" class="d-flex align-center ga-3 mb-0 pb-0">
                        <v-text-field
                            v-model="inputItemNome" 
                            variant="outlined" 
                            density="compact"
                            color="green-darken-3"
                            hide-details
                            clearable
                        />
                        <v-btn
                            class="rounded"
                            icon="mdi-check"
                            variant="tonal"
                            size="x-small"
                            color="green-darken-4"
                            @click.prevent="saveNameItem()"
                        ></v-btn>
                    </v-col>
                    <v-col v-else class="d-flex align-center ga-3 mb-0 pb-0">
                        <h4 class="text-green-darken-4"> {{ inputItemNome }} </h4>
                        <v-btn
                            class="rounded"
                            icon="mdi-pencil"
                            variant="tonal"
                            size="x-small"
                            color="green-darken-4"
                            @click.prevent="isEditingName = !isEditingName"
                        ></v-btn>
                    </v-col>
                    <v-col cols="12">
                        <v-combobox
                            v-model="inputSubitem"
                            label="Escolher ou inserir subitem"
                            :items="subitens"
                            variant="outlined"
                            item-title="nome"
                            density="compact"
                            color="green-darken-3"
                            hide-details
                            clearable
                        >
                            <template v-slot:prepend-item>
                                <v-divider></v-divider>
                                <v-list-item
                                    title="Deseja inserir um novo subitem?"
                                    subtitle="Clique para cadastrar um novo"
                                    prepend-icon="mdi-plus-circle-outline"
                                    color="primary"
                                    @click="dialogNovoSubitem = true"
                                >
                                </v-list-item>
                                <v-divider></v-divider>
                            </template>

                            <template v-slot:no-data>
                                <v-list-item
                                    title="Nenhum item encontrado"
                                    subtitle="Clique aqui para criar um novo"
                                    @click="dialogNovoSubitem = true"
                                ></v-list-item>
                            </template>
                        </v-combobox>
                    </v-col>
                    <v-col cols="12">
                        <v-btn
                            class="text-none w-100"
                            color="green-darken-1"
                            prepend-icon="mdi-playlist-plus"
                            text="Adicionar"
                            :disabled="!inputSubitem"
                            @click.prevent="dialogConfirmation = true"
                        />
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="7" class="my-0 py-0">
                <v-table
                    fixed-header
                    class="overflow-y-auto border rounded-lg elevation-3"
                    density="compact"
                    height="200"
                >
                    <thead>
                        <tr>
                            <th class="text-left">Subitem</th>
                            <th class="text-left">Por</th>
                            <th class="text-left">***</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, id) in item?.subitens" :key="id">
                            <td>
                                {{ item.nome }}<br />
                                <p class="text-body-2 text-disabled">
                                    {{ isDate(item.created_at) }}
                                </p>
                            </td>
                            <td>
                                <Avatar :nomeCompleto="item.created_by.name" />
                            </td>
                            <td>
                                <v-btn
                                    icon="mdi-delete"
                                    variant="text"
                                    density="compact"
                                    color="red"
                                    @click.prevent="deleteItem()"
                                ></v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-col>
        </v-row>

        <NovoSubitem v-model="dialogNovoSubitem" @insertProcess="" />

        <Confirmation
            title="Para continuar confirme a ação!"
            subtitle="Vincular um subitem a um item deixa ele disponível para utilizar na edição da base zero, mas este mesmo item nao pode ser utilizado ou 'visto' por outro."
            v-model="dialogConfirmation"
            @confirmed="carregandoSubitensPeloitem"
            @canceled="((inputSubitem = null), (dialogConfirmation = false))"
        />
    </Dialog>
</template>

<script setup>
import { useFeedback } from "@/Composables/useFeedback";
import { useSubitem } from "@/Composables/useSubitem";
import NovoSubitem from "../Subitens/NovoSubitem.vue";
import Confirmation from "../Confirmation.vue";
import { ref, computed, watch } from "vue";
import Dialog from "../Dialog.vue";
import axios from "axios";
import Avatar from "@/Components/Bases/Avatar.vue";

const model = defineModel();
const emit = defineEmits(["editeProcess"]);

const props = defineProps({
    item: Object,
    subitens: Object,
});
const { trigger } = useFeedback();
const { index } = useSubitem();

const isEditingName = ref(false);
// input text
const itemId = computed(() => props.item?.id ?? null);
const inputItemNome = ref("");
// options
const inputSubitem = ref(null);
const subitens = ref(props.subitens);
// Dialogs
const dialogConfirmation = ref(false);
const dialogNovoSubitem = ref(false);

watch(
    () => props.item,
    (novo) => {
        if (novo) {
            itemId.value = props.item.id;
            inputItemNome.value = props.item.nome;
        }
    },
    { immediate: true },
);
async function carregandoSubitensPeloitem() {
    await axios
        .post(route("subitem.subitensAssociaveis"), { itemId: itemId.value })
        .then((res) => {
            if (res.data.message) {
                trigger(res.data.message, "success");
                return;
            }
            trigger(res.data.message, "warning");
        })
        .catch((err) => trigger(err, "error"));
}
async function saveNameItem(){
    console.log(inputItemNome.value)
}
function deleteItem() {
    dialogConfirmation.value = true;
}
</script>

<style scoped></style>
